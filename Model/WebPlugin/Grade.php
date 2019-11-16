<?php

namespace Model\WebPlugin;
use Model\WebPlugin\Model_User;

class Model_Grade extends \Model
{
    public static function getGrade(){
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

    public static function getOneGrade($id = 0){
        if(!$id){
            return false;
        }
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('grade s');
        $obj->addAndWhere('id='.$id);

        $info = $obj->query(false);

        return $info ? $info[0] : false;
    }

    public static function getGradeByUserGrade($grade = 0,$user_id = 0){
        if(!$grade || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('grade s');
        $obj->addAndWhere('grade='.$grade.' and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function upOneGrade($data = array(),$id = 0){
        if(!$data || !$id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        return $obj->update('grade s',$data,'id='.$id.' and user_id='.USER_ID);
    }

    public static function addOneGrade($data = array()){
        if(!$data){
            return false;
        }
        var_dump($data);

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->insert('grade',$data);
        echo $obj->getSql();exit;
    }

    public static function getGradeListByUser(){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Pluginl'));
        $obj->from('grade s');
        $obj->addAndWhere('user_id='.USER_ID);
        $obj->addOrderBy('grade','desc');

        return $obj->query(false);
    }
}