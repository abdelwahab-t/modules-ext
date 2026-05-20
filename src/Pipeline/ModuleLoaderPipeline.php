<?php

namespace AbdelwahabT\ModulesExt\Pipeline;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;

class ModuleLoaderPipeline
{

    /**
     * @var ModuleLoaderInterface[]
     */
    private array $loaders = [];

    public function add(ModuleLoaderInterface $loader): self
    {
        $this->loaders[] = $loader;
        return $this;
    }

    public function run(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {
        foreach ($this->loaders as $loader) {
            $loader->load($moduleDetailsDto, $provider);
        }
    }
}