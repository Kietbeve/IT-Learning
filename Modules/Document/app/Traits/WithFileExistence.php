<?php

namespace Modules\Document\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait WithFileExistence
{
    protected function checkFileExists($path)
    {
        if (!$path) return false;
        
        $cacheKey = 'file_exists_' . md5($path);
        return Cache::rememberForever($cacheKey, function() use ($path) {
            try {
                if (Storage::disk('public')->exists($path)) {
                    return true;
                }
                if (Storage::disk('r2')->exists($path)) {
                    return true;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to check file existence', ['path' => $path, 'error' => $e->getMessage()]);
            }
            return false;
        });
    }
}
