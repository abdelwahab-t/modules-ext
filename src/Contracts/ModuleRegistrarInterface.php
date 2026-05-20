<?php

namespace AbdelwahabT\ModulesExt\Contracts;

use Illuminate\Contracts\Foundation\Application;

interface ModuleRegistrarInterface
{
    public function loadViews(string|array $path, string $namespace): void;

    public function loadTranslations(string $path, string $namespace): void;

    public function loadRoutes(string $path): void;

    public function loadMigrations(array|string $paths): void;

    public function getApp(): Application;
}
