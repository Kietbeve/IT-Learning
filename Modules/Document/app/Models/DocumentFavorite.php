<?php

namespace Modules\Document\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFavorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    const UPDATED_AT = null; // Bảng này theo DBML chỉ có created_at

    protected $fillable = ['document_id', 'user_id'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
