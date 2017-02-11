<?php

class Router
{

    public static $URN;
    public static $URNParts;
    private static $module;
    private static $file;

    public static function init()
    {
        self::getURN();
        self::getControllerAndMethod();
        self::dispatcher();
    }

    private static function getURN()
    {
        self::$URN = $_SERVER['REQUEST_URI'];
        self::$URN = substr(self::$URN, 1);

        if (stripos(self::$URN, "?") !== false) {
            self::$URN = substr(self::$URN, 0, stripos(self::$URN, "?"));
        }

        if (substr(self::$URN, -1, 1) == "/") {
            self::$URN = substr(self::$URN, 0, -1);
        }
        self::$URNParts = explode('/', self::$URN);
    }

    private static function getControllerAndMethod()
    {
        if (isset(self::$URNParts[0]) && self::$URNParts[0] != '') {
            self::$module = self::$URNParts[0];
        } else {
            self::$module = "homepage";
        }


        if (isset(self::$URNParts[1])) {
            self::$file = self::$URNParts[1];
        } else {
            self::$file = "index";
        }

        unset(self::$URNParts[0]);
        unset(self::$URNParts[1]);
        self::$URNParts = array_values(self::$URNParts);
    }

    private static function dispatcher()
    {
        $module = self::$module;
        $filename = self::$file;

        $file = '../modules/' . $module . '/' . $filename . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            include("../modules/errorpage404/index.php");
        }
    }

}
