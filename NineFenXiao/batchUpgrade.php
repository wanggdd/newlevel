<?php
/**
批量修改等级功能
 */
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_Grade;
use Model\WebPlugin\Model_Member;

$user_ids = $_GET['user_ids'];

if(isset($_POST['type']) && $_POST['user_ids'] && $_POST['upgrade']){
    $user_arr = explode(',',$_POST['user_ids']);
    foreach($user_arr as $key=>$item){
        Model_Member::upOneMember(array('grade'=>intval($_POST['upgrade'])),$item);
    }
    header('Content-type: application/json');
    echo json_encode(['status'=>'success','msg'=>'']);
    exit;
}

//查找所有等级
$grade_list = Model_Grade::getGradeListByUser($uid);

$smarty->assign("grade_list",$grade_list);
$smarty->assign("user_ids",$user_ids);
$smarty->display('nine_fenxiao/batchupgrade.tpl');