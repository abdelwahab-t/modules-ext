<?php

namespace AbdelwahabT\ModulesExt\Contracts;

use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;

interface ModuleLoaderInterface
{
    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void;
}