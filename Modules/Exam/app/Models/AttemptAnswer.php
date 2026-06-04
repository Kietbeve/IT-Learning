<?php

namespace Modules\Exam\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    protected $table = 'attempt_answers';

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option_ids',
        'answer_text',
        'is_correct',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'selected_option_ids' => 'array',
            'is_correct' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(
            ExamAttempt::class,
            'attempt_id'
        );
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            Question::class,
            'question_id'
        );
    }
}
