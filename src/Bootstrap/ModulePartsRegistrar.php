<?php

namespace AbdelwahabT\ModulesExt\Bootstrap;

use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use AbdelwahabT\ModulesExt\Factories\ModuleLoaderFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;

final readonly class ModulePartsRegistrar
{

    public function __construct(
        private ModuleLoaderFactory $moduleLoaderFactory,
    ){}

    /**
     * @throws BindingResolutionException
     */
    public function register(string $modulesPath, ModuleRegistrarInterface $provider): void
    {
        foreach (File::directories($modulesPath) as $module) {
            $this->moduleLoaderFactory->make($provider->getApp())->run(
                $this->getModuleDto($module),
                $provider
            );
        }
    }

    private function getModuleDto(string $module): ModuleDetailsDto
    {
        return new ModuleDetailsDto(
            $module,
            basename($module),
            $module . '/views',
            $module . '/lang',
            [$module . '/routes/web.php', $module . '/routes/api.php'],
            [$module . '/database/migrations'],
            $module . '/database/seeders',
        );
    }

}