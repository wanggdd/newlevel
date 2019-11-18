<?php

namespace Model\WebPlugin;
use Model\WebPlugin\Model_User;

class Model_Grade extends \Model
{
    public static function getGrade($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('user_id='.$user_id);

        $info = $obj->query(false);
        if($info){
            foreach($info as $key=>$item){
                $user_info = Model_User::getUser('id='.$item['user_user_id']);
                $info[$key]['name'] = $user_info['user_name'].'/'.$user_info['nick_name'];
            }
        }
        return $info;
    }

    public static function getOneGrade($id = 0){
        if(!$id){
            return false;
        }
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('id='.$id);

        $info = $obj->query(false);

        return $info ? $info[0] : false;
    }

    public static function getGradeByUserGrade($grade = 0,$user_id = 0){
        if(!$grade || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('grade='.$grade.' and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function getGradeById($id = 0,$user_id = 0){
        if(!$id || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('id='.$id.' and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function getGradeByTitle($title = '',$user_id = 0){
        if(!$title || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('title="'.$title.'" and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function upOneGrade($data = array(),$id = 0){
        if(!$data || !$id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        return $obj->update('grade s',$data,'id='.$id);
    }

    public static function addOneGrade($data = array()){
        if(!$data){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        return $obj->insert('grade',$data);
    }

    public static function getGradeListByUser($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('user_id='.$user_id);
        $obj->addOrderBy('grade','desc');

        return $obj->query(false);
    }

    public static function getMaxGrade($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',['grade'=>'grade']);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('user_id='.$user_id);
        $obj->addOrderBy('grade','desc');
        $obj->setLimiter(0,1);

        return $obj->query(false);
    }
}