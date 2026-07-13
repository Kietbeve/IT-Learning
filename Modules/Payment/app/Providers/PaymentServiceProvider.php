<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Payment\Console\Commands\SendVipExpiryReminders;
use Modules\Payment\Http\Livewire\Admin\OrderManagement;
use Modules\Payment\Http\Livewire\Admin\PayoutReview;
use Modules\Payment\Http\Livewire\Admin\PlatformRevenue;
use Modules\Payment\Http\Livewire\Admin\RevenueSettings;
use Modules\Payment\Http\Livewire\Admin\TransactionList;
use Modules\Payment\Http\Livewire\Contributor\EarningsReport;
use Modules\Payment\Http\Livewire\Contributor\PayoutRequest;
use Modules\Payment\Http\Livewire\Contributor\Wallet;
use Modules\Payment\Http\Livewire\User\CheckoutModal;
use Modules\Payment\Http\Livewire\User\Purchases;
use Modules\Payment\Http\Livewire\User\Subscription;
use Modules\Payment\Http\Livewire\User\TransactionHistory;

class PaymentServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Payment';

    protected string $moduleNameLower = 'payment';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Dang ky Livewire components
        if (class_exists(Livewire::class)) {
            Livewire::component('user-subscription', Subscription::class);
            Livewire::component('user-transaction-history', TransactionHistory::class);
            Livewire::component('user-purchases', Purchases::class);
            Livewire::component('payment-checkout-modal', CheckoutModal::class);

            // Contributor payment components
            Livewire::component('contributor-wallet', Wallet::class);
            Livewire::component('contributor-transaction-history', \Modules\Payment\Http\Livewire\Contributor\TransactionHistory::class);
            Livewire::component('contributor-payout-request', PayoutRequest::class);
            Livewire::component('contributor-earnings-report', EarningsReport::class);

            // Admin payment components
            Livewire::component('admin-order-management', OrderManagement::class);
            Livewire::component('admin-transaction-list', TransactionList::class);
            Livewire::component('admin-payout-review', PayoutReview::class);
            Livewire::component('admin-platform-revenue', PlatformRevenue::class);
            Livewire::component('admin-revenue-settings', RevenueSettings::class);
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        $this->commands([
            SendVipExpiryReminders::class,
        ]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);

        // Cấu hình VIP Subscriptions
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/subscription.php'), 'subscription');
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.ltrim(config('modules.paths.generator.component-class.path'), config('modules.paths.app_folder', '')));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
