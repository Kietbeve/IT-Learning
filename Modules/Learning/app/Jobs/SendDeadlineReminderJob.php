<?php

namespace Modules\Learning\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Services\AssignmentService;
use Illuminate\Support\Facades\Log;

class SendDeadlineReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $assignmentService = app(AssignmentService::class);
            
            // Check và update late submissions
            $lateCount = $assignmentService->checkLateSubmissions();
            
            // Gửi deadline reminders
            $reminderCount = $assignmentService->sendDeadlineReminders();
            
            Log::info("SendDeadlineReminderJob completed", [
                'late_submissions' => $lateCount,
                'reminders_sent' => $reminderCount,
            ]);
        } catch (\Exception $e) {
            Log::error("SendDeadlineReminderJob failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['learning', 'reminders', 'assignments'];
    }
}
