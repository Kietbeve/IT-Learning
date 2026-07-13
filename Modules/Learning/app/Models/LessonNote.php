<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class LessonNote extends Model
{
    protected $table = 'lesson_notes';

    protected $fillable = [
        'user_id',
        'roadmap_lesson_id',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who wrote this note
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson this note belongs to
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(RoadmapLesson::class, 'roadmap_lesson_id');
    }
}
