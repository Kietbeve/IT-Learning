<?php

namespace Modules\Document\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Document\Events\CommentReplied;
use Modules\Document\Listeners\SendCommentRepliedNotification;
use Modules\Document\Events\DocumentApproved;
use Modules\Document\Listeners\SendDocumentApprovedNotification;
use Modules\Document\Events\DocumentRejected;
use Modules\Document\Listeners\SendDocumentRejectedNotification;
use Modules\Document\Events\DocumentDeletedByAdmin;
use Modules\Document\Listeners\SendDocumentDeletedNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the Document module.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CommentReplied::class => [
            SendCommentRepliedNotification::class,
        ],
        DocumentApproved::class => [
            SendDocumentApprovedNotification::class,
        ],
        DocumentRejected::class => [
            SendDocumentRejectedNotification::class,
        ],
        DocumentDeletedByAdmin::class => [
            SendDocumentDeletedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
