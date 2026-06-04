<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\Exam\Models\Question;
use Modules\Exam\Models\Exam;
class Category extends Model
{
     use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'is_active',
        'sort_order',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Danh mục cha
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Danh mục con
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(
            Question::class,
            'category_id'
        );
    }

    public function exams(): HasMany
    {
        return $this->hasMany(
            Exam::class,
            'category_id'
        );
    }
}
