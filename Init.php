<?php
// 不可同步 aliang
if (isset($_REQUEST['aa']) && $_REQUEST['aa']) {
    ini_set('display_errors', 1);
    error_reporting (E_ALL & ~E_NOTICE);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);

}

header('content-type:text/html;charset=gbk');

// 测试机标记
define('TESTER', 1);
$GLOBALS['MYSPACE']['SQL_ECHO']  = 1;
$GLOBALS['MYSPACE']['SQL_PARAM'] = [];
$GLOBALS['MYSPACE']['SQL']       = '';

define('APP_BASE', __dir__);
define('WAP_ROOT',UPPER_ROOT."wap/");
define('CACHEPATH',UPPER_ROOT."self_define/");
define('CATEGORY_ROOT',SYSTEM_ROOT.'dom/');
define('TMP_UPLOAD_DIR',UPPER_ROOT.'tmp_upload/');
define('EV_ACCESS', 1);
define('DS',DIRECTORY_SEPARATOR);

define('EMAIL_HOST', 'http://www.baidu.com'); // 发送邮件域名

//2012版本扩展
require_once(UPPER_ROOT."include/conntent.php");
require_once(UPPER_ROOT."include/functions.php");//VIP公共函数库

//2013版本扩展
require_once(APP_BASE.DS.'Core'.DS.'AutoLoader.php');
require_once(APP_BASE.DS.'Core'.DS.'DB.php');
spl_autoload_register(array('AutoLoader','autoLoad'));
require_once(APP_BASE.DS.'Lib'.DS.'import.php');
Ebase::run();
$request = new Request();

