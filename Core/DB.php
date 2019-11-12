<?php
/*
* Session Management for PHP3
*
* Copyright (c) 1998-2000 NetUSE AG
* Boris Erdmann, Kristian Koehntopp
*
* $Id: public_connect.php,v 1.1 2006/04/12 05:29:25 dingxf Exp $
*
*/

/*
* 一个实例只能同时做一个select/update/insert操作
* 要同时对多个表格或者纪录集操作必须创建多个实例
* 命名变量名和函数名规则不同一
* 没有翻页功能
* 使用了pconnect()
* 没有关闭连结
* 获得最后添加的ID的方法(如果需要则要求添加)
*
* MySQL提供锁多表格功能,OCI8只提供锁但表格功能
* affected_rows($link) mysql & oci8 不同
* Result自动清空比较完善
* 错误提示比较完善
*/

include_once(__dir__.'/conntent.php');

class DB_Pluginl extends DB_PDO
{
    protected $host     = '127.0.0.1';
    protected $username = 'root';
    protected $password = 'fdasfQEW1231';
    protected $post     = 3306;
    protected $database = "web_plugin";
    protected $charset  = "latin1";
}

class DB_Plugin_Wl extends DB_PDO
{
    protected $host     = '127.0.0.1';
    protected $username = 'root';
    protected $password = 'fdasfQEW1231';
    protected $post     = 3306;
    protected $database = "web_plugin";
    protected $charset  = "latin1";
}
