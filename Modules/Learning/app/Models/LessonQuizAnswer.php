<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonQuizAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_options',
        'is_correct',
        'score',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'selected_options' => 'array', // JSON array
            'is_correct' => 'boolean',
            'score' => 'decimal:2',
            'answered_at' => 'datetime',
        ];
    }

    /**
     * Quan hệ với LessonQuizAttempt
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(LessonQuizAttempt::class, 'attempt_id');
    }

    /**
     * Quan hệ với LessonQuizQuestion
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(LessonQuizQuestion::class, 'question_id');
    }

    /**
     * Kiểm tra và tính điểm cho câu trả lời
     */
    public function gradeAnswer(): void
    {
        $question = $this->question;
        
        if (!$question) {
            return;
        }

        $selectedOptions = $this->selected_options ?? [];
        
        // Kiểm tra đáp án có đúng không
        $this->is_correct = $question->isAnswerCorrect($selectedOptions);
        
        // Tính điểm
        $this->score = $question->getScoreForAnswer($selectedOptions);
        
        $this->save();
    }

    /**
     * Kiểm tra đã trả lời chưa
     */
    public function isAnswered(): bool
    {
        return $this->answered_at !== null;
    }

    /**
     * Lấy text của các đáp án đã chọn
     */
    public function getSelectedOptionsTextAttribute(): array
    {
        if (!$this->selected_options || !is_array($this->selected_options)) {
            return [];
        }

        $question = $this->question;
        if (!$question) {
            return [];
        }

        return collect($this->selected_options)
            ->map(function ($key) use ($question) {
                return $question->getOptionText($key);
            })
            ->filter()
            ->toArray();
    }
}
