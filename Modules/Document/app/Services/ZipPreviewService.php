<?php

namespace Modules\Document\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;

class ZipPreviewService
{
    /**
     * Get the ZIP structure and text contents for previewing
     * 
     * @param Document $doc
     * @param DocumentVersion $activeVer
     * @return array
     */
    public function getZipStructure(Document $doc, DocumentVersion $activeVer): array
    {
        return Cache::rememberForever('zip_structure_' . $activeVer->id, function() use ($doc, $activeVer) {
            $zipPath = ($doc->watermark_status === 'success' && $activeVer->file_watermarked_path)
                ? $activeVer->file_watermarked_path
                : $activeVer->file_original_path;

            $localZip = null;
            $zipFiles = [];

            // Local path
            $localPath = storage_path('app/public/' . $zipPath);
            if (file_exists($localPath)) {
                $localZip = $localPath;
            }

            // R2 path
            if (!$localZip && $zipPath && Storage::disk('r2')->exists($zipPath)) {
                $tempZip = storage_path('app/temp/' . uniqid('zip_') . '.zip');
                $dir = dirname($tempZip);
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                file_put_contents($tempZip, Storage::disk('r2')->get($zipPath));
                $localZip = $tempZip;
            }

            if ($localZip) {
                $zip = new \ZipArchive();
                if ($zip->open($localZip) === TRUE) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $name = $zip->getNameIndex($i);
                        if (substr($name, -1) === '/') continue;

                        $skipExts = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'webp', 'bmp', 'tiff', 'tif',
                            'ttf', 'woff', 'woff2', 'eot', 'otf',
                            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                            'zip', 'rar', '7z', 'tar', 'gz',
                            'mp3', 'mp4', 'avi', 'mov', 'wav', 'ogg',
                            'exe', 'dll', 'so', 'dylib', 'bin', 'obj'];
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (in_array($ext, $skipExts)) continue;

                        $content = $zip->getFromIndex($i);
                        
                        if (!mb_check_encoding($content, 'UTF-8')) {
                            $detected = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
                            if ($detected && $detected !== 'UTF-8') {
                                $content = mb_convert_encoding($content, 'UTF-8', $detected);
                            } else {
                                $zipFiles[$name] = [
                                    'isDir' => false,
                                    'content' => '[Binary file - không thể preview]'
                                ];
                                continue;
                            }
                        }
                        
                        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $content);
                        
                        $lines = explode("\n", $content);
                        if (count($lines) > 50) {
                            $content = implode("\n", array_slice($lines, 0, 50));
                            $content .= "\n\n// ... [Hiển thị 50 dòng đầu, vui lòng tải xuống để xem đầy đủ]";
                        } elseif (strlen($content) > 10000) {
                            $content = substr($content, 0, 10000);
                            $content .= "\n\n// ... [Nội dung đã được cắt ngắn, vui lòng tải xuống để xem đầy đủ]";
                        }

                        $zipFiles[$name] = [
                            'isDir' => false,
                            'content' => $content
                        ];
                    }
                    $zip->close();
                }
                if (isset($tempZip) && file_exists($tempZip)) @unlink($tempZip);
            }
            return $zipFiles;
        });
    }
}
