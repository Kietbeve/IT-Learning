<?php

namespace Modules\Exam\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\Auth\Models\User;


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
}
