<?php

namespace Modules\Document\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    const UPDATED_AT = null; // Bảng này theo DBML chỉ có created_at

    protected $fillable = ['document_id', 'user_id', 'document_download_id', 'rating', 'review', 'status'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documentDownload()
    {
        return $this->belongsTo(DocumentDownload::class);
    }
}
