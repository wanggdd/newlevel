<?php

class Ebase
{

    public static $_DB = array();

    public static function run()
    {
    }

    public static function getDb($name)
    {
        return self::$_DB[$name] ? self::$_DB[$name] : false;
    }
}

Ebase::$_DB['DB_Plugin']      = $DB_Plugin;
Ebase::$_DB['DB_Plugin_R']    = $DB_Plugin_R;

