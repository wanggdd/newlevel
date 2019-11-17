<?php

namespace Model\WebPlugin;
use Model\WebPlugin\Model_User;

class Model_Member extends \Model
{
    public static function getMemberById($user_id = 0)
    {
        if (!$user_id) {
            return fasle;
        }
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member s',[]);
        $obj->addAndWhere('user_user_id=' . $user_id);

        return $obj->query(faslse);
    }

    public static function upOneMember($data = array(), $user_user_id = 0)
    {
        if (!$data || !$user_user_id) {
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));

        $info = self::getMemberById($user_user_id);
        if ($info){
            return $obj->update('member s', $data, 'and user_id=' . USER_ID . ' and user_user_id=' . $user_user_id);
        }else{
            $data['user_id'] = USER_ID;
            $data['user_user_id'] = $user_user_id;
            $data['status'] = 2;
            $data['grade'] = $data['grade_id'];

            return $obj->insert('member',$data);
        }

    }
}


