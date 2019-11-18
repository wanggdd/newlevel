<?php
//收款码
include_once __dir__.'/../setting.php';

require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

$uid = USER_ID;

use Model\WebPlugin\Model_Member;

$payment_code = '';
if($_REQUEST['user_user_id']){
    $member_info = Model_Member::getMemberById($_REQUEST['user_user_id']);
    if($member_info){
        $payment_code = $member_info[0]['payment_code'];

        if($_POST['qrcode']){
            $result = Model_Member::upOneMember(array('payment_code'=>$_POST['qrcode']),$_REQUEST['user_user_id']);
            if($result){
                header('Content-type: application/json');
                echo json_encode(['status'=>'success','msg'=>'success']);
                exit;
            }else{
                header('Content-type: application/json');
                echo json_encode(['status'=>'fail','msg'=>'fail']);
                exit;
            }
        }
    }else{
        header('Content-type: application/json');
        echo json_encode(['status'=>'fail','msg'=>'fail']);
    }

}else{
    die('没有用户');
}

$smarty->assign("title",'收款码');
$smarty->assign('user_user_id',$_GET['user_user_id']);
$smarty->assign('payment_code',$payment_code);
$smarty->display('nine_fenxiao/paymentcode.tpl');

