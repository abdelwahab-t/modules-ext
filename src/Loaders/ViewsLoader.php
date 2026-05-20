<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use Illuminate\Contracts\Filesystem\Filesystem;

final readonly class ViewsLoader implements ModuleLoaderInterface
{

    public function __construct(
        private Filesystem $filesystem
    ){}

    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {
        if (!$this->filesystem->exists($moduleDetailsDto->viewsPath)) {
            return;
        }

        $provider->loadViews(
            $moduleDetailsDto->viewsPath,
            $moduleDetailsDto->moduleBasename
        );
    }
}