<?php

define('USER_ID', 1); // 用户id
define('USER_NAME', 'wolaiceshi'); // 用户名
define('AGENT_ID', 0); // 代理商id
define('SITE_VIP', 1); // 0 免费用户 1 付费用户
define('IP', trim(getIP2()));
define("HOME_URL", '/'); // 首页地址

$zz_userid = isset($_REQUEST['zz_userid']) ? (int)$_REQUEST['zz_userid'] : 0;

$zz_user_info = [];
if ($zz_userid) {
    $zz_user_info = [
        'id'         => '248478',
        'user_name'  => 'wolaiceshi',
        'nick_name'  => '昵称',
        'pic'        => '头像',
        'input_time' => '2009-11-04 10:19:58', // 注册时间
        'mobile'     => '1111111111111', // 手机号
    ];

    $zz_user_info['user_user_nick_name'] = $zz_user_info['nick_name'] ? $zz_user_info['nick_name'] : $zz_user_info['user_name'];
    $zz_user_info['user_user_head_pic']  = $zz_user_info['pic'] ? $zz_user_info['pic'] : 'http://aimg8.dlszyht.net.cn/default/user_user_profile.jpg';

    define('USER_USER_ID', $zz_user_info['id']); // 网站用户id
    define('USER_USER_NAME', $zz_user_info['user_name']); // 网站下用户的用户名
    define('USER_USER_NICK_NAME', $zz_user_info['user_user_nick_name']);
    define('USER_USER_HEAD_PIC', $zz_user_info['user_user_head_pic']); // 用户头像
}