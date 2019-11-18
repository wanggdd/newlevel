<?php
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_Paymentrecord;

$user_user_id = $_GET['user_user_id'];

$where = array('m.enter_member'=>$user_user_id,'enter'=>'out');
if($_GET['start_date']){
    $where['start_date'] = $_GET['start_date'];
}
if($_GET['end_date']){
    $where['end_date'] = $_GET['end_date'];
}
if($_GET['search_mix']){
    $where['search_mix'] = $_GET['search_mix'];
}

$record_list = Model_Paymentrecord::getRecordList($where);

header('Content-Type: application/vnd.ms-excel;charset=gbk');
header('Content-Disposition: attachment;filename="member_info.csv"');
header('Cache-Control: max-age=0');
$fp = fopen('php://output', 'a');
$head = array(
    0 => '�û���',
    1 => '�ǳ�',
    2 => '�ֻ�',
    3 => '��Ա���',
    4 => '�տ�����',
    5 => '�����',
    6 => '���״̬',
    7 => '�������',

);
foreach ($head as $i => $v){
    //$head[$i] = iconv('utf-8', 'gbk', $v);
}
fputcsv($fp, $head);
$datas = [];

foreach($record_list as $key=>$item){
    if($item['status'] == 1)
        $status = '���տ�';
    if($item['status'] == 2)
        $status = '���տ�';
    if($item['status'] == 3)
        $status = '�Ѿܾ�';

    $nick_name = $item['nick_name'] ? $item['nick_name'] : $item['user_name'];
    $out_time = $item['out_time'] ? date('Y-m-d',$item['out_time']) : '';
    $enter_time = $item['enter_time'] ? date('Y-m-d',$item['enter_time']) : '';

    $datas[] = array($item['user_name'],$nick_name,$item['mobile'],$item['out_member']
    ,$enter_time,$item['promote_money'],$status,$out_time);
}

foreach ($datas as $data){

    fputcsv($fp, $data);
}
