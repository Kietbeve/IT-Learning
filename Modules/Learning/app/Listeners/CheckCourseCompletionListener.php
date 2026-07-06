<?php

namespace Modules\Learning\Listeners;

use Modules\Learning\Events\SectionCompleted;
use Modules\Learning\Services\SectionProgressService;
use Modules\Learning\Services\CertificateService;
use Modules\Learning\Jobs\GenerateCertificateJob;

class CheckCourseCompletionListener
{
    /**
     * Handle the event.
     */
    public function handle(SectionCompleted $event): void
    {
        $sectionProgress = $event->sectionProgress;
        
        // Check if user đã hoàn thành toàn bộ roadmap
        $sectionProgressService = app(SectionProgressService::class);
        $isCompleted = $sectionProgressService->isRoadmapCompleted(
            $sectionProgress->user_id,
            $sectionProgress->roadmap_id
        );

        if ($isCompleted) {
            // Fire course completed event
            event(new \Modules\Learning\Events\CourseCompleted(
                $sectionProgress->user_id,
                $sectionProgress->roadmap_id
            ));
        }
    }
}
