<?php

namespace Model\WebPlugin;

class Model_Setting extends \Model
{
    public static function getSetting()
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('setting s');
        $obj->addAndWhere('user_id='.USER_ID);
        return $obj->query(false);
    }

    public static function upSetting($data = array())
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));

        $info = self::getSetting();
        if($info){
            return $obj->update('setting',$data,'user_id=1');
        }else{
            $data['user_id'] = USER_ID;
            return $obj->insert('setting',$data);
        }
    }
}