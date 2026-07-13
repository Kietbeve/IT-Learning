<?php

namespace Modules\Learning\Listeners;

use Modules\Learning\Events\AssignmentSubmitted;
use Modules\Learning\Services\NotificationService;

class NotifyInstructorListener
{
    /**
     * Handle the event.
     */
    public function handle(AssignmentSubmitted $event): void
    {
        $submission = $event->submission;
        $assignment = $submission->assignment;
        $instructorId = $assignment->created_by;

        // Send notification to instructor
        \Modules\Learning\Models\LearningNotification::create([
            'user_id' => $instructorId,
            'type' => 'assignment_due',
            'notifiable_type' => get_class($submission),
            'notifiable_id' => $submission->id,
            'roadmap_id' => $assignment->lesson->roadmap_id,
            'title' => 'Có bài nộp mới',
            'message' => "Học viên {$submission->student->name} đã nộp bài '{$assignment->title}'",
            'action_url' => "/admin/submissions/{$submission->id}/review",
            'action_text' => 'Chấm bài',
            'priority' => 'normal',
            'sent_at' => now(),
        ]);
    }
}
