<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;


use Model\WebPlugin\Model_Grade;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
if(!$id){
    echo '请先保存等级或选择一个已有等级';
    exit;
}

if($id && isset($_POST['is_set'])){
    $fail = '';
    if(intval($_POST['promote_lower_num']) <=0){
        $fail = 'num_error';

    }
    if(floatval($_POST['promote_money']) <= 0){
        $fail = 'money_error';
    }

    if($fail){
        header('Content-type: application/json');
        echo json_encode(['status'=>'fail','msg'=>$fail]);
        exit;
    }

    unset($_POST['is_set']);
    unset($_POST['id']);
    //修改
    $result = Model_Grade::upOneGrade($_POST,$id);
    if($result){
        header('Content-type: application/json');
        echo json_encode(['status'=>'success','msg'=>'']);
        exit;
    }else{
        header('Content-type: application/json');
        echo json_encode(['status'=>'fail','msg'=>'']);
        exit;
    }

}

$info = Model_Grade::getOneGrade($id);

$smarty->assign('info', $info);
$smarty->display('nine_fenxiao/promoteSet.tpl');