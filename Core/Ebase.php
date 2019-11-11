<?php

class Ebase
{
    public static $_DB = array();

    public static function run()
    {
    }

    public static function getDb($name = '')
    {

        if ($name == 'DB_Pluginl' && !self::$_DB['DB_Pluginl']) {
            self::$_DB['DB_Pluginl'] = new DB_Plugin_Wl;
            self::$_DB['DB_Pluginl']->query("set names latin1");
        }
        if ($name == 'DB_Plugin_Wl' && !self::$_DB['DB_Plugin_Wl']) {
            self::$_DB['DB_Plugin_Wl'] = new DB_Plugin_Wl;
            self::$_DB['DB_Plugin_Wl']->query("set names latin1");
        }

        return self::$_DB[$name];
    }
}
