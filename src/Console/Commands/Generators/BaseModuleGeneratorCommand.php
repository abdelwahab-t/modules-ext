<?php

namespace AbdelwahabT\ModulesExt\Console\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

abstract class BaseModuleGeneratorCommand extends Command
{
    protected $signature = '';

    protected $description = '';

    protected function getModulesBasePath(): string
    {
        // Config key modules-artisan.path defaults to app/Modules
        return base_path(Config::get('modules-artisan.path', 'app/Modules'));
    }

    protected function resolveModulePath(string $module): string
    {
        return $this->getModulesBasePath() . '/' . $module;
    }

    protected function resolveNamespace(string $module): string
    {
        $baseNamespace = Config::get('modules-artisan.namespace', 'App\\Modules');
        return $baseNamespace . '\\' . $module;
    }

    protected function ensureModuleExists(string $module): void
    {
        $path = $this->resolveModulePath($module);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }
}
