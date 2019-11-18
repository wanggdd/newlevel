<?php

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_Grade;

if($_POST){
    $grade = intval($_POST['grade']);
    $title = $_POST['title'];
    $grade_info = Model_Grade::getGradeByTitle($title,$uid);
    $error = false;
    /*if($grade > 9){
        $error = true;
    }*/
    if($grade_info){
        $error = true;
    }

    if(!$error){
        $addinfo = Model_Grade::addOneGrade(array('grade'=>$grade,'title'=>$title,'user_id'=>$uid));
        if(!$addinfo)
            $error = true;
    }

    if($error){
        header('Content-type: application/json');
        echo json_encode(['status'=>'fail','msg'=>'']);
        exit;
    }else{
        header('Content-type: application/json');
        echo json_encode(['status'=>'success','msg'=>'']);
        exit;
    }
}


$smarty->display('nine_fenxiao/addGrade.tpl');