<?php

namespace Modules\Learning\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendScheduledNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $notificationService = app(NotificationService::class);
            
            // Send all scheduled notifications
            $count = $notificationService->sendScheduledNotifications();
            
            Log::info("Scheduled notifications sent", [
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error("SendScheduledNotificationsJob failed", [
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['learning', 'notifications', 'scheduled'];
    }
}
