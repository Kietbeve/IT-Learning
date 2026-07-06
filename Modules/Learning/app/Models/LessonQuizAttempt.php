<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Auth\Models\User;

class LessonQuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'user_id',
        'lesson_id',
        'started_at',
        'submitted_at',
        'expires_at',
        'total_questions',
        'answered_questions',
        'correct_answers',
        'wrong_answers',
        'score',
        'max_score',
        'percent_score',
        'is_passed',
        'status',
        'attempt_number',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'expires_at' => 'datetime',
            'score' => 'decimal:2',
            'max_score' => 'decimal:2',
            'percent_score' => 'decimal:2',
            'is_passed' => 'boolean',
        ];
    }

    /**
     * Quan hệ với LessonQuiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(LessonQuiz::class, 'quiz_id');
    }

    /**
     * Quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Quan hệ với RoadmapLesson
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(RoadmapLesson::class, 'lesson_id');
    }

    /**
     * Quan hệ với các câu trả lời
     */
    public function answers(): HasMany
    {
        return $this->hasMany(LessonQuizAnswer::class, 'attempt_id');
    }

    /**
     * Kiểm tra quiz đã hết giờ chưa
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return now()->isAfter($this->expires_at);
    }

    /**
     * Kiểm tra quiz đang trong quá trình làm
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress' && !$this->isExpired();
    }

    /**
     * Kiểm tra quiz đã hoàn thành
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ['submitted', 'auto_submitted', 'completed']);
    }

    /**
     * Lấy thời gian còn lại (phút)
     */
    public function getRemainingTimeAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        $diffInMinutes = now()->diffInMinutes($this->expires_at, false);
        return max(0, $diffInMinutes);
    }

    /**
     * Lấy phần trăm hoàn thành
     */
    public function getProgressPercentAttribute(): float
    {
        if ($this->total_questions === 0) {
            return 0;
        }

        return round(($this->answered_questions / $this->total_questions) * 100, 2);
    }

    /**
     * Tính toán và cập nhật điểm số
     */
    public function calculateScore(): void
    {
        $this->total_questions = $this->quiz->total_questions;
        $this->max_score = $this->quiz->max_score;
        
        $answeredCount = $this->answers()->whereNotNull('answered_at')->count();
        $correctCount = $this->answers()->where('is_correct', true)->count();
        $wrongCount = $this->answers()->where('is_correct', false)->whereNotNull('answered_at')->count();
        
        $totalScore = $this->answers()->sum('score');
        
        $this->answered_questions = $answeredCount;
        $this->correct_answers = $correctCount;
        $this->wrong_answers = $wrongCount;
        $this->score = $totalScore;
        
        if ($this->max_score > 0) {
            $this->percent_score = round(($totalScore / $this->max_score) * 100, 2);
        } else {
            $this->percent_score = 0;
        }
        
        $this->is_passed = $this->percent_score >= $this->quiz->pass_score;
        
        $this->save();
    }
}
