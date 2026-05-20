<?php

namespace AbdelwahabT\ModulesExt\Dto;

final readonly class ModuleDetailsDto
{

    public function __construct(
        public string $moduleName,
        public string $moduleBasename,
        public string $viewsPath,
        public string $translationsPath,
        public array $routesPaths,
        public array $migrationsPaths,
        public string $seedersPath,
    ){}

}