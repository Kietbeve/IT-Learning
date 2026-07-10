<?php

namespace Modules\Exam\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;


class ExamAttempt extends Model
{
    protected $table = 'exam_attempts';

    protected $fillable = [
        'exam_id',
        'user_id',
        'session_id',
        'started_at',
        'submitted_at',
        'expires_at',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'skipped_answers',
        'score',
        'percent_score',
        'is_passed',
        'status',
        'violation_count',
        'teacher_comment',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'expires_at' => 'datetime',

            'score' => 'decimal:2',
            'percent_score' => 'decimal:2',

            'is_passed' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function exam(): BelongsTo
    {
        return $this->belongsTo(
            Exam::class,
            'exam_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function answers(): HasMany
    {
        return $this->hasMany(
            AttemptAnswer::class,
            'attempt_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Route Binding
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName(): string
    {
        return 'session_id';
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function canBeAccessed(): bool
    {
        return $this->status === 'in_progress' && !$this->isExpired();
    }

    public function getTimeRemaining(): int
    {
        if (!$this->started_at || !$this->exam) {
            return 0;
        }

        $elapsed = now()->diffInSeconds($this->started_at);
        $totalSeconds = $this->exam->duration_minutes * 60;
        
        return max(0, $totalSeconds - $elapsed);
    }
    //Hàm tính tổng điểm tự luận
    public function getMultipleChoiceScore(): float
    {
        return (float) $this->answers()
            ->whereHas('question', function ($query) {
                $query->whereNot('type', 'essay');
            })
            ->sum('score');
    }
    //Hàm tính tổng điểm trắc nghiệm
    public function getEssayScore(): float
    {
        return (float) $this->answers()
            ->whereHas('question', function ($query) {
                $query->where('type', 'essay');
            })
            ->sum('score');
    }

    public function getAnsweredQuestionsCount(): int
    {
        return $this->answers()->count();
    }
}
