<?php
//����¼
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_Paymentrecord;
use Model\WebPlugin\Model_Member;
use Model\WebPlugin\Model_Grade;

$user_user_id = $_REQUEST['user_user_id'];
if(!$user_user_id){
    die();
}

//��ȡ����¼
$where = array('m.out_member'=>$user_user_id,'enter'=>'out');
if(isset($_GET['type']) && $_GET['type'] == 'search' && $_GET['all'] != '1'){
    if($_GET['start_date']){
        $where['start_date'] = $_GET['start_date'];
    }
    if($_GET['end_date']){
        $where['end_date'] = $_GET['end_date'];
    }
    if($_GET['search_mix']){
        $where['search_mix'] = $_GET['search_mix'];
    }
}

//var_dump($_GET);exit;
//�޸ĵȼ�
if(isset($_GET['type']) && $_GET['type'] == 'upgrade'){
    $ids_arr = explode(',',$_GET['ids']);
    $status = $_GET['status'];
    foreach($ids_arr as $key=>$item){
        Model_Paymentrecord::upRecord(array('status'=>intval($status[$key])),intval($item));
        $info = Model_Paymentrecord::getRecordById(intval($item));
        //�ж�����ǽ�״̬��Ϊ"���տ�"������Ҫ�ж��Ƿ���Ҫ�������߼���
        if($status[$key] == 2){
            $member = Model_Member::getMemberById($info[0]['out_member']);
            $member2 = Model_Member::getMemberById($info[0]['enter_member']);
            if($member2[0]['status'] == 1){//δ����
                //todo
                Model_Member::active($member2[0]['user_user_id']);
            }
            if($member[0]['status'] == 2){
                //todo
                Model_Grade::promote($uid,$member[0]['user_user_id']);
            }
        }else{
            //�ܾ��ʹ��տ��ж��Ƿ���Ҫ����
            $member = Model_Member::getMemberById($info[0]['enter_member']);
            $grade_id = $member[0]['grade'];
            $grade_info = Model_Grade::getGradeById($grade_id,$uid);
            $lower_count = Model_Member::getLowerCount($info[0]['enter_member']);

            //��ȡ��ǰ���տ��¼
            $enter_count = Model_Paymentrecord::getRecordCount(array('enter_member'=>$info[0]['enter_member'],'payment_type'=>1,'status'=>2));
            if(($grade_info[0]['promote_lower_num'] > $lower_count) || ($grade_info[0]['promote_lower_num'] > $enter_count)){
                //��Ҫ����
                $lower_grade = Model_Grade::getNextGrade($uid,$grade_id,'small');
                Model_Member::upOneMember(array('grade'=>$lower_grade),$info[0]['enter_member']);
            }

        }
    }
}

//ɾ������
if(isset($_GET['type']) && $_GET['type'] == 'delgrade'){
    $ids_arr = explode(',',$_GET['ids']);
    foreach($ids_arr as $key=>$item){
        Model_Paymentrecord::delRecord($item);
    }
}

$page  = intval($_GET['page']);
if($page<1){
    $page = 1;
}

$pagesize = 20;
$offset = ($page-1)*$pagesize;

$record_list = Model_Paymentrecord::getRecordList($where,$offset,$pagesize);
//var_dump($record_list);exit;
$enter_count = Model_Paymentrecord::getRecordCount($where);
if($record_list){
    foreach($record_list as $key=>$item){
        $record_list[$key]['pic'] = $item['pic'] ? $item['pic'] : 'http://aimg8.dlszyht.net.cn/default/user_user_profile.jpg';
        $record_list[$key]['enter_time'] = date('Y-m-d',$item['enter_time']);
        $record_list[$key]['out_time'] = date('Y-m-d',$item['out_time']);
        //�տ���
        $code = Model_Member::getMemberById($item['enter_member']);
        $record_list[$key]['payment_code'] = $code[0]['payment_code'];
    }
}

$totalpage = ceil($enter_count/$pagesize);
$page = new Pager($enter_count,$page,$pagesize);
$page_str = $page->GetPagerContent();

$smarty->assign("record_list",$record_list);
$smarty->assign("enter_count",$enter_count);
$smarty->assign("totalpage",$totalpage);
$smarty->assign("page_str",$page_str);

$smarty->assign("start_date",$_GET['start_date'] ? $_GET['start_date'] : '');
$smarty->assign("end_date",$_GET['end_date'] ? $_GET['end_date'] : '');
$smarty->assign("search_mix",$_GET['search_mix'] ? $_GET['search_mix'] : '');

$smarty->assign("title",'����¼');
$smarty->assign("action",'memberlist');
$smarty->assign('user_user_id',$user_user_id);
$smarty->display('nine_fenxiao/outrecord.tpl');
