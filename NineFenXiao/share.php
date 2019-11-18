<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_MemberList;

$payment_code = '';
if($_GET['user_user_id']){
    $member_info = Model_MemberList::getMember($uid,$_GET['user_user_id']);
    if($member_info)
        $payment_code = $member_info['payment_code'];
}

$smarty->assign('payment_code',$payment_code);
$smarty->display('nine_fenxiao/share.tpl');
