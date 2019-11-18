<?php
namespace Model\WebPlugin;
use Model\WebPlugin\Model_User;

class Model_Paymentrecord extends \Model
{
    public static function getRecordList($where=[],$start=0,$limit=20)
    {
        $enter = $where['enter'] ? $where['enter'] : 'out';
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('payment_record m',[]);
        if($enter == 'enter'){
            $obj->leftJoin('user_user u',['user_name'=>'user_name','nick_name'=>'nick_name','input_time'=>'input_time','pic'=>'pic'],'m.out_member=u.id');
        }else{
            $obj->leftJoin('user_user u',['user_name'=>'user_name','nick_name'=>'nick_name','input_time'=>'input_time','pic'=>'pic'],'m.enter_member=u.id');
        }


        if(isset($where['start_date'])){
            $obj->addAndWhere('u.out_time>'.strtotime($where['start_date']));
            unset($where['start_date']);
        }
        if(isset($where['end_date'])){
            $obj->addAndWhere('u.input_time<='.strtotime($where['end_date']));
            unset($where['end_date']);
        }
        if(isset($where['search_mix'])){
            $obj->addAndWhere('(u.user_name like "%'.$where['search_mix'].'%" or u.nick_name like "%'.$where['search_mix'].'%" or u.mobile like "%'.$where['search_mix'].'%")');
            unset($where['search_mix']);
        }

        unset($where['enter']);
        foreach ($where as $k=>$v){
            $where_sql = $k.'='.$v;
            $obj->addAndWhere($where_sql);
        }
        $obj->addAndWhere('m.is_del=0');
        $obj->setLimiter($start,$limit);
        //$obj->query(false);
        //echo $obj->getSQL();exit;
        return $obj->query(false);
    }

    public static function getRecordCount($where=[]){
        $enter = $where['enter'] ? $where['enter'] : 'out';
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('payment_record m',[]);
        if($enter == 'enter'){
            $obj->leftJoin('user_user u',[],'m.enter_member=u.id');
        }else{
            $obj->leftJoin('user_user u',[],'m.out_member=u.id');
        }


        if(isset($where['start_date'])){
            $obj->addAndWhere('u.out_time>'.strtotime($where['start_date']));
            unset($where['start_date']);
        }
        if(isset($where['end_date'])){
            $obj->addAndWhere('u.input_time<='.strtotime($where['end_date']));
            unset($where['end_date']);
        }
        if(isset($where['search_mix'])){
            $obj->addAndWhere('(u.user_name like "%'.$where['search_mix'].'%" or u.nick_name like "%'.$where['search_mix'].'%" or u.mobile like "%'.$where['search_mix'].'%")');
            unset($where['search_mix']);
        }

        unset($where['enter']);
        foreach ($where as $k=>$v){
            $where_sql = $k.'='.$v;
            $obj->addAndWhere($where_sql);
        }

        $obj->addAndWhere('m.is_del=0');
        //echo $obj->getSQL();exit;
        return $obj->count();
    }

    public static function getRecordById($id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('payment_record m',[]);
        $obj->addAndWhere('id='.$id);

        return $obj->query(false);

    }

    public static function upRecord($data,$id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        return $obj->update('payment_record',$data,'id='.$id);
    }

    public static function delRecord($id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->update('payment_record',array('is_del'=>1),'id='.$id);
    }
}