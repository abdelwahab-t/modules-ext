<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use Illuminate\Support\Facades\File;

final readonly class RoutesLoader implements ModuleLoaderInterface
{

    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {

        foreach ($moduleDetailsDto->routesPaths as $path) {
            if (!File::exists($path)) {
                continue;
            }
            $provider->loadRoutes($path);
        }

    }
}