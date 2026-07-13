<?php

namespace Modules\Learning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Models\RoadmapLesson;

class LessonCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $userId,
        public RoadmapLesson $lesson
    ) {}
}
