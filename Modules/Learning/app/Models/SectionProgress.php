<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Models\User;

class SectionProgress extends Model
{
    protected $table = 'section_progress';

    protected $fillable = [
        'user_id',
        'section_id',
        'roadmap_id',
        'completed_lessons',
        'total_lessons',
        'completion_percent',
        'status',
        'started_at',
        'completed_at',
        'time_spent_minutes',
    ];

    protected function casts(): array
    {
        return [
            'completion_percent' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * User đang học section này
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Section đang được học
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(RoadmapSection::class, 'section_id');
    }

    /**
     * Roadmap mà section này thuộc về
     */
    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Cập nhật tiến độ dựa trên số bài học đã hoàn thành
     */
    public function updateProgress(): void
    {
        $this->completion_percent = $this->total_lessons > 0 
            ? ($this->completed_lessons / $this->total_lessons) * 100 
            : 0;

        // Cập nhật status
        if ($this->completion_percent >= $this->section->min_completion_percent) {
            $this->status = 'completed';
            $this->completed_at = now();
        } elseif ($this->completion_percent > 0) {
            $this->status = 'in_progress';
            if (!$this->started_at) {
                $this->started_at = now();
            }
        }

        $this->save();
    }

    /**
     * Đánh dấu bài học đã hoàn thành
     */
    public function markLessonCompleted(): void
    {
        $this->completed_lessons++;
        $this->updateProgress();
    }

    /**
     * Thêm thời gian học
     */
    public function addTimeSpent(int $minutes): void
    {
        $this->time_spent_minutes += $minutes;
        $this->save();
    }
}
