<?php
include_once __dir__.'/setting.php';
include_once __dir__.'/include/public.php';

use Model\WebPlugin\Model_Wolaiceshi;
$int     = isset($_REQUEST['int'])                       ? (int)$_REQUEST['int']                         : 0;
$keyword = new_format_mysql_insert($_REQUEST['keyword']) ? new_format_mysql_insert($_REQUEST['keyword']) : '';

$sql = "select * from web_plugin.wolaiceshi where id=1";
$info1 = $DB_Plugin->get_row($sql, 'O');
//var_dump($info1);

$info = Model_Wolaiceshi::getInfo([], 'id=1');
//var_dump($info);

$smarty->assign('title', 'hello world');
$smarty->assign('con', 'help');
$smarty->display('index.tpl');