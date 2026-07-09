<?php

namespace Modules\Document\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Document\Events\CommentReplied;
use Modules\Document\Notifications\CommentRepliedNotification;

class SendCommentRepliedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CommentReplied $event): void
    {
        $event->targetUser->notify(new CommentRepliedNotification($event->replier, $event->document, $event->commentId));
    }
}
