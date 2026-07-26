<?php

namespace AbdelwahabT\ModulesExt\Support;

use AbdelwahabT\ModulesExt\Exceptions\ClassNotFoundException;

final readonly class ClassNameResolver
{

    /**
     * @throws ClassNotFoundException
     */
    public function resolve(string $file, string $basePath): string
    {
        $class = str_replace(
            [$basePath . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, ".php"],
            ['', '\\', ''],
            $file
        );

        if (!class_exists($class)) {
            $class = ucfirst($class);

            if (!class_exists($class)) {
                throw new ClassNotFoundException($class);
            }
        }

        return $class;
    }

}