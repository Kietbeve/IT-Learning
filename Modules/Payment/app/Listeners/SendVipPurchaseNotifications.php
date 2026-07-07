<?php

namespace Modules\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Payment\Events\VipPurchased;
use Modules\Payment\Notifications\VipSubscriptionSuccessNotification;

class SendVipPurchaseNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VipPurchased $event): void
    {
        $event->user->notify(new VipSubscriptionSuccessNotification($event->order));
    }
}
