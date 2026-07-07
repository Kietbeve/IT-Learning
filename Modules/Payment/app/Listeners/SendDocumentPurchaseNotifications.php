<?php

namespace Modules\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Document\Models\Document;
use Modules\Payment\Events\DocumentPurchased;
use Modules\Payment\Notifications\DocumentPurchaseSuccessNotification;

class SendDocumentPurchaseNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(DocumentPurchased $event): void
    {
        $firstItem = $event->order->items->first();
        if ($firstItem && $firstItem->document_id) {
            $document = Document::find($firstItem->document_id);
            if ($document) {
                $event->user->notify(new DocumentPurchaseSuccessNotification($document, $event->order));
            }
        }
    }
}
