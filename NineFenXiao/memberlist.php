<?php
/**
 会员列表功能
 */
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_MemberList;

$memberlist = Model_MemberList::getMemberList();
$member_number = Model_MemberList::getMemberCount();
//var_dump($memberlist);exit;
$page  = intval($_POST['page']);
if($page<1){
    $page = 1;
}

$pagesize = 10;
$totalpage = ceil($member_number/$pagesize);
$page = new Pager($member_number,$page,$pagesize);
$page_str = $page->GetPagerContent();
//echo $page_str;exit;
$smarty->assign("memberlist",$memberlist);
$smarty->assign("membernumber",$member_number);
$smarty->assign("totalpage",$totalpage);
$smarty->assign("page_str",$page_str);
$smarty->assign("action",'memberlist');
$smarty->display('nine_fenxiao/memberList.tpl');