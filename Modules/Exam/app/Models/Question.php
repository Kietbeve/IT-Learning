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

class Question extends Model
{
    use SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'author_id',
        'category_id',
        'content',
        'answer_text',
        'explanation',
        'difficulty',
        'status',
        'is_shared',
        'type',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'deleted_at'  => 'datetime',
            'is_shared'   => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function options(): HasMany
    {
        return $this->hasMany(
            QuestionOption::class,
            'question_id'
        )->orderBy('sort_order');
    }

    public function exams(): BelongsToMany//tuong duong exam_questions
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_questions',
            'question_id',
            'exam_id'
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
            'question_tag_maps',
            'question_id',
            'tag_id'
        );
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(
            AttemptAnswer::class,
            'question_id'
        );
    }
}
