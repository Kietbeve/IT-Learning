<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DocumentComment extends Model
{
    protected $fillable = ['document_id', 'user_id', 'parent_id', 'content', 'status'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(DocumentComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(DocumentComment::class, 'parent_id')
            ->where('status', 'visible')
            ->orderBy('created_at', 'asc');
    }

    public function scopeVisible($query)
    {
        return $query->where('status', 'visible');
    }
}
