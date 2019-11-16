<?php
include_once __dir__.'/../setting.php';
require_once(SYSTEM_ROOT."Smarty.class.php");
require_once(SYSTEM_ROOT."include/smarty_setting.php");

use Model\WebPlugin\Model_Wolaiceshi;

$int     = isset($_REQUEST['int'])                       ? (int)$_REQUEST['int']                         : 0;
$keyword = format_mysql_insert($_REQUEST['keyword'])     ? format_mysql_insert($_REQUEST['keyword'])     : '';

$sql = "select * from web_plugin.wolaiceshi where id=1";
$info1 = $DB_Plugin->get_row($sql, 'O');
//var_dump($info1);

$info = Model_Wolaiceshi::getInfo([], 'id=1');
//var_dump($info);

$smarty->assign('title', '±êÌâ');
$smarty->assign('con', '²âÊÔÄÚÈÝ');
$smarty->display('nine_fenxiao/index.tpl');