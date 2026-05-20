<?php

namespace AbdelwahabT\ModulesExt\Providers;

use AbdelwahabT\ModulesExt\Bootstrap\ModuleBootManager;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Exceptions\ClassNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ModulesExtServiceProvider extends ServiceProvider implements ModuleRegistrarInterface
{

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
        $this->moduleBootManager->boot('modules', $this);
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