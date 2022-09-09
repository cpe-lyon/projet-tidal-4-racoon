<?php

namespace Helper\App;

class Autoloader
{

    public static function autoload(): void
    {
        spl_autoload_register(function ($class) {
            $class = str_replace('\\', '/', $class);
            $class = __DIR__ . '/../../' . $class . '.php';
            if (file_exists($class)) {
                require_once $class;
            }
        });

        require_once Constant::DIR_VENDOR . 'autoload.php';
    }
}

Autoloader::autoload();