<?php
namespace Model\WebPlugin;

class Model_MemberList extends \Model
{
    public static function getMemberList($where=[],$start=0,$limit=20)
    {
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member m',[]);
        foreach ($where as $k=>$v){
            $where_sql = $k.'='.$v;
            $obj->addAndWhere($where_sql);
        }
        $obj->setLimiter($start,$limit);
        return $obj->query(false);
    }

    public static function getMemberCount($where=[]){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('member m',[]);
        foreach ($where as $k=>$v){
            $where_sql = $k.'='.$v;
            $obj->addAndWhere($where_sql);
        }
        return $obj->count();
    }


}