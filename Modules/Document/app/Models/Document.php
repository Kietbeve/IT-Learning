<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Document\Database\factories\DocumentFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\Tag;
use Modules\Document\Models\DocumentFavorite;
use Modules\Document\Models\DocumentReview;
use Modules\Document\Models\DocumentDownload;
use App\Models\User;
use Modules\Payment\Models\Product;

class Document extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'public_id', 'author_id', 'category_id', 'title', 'slug', 'short_description', 
        'description', 'thumbnail', 'preview_file_path', 'file_original_path', 
        'file_watermarked_path', 'file_type', 'file_size', 'visibility', 'is_downloadable',
        'watermark_status', 'status', 'rejected_reason', 'reviewed_by', 'reviewed_at',
        'published_at', 'parent_document_id', 'download_count', 'favorite_count', 'view_count'
    ];

    protected $casts = [
        'is_downloadable' => 'boolean',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

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

    public function getThumbnailUrlAttribute()
    {
        return $this->resolveFileUrl($this->thumbnail);
    }

    protected function resolveFileUrl($path)
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, 'documents/')) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }
        $publicUrl = config('filesystems.disks.r2.url');
        $bucket = config('filesystems.disks.r2.bucket');
        return rtrim($publicUrl, '/') . '/' . $bucket . '/' . ltrim($path, '/');
    }

    public function author() { return $this->belongsTo(\App\Models\User::class, 'author_id'); }
    public function reviewer() { return $this->belongsTo(\App\Models\User::class, 'reviewed_by'); }
    public function category() { return $this->belongsTo(Category::class); }
    public function tags() { return $this->belongsToMany(Tag::class, 'document_tag_maps'); }
    public function favorites() { return $this->hasMany(DocumentFavorite::class); }
    public function reviews() { return $this->hasMany(DocumentReview::class); }
    public function downloads() { return $this->hasMany(DocumentDownload::class); }
    
    // OLD Draft relationships (via parent_document_id - legacy, keeping for backward compatibility)
    public function rejectedDrafts() { return $this->hasMany(Document::class, 'parent_document_id')->where('status', 'rejected')->orderBy('created_at', 'desc'); }
    public function pendingDrafts() { return $this->hasMany(Document::class, 'parent_document_id')->where('status', 'pending')->orderBy('created_at', 'desc'); }
    public function parentDocument() { return $this->belongsTo(Document::class, 'parent_document_id')->withTrashed(); }
    
    // NEW Submission relationships (via document_relationships table)
    public function submissionHistory() { return $this->hasMany(DocumentRelationship::class, 'draft_document_id')->orderBy('submitted_at', 'desc'); }
    public function editHistory() { return $this->hasMany(DocumentRelationship::class, 'parent_document_id')->orderBy('submitted_at', 'desc'); }
    public function pendingRelationships() { return $this->hasMany(DocumentRelationship::class, 'draft_document_id')->where('status', 'pending'); }
    public function approvedRelationships() { return $this->hasMany(DocumentRelationship::class, 'draft_document_id')->where('status', 'approved'); }
    public function rejectedRelationships() { return $this->hasMany(DocumentRelationship::class, 'draft_document_id')->where('status', 'rejected'); }
    
    // Lifecycle history - chain của document (parent + children)
    public function histories() {
        return $this->hasMany(Document::class, 'parent_document_id')
            ->with(['author', 'reviewer'])
            ->orderBy('created_at', 'asc');
    }
    
    // Cross-Module
    public function product() { return $this->hasOne(\Modules\Payment\Models\Product::class); }
}
