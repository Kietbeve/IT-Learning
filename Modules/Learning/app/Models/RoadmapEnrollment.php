<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\Auth\Models\User;

class RoadmapEnrollment extends Model
{
    protected $fillable = [
        'roadmap_id',
        'user_id',
        'status',
        'progress_percent',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_percent' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lessonProgresses(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'enrollment_id');
    }
}
