<?php

namespace Modules\Exam\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\Auth\Models\User;
use App\Models\Category;
use App\Models\Tag;

class Exam extends Model
{
    use SoftDeletes;

    protected $table = 'exams';

    protected $fillable = [
        'public_id',
        'author_id',
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'type',
        'mode',
        'duration_minutes',
        'pass_percent',
        'visibility',
        'status',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
        'publish_at',
        'attempt_count',
    ];

    protected function casts(): array
    {
        return [
            'pass_percent' => 'decimal:2',
            'reviewed_at' => 'datetime',
            'publish_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'exam_questions',
            'exam_id',
            'question_id'
        )
        ->withPivot([
            'sort_order',
            'score',
        ])
        ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'exam_tag_maps',
            'exam_id',
            'tag_id'
        );
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(
            ExamAttempt::class,
            'exam_id'
        );
    }
}
