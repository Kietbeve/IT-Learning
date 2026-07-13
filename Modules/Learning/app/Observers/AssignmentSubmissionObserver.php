<?php

namespace Modules\Learning\Observers;

use Modules\Learning\Models\AssignmentSubmission;
use Modules\Learning\Services\NotificationService;

class AssignmentSubmissionObserver
{
    /**
     * Handle the AssignmentSubmission "creating" event.
     */
    public function creating(AssignmentSubmission $submission): void
    {
        // Set deadline if not already set
        if (!$submission->deadline && $submission->assignment) {
            $submission->deadline = now()->addDays($submission->assignment->deadline_days);
        }
    }

    /**
     * Handle the AssignmentSubmission "created" event.
     */
    public function created(AssignmentSubmission $submission): void
    {
        // Schedule deadline reminder
        if ($submission->assignment && $submission->assignment->send_reminder) {
            app(NotificationService::class)->scheduleDeadlineReminder($submission);
        }
    }

    /**
     * Handle the AssignmentSubmission "updated" event.
     */
    public function updated(AssignmentSubmission $submission): void
    {
        // Check if just submitted
        if ($submission->isDirty('status') && $submission->status === 'submitted') {
            $this->handleSubmitted($submission);
        }

        // Check if just graded
        if ($submission->isDirty('status') && $submission->status === 'graded') {
            $this->handleGraded($submission);
        }

        // Check if became late
        if ($submission->isDirty('is_late') && $submission->is_late === true) {
            $this->handleLateSubmission($submission);
        }
    }

    /**
     * Handle submission submitted
     */
    protected function handleSubmitted(AssignmentSubmission $submission): void
    {
        // Check if late
        if ($submission->deadline && now()->gt($submission->deadline)) {
            $submission->is_late = true;
            $submission->days_late = now()->diffInDays($submission->deadline);
            $submission->saveQuietly();
        }

        // Fire event (will notify instructor)
        event(new \Modules\Learning\Events\AssignmentSubmitted($submission));
    }

    /**
     * Handle submission graded
     */
    protected function handleGraded(AssignmentSubmission $submission): void
    {
        // Fire event (will notify student)
        event(new \Modules\Learning\Events\AssignmentGraded($submission));
    }

    /**
     * Handle late submission
     */
    protected function handleLateSubmission(AssignmentSubmission $submission): void
    {
        // Send late submission notification
        $notificationService = app(NotificationService::class);
        
        \Modules\Learning\Models\LearningNotification::create([
            'user_id' => $submission->user_id,
            'type' => 'deadline_reminder',
            'notifiable_type' => get_class($submission),
            'notifiable_id' => $submission->id,
            'roadmap_id' => $submission->assignment->lesson->roadmap_id,
            'title' => 'Bài nộp trễ deadline',
            'message' => "Bài tập '{$submission->assignment->title}' đã quá hạn {$submission->days_late} ngày.",
            'action_url' => "/roadmaps/{$submission->assignment->lesson->roadmap_id}/submissions/{$submission->id}",
            'action_text' => 'Xem chi tiết',
            'priority' => 'high',
            'sent_at' => now(),
        ]);
    }

    /**
     * Handle the AssignmentSubmission "deleting" event.
     */
    public function deleting(AssignmentSubmission $submission): void
    {
        // Delete related attachments and feedback
        $submission->attachments()->delete();
        $submission->feedback()->delete();
    }
}
