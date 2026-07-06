<?php

namespace Modules\Learning\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Learning\Models\SectionProgress;
use Modules\Learning\Models\AssignmentSubmission;
use Modules\Learning\Observers\SectionProgressObserver;
use Modules\Learning\Observers\AssignmentSubmissionObserver;

class LearningServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Learning';

    protected string $moduleNameLower = 'learning';

    /**
     * Event listener mappings
     */
    protected $listen = [
        \Modules\Learning\Events\LessonCompleted::class => [
            \Modules\Learning\Listeners\UpdateSectionProgressListener::class,
        ],
        \Modules\Learning\Events\SectionCompleted::class => [
            \Modules\Learning\Listeners\CheckCourseCompletionListener::class,
        ],
        \Modules\Learning\Events\CourseCompleted::class => [
            \Modules\Learning\Listeners\IssueCertificateListener::class,
        ],
        \Modules\Learning\Events\AssignmentSubmitted::class => [
            \Modules\Learning\Listeners\NotifyInstructorListener::class,
        ],
    ];

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
        
        // Register model observers
        $this->registerObservers();
        
        // Register event listeners
        $this->registerEventListeners();
    }
    
    /**
     * Register model observers
     */
    protected function registerObservers(): void
    {
        SectionProgress::observe(SectionProgressObserver::class);
        AssignmentSubmission::observe(AssignmentSubmissionObserver::class);
    }
    
    /**
     * Register event listeners
     */
    protected function registerEventListeners(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
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
