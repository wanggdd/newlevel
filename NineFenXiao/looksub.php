<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_MemberList;
use Model\WebPlugin\Model_Grade;

$user_user_id = $_REQUEST['user_user_id'];
if(!$user_user_id){
    die();
}

//�¼��б�

$page  = intval($_GET['page']);
if($page<1){
    $page = 1;
}

$pagesize = 20;
$offset = ($page-1)*$pagesize;

$where = array('u.user_id'=>$uid,'m.higher_id'=>$user_user_id);

$memberlist = Model_MemberList::getMemberList($where,0,1000000);
//var_dump($memberlist);exit;
if($memberlist){
    foreach($memberlist as $key=>$item){
        //�ȼ�
        $member_grade = Model_Grade::getOneGrade($item['grade']);
        if($member_grade){
            $memberlist[$key]['grade_val'] = $member_grade['title'];
        }
        //״̬
        if($item['status'] == 0)
            $memberlist[$key]['status_title'] = '��״̬';
        if($item['status'] == 1)
            $memberlist[$key]['status_title'] = 'δ����';
        if($item['status'] == 2)
            $memberlist[$key]['status_title'] = '�Ѽ���';
        if($item['status'] == 0)
            $memberlist[$key]['status_title'] = '��';
    }
}
//var_dump($memberlist);exit;
$member_number = Model_MemberList::getMemberCount($where);

$totalpage = ceil($member_number/$pagesize);
$page = new Pager($member_number,$page,$pagesize);
$page_str = $page->GetPagerContent();
//echo $page_str;exit;

//�ȼ��б�
$grade_list = Model_Grade::getGradeListByUser($uid);

$smarty->assign("memberlist",$memberlist);
$smarty->assign("membernumber",$member_number);
$smarty->assign("totalpage",$totalpage);
$smarty->assign("page_str",$page_str);
$smarty->assign("page",$page);
$smarty->assign("grade_list",$grade_list);
$smarty->assign("action",'memberlist');
$smarty->assign("title",'��Ա�б�');
$smarty->display('nine_fenxiao/lookSub.tpl');