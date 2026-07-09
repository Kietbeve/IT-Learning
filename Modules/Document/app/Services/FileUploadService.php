<?php

namespace Modules\Document\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload an original document file to R2
     *
     * @return string The generated R2 path
     */
    public function uploadOriginalDocument(UploadedFile $file): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $ext = strtolower($file->getClientOriginalExtension());
        $uuid = Str::uuid();
        
        $path = "originals/resources/{$year}/{$month}/{$uuid}.{$ext}";
        Storage::disk('r2')->put($path, file_get_contents($file->getRealPath()));

        return $path;
    }

    /**
     * Upload a thumbnail to R2
     *
     * @return string The generated R2 path
     */
    public function uploadThumbnail(UploadedFile $file): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $ext = strtolower($file->getClientOriginalExtension());
        $uuid = Str::uuid();
        
        $path = "thumbnails/resources/{$year}/{$month}/{$uuid}.{$ext}";
        Storage::disk('r2')->put($path, file_get_contents($file->getRealPath()));

        return $path;
    }

    /**
     * Upload gallery images to R2
     *
     * @param UploadedFile[] $files
     * @param array $excludedIndices Indices of files to skip
     * @return array Array of associative arrays with 'path', 'order', and 'caption'
     */
    public function uploadGalleryImages(array $files, array $excludedIndices = []): array
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $galleryImagesData = [];

        foreach ($files as $index => $galleryFile) {
            if ($galleryFile && ! in_array($index, $excludedIndices)) {
                $ext = strtolower($galleryFile->getClientOriginalExtension());
                $uuid = Str::uuid();
                $path = "thumbnails/gallery/{$year}/{$month}/{$uuid}.{$ext}";
                
                Storage::disk('r2')->put($path, file_get_contents($galleryFile->getRealPath()));

                $galleryImagesData[] = [
                    'path' => $path,
                    'order' => $index + 1,
                ];
            }
        }

        return $galleryImagesData;
    }
}
