<?php
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Grade;

if($_POST){
    foreach($_POST['grade'] as $key=>$item){
        //存在此等级时，修改标题;否则添加等级
        if(Model_Grade::getGradeByUserGrade($item,USER_ID)){
            Model_Grade::upOneGrade(['title'=>$_POST['title'][$key]],$_POST['ids'][$key]);
        }else{
            Model_Grade::addOneGrade(array('grade'=>$item,'title'=>$_POST['title'][$key],'user_id'=>USER_ID));
        }
    }
}

$info = Model_Grade::getGrade();
$smarty->assign('info', $info);
$smarty->display('nine_fenxiao/grade.tpl');