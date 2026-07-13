<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

use Modules\Exam\Models\Exam;

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
        return $this->hasMany(DocumentVersion::class)->where('status', 'approved');
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

    /**
     * Tạo slug duy nhất từ tên môn học
     * 
     * @param string $name Tên môn học
     * @return string Slug duy nhất
     */
    public static function generateUniqueSlug(string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
