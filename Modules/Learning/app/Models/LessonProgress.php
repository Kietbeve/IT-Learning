<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;
class LessonProgress extends Model
{
     protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'user_id',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(
            RoadmapEnrollment::class,
            'enrollment_id'
        );
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(
            RoadmapLesson::class,
            'lesson_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
