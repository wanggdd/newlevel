<?php

define('USER_ID', 1); // 用户id
define('USER_NAME', 'wolaiceshi'); // 用户名
define('AGENT_ID', 2); // 代理商id
define('SITE_VIP', 1); // 0 免费用户 1 付费用户
define('IP', trim(getIP2()));
define("HOME_URL", '/'); // 首页地址

$zz_userid = isset($_REQUEST['zz_userid']) ? (int)$_REQUEST['zz_userid'] : 0;

$zz_user_info = [];
if ($zz_userid) {
    $zz_user_info = [
        'input_time' => '2009-11-04 10:19:58', // 注册时间
    ];

    define('USER_USER_ID', $zz_userid); // 网站用户id
    define('USER_USER_NAME', 'ceshi'); // 网站下用户的用户名
    define('USER_USER_NICK_NAME', '昵称');
    define('USER_USER_HEAD_PIC', ''); // 用户头像
}