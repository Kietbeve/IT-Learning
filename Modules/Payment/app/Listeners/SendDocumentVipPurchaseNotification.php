<?php

namespace Modules\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Payment\Events\DocumentDownloadedByVip;
use Modules\Payment\Notifications\DocumentVipPurchaseNotification;
use Modules\Payment\Notifications\DocumentSoldNotification;
use Modules\Payment\Models\OrderItem;
use App\Models\User;

class SendDocumentVipPurchaseNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(DocumentDownloadedByVip $event): void
    {
        $event->user->notify(new DocumentVipPurchaseNotification($event->document));

        if ($event->document->author_id) {
            $author = User::find($event->document->author_id);
            if ($author) {
                // Find latest order item for this document by this user
                $orderItem = OrderItem::where('document_id', $event->document->id)
                    ->whereHas('order', function ($q) use ($event) {
                        $q->where('user_id', $event->user->id)->where('order_type', 'document');
                    })
                    ->latest()
                    ->first();

                if ($orderItem && $orderItem->order) {
                    $author->notify(new DocumentSoldNotification($event->document, $orderItem->order, (int) $orderItem->contributor_amount, 'vip'));
                }
            }
        }
    }
}
