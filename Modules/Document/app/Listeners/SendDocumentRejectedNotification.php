<?php

namespace Modules\Document\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Document\Events\DocumentRejected;
use Modules\Document\Notifications\DocumentRejectedNotification;

class SendDocumentRejectedNotification implements ShouldQueue
{
    public function __construct() {}

    public function handle(DocumentRejected $event): void
    {
        $authorId = $event->document->author_id;
        $author = \App\Models\User::find($authorId);
        if ($author) {
            $author->notify(new DocumentRejectedNotification($event->document, $event->reason));
        }
    }
}
