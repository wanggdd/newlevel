<?php
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Grade;
use Model\WebPlugin\Model_User;
use Model\WebPlugin\Model_Member;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
if(!$id){
    echo '请先保存等级或选择一个已有等级';
    exit;
}

//修改等级初始用户&修改用户等级
if($id && isset($_POST['type']) && $_POST['type'] == 'up'){
    if(empty($_POST['user_user_id'])){
        echo '请选择一个会员';
        exit;
    }

    Model_Grade::upOneGrade(array('user_user_id' => $_POST['user_user_id'],''));
    $_POST['grade_id'] = $id;
    Model_Member::upOneMember($_POST);

}

$info = Model_Grade::getOneGrade($id);
//var_dump($info);exit;

//获取等级列表
$gradeList = Model_Grade::getGradeListByUser();
//var_dump($gradeList);

//获取用户列表
$zz_user_info[0] = [
    'id'         => '1',
    'user_name'  => 'wolaiceshi',
    'nick_name'  => '昵称',
    'pic'        => '头像',
    'input_time' => '2009-11-04 10:19:58', // 注册时间
    'mobile'     => '1111111111111', // 手机号
];

if($zz_user_info){
    foreach($zz_user_info as $key=>$item){
        $member = Model_Member::getMemberById($item['id']);
        if($member){
            $grade = Model_Grade::getGradeByUserGrade($member[0]['grade'],$member[0]['user_user_id']);
            $zz_user_info[$key]['user_grade'] = $grade ? $grade[0]['grade'].'级#'.$grade[0]['title'] : '无等级';
        }
    }
}

//var_dump($zz_user_info);exit;
$smarty->assign('info', $info);
$smarty->assign('gradeList', $gradeList);
$smarty->assign('user_info',$zz_user_info);
$smarty->display('backend/memberSet.tpl');