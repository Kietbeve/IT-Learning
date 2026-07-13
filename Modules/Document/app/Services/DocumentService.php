<?php

namespace Modules\Document\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
use Modules\Payment\Models\Product;
use Modules\Document\Jobs\ProcessWatermarkJob;
use Illuminate\Http\UploadedFile;

class DocumentService
{
    public function __construct(
        protected FileUploadService $uploadService,
        protected TagService $tagService
    ) {}

    public function createDocument(
        array $data,
        UploadedFile $originalFile,
        UploadedFile $thumbnailFile,
        array $galleryFiles,
        array $excludedGalleryIndices,
        string $status
    ) {
        return DB::transaction(function () use ($data, $originalFile, $thumbnailFile, $galleryFiles, $excludedGalleryIndices, $status) {
            $originalR2Path = $this->uploadService->uploadOriginalDocument($originalFile);
            $originalExt = strtolower($originalFile->getClientOriginalExtension());
            $thumbnailR2Path = $this->uploadService->uploadThumbnail($thumbnailFile);

            // Upload gallery images
            $galleryImagesData = [];
            if (!empty($galleryFiles)) {
                $galleryImagesData = $this->uploadService->uploadGalleryImages($galleryFiles, $excludedGalleryIndices);
            }

            $slug = Document::generateUniqueSlug($data['title']);
            $authorId = Auth::id();

            // Create Document record (identity fields only)
            $document = Document::create([
                'public_id' => 'doc_'.Str::random(12),
                'author_id' => $authorId,
                'slug' => $slug,
                'status' => $status,
                'current_version_id' => null,
            ]);

            $price = ($data['isPaid'] ?? false) && ($data['price'] > 0) ? $data['price'] : 0;
            $sale_price = ($data['isPaid'] ?? false) && ($data['price'] > 0) && (!empty($data['sale_price']) && $data['sale_price'] > 0) ? $data['sale_price'] : null;

            // Create DocumentVersion record
            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => 1,
                'title' => $data['title'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'subject_id' => $data['subject_id'] ?? null,
                'thumbnail' => $thumbnailR2Path,
                'gallery_images' => $galleryImagesData,
                'file_original_path' => $originalR2Path,
                'file_watermarked_path' => null,
                'preview_file_path' => null,
                'file_type' => $originalExt,
                'file_size' => $originalFile->getSize(),
                'visibility' => $data['visibility'] ?? 'public',

                'watermark_status' => 'pending',
                'price' => $price,
                'sale_price' => $sale_price,
                'status' => $status,
                'submitted_by' => $authorId,
                'submitted_at' => now(),
                'reviewed_by' => $status === 'approved' ? $authorId : null,
                'reviewed_at' => $status === 'approved' ? now() : null,
            ]);

            // Link current version to document if approved
            if ($status === 'approved') {
                $document->update([
                    'current_version_id' => $version->id,
                ]);
            }

            // Handle watermark job dispatch or instant ZIP success
            if (in_array($originalExt, ['pdf', 'docx'])) {
                ProcessWatermarkJob::dispatch($document->id);
            } else {
                // ZIP: instant success
                $version->update([
                    'watermark_status' => 'success',
                    'file_watermarked_path' => $originalR2Path,
                ]);
            }

            // Create Product if paid
            if ($price > 0) {
                Product::create([
                    'document_id' => $document->id,
                    'name' => $data['title'],
                    'price' => $price,
                    'sale_price' => $sale_price ?: null,
                    'is_active' => true,
                ]);
            }

            $this->tagService->syncTags($document, $data['selectedTags'] ?? [], $data['customTagsInput'] ?? '');

            return $document;
        });
    }
}
