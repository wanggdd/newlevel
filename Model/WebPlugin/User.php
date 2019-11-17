<?php

namespace Model\WebPlugin;

class Model_User extends \Model
{
    public static function getUser($where = '')
    {

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('user_user uu', []);
        $obj->addAndWhere($where);
        $obj->setLimiter(0, 1);
        return $obj->query(false);
    }

    public static function upGrade($user_id,$data = array())
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));

        $info = Model_Grade::getGrade();
        if($info){
            return $obj->update('setting',$data,'user_id=1');
        }else{
            $data['user_id'] = $user_id;
            return $obj->insert('setting',$data);
        }
    }
}