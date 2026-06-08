<?php

namespace Modules\Document\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Document';

    protected string $moduleNameLower = 'document';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Log::info('DocumentServiceProvider booting...');
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Đăng ký Livewire components
        if (class_exists(\Livewire\Livewire::class)) {
            \Illuminate\Support\Facades\Log::info('Livewire class exists, registering components...');
            \Livewire\Livewire::component('user-document-list', \Modules\Document\Http\Livewire\User\DocumentList::class);
            \Livewire\Livewire::component('user-document-detail', \Modules\Document\Http\Livewire\User\DocumentDetail::class);
            \Livewire\Livewire::component('user-bookmarked-documents', \Modules\Document\Http\Livewire\User\BookmarkedDocuments::class);
            \Livewire\Livewire::component('user-purchased-documents', \Modules\Document\Http\Livewire\User\PurchasedDocuments::class);

            \Livewire\Livewire::component('admin-document-moderation', \Modules\Document\Http\Livewire\Admin\DocumentModeration::class);
            \Livewire\Livewire::component('admin-document-detail', \Modules\Document\Http\Livewire\Admin\DocumentDetail::class);
            \Livewire\Livewire::component('admin-document-list', \Modules\Document\Http\Livewire\Admin\DocumentList::class);
            \Livewire\Livewire::component('admin-category-list', \Modules\Document\Http\Livewire\Admin\CategoryList::class);
        } else {
            \Illuminate\Support\Facades\Log::warning('Livewire class NOT found!');
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
        // $this->commands([]);
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

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.ltrim(config('modules.paths.generator.component-class.path'), config('modules.paths.app_folder','')));
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
