<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

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

$info = Model_Setting::getSetting($uid);
$smarty->assign('info', $info);
$smarty->assign('action', 'setting');
$smarty->display('nine_fenxiao/setting.tpl');