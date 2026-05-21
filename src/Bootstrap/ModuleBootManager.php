<?php

namespace AbdelwahabT\ModulesExt\Bootstrap;

use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Exceptions\ClassNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;

final readonly class ModuleBootManager
{

    public function __construct(
        private ModuleProvidersRegistrar $providersRegistrar,
        private ModulePartsRegistrar $partsRegistrar
    ){}

    /**
     * @throws ClassNotFoundException|BindingResolutionException
     */
    public function boot(string $modulesPath, ModuleRegistrarInterface $provider): void
    {

        if (!File::exists($modulesPath)) {
            return;
        }

        $this->providersRegistrar->register($modulesPath, $provider);
        $this->partsRegistrar->register($modulesPath, $provider);

    }

}