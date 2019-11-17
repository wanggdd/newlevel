<?php

namespace Model\WebPlugin;

class Model_Setting extends \Model
{
    public static function getSetting($uid)
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('setting s',array());
        $obj->addAndWhere('user_id='.$uid);
        $result = $obj->query(false);
        if($result)
            return $result[0];
        return false;
    }

    public static function upSetting($data = array())
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));

        $info = self::getSetting();
        if($info){
            return $obj->update('setting',$data,'user_id=1');
        }else{
            $data['user_id'] = USER_ID;
            return $obj->insert('setting',$data);
        }
    }
}