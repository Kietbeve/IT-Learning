<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tag;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'current_version_id',
        'public_id',
        'author_id',
        'slug',
        'status',
        'download_count',
        'favorite_count',
        'view_count',
    ];

    /* ──────────── Core Relations ──────────── */

    public function author()   { return $this->belongsTo(\App\Models\User::class, 'author_id'); }
    public function tags()     { return $this->belongsToMany(Tag::class, 'document_tag_maps'); }
    public function favorites(){ return $this->hasMany(DocumentFavorite::class); }
    public function reviews()  { return $this->hasMany(DocumentReview::class); }
    public function comments() { return $this->hasMany(DocumentComment::class); }
    public function downloads(){ return $this->hasMany(DocumentDownload::class); }

    /* ──────────── Versioning Relations ──────────── */

    /** All versions of this document */
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_number');
    }

    /** The currently live / published version (approved) */
    public function currentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version_id');
    }

    /** The latest version regardless of status */
    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latestOfMany('version_number');
    }

    /** The latest pending (waiting for review) version */
    public function pendingVersion()
    {
        return $this->hasOne(DocumentVersion::class)
                    ->where('status', 'pending')
                    ->latestOfMany('version_number');
    }

    /** The latest rejected version */
    public function rejectedVersion()
    {
        return $this->hasOne(DocumentVersion::class)
                    ->where('status', 'rejected')
                    ->latestOfMany('version_number');
    }

    /* ──────────── Cross-Module ──────────── */
    public function product() { return $this->hasOne(\Modules\Payment\Models\Product::class); }

    /* ──────────── Proxy Accessors (delegate to currentVersion or pendingVersion) ────────────
     * These allow existing blade/code to use $doc->title, $doc->thumbnail, etc.
     * They resolve from the most "live" version available so views don't break.
     */
    protected function resolveVersion(): ?DocumentVersion
    {
        // Prefer currentVersion (approved), fall back to latestVersion for pending-only docs
        return $this->relationLoaded('currentVersion') && $this->currentVersion
            ? $this->currentVersion
            : ($this->relationLoaded('latestVersion') && $this->latestVersion
                ? $this->latestVersion
                : $this->currentVersion ?? $this->latestVersion);
    }

    public function getTitleAttribute()           { return $this->resolveVersion()?->title; }
    public function getCategoryIdAttribute()      { return $this->resolveVersion()?->category_id; }
    public function getSubjectIdAttribute()       { return $this->resolveVersion()?->subject_id; }
    public function getShortDescriptionAttribute(){ return $this->resolveVersion()?->short_description; }
    public function getDescriptionAttribute()     { return $this->resolveVersion()?->description; }
    public function getThumbnailAttribute()       { return $this->resolveVersion()?->thumbnail; }
    public function getPreviewFilePathAttribute() { return $this->resolveVersion()?->preview_file_path; }
    public function getFileOriginalPathAttribute(){ return $this->resolveVersion()?->file_original_path; }
    public function getFileWatermarkedPathAttribute() { return $this->resolveVersion()?->file_watermarked_path; }
    public function getFileTypeAttribute()        { return $this->resolveVersion()?->file_type; }
    public function getFileSizeAttribute()        { return $this->resolveVersion()?->file_size; }
    public function getVisibilityAttribute()      { return $this->resolveVersion()?->visibility ?? 'public'; }
    public function getIsDownloadableAttribute()  { return $this->resolveVersion()?->is_downloadable ?? true; }
    public function getWatermarkStatusAttribute() { return $this->resolveVersion()?->watermark_status ?? 'pending'; }
    public function getRejectedReasonAttribute()  {
        // For a rejected document (v1 rejected), show v1's rejected_reason
        return $this->rejectedVersion?->rejected_reason;
    }
    public function getReviewedByAttribute()      { return $this->resolveVersion()?->reviewed_by; }
    public function getReviewedAtAttribute()      { return $this->resolveVersion()?->reviewed_at; }
    public function getGalleryImagesAttribute()   { return $this->resolveVersion()?->gallery_images ?? []; }
    public function getPublishedAtAttribute()     {
        // Published at = when the current version was approved
        return $this->currentVersion?->reviewed_at;
    }

    /* ──────────── URL Helpers (proxy through version) ──────────── */

    public function getThumbnailUrlAttribute()
    {
        return $this->resolveVersion()?->thumbnail_url;
    }

    public function getFileOriginalUrlAttribute()
    {
        return $this->resolveVersion()?->file_original_url;
    }

    public function getFileWatermarkedUrlAttribute()
    {
        return $this->resolveVersion()?->file_watermarked_url;
    }

    public function getPreviewFileUrlAttribute()
    {
        return $this->resolveVersion()?->preview_file_url;
    }

    public function getFileOriginalViewUrlAttribute()
    {
        return $this->resolveVersion()?->file_original_view_url;
    }

    public function getFileWatermarkedViewUrlAttribute()
    {
        return $this->resolveVersion()?->file_watermarked_view_url;
    }

    public function getPreviewFileViewUrlAttribute()
    {
        return $this->resolveVersion()?->preview_file_view_url;
    }

    /* ──────────── Category / Subject accessors (via currentVersion) ──────────── */

    public function getCategoryAttribute()
    {
        return $this->resolveVersion()?->category;
    }

    public function getSubjectAttribute()
    {
        return $this->resolveVersion()?->subject;
    }

    /* ──────────── reviewer shortcut (via currentVersion) ──────────── */
    public function getReviewerAttribute()
    {
        return $this->resolveVersion()?->reviewedByUser;
    }
}
