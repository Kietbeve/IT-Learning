<?php

namespace Modules\Document\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Document\Events\DocumentDeletedByAdmin;
use Modules\Document\Notifications\DocumentDeletedNotification;

class SendDocumentDeletedNotification implements ShouldQueue
{
    public function __construct() {}

    public function handle(DocumentDeletedByAdmin $event): void
    {
        $author = \App\Models\User::find($event->author->id);
        if ($author) {
            $author->notify(new DocumentDeletedNotification($event->documentTitle));
        }
    }
}
