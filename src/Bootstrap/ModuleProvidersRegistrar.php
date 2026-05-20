<?php

namespace AbdelwahabT\ModulesExt\Bootstrap;

use AbdelwahabT\ModulesExt\Contracts\ModuleRegistrarInterface;
use AbdelwahabT\ModulesExt\Support\ClassNameResolver;
use AbdelwahabT\ModulesExt\Exceptions\ClassNotFoundException;

final readonly class ModuleProvidersRegistrar
{

    public function __construct(
        private ClassNameResolver $classNameResolver,
    ){}

    /**
     * @throws ClassNotFoundException
     */
    public function register(string $providersDir, ModuleRegistrarInterface $provider): void
    {
        foreach (glob($providersDir . '/*.php') as $file) {
            $provider->getApp()->register(
                $this->classNameResolver->resolve($file, $provider->getApp()->basePath())
            );
        }
    }

}