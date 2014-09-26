<?php

namespace NDB;

class Autoloader
{

    public static function prepare($className)
    {

        $className = str_replace('\\', '/', $className);
       
        $path =  __DIR__ . DIRECTORY_SEPARATOR;

        if (is_readable($path . $className . '.php')) {

            require $path . $className . '.php';

        } else if (is_readable($path . $className . '.Class.php')) {

            require $path . $className . '.Class.php';
        }
       
    }

    public static function init()
    {
        spl_autoload_register(__NAMESPACE__ . '\\' . 'Autoloader::prepare');
    }
}

\NDB\Autoloader::init();
