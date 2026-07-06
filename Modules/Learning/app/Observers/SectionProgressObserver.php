<?php

namespace Modules\Learning\Observers;

use Modules\Learning\Models\SectionProgress;
use Modules\Learning\Services\NotificationService;
use Modules\Learning\Services\CertificateService;

class SectionProgressObserver
{
    /**
     * Handle the SectionProgress "updated" event.
     */
    public function updated(SectionProgress $sectionProgress): void
    {
        // Check if section just completed
        if ($sectionProgress->isDirty('status') && $sectionProgress->status === 'completed') {
            $this->handleSectionCompleted($sectionProgress);
        }
    }

    /**
     * Handle section completion
     */
    protected function handleSectionCompleted(SectionProgress $sectionProgress): void
    {
        // Fire section completed event
        event(new \Modules\Learning\Events\SectionCompleted($sectionProgress));

        // Check if all sections in roadmap are completed
        $allCompleted = SectionProgress::where('user_id', $sectionProgress->user_id)
            ->where('roadmap_id', $sectionProgress->roadmap_id)
            ->where('status', '!=', 'completed')
            ->doesntExist();

        if ($allCompleted) {
            // Fire course completed event
            event(new \Modules\Learning\Events\CourseCompleted(
                $sectionProgress->user_id,
                $sectionProgress->roadmap_id
            ));
        }
    }

    /**
     * Handle the SectionProgress "created" event.
     */
    public function created(SectionProgress $sectionProgress): void
    {
        // Initialize progress tracking
        if ($sectionProgress->status === 'not_started') {
            $sectionProgress->started_at = now();
            $sectionProgress->saveQuietly(); // Don't trigger updated event
        }
    }
}
