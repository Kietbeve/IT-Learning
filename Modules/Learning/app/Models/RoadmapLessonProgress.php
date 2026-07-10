<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class RoadmapLessonProgress extends Model
{
    protected $table = 'roadmap_lesson_progress';

    protected $fillable = [
        'user_id',
        'roadmap_id',
        'roadmap_lesson_id',
        'status',
        'time_spent',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(RoadmapLesson::class, 'roadmap_lesson_id');
    }

    /**
     * Check if lesson is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark lesson as started
     */
    public function markAsStarted(): void
    {
        if ($this->status === 'not_started') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
    }

    /**
     * Mark lesson as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
