<?php

namespace AbdelwahabT\ModulesExt\Exceptions;

use Exception;
use Throwable;

class ClassNotFoundException extends Exception
{
    public function __construct(string $class = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Class {$class} not found", $code, $previous);
    }
}