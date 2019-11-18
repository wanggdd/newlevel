<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_Paymentrecord;

$id = $_REQUEST['id'] ? intval($_REQUEST['id']) : 0;
if(!$id){
    die();
}

$record = Model_Paymentrecord::getRecordById($id);
$smarty->assign("record",$record[0]);

$smarty->assign("action",'memberlist');
$smarty->assign("title",'�鿴ƾ֤');
$smarty->display('nine_fenxiao/lookvoucher.tpl');