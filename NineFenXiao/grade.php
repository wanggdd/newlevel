<?php
//error_reporting(E_ALL);

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

use Model\WebPlugin\Model_Grade;

$uid = USER_ID;
if($_POST){
    $grade_count = isset($_POST['grade'])?count($_POST['grade']):0;
    if($grade_count>0&&$grade_count<10){ //判断是否等级在合理范围
        $grades = $_POST['grade'];
        sort($grades);
        $error = false;
        foreach ($grades as $k=>$v){
            if($k+1!=$v){
                $error = true;
                break;
            }
        }
        if($error){
            echo 'error2';
            //
        }else{

            foreach($_POST['grade'] as $key=>$item){
                //存在此等级时，修改标题;否则添加等级
                if(Model_Grade::getGradeByUserGrade($item,$uid)){
                    Model_Grade::upOneGrade(['title'=>$_POST['title'][$key]],$_POST['ids'][$key]);
                }else{
                    Model_Grade::addOneGrade(array('grade'=>$item,'title'=>$_POST['title'][$key],'user_id'=>USER_ID));
                }
            }
        }

    }else{
        echo 'error1';
        //todo
    }



}

$info = Model_Grade::getGrade($uid);
$smarty->assign('info', $info);
$smarty->assign('action', 'grade');
$smarty->display('nine_fenxiao/grade.tpl');