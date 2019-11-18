<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_MemberList;
use Model\WebPlugin\Model_Grade;
use Model\WebPlugin\Model_Member;

$where = array('u.user_id'=>$uid);
if($_GET['start_date']){
    $where['start_date'] = $_GET['start_date'];
}
if($_GET['end_date']){
    $where['end_date'] = $_GET['end_date'];
}
if($_GET['search_mix']){
    $where['search_mix'] = $_GET['search_mix'];
}

$memberlist = Model_MemberList::getMemberList($where,0,1000000);
$member_number = Model_MemberList::getMemberCount($where);
//var_dump($memberlist);exit;

header('Content-Type: application/vnd.ms-excel;charset=gbk');
header('Content-Disposition: attachment;filename="member_info.csv"');
header('Cache-Control: max-age=0');
$fp = fopen('php://output', 'a');
$head = array(
    0 => '用户名',
    1 => '昵称',
    2 => '手机',
    3 => '会员编号',
    4 => '状态',
    5 => '等级',
    6 => '下级数量',
    7 => '注册时间',

);
foreach ($head as $i => $v){
    //$head[$i] = iconv('utf-8', 'gbk', $v);
}
fputcsv($fp, $head);
$datas = [];

foreach($memberlist as $key=>$item){
    if($item['status'] == 0)
        $status = '无状态';
    if($item['status'] == 1)
        $status = '未激活';
    if($item['status'] == 2)
        $status = '已激活';
    if($item['status'] == 3)
        $status = '空';

    //等级
    $grade_info = Model_Grade::getOneGrade($item['grade']);
    if($grade_info)
        $grade = $grade_info['grade'];
    else
        $grade = '无等级';

    $nick_name = $item['nick_name'] ? $item['nick_name'] : $item['user_name'];

    //下级数量
    $lower_count = Model_Member::getLowerCount($item['user_user_id']);

    $datas[] = array($item['user_name'],$nick_name,$item['mobile'],$item['user_user_id'],$status,$grade,$lower_count,$item['input_time']);
}

foreach ($datas as $data){

    fputcsv($fp, $data);
}
