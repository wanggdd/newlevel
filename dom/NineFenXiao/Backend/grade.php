<?php
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'].'/setting.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/include/public.php';


use Model\WebPlugin\Model_Grade;

$info = Model_Grade::getGrade();
$smarty->assign('info', $info);
$smarty->display('backend/grade.tpl');