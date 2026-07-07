<?php

namespace Modules\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Payment\Events\DocumentDownloadedByVip;
use Modules\Payment\Notifications\DocumentVipPurchaseNotification;

class SendDocumentVipPurchaseNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(DocumentDownloadedByVip $event): void
    {
        $event->user->notify(new DocumentVipPurchaseNotification($event->document));
    }
}
