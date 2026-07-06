<?php

namespace Modules\Learning\Listeners;

use Modules\Learning\Events\LessonCompleted;
use Modules\Learning\Jobs\UpdateSectionProgressJob;

class UpdateSectionProgressListener
{
    /**
     * Handle the event.
     */
    public function handle(LessonCompleted $event): void
    {
        // Dispatch job to update section progress
        UpdateSectionProgressJob::dispatch(
            $event->userId,
            $event->lesson->id
        );
    }
}
