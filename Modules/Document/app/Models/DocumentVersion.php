<?php

namespace Modules\Document\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'version_number',
        'title',
        'short_description',
        'description',
        'category_id',
        'subject_id',
        'thumbnail',
        'gallery_images',
        'file_original_path',
        'file_watermarked_path',
        'preview_file_path',
        'file_type',
        'file_size',
        'visibility',

        'watermark_status',
        'price',
        'sale_price',
        'status',
        'rejected_reason',
        'submitted_by',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [

        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'price' => 'decimal:2',
        'gallery_images' => 'array',
    ];

    /* ──────────── Relationships ──────────── */

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function submittedByUser()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedByUser()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /* ──────────── URL Helpers ──────────── */

    public function getFileOriginalUrlAttribute()
    {
        return $this->resolveFileUrl($this->file_original_path);
    }

    public function getFileWatermarkedUrlAttribute()
    {
        return $this->resolveFileUrl($this->file_watermarked_path);
    }

    public function getPreviewFileUrlAttribute()
    {
        return $this->resolveFileUrl($this->preview_file_path);
    }

    public function getFileOriginalViewUrlAttribute()
    {
        return $this->resolveViewUrl($this->file_original_path);
    }

    public function getFileWatermarkedViewUrlAttribute()
    {
        return $this->resolveViewUrl($this->file_watermarked_path);
    }

    public function getPreviewFileViewUrlAttribute()
    {
        return $this->resolveViewUrl($this->preview_file_path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->resolveFileUrl($this->thumbnail);
    }

    protected function resolveFileUrl($path)
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        if (str_starts_with($path, 'documents/')) {
            return Storage::disk('public')->url($path);
        }
        $publicUrl = config('filesystems.disks.r2.url');
        $url = rtrim($publicUrl, '/').'/'.ltrim($path, '/');

        // Force inline viewing for PDFs instead of download
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($extension, ['pdf'])) {
            $url .= '?response-content-disposition=inline';
        }

        return $url;
    }

    protected function resolveViewUrl($path)
    {
        $url = $this->resolveFileUrl($path);
        if (! $url) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            return 'https://view.officeapps.live.com/op/view.aspx?src='.urlencode($url);
        }

        return $url;
    }

    /* ──────────── Helpers ──────────── */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /* ──────────── Scopes ──────────── */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
