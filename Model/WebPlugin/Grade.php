<?php

namespace Model\WebPlugin;
use Model\WebPlugin\Model_User;

class Model_Grade extends \Model
{
    public static function getGrade()
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('grade s');
        $obj->addAndWhere('user_id='.USER_ID);

        $info = $obj->query(false);
        if($info){
            foreach($info as $key=>$item){
                $user_info = Model_User::getUser('user_name','id='.$item['user_user_id']);
                $info[$key]['name'] = $user_info['user_name'].'/'.$user_info['nick_name'];
            }
        }
        return $info;
    }

    public static function upGrade($data = array())
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));

        $info = self::getGrade();
        if($info){
            return $obj->update('setting',$data,'user_id=1');
        }else{
            $data['user_id'] = USER_ID;
            return $obj->insert('setting',$data);
        }
    }
}