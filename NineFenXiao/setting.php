<?php
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Setting;

if(!empty($_POST)){
    $_POST['nostate_active'] = isset($_POST['nostate_active']) ? $_POST['nostate_active'] : 0;
    $_POST['noactive_active'] = isset($_POST['noactive_active']) ? $_POST['noactive_active'] : 0;

    if("1" == $_POST['on_off']){
        if(empty($_POST['nostate_active']) || empty($_POST['noactive_active'])){
            echo "<script>alert('请选择升级和激活任务');</script>";
        }
        if(floatval($_POST['nostate_active_money']) <=0 || floatval($_POST['noactive_active_money']) <=0){
            echo "<script>alert('请输入金额');</script>";
        }
    }

    Model_Setting::upSetting($_POST);
}

$info = Model_Setting::getSetting();
$smarty->assign('info', $info[0]);
$smarty->display('backend/setting.tpl');