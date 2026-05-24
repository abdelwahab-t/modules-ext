<?php

namespace AbdelwahabT\ModulesExt\Providers;

use AbdelwahabT\ModulesExt\Console\Commands\MakeModuleGenerateCommand;
use Illuminate\Support\ServiceProvider;

class ModuleArtisanServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/modules-artisan.php', 'modules-artisan');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/modules-artisan.php' => config_path('modules-artisan.php'),
        ], 'modules-artisan-config');

        // Register custom Artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModuleGenerateCommand::class
            ]);
        }
    }
}
