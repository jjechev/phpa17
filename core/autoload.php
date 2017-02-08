<?php

spl_autoload_register('Autoloader::load');

class Autoloader
{

    private static $autoloadPaths = array("core");

    public static function load($className)
    {
        foreach (self::$autoloadPaths as $path) {
            $file = "../" . $path . "/" . $className . ".php";
            if (file_exists($file)) {
                require_once($file);
                break;
            }
        }
    }

}
