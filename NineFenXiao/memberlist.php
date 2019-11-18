<?php
/**
 会员列表功能
 */
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_MemberList;
use Model\WebPlugin\Model_Grade;
use Model\WebPlugin\Model_Member;

$where = array('u.user_id'=>$uid);
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
    $smarty->assign("start_date",$_GET['start_date']);
    $smarty->assign("end_date",$_GET['end_date']);
    $smarty->assign("search_mix",$_GET['search_mix']);
}

$page  = intval($_GET['page']);
if($page<1){
    $page = 1;
}

$pagesize = 20;
$offset = ($page-1)*$pagesize;

$memberlist = Model_MemberList::getMemberList($where,$offset,$pagesize);
if($memberlist){
    foreach($memberlist as $key=>$item){
        $memberlist[$key]['pic'] = $item['pic'] ? $item['pic'] : 'http://aimg8.dlszyht.net.cn/default/user_user_profile.jpg';

        //下级数量
        $lower_num = Model_Member::getLowerCount($item['user_user_id']);
        $memberlist[$key]['lower_num'] = $lower_num;

        //等级
        $member_grade = Model_Grade::getOneGrade($item['grade']);
        if($member_grade){
            $memberlist[$key]['grade_val'] = $member_grade['grade'];
        }
    }
}
//var_dump($memberlist);exit;
$member_number = Model_MemberList::getMemberCount($where);

$totalpage = ceil($member_number/$pagesize);
$page = new Pager($member_number,$page,$pagesize);
$page_str = $page->GetPagerContent();
//echo $page_str;exit;

//等级列表
$grade_list = Model_Grade::getGradeListByUser($uid);

$smarty->assign("memberlist",$memberlist);
$smarty->assign("membernumber",$member_number);
$smarty->assign("totalpage",$totalpage);
$smarty->assign("page_str",$page_str);
$smarty->assign("grade_list",$grade_list);
$smarty->assign("action",'memberlist');
$smarty->assign("title",'会员列表');
$smarty->display('nine_fenxiao/memberList.tpl');