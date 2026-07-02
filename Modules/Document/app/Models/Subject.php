<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Subject extends Model
{

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Môn học thuộc 1 danh mục
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 1 môn học có nhiều tài liệu
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Scope: Chỉ lấy môn học đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Lọc theo category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
