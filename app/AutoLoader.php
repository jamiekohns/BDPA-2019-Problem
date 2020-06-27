<?php
declare(strict_types=1);

namespace App;

class AutoLoader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            if (stripos($class, __NAMESPACE__) === 0) {
                @include(__DIR__ . DIRECTORY_SEPARATOR . 'app' . str_replace('\\', DIRECTORY_SEPARATOR,
                        strtolower(substr($class, strlen(__NAMESPACE__)))) . '.php');
            }
        }
        );
    }
}
