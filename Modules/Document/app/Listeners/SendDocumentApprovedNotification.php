<?php

namespace Modules\Document\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Document\Events\DocumentApproved;
use Modules\Document\Notifications\DocumentApprovedNotification;

class SendDocumentApprovedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;

    public function __construct() {}

    public function handle(DocumentApproved $event): void
    {
        $authorId = $event->document->author_id;
        $author = \App\Models\User::find($authorId);
        if ($author) {
            $author->notify(new DocumentApprovedNotification($event->document));
        }
    }
}
