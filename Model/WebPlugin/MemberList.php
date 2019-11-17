<?php
namespace Model\WebPlugin;

class Model_MemberList extends \Model
{
    public static function getMemberList($where=[],$start=0,$limit=20)
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member m',[]);
        $obj->leftJoin('user_user u',[],'m.user_user_id=u.id');

        if(isset($where['start_date'])){
            $obj->addAndWhere('u.input_time>='.$where['start_date']);
            unset($where['start_date']);
        }
        if(isset($where['end_date'])){
            $obj->addAndWhere('u.input_time<='.$where['end_date']);
            unset($where['end_date']);
        }
        if(isset($where['search_mix'])){
            $obj->addAndWhere('(u.user_name like "%'.$where['search_mix'].'%" or u.nick_name like "%'.$where['search_mix'].'%" or u.mobile like "%'.$where['search_mix'].'%")');
            unset($where['search_mix']);
        }

        foreach ($where as $k=>$v){
            $where_sql = $k.'='.$v;
            $obj->addAndWhere($where_sql);
        }
        $obj->setLimiter($start,$limit);
        //$obj->query(false);
        //echo $obj->getSQL();exit;
        return $obj->query(false);
    }

    public static function getMemberCount($where=[]){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member m',[]);
        $obj->leftJoin('user_user u',[],'m.user_user_id=u.id');

        if(isset($where['start_date'])){
            $obj->addAndWhere('u.input_time>='.$where['start_date']);
            unset($where['start_date']);
        }
        if(isset($where['end_date'])){
            $obj->addAndWhere('u.input_time<='.$where['end_date']);
            unset($where['end_date']);
        }
        if(isset($where['search_mix'])){
            $obj->addAndWhere('(u.user_name like "%'.$where['search_mix'].'%" or u.nick_name like "%'.$where['search_mix'].'%" or u.mobile like "%'.$where['search_mix'].'%")');
            unset($where['search_mix']);
        }

        foreach ($where as $k=>$v){
            $where_sql = $k.'='.$v;
            $obj->addAndWhere($where_sql);
        }
        return $obj->count();
    }

    public static function getMember($user_id,$user_user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member m',[]);
        $obj->addAndWhere('user_id='.$user_id);
        $obj->addAndWhere('user_user_id='.$user_user_id);
        $result = $obj->query(false);
        if($result)
            return $result[0];

        return false;
    }

}