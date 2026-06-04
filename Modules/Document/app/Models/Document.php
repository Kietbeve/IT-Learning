<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Document\Database\factories\DocumentFactory;
use App\Models\Category;
use Modules\Document\Models\Tag;
use Modules\Document\Models\DocumentFavorite;
use Modules\Document\Models\DocumentReview;
use Modules\Document\Models\DocumentDownload;
class Document extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'public_id', 'author_id', 'category_id', 'title', 'slug', 'short_description', 
        'description', 'thumbnail', 'preview_file_path', 'file_original_path', 
        'file_watermarked_path', 'file_type', 'file_size', 'visibility', 'is_downloadable',
        'watermark_status', 'status', 'rejected_reason', 'reviewed_by', 'reviewed_at',
        'published_at', 'download_count', 'favorite_count', 'view_count'
    ];

    protected $casts = [
        'is_downloadable' => 'boolean',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    // public function author() { return $this->belongsTo(\App\Models\User::class, 'author_id'); }
    // public function reviewer() { return $this->belongsTo(\App\Models\User::class, 'reviewed_by'); }
    public function category() { return $this->belongsTo(Category::class); }
    public function tags() { return $this->belongsToMany(Tag::class, 'document_tag_maps'); }
    public function favorites() { return $this->hasMany(DocumentFavorite::class); }
    public function reviews() { return $this->hasMany(DocumentReview::class); }
    public function downloads() { return $this->hasMany(DocumentDownload::class); }
    
    // Cross-Module
    public function product() { return $this->hasOne(\Modules\Payment\Models\Product::class); }
}
