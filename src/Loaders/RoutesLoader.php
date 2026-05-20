<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use Illuminate\Contracts\Filesystem\Filesystem;

final readonly class RoutesLoader implements ModuleLoaderInterface
{

    public function __construct(
        private Filesystem $filesystem
    ){}

    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {

        foreach ($moduleDetailsDto->routesPaths as $path) {
            if (!$this->filesystem->exists($path)) {
                continue;
            }
            $provider->loadRoutes($path);
        }

    }
}