<?php

namespace Model\WebPlugin;

class Model_Setting extends \Model
{
    public static function getSetting()
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('setting s');
        return $obj->query();
    }


}