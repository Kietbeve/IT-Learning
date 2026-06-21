<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Models\User;

class LessonQuestion extends Model
{
    protected $table = 'lesson_questions';

    protected $fillable = [
        'user_id',
        'roadmap_lesson_id',
        'content',
        'is_answered',
    ];

    protected function casts(): array
    {
        return [
            'is_answered' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who asked this question
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson this question belongs to
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(RoadmapLesson::class, 'roadmap_lesson_id');
    }
}
