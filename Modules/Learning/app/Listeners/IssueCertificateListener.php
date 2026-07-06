<?php

namespace Modules\Learning\Listeners;

use Modules\Learning\Events\CourseCompleted;
use Modules\Learning\Jobs\GenerateCertificateJob;

class IssueCertificateListener
{
    /**
     * Handle the event.
     */
    public function handle(CourseCompleted $event): void
    {
        // Dispatch job to generate certificate
        GenerateCertificateJob::dispatch(
            $event->userId,
            $event->roadmapId
        );
    }
}
