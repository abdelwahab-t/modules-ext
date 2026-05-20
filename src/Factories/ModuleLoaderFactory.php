<?php

namespace AbdelwahabT\ModulesExt\Factories;

use AbdelwahabT\ModulesExt\Pipeline\ModuleLoaderPipeline;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use AbdelwahabT\ModulesExt\Loaders\{
    ViewsLoader, TranslationsLoader, RoutesLoader, MigrationsLoader, SeedersLoader
};

class ModuleLoaderFactory
{

    private const LOADERS = [
        ViewsLoader::class,
        TranslationsLoader::class,
        RoutesLoader::class,
        MigrationsLoader::class,
        SeedersLoader::class,
    ];

    /**
     * @throws BindingResolutionException
     */
    public function make(Application $application): ModuleLoaderPipeline
    {
        $pipeline = new ModuleLoaderPipeline();

        foreach (self::LOADERS as $loaderClass) {
            $pipeline->add($application->make($loaderClass));
        }

        return $pipeline;
    }

}