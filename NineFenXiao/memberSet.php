<?php
error_reporting(ALL);

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

use Model\WebPlugin\Model_Grade;
use Model\WebPlugin\Model_User;
use Model\WebPlugin\Model_Member;

$uid = USER_ID;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
if(!$id){
    //echo "<script>alert('请先保存等级或选择一个已有等级');</script>";
    die();
}

//修改等级初始用户&修改用户等级
if($id && isset($_GET['type']) && $_GET['type'] == '1'){
    $fail = '';
    if(intval($_GET['user_user_id']) <=0){
        $fail = 'no_user';
    }
    if($fail){
        header('Content-type: application/json');
        echo json_encode(['status'=>'fail','msg'=>$fail]);
        exit;
    }
    Model_Grade::upOneGrade(array('user_user_id' => $_GET['user_user_id']),$id);

    $member_info = Model_Member::getMemberById($_GET['user_user_id']);
    if($member_info){
        $data = array('grade'=>$id,'status'=>2);
        Model_Member::upOneMember($data,$_GET['user_user_id']);
        header('Content-type: application/json');
        echo json_encode(['status'=>'success','msg'=>'']);
        exit;
    }else{
        $data = array(
            'user_id' => $uid,
            'user_user_id'      => $_GET['user_user_id'],
            'status'            => 2,
            'grade'             => $id,
            'create_time'       => time(),
        );
        $result = Model_Member::addOneMember($data);
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

}

$info = Model_Grade::getOneGrade($id);
//var_dump($info);exit;

//获取等级列表
$gradeList = Model_Grade::getGradeListByUser($uid);
//var_dump($gradeList);exit;


$pagesize = 10;
$page  = intval($_GET['page']);
if($page<1){
    $page = 1;
}
$offset = ($page-1)*$pagesize;

$user_info = Model_User::getUserList('user_id='.$uid.' and is_del=0',$offset,$pagesize);
$user_number = Model_User::getUserCount('user_id='.$uid.' and is_del=0');
if($user_info){
    foreach($user_info as $key=>$item){
        $member = Model_Member::getMemberById($item['id']);
        if($member){
            $grade = Model_Grade::getGradeByUserGrade($member['grade'],$member['user_user_id']);
            $user_info[$key]['user_grade'] = $grade ? $grade['grade'].'级#'.$grade['title'] : '无等级';
        }else{
            $user_info[$key]['user_grade'] = '非邀请会员';
        }
    }
}


$totalpage = ceil($user_number/$pagesize);
$page = new Pager($user_number,$page,$pagesize);
$page_str = $page->GetPagerContent();
$smarty->assign("usernumber",$user_number);
$smarty->assign("totalpage",$totalpage);
$smarty->assign("page_str",$page_str);

$smarty->assign('info', $info);
$smarty->assign('gradeList', $gradeList);
$smarty->assign('user_info',$user_info);
$smarty->display('nine_fenxiao/memberSet.tpl');