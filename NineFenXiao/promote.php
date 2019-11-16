<?php
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Grade;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
if(!$id){
    echo '请先保存等级或选择一个已有等级';
    exit;
}

if($id && isset($_POST['is_set'])){
    if(intval($_POST['promote_lower_num']) <=0){
        echo '数量为大于0的正整数';
        return false;
    }
    if(floatval($_POST['promote_money']) <= 0){
        echo '金额必须大于0';
        return false;
    }

    unset($_POST['is_set']);
    unset($_POST['id']);
    //修改
    Model_Grade::upOneGrade($_POST,$id);
}

$info = Model_Grade::getOneGrade($id);

$smarty->assign('info', $info);
$smarty->display('nine_fenxiao/promoteSet.tpl');