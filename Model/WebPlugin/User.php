<?php

namespace Model\WebPlugin;

class Model_User extends \Model
{
    public static function getUser($field = [], $where = '')
    {

        $fields = is_array($field) ? $field : [$field => $field];
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('user_user uu', $fields);
        $obj->addAndWhere($where);
        $obj->setLimiter(0, 1);
        return is_array($field) ? $obj->query() : $obj->query()->$field;
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