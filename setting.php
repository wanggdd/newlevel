<?php

//预设常量
header("Content-type: text/html; charset=gbk");
define('SYSTEM_ROOT',__dir__."/");
define('CACHEPATH',__dir__.'/');
define('APP_BASE',__dir__);

define('DS', DIRECTORY_SEPARATOR);
define('EV_ACCESS', 1);

// 系统配置文件
include_once(SYSTEM_ROOT."include/conntent.php");
include_once(SYSTEM_ROOT."include/functions.php");
include_once(SYSTEM_ROOT."Core/Ebase.php");

//2015新增框架
include_once(SYSTEM_ROOT.'Core/AutoLoader.php');
spl_autoload_register(array('AutoLoader', 'autoLoad'));
include_once(SYSTEM_ROOT.'Core/import.php');


define('USER_ID', 248987);
define('USER_NAME', 'wolaiceshi');
define('IP', trim(getIP()));
define('SITE_VIP', 1); // 付费用户
define('AGENT_ID', 0); // 代理商id


date_default_timezone_set("PRC");
$sysTime = $_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time();
define('TIME_STAMP', $sysTime);
define('DATE_STR', date('Y-m-d', TIME_STAMP));
define('TIME_STR', date('Y-m-d H:i:s', TIME_STAMP));
define('TIME_HIS', date('H:i:s', TIME_STAMP));
define('TIME_HI', date('H:i', TIME_STAMP));
define('TIME_H', date('H', TIME_STAMP));
define('TIME_I', date('i', TIME_STAMP));
define('TIME_S', date('s', TIME_STAMP));
define('SYS_MICRO_TIME', microtime());

