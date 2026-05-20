<?php

namespace AbdelwahabT\ModulesExt\Loaders;

use AbdelwahabT\ModulesExt\Contracts\ModuleLoaderInterface;
use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Dto\ModuleDetailsDto;
use AbdelwahabT\ModulesExt\Exceptions\ClassNotFoundException;
use AbdelwahabT\ModulesExt\Support\ClassNameResolver;

final readonly class SeedersLoader implements ModuleLoaderInterface
{

    public function __construct(
        private ClassNameResolver $classNameResolver,
    ){}

    /**
     * @throws ClassNotFoundException
     */
    public function load(ModuleDetailsDto $moduleDetailsDto, ModuleRegistrarInterface $provider): void
    {
        if (!$provider->getApp()->runningInConsole()){
            return;
        }

        foreach (glob($moduleDetailsDto->seedersPath . '/*Seeder.php') as $file) {
            $provider->getApp()->afterResolving(
                'Illuminate\Database\Seeder',
                fn ($seeder) => $seeder->call(
                    $this->classNameResolver->resolve($file, $provider->getApp()->basePath())
                )
            );

        }
    }


}