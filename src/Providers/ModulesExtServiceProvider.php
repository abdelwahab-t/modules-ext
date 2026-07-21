<?php

namespace AbdelwahabT\ModulesExt\Providers;

use AbdelwahabT\ModulesExt\Bootstrap\ModuleBootManager;
use AbdelwahabT\ModulesExt\Console\Commands\MakeModuleCommand;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Exceptions\ClassNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ModulesExtServiceProvider extends ServiceProvider implements ModuleRegistrarInterface
{

    const MODULES_PATH = [
        'App/Modules',
        'app/Modules',
        'app/modules'
    ];

    private ModuleBootManager $moduleBootManager;

    public function __construct($app)
    {
        $this->moduleBootManager = $app->make(ModuleBootManager::class);
        parent::__construct($app);
    }

    /**
     * @throws BindingResolutionException|ClassNotFoundException
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModuleCommand::class,
            ]);
        }

        $modulesPath = null;

        foreach (self::MODULES_PATH as $path) {
            if (is_dir(base_path($path))) {
                $modulesPath = base_path($path);
                break;
            }
        }

        if ($modulesPath) {
            $this->moduleBootManager->boot($modulesPath, $this);
        }

    }

    public function loadViews(string|array $path, string $namespace): void
    {
        parent::loadViewsFrom($path, $namespace);
    }

    public function loadTranslations(string $path, string $namespace): void
    {
        parent::loadTranslationsFrom($path, $namespace);
    }

    public function loadRoutes(string $path): void
    {
        parent::loadRoutesFrom($path);
    }

    public function loadMigrations(array|string $paths): void
    {
        parent::loadMigrationsFrom($paths);
    }

    public function getApp(): Application
    {
        return $this->app;
    }
}