<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use Illuminate\Support\Facades\Storage;

final readonly class MigrationsLoader implements ModuleLoaderInterface
{

    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {

        $paths = [];

        foreach ($moduleDetailsDto->migrationsPaths as $path) {
            if (Storage::exists($path)) {
                $paths[] = $path;
            }
        }

        if (empty($paths)) {
            return;
        }

        $provider->loadMigrations($paths);

    }
}