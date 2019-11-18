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

    public static function getLowerList(){

    }

    public static function getLowerCount($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member s',[]);
        $obj->addAndWhere('higher_id=' . $user_id);

        return $obj->count();
    }

    public static function upOneMember($data = array(), $user_user_id = 0)
    {
        if (!$data || !$user_user_id) {
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));

        $info = self::getMemberById($user_user_id);
        if ($info){
            $obj->update('member s',$data,'user_user_id='.$user_user_id);
        }else{
            $data['user_id'] = USER_ID;
            $data['user_user_id'] = $user_user_id;
            $data['status'] = 2;
            $data['grade'] = $data['grade'];
            $data['create_time'] = time();

            return $obj->insert('member',$data);
        }

    }

    public static function addOneMember($data = array()){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        return $obj->insert('member',$data);
    }

    //激活 从1-2
    public static function active($uid,$user_id=1){
        //找出grade表中主键
        $grade = Model_Grade::getMinGrade($user_id);
        $gid = 0;
        if($grade){
            $gid = $grade['id'];
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->update('member s',['status'=>2,'grade'=>$gid],'user_user_id='.$uid);

        //查看此会员是否有下级，若有下级需要将下级的状态改为未激活，就可以做邀请下级的任务了
        $list = self::getLowerListAndCount($uid);
        if($list){
            foreach($list['record'] as $key=>$item){
                self::upOneMember(array('status'=>1),$item['user_user_id']);
            }
        }

        return true;
    }

    public static function getLowerListAndCount($user_id = 0){
        if(!$user_id)
            return false;

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member s',[]);
        $obj->addAndWhere('higher_id='.$user_id);

        $record = $obj->query(false);
        if($record){
            $count = $obj->count();
            return array('record'=>$record,'count'=>$count);
        }
        return fasle;
    }

}


