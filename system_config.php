<?php
require_once(UPPER_ROOT."include/functions.php");//VIP¹«¹²º¯Êý¿â

define("SERVER_ADDR", $_SERVER['SERVER_ADDR']);
define('IP', trim(getIP2()));
define('UPDATA_DIR_IMG4', UPPER_ROOT.'temp_upload_pic/');

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

include_once(UPPER_ROOT. "include/conntent.php");
define('IMG_HOST', 'https://aimg8.dlssyht.cn');