<?php
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Grade;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
if(!$id){
    echo '���ȱ���ȼ���ѡ��һ�����еȼ�';
    exit;
}

if($id && isset($_POST['is_set'])){
    if(intval($_POST['promote_lower_num']) <=0){
        echo '����Ϊ����0��������';
        return false;
    }
    if(floatval($_POST['promote_money']) <= 0){
        echo '���������0';
        return false;
    }

    unset($_POST['is_set']);
    unset($_POST['id']);
    //�޸�
    Model_Grade::upOneGrade($_POST,$id);
}

$info = Model_Grade::getOneGrade($id);

$smarty->assign('info', $info);
$smarty->display('nine_fenxiao/promoteSet.tpl');