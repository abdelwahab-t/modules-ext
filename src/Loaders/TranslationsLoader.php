<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use Illuminate\Support\Facades\File;

final readonly class TranslationsLoader implements ModuleLoaderInterface
{

    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {
        if (!File::exists($moduleDetailsDto->translationsPath)) {
            return;
        }

        $provider->loadTranslations(
            $moduleDetailsDto->translationsPath,
            $moduleDetailsDto->moduleBasename
        );
    }
}