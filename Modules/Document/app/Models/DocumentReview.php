<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Document\Database\factories\DocumentReviewFactory;
use App\Models\User;
use Modules\Document\Models\Document;
class DocumentReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    const UPDATED_AT = null; // Bảng này theo DBML chỉ có created_at

    protected $fillable = ['document_id', 'user_id', 'document_download_id', 'rating', 'review', 'status'];

    public function document() { return $this->belongsTo(Document::class); }
    public function user() { return $this->belongsTo(\App\Models\User::class); }
    public function documentDownload() { return $this->belongsTo(DocumentDownload::class); }
}
