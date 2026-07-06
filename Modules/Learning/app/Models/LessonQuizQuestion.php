<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonQuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'options',
        'explanation',
        'score',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array', // JSON array
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
     * Quan hệ với các câu trả lời của học viên
     */
    public function answers(): HasMany
    {
        return $this->hasMany(LessonQuizAnswer::class, 'question_id');
    }

    /**
     * Lấy các đáp án đúng
     */
    public function getCorrectOptionsAttribute(): array
    {
        if (!is_array($this->options)) {
            return [];
        }

        return collect($this->options)
            ->filter(function ($option) {
                return isset($option['is_correct']) && $option['is_correct'] === true;
            })
            ->pluck('key')
            ->toArray();
    }

    /**
     * Kiểm tra câu trả lời có đúng không
     * 
     * @param array $selectedOptions Mảng các key đã chọn ['A', 'B']
     * @return bool
     */
    public function isAnswerCorrect(array $selectedOptions): bool
    {
        $correctOptions = $this->correct_options;
        
        // Sắp xếp để so sánh
        sort($selectedOptions);
        sort($correctOptions);
        
        // Phải chọn đúng tất cả đáp án đúng và không chọn đáp án sai
        return $selectedOptions === $correctOptions;
    }

    /**
     * Lấy điểm cho câu trả lời
     * 
     * @param array $selectedOptions
     * @return float
     */
    public function getScoreForAnswer(array $selectedOptions): float
    {
        if ($this->isAnswerCorrect($selectedOptions)) {
            return $this->score;
        }
        
        return 0;
    }

    /**
     * Lấy text của option theo key
     */
    public function getOptionText(string $key): ?string
    {
        if (!is_array($this->options)) {
            return null;
        }

        $option = collect($this->options)->firstWhere('key', $key);
        return $option['text'] ?? null;
    }

    /**
     * Kiểm tra option có phải đáp án đúng không
     */
    public function isOptionCorrect(string $key): bool
    {
        return in_array($key, $this->correct_options);
    }
}
