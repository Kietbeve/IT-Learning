<?php

namespace Modules\Document\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Document\Models\Document;
use Modules\Document\Services\WatermarkService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessWatermarkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $documentId;

    public $tries = 3;
    public $timeout = 300;

    public function __construct($documentId)
    {
        $this->documentId = $documentId;
    }

    public function handle()
    {
        $document = Document::find($this->documentId);

        if (!$document) {
            Log::error("ProcessWatermarkJob: Document not found", ['id' => $this->documentId]);
            return;
        }

        $originalPath = null;

        try {
            Log::info("ProcessWatermarkJob: Starting", ['doc_id' => $document->id, 'type' => $document->file_type]);

            $fileType = strtolower($document->file_type);

            if (in_array($fileType, ['pdf', 'docx'])) {
                $watermarkService = app(WatermarkService::class);

                $originalPath = $this->resolveOriginalPath($document->file_original_path);

                if (!$originalPath || !file_exists($originalPath)) {
                    throw new \Exception("Original file not found for document {$document->id}");
                }

                $watermarkedR2Path = $watermarkService->addWatermark($originalPath, $fileType, $document);

                $updateData = [
                    'file_watermarked_path' => $watermarkedR2Path,
                    'watermark_status' => 'success',
                ];

                if ($fileType === 'docx') {
                    $updateData['file_type'] = 'pdf';
                }

                $document->update($updateData);

                // Generate preview for PDFs (from watermarked file)
                if ($fileType === 'pdf' || ($fileType === 'docx' && isset($updateData['file_type']) && $updateData['file_type'] === 'pdf')) {
                    try {
                        // Download watermarked from R2 to generate preview
                        $tempWatermarked = storage_path('app/temp/' . uniqid('wm_preview_') . '.pdf');
                        $dir = dirname($tempWatermarked);
                        if (!is_dir($dir)) mkdir($dir, 0755, true);
                        
                        $contents = Storage::disk('r2')->get($watermarkedR2Path);
                        file_put_contents($tempWatermarked, $contents);
                        
                        $previewR2Path = $watermarkService->generatePreview($tempWatermarked, 'pdf');
                        
                        if ($previewR2Path) {
                            $document->update(['preview_file_path' => $previewR2Path]);
                            Log::info("Preview generated", ['doc_id' => $document->id, 'preview_path' => $previewR2Path]);
                        } else {
                            // Fallback: use watermarked file as preview
                            $document->update(['preview_file_path' => $watermarkedR2Path]);
                            Log::info("Preview fallback to watermarked", ['doc_id' => $document->id]);
                        }
                        
                        if (file_exists($tempWatermarked)) @unlink($tempWatermarked);
                        
                    } catch (\Exception $e) {
                        Log::warning("Preview generation failed", ['doc_id' => $document->id, 'error' => $e->getMessage()]);
                        // Fallback: use watermarked as preview
                        $document->update(['preview_file_path' => $watermarkedR2Path]);
                    }
                }

                Log::info("ProcessWatermarkJob: Success", ['doc_id' => $document->id]);

            } elseif ($fileType === 'zip') {
                $document->update([
                    'file_watermarked_path' => $document->file_original_path,
                    'watermark_status' => 'success',
                ]);

                Log::info("ProcessWatermarkJob: ZIP file, skipped watermark", ['doc_id' => $document->id]);
            } else {
                throw new \Exception("Unsupported file type: {$fileType}");
            }

        } catch (\Exception $e) {
            Log::error("ProcessWatermarkJob: Failed", [
                'doc_id' => $document->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $document->update([
                'watermark_status' => 'failed',
            ]);

            throw $e;

        } finally {
            $this->cleanupTemp($originalPath);
        }
    }

    protected function resolveOriginalPath($storedPath)
    {
        if (!$storedPath) return null;

        // Local path (legacy)
        $localPath = storage_path('app/public/' . $storedPath);
        if (file_exists($localPath)) {
            return $localPath;
        }

        // R2 path — download to temp
        if (Storage::disk('r2')->exists($storedPath)) {
            $tempPath = storage_path('app/temp/' . uniqid('orig_') . '.' . pathinfo($storedPath, PATHINFO_EXTENSION));
            $dir = dirname($tempPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $contents = Storage::disk('r2')->get($storedPath);
            file_put_contents($tempPath, $contents);
            return $tempPath;
        }

        // Full URL — download via HTTP
        if (str_starts_with($storedPath, 'http')) {
            $tempPath = storage_path('app/temp/' . uniqid('orig_') . '.' . pathinfo(parse_url($storedPath, PHP_URL_PATH), PATHINFO_EXTENSION));
            $dir = dirname($tempPath);
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $contents = file_get_contents($storedPath);
            if ($contents !== false) {
                file_put_contents($tempPath, $contents);
                return $tempPath;
            }
        }

        return null;
    }

    protected function cleanupTemp($path)
    {
        if ($path && str_contains($path, 'app/temp/') && file_exists($path)) {
            @unlink($path);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("ProcessWatermarkJob: Job failed permanently", [
            'doc_id' => $this->documentId,
            'error' => $exception->getMessage()
        ]);

        $document = Document::find($this->documentId);
        if ($document) {
            $document->update([
                'watermark_status' => 'failed',
            ]);
        }
    }
}
