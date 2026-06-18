<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Category;
use Modules\Auth\Models\User;

class Roadmap extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'author_id',
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'objective',
        'thumbnail',
        'visibility',
        'status',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // SỬA TẠI ĐÂY: Trỏ trực tiếp chuỗi Class Name có kèm đầy đủ namespace để Laravel tự tìm kiếm
    public function sections(): HasMany
    {
        return $this->hasMany('Modules\Learning\app\Models\RoadmapSection');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany('Modules\Learning\app\Models\RoadmapLesson');
    }

    public function projects(): HasMany
    {
        return $this->hasMany('Modules\Learning\app\Models\Project');
    }
}