<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonQuiz extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'duration_minutes',
        'pass_score',
        'max_attempts',
        'shuffle_questions',
        'shuffle_options',
        'show_correct_answers',
        'allow_review',
        'is_required',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_correct_answers' => 'boolean',
            'allow_review' => 'boolean',
            'is_required' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Quan hệ với RoadmapLesson
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(RoadmapLesson::class, 'lesson_id');
    }

    /**
     * Quan hệ với các câu hỏi
     */
    public function questions(): HasMany
    {
        return $this->hasMany(LessonQuizQuestion::class, 'quiz_id')
            ->orderBy('sort_order');
    }

    /**
     * Quan hệ với các lượt làm bài
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(LessonQuizAttempt::class, 'quiz_id');
    }

    /**
     * Lấy tổng số câu hỏi
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Lấy tổng điểm tối đa của quiz
     */
    public function getMaxScoreAttribute(): int
    {
        return $this->questions()->sum('score');
    }

    /**
     * Kiểm tra user đã làm quiz này chưa
     */
    public function hasAttemptByUser($userId): bool
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Lấy số lần đã làm của user
     */
    public function getAttemptsCountByUser($userId): int
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Kiểm tra user có thể làm lại không
     */
    public function canUserRetake($userId): bool
    {
        if ($this->max_attempts === null) {
            return true; // Không giới hạn số lần làm
        }

        $attemptsCount = $this->getAttemptsCountByUser($userId);
        return $attemptsCount < $this->max_attempts;
    }

    /**
     * Lấy lần làm tốt nhất của user
     */
    public function getBestAttemptByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->orderBy('percent_score', 'desc')
            ->first();
    }

    /**
     * Lấy lần làm gần nhất của user
     */
    public function getLatestAttemptByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->latest()
            ->first();
    }
}
