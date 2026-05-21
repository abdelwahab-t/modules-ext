<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use Illuminate\Support\Facades\File;

final readonly class ViewsLoader implements ModuleLoaderInterface
{

    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {
        if (!File::exists($moduleDetailsDto->viewsPath)) {
            return;
        }

        $provider->loadViews(
            $moduleDetailsDto->viewsPath,
            $moduleDetailsDto->moduleBasename
        );
    }
}