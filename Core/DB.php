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
* һ��ʵ��ֻ��ͬʱ��һ��select/update/insert����
* Ҫͬʱ�Զ�������߼�¼���������봴�����ʵ��
* �����������ͺ���������ͬһ
* û�з�ҳ����
* ʹ����pconnect()
* û�йر�����
* ��������ӵ�ID�ķ���(�����Ҫ��Ҫ�����)
*
* MySQL�ṩ��������,OCI8ֻ�ṩ���������
* affected_rows($link) mysql & oci8 ��ͬ
* Result�Զ���ձȽ�����
* ������ʾ�Ƚ�����
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
