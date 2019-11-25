<?php
//error_reporting(E_ALL);

include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

use Model\WebPlugin\Model_Grade;
use Model\WebPlugin\Model_User;
use Model\WebPlugin\Model_Member;

$uid = USER_ID;
if(isset($_POST['del']) && $_POST['del'] == '1' && isset($_POST['id'])){
    //判断此等级下是否有会员
    $member = \Model\WebPlugin\Model_MemberList::getMemberList(array('grade'=>$_POST['id']));
    if($member){
        header('Content-type: application/json');
        echo json_encode(['code'=>'2']);
        exit;
    }

    //修改is_del值
    $result = Model_Grade::upOneGrade(array('is_del'=>1),intval($_POST['id']));
    if($result){
        header('Content-type: application/json');
        echo json_encode(['code'=>'1']);
        exit;
    }else{
        header('Content-type: application/json');
        echo json_encode(['code'=>'3']);
        exit;
    }
}

if($_POST){
    //$grade_count = isset($_POST['grade'])?count($_POST['grade']):0;
    /*if($grade_count>0&&$grade_count<10){ //判断是否等级在合理范围
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
    }*/
    $grade_count = isset($_POST['grade'])?count($_POST['grade']):0;
    if($grade_count > 0){
        if($grade_count != count(array_unique($_POST['grade']))){

        }else{
            foreach($_POST['grade'] as $key=>$item){
                //存在此等级时，修改标题;否则添加等级
                if(Model_Grade::getGradeById($_POST['ids'][$key],$uid)){
                    //Model_Grade::upOneGrade(['title'=>$_POST['title'][$key]],$_POST['ids'][$key]);
                    Model_Grade::upOneGrade(['grade'=>$item],$_POST['ids'][$key]);
                }else{
                    Model_Grade::addOneGrade(array('grade'=>$item,'title'=>$_POST['title'][$key],'user_id'=>USER_ID));
                }
            }
        }
    }
}

$info = Model_Grade::getGradeListByUser($uid);
var_dump($info);exit;
if($info){
    foreach($info as $key=>$item){
        $user_info = Model_User::getUser('id='.$item['user_user_id']);
        $info[$key]['name'] = $user_info['user_name'];
        if($user_info['nick_name']){
            $info[$key]['name'] .= '/'.$user_info['nick_name'];
        }
    }
}

$smarty->assign('info', $info);
$smarty->assign('action', 'grade');
$smarty->display('nine_fenxiao/grade.tpl');