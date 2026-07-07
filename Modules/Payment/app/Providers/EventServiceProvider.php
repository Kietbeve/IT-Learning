<?php

namespace Modules\Payment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Payment\Events\VipExpiringSoon;
use Modules\Payment\Events\VipPurchased;
use Modules\Payment\Listeners\SendVipExpiryReminder;
use Modules\Payment\Listeners\SendVipPurchaseNotifications;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the Payment module.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        VipPurchased::class => [
            SendVipPurchaseNotifications::class,
        ],

        VipExpiringSoon::class => [
            SendVipExpiryReminder::class,
        ],

        \Modules\Payment\Events\DocumentPurchased::class => [
            \Modules\Payment\Listeners\SendDocumentPurchaseNotifications::class,
        ],

        \Modules\Payment\Events\DocumentDownloadedByVip::class => [
            \Modules\Payment\Listeners\SendDocumentVipPurchaseNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
