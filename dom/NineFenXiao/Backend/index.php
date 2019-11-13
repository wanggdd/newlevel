<?php
error_reporting(E_ALL);


include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Setting;

$info = Model_Setting::getSetting();
//var_dump($info);exit;
$smarty->assign('con', $info);
$smarty->display('backend/setting.tpl');