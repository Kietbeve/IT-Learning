<?php

namespace Modules\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Payment\Events\VipExpiringSoon;
use Modules\Payment\Mail\VipExpiryReminderMail;

class SendVipExpiryReminder implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VipExpiringSoon $event): void
    {
        $user = $event->user;
        $daysRemaining = $event->daysRemaining;

        // Send email notification via Resend
        Mail::to($user->email)->send(
            new VipExpiryReminderMail($user, $daysRemaining)
        );

    }
}
