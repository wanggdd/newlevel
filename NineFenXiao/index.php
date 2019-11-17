<?php
error_reporting(E_ALL);
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

//include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';

echo USER_ID;exit;
use Model\WebPlugin\Model_Setting;

$info = Model_Setting::getSetting();
//var_dump($info);exit;
$smarty->assign('con', $info);
$smarty->display('nine_fenxiao/setting.tpl');