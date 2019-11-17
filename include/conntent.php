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

function err404()
{
    echo <<<EOT
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
            <meta name="renderer" content="webkit">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <title></title>
            <style>
                body{margin:0;padding:0;}
                .max_div{ width: 1200px; margin:0 auto;background:url(/images/err404/bg.jpg) left top;padding-top:80px;}
                .max_div .max_img{ display: block; width:366px; height:226px; margin:0  auto;}
                .max_div p{ display: block; text-align: center; font-size:24px;color:#888;padding-top:34px;margin:0;}
                .max_div_but{ width: 240px; height: 35px; overflow: hidden; margin:0 auto;padding-top:42px;}
                .max_div_but a{ display: block;float: left; width: 98px; height: 33px;border:1px solid #e5e5e5;border-radius: 3px;margin:0px 10px;}
                .max_div_but a span{ display:table; margin:0 auto; overflow: hidden; }
                .max_div_but a span em{ display: block; width: 12px; height: 14px;float: left;margin-top:10px;margin-right: 8px;}
                .max_div_but a span em img{ width: 100%; }
                .max_div_but a span strong{ display: block;float: left; line-height: 34px; font-size:14px;color:#666; font-weight: 500; }
                .max_div_but a:hover{background: #fafafa;}
            </style>
        </head>
        <body>
            <div class="max_div">
                <span class="max_img"><img src="/images/err404/center_img.png" alt="" /></span>
                <p>页面走丢了.....</p>
                <div class="max_div_but">
                    <a href="javascript:history.go(-1)">
                        <span>
                            <em><img src="/images/err404/icon1.png" alt="" /></em>
                            <strong>返回</strong>
                        </span>
                    </a>
                    <a href="javascript:location.reload(true)">
                        <span>
                            <em><img src="/images/err404/icon2.png" alt="" /></em>
                            <strong>刷新</strong>
                        </span>
                    </a>
                </div>
            </div>
        </body>
    </html>
EOT;

    exit();

    return true;
}

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

class DB_Sql
{

    /* public: connection parameters */
    //数据库连结
    var $Host     = "";
    var $Database = "";
    var $User     = "";
    var $Password = "";
    var $LinkName = ""; ## 对于同一个数据库服务器,可以设置相同联结名称

    /* public: configuration parameters */
    var $Auto_Free = 1; ## Set to 1 for automatic mysql_free_result()
    ## 类是否需要注销纪录集
    var $Debug = 0; ## Set to 1 for debugging messages.
    ## 调试是否显示
    var $Halt_On_Error = "no"; ## 报错　"yes" (halt with message),
    ## 不报错误信息　"no" (ignore errors quietly),
    ## 不报错误信息,中断程序　?report" (ignore errror, but spit a warning)
    var $Seq_Table = "db_sequence"; //序列表

    /* public: result array and current row number */
    var $Record = array(); //一条纪录信息

    var $Row; // 当前行号

    var $AbsolutePage = 1; // 当前页码
    var $PageSize     = 0; // 一页行数
    var $PageCount    = 0; // 总共页数
    var $RecordCount  = 0; // 总行数

    /* public: current error number and error text */
    var $Errno = 0; //错误编号
    var $Error = ""; //错误信息

    /* public: this is an api revision, not a CVS revision. */
    var $type     = "mysql"; //数据库类型
    var $revision = "1.2"; //代码版本号

    /* private: link and query handles */
    var $Query_ID = 0; //查询结果句柄

    var $JkArr = array('1247655', '1249246', '1249263', '1249272', '1249276', '1249291', '1249297', '1249311', '1249317', '1249363', '1249369', '1249373', '1249379', '1249396', '1249400', '1249402', '1249413', '1249418', '1249421', '1249433', '1249438', '1249701', '1249706', '1249709', '1249711', '1249714', '1249715', '1249717', '1249719', '1249726', '1249730', '1249735', '1249739', '1249743', '1249745', '1249749', '1249752', '1277902', '1249754', '1249757', '1249759', '1249763', '1249764', '1249768', '1249770', '1249777', '1249781', '1249789', '1249791', '1249794', '1249796', '1249798', '1249801', '1249808', '1249809', '1249812', '1269065', '1269250', '1270708', '1269134', '1269150', '1269152', '1269153', '1269155', '1272163', '1272432', '1272442', '1272911', '1272920', '1274208', '1274411', '1274873', '1275002', '1276251', '1276634', '1276636', '1276640', '1276645', '1276650', '1277856', '1277861', '1277870', '1277879', '1278772', '1278934', '1278936', '1278937', '1278940', '1278943', '1281306', '1281315', '1281316', '1281319', '1281322', '1243337', '1267234', '1269116', '1269162', '1269163', '1269164', '1269165', '1269167', '1272167', '1272170', '1272171', '1272172', '1272173', '1273727', '1273728', '1273729', '1273730', '1273731', '1276437', '1276438', '1276441', '1276442', '1276443', '1277697', '1277704', '1277705', '1277707', '1277709', '1278773', '1278775', '1278777', '1278778', '1278781', '1280880', '1280883', '1280885', '1280888', '1280890', '1269127', '1269128', '1269130', '1269133', '1269135', '1272220', '1272221', '1272222', '1272223', '1272225', '1273409', '1273410', '1273411', '1273413', '1273414', '1274849', '1274850', '1274851', '1274852', '1274853', '1276972', '1276974', '1276976', '1276977', '1280120', '1280121', '1280124', '1280125', '1280127', '1281307', '1281308', '1281309', '1281310', '1281311', '1261991', '1213886', '1217348', '1267287', '1219396', '1258453', '1258464', '1230871');

    /* public: constructor */
    function DB_Sql($query = "")
    { //构造函数
        $this->query($query);
        $this->query("set names latin1");
    }

    function query_id()
    { //返回查询结果,得到结果句柄
        return $this->Query_ID;
    }

    /* public: connection management */
    // 创建连结
    function connect($Database = "", $Host = "", $User = "", $Password = "")
    {
        /* Handle defaults */
        if ("" == $Database)
            $Database = $this->Database;
        if ("" == $Host)
            $Host = $this->Host;
        if ("" == $User)
            $User = $this->User;
        if ("" == $Password)
            $Password = $this->Password;
        //if ("" == $linkName)
        $LinkName = $this->LinkName;

        global $$LinkName;

        /* establish connection, select database */
        if ($$LinkName && empty($_GET['err404'])) {
            $is_success = @mysql_db_query($this->Database, "set names latin1", $$LinkName);
            if ($is_success != false) {
                return 1;
            }
        }
        $$LinkName = @mysql_connect($Host, $User, $Password, 1);
        if ($$LinkName) {
            $select_db = mysql_select_db($Database, $$LinkName);
            if ($select_db && empty($_GET['err404'])) {
                $this->query("set names latin1");

                return 1;
            } else {
                err404();
                echo "数据加载超时，请重新刷新页面";
                exit();

                return 0;
            }
        } else {
            err404();
            echo "数据加载超时，请重新刷新页面";
            exit();

            //$this->halt("connect($Host, $User, \$Password) failed.");
            return 0;
        }
    }

    /* public: discard the query result */
    // 释放纪录集内存
    function free()
    {
        @mysql_free_result($this->Query_ID);
        $this->Query_ID = 0;
    }

    /* private: 初始化与页面数相关的信息, writed by Ge Chuanqing*/
    function init_pagesize()
    {
        $this->AbsolutePage = 1;
        $this->PageSize     = 0;
        $this->PageCount    = 0;
        $this->RecordCount  = 0;
    }

    /* private: parse a query, writed by Ge Chuanqing*/
    function parse_query_string($Query_String)
    {

        $LinkName = $this->LinkName;
        global $$LinkName;

        if (($this->PageSize > 0) && ($this->AbsolutePage > 0) && eregi("select ", $Query_String)) {
            $strsql = ereg_replace("select .+ from", "select count(*) as sumrows from", $Query_String);
            $rec    = @mysql_query($strsql, $$LinkName);
            if (!$rec) {
                $this->Errno = mysql_errno();
                $this->Error = mysql_error();
                $this->halt("Invalid SQL(count): ".$Query_String);
                $this->init_pagesize();

                return 0;
            } else {
                $this->RecordCount = mysql_result($rec, 0, "sumrows");
                $this->PageCount   = ceil($this->RecordCount / $this->PageSize);

                return "$Query_String limit ".
                       ($this->AbsolutePage - 1) * $this->PageSize.",".$this->PageSize;
            }
        } else {
            $this->init_pagesize();

            return 0;
        }
    }

    /* public: perform a query */
    // 执行SQL
    function query($Query_String)
    {
        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            $msc = microtime(true);
        }
        if (defined("TESTER") && $_GET['echo_all_sql']) {
            echo $Query_String."<br />";
        }

        $USERID = !empty($_COOKIE['ev_userid']) ? (int)$_COOKIE['ev_userid'] : 0;
        if (substr(trim($Query_String), -1, 1) == ';') {
            $Query_String = substr(trim($Query_String), 0, -1);
        }

        $pos = strpos(strtolower($Query_String), 'from user where username is not null');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), 'where user_id > 0');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), ' or 1 ');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '<BE>');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '^M\n');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '<FA>');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '<B9>');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '<B0>');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '0xbf27');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), '0xbf5c27');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($Query_String), "wap_host='') and enddate>=");
        if ($pos !== false) {
            $log_dir = SYSTEM_ROOT.'logs/';

            if (!is_dir($log_dir)) {
                @mkdir($log_dir, 0777, true);
                @chmod($log_dir, 0777);
            }

            $log_file = "{$log_dir}".date('Ymd').".log";
            $logs     = "{$Query_String} ### "."http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']} ### ".TIME_STR;
            $Ts       = fopen($log_file, "a+");
            fputs($Ts, "{$logs}\r\n");
            fclose($Ts);

            return false;
        }

        //更新限制
        $pos = strpos(strtolower(trim($Query_String)), 'update ');
        if ($pos === 0) {
            $pos = strpos(strtolower($Query_String), 'where');
            if ($pos === false) {
                return false;
            }
            $pos = strpos(strtolower($Query_String), 'limit');
            if ($pos === false) {
                $Query_String = $Query_String." limit 20000";
            }
            //监控更新
            if (0) {
                $log_dir = SYSTEM_ROOT.'logs/';

                if (!is_dir($log_dir)) {
                    @mkdir($log_dir, 0777, true);
                    @chmod($log_dir, 0777);
                }

                $log_file = "{$log_dir}{$USERID}.log";
                $logs     = "{$Query_String} ### "."http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']} ### ".TIME_STR;
                $Ts       = fopen($log_file, "a+");
                fputs($Ts, "{$logs}\r\n");
                fclose($Ts);
            }

            mysql_update_website_user_update_time($Query_String);
        }

        //删除限制
        $pos = strpos(strtolower(trim($Query_String)), 'delete ');
        if ($pos === 0) {
            $pos = strpos(strtolower($Query_String), 'where');
            if ($pos === false) {
                return false;
            }
            $pos = strpos(strtolower($Query_String), 'limit');
            if ($pos === false) {
                $Query_String = $Query_String." limit 100";
            }
        }

        //查询限制
        $pos = strpos(strtolower(trim($Query_String)), 'select ');
        if ($pos === 0) {
            $pos = strpos(strtolower($Query_String), 'limit');
            if ($pos === false) {
                $Query_String = $Query_String." limit 10000";
            }
        }

        $Query_String = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $Query_String);
        //匹配此类注入 select * from proinfo where proname='1'#' and userid = 1001739
        /*$Query_String=preg_replace('/=\s*\'\s*#/', "='#",$Query_String);
        $Query_String=str_replace("='#", "='***#",$Query_String);
        $Query_String=preg_replace("/'\s*#/", "#",$Query_String);
        $Query_String=str_replace("='***#","='#",$Query_String);*/

        /*$Query_String=preg_replace('/=\s*"\s*#/', '="#',$Query_String);
        $Query_String=str_replace('="#', '="***#',$Query_String);
        $Query_String=preg_replace('/"\s*#/', "#",$Query_String);
        $Query_String=str_replace('="***#','="#',$Query_String);*/

        #$Query_String=str_replace("'#", '#',$Query_String);

        /* No empty queries, please, since PHP4 chokes on them. */
        if ($Query_String == "")
            /* The empty query string is passed on from the constructor,
            * when calling the class without a query, e.g. in situations
            * like these: '$db = new DB_Subclass;'
            */
            return false;

        if ($this->connect() == 0) {//如果链接失败
            return false; /* we already complained in connect() about that. */
        };
        $LinkName = $this->LinkName;
        global $$LinkName;

        # New query, discard previous result.
        if ($this->Query_ID) {
            $this->free();
        }

        # 如果没有设置PageSize,就应该不能设置AbsolutePage
        if (($this->PageSize < 1) && ($this->AbsolutePage > 1)) {
            echo "<b>";
            echo "每一页的行数(".$this->PageSize.")，";
            echo "或者页码(".$this->AbsolutePage.")，";
            echo "设置错误!";
            echo "</b><br>";

            return 0;
        }

        if ($this->Debug)
            printf("Debug: query = %s<br>\n", $Query_String);

        if ($this->parse_query_string($Query_String)) {
            $Query_String = $this->parse_query_string($Query_String);
            if ($this->Debug) {
                printf("Debug: parse_query_string = %s<br>\n", $Query_String);
            }
        }
        $this->Query_ID = mysql_query($Query_String, $$LinkName);
        $this->Row      = 0;
        $this->Errno    = mysql_errno();
        $this->Error    = mysql_error();
        if (!$this->Query_ID) {
            $this->init_pagesize();
            $this->halt("Invalid SQL: ".$Query_String);
        } else {
            if ($this->RecordCount <= 0) {
                $this->RecordCount = @mysql_num_rows($this->Query_ID);
            }
        }

        //写操作延时0.5秒
        $pos  = strpos(strtolower(trim($Query_String)), 'insert ');
        $pos2 = strpos(strtolower(trim($Query_String)), 'update ');
        if ($pos === 0 || $pos2 === 0) {
            //usleep(100000);
        }

        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            echo microtime(true) - $msc ."<br />";
        }
        # Will return nada if it fails. That's fine.
        return $this->Query_ID;
    }

    /* public: walk result set */
    /* 获取当前纪录,如果为空则清除纪录集 */
    // 参数: 无
    // 返回: 成功或者失败
    function next_record()
    {
        if (!$this->Query_ID) {
            $this->halt("next_record called with no query pending.");

            return 0;
        }

        $this->Record = @mysql_fetch_array($this->Query_ID);
        $this->Row    += 1;
        $this->Errno  = mysql_errno();
        $this->Error  = mysql_error();

        $stat = is_array($this->Record);
        if (!$stat && $this->Auto_Free) {//如果没有得到结果集
            $this->free();
        }

        return $stat;
    }

    /* public: position in result set */
    // 设置纪录集指针位置$pos
    // 当超过范围时,则报错,并且自动返回到最后一行，并且返回0
    //成功返回1，并且把纪录集指针定位到$pos
    function seek($pos = 0)
    {


        $status = @mysql_data_seek($this->Query_ID, $pos);
        if ($status)
            $this->Row = $pos;
        else {
            $this->halt("seek($pos) failed: result has ".$this->num_rows()." rows");

            /* half assed attempt to save the day,
            * but do not consider this documented or even
            * desireable behaviour.
            */
            @mysql_data_seek($this->Query_ID, $this->num_rows());
            $this->Row = $this->num_rows();

            return 0;
        }

        return 1;
    }

    /* public: table locking */
    // 将一个表以某种方式锁死
    // $table可以是数组,如:array("read"=>"table1","table2","table3")
    // 设置为读锁死的表,最多只能有一个
    function lock($table, $mode = "write")
    {

        $this->connect();
        $LinkName = $this->LinkName;
        global $$LinkName;

        $query = "lock tables ";
        if (is_array($table)) {
            while (list($key, $value) = each($table)) {
                if ($key == "read" && $key != 0) { //测试表明,无法读锁死表
                    $query .= "$value read, ";
                } else {
                    $query .= "$value $mode, ";
                }
            }
            $query = substr($query, 0, -2); //删除", "
        } else {
            $query .= "$table $mode";
        }
        $res = @mysql_query($query, $$LinkName);
        if (!$res) {
            $this->halt("lock($table, $mode) failed.");

            return 0;
        }

        return $res;
    }

    //解锁
    function unlock()
    {
        $this->connect();
        $res = @mysql_query("unlock tables", $$LinkName);
        if (!$res) {
            $this->halt("unlock() failed.");

            return 0;
        }

        return $res;
    }


    /* public: evaluate the result (size, width) */
    // 最后一次SQL操作,影响的数据库行数,比如对成功执行了delete,insert 等，select则和num_rows()一致
    // 如果是select,则返回0
    // 如果没有where字句,返回也是0
    function affected_rows()
    {
        $LinkName = $this->LinkName;
        global $$LinkName;

        return @mysql_affected_rows($$LinkName);
    }

    //当前结果集的行数
    function num_rows()
    {
        return $this->RecordCount;
    }

    //取得返回结果集的列数（也就是字段的数目）
    function num_fields()
    {
        return @mysql_num_fields($this->Query_ID);
    }

    //返回最后插入的记录ID
    function last_insert_id()
    {
        $LinkName = $this->LinkName;
        global $$LinkName;

        return @mysql_insert_id($$LinkName);
    }

    /* public: shorthand notation */
    //当前纪录集的行数
    function nf()
    {
        return $this->num_rows();
    }

    //当前纪录集的行数
    function np()
    {
        print $this->num_rows();
    }

    //获取Record中的纪录
    function f($Name)
    {
        return $this->Record[$Name];
    }

    //获取Record中的纪录
    function p($Name)
    {
        print $this->Record[$Name];
    }

    //返回一个变量，用于结果只有一行一列的SQL //卓军辉添加
    //例：$var = $DB_Product->get_var("SELECT count(*) FROM users");
    function get_var($query)
    {
        $is_success = $this->query($query); //执行SQL
        if ($is_success === false) {
            return false;
        }
        $this->next_record(); //取一条记录

        return $this->Record[0]; //返回记录的第一列
    }


    //返回包含一行记录的数组或对象(默认为数组)，用于结果只有一行的SQL //卓军辉添加
    //例：$user = $DB_Product->get_row("SELECT name,email FROM users WHERE id = 2");
    function get_row($query, $oMethod = 'A')
    {

        $is_success = $this->query($query); //执行SQL

        if ($is_success === false) {
            return false;
        }
        //SQL异常处理
        if (!$this->Query_ID) {
            $this->halt("next_record called with no query pending.");

            return 0;
        }

        if ($oMethod == 'A') { //返回数组
            if ($t = @mysql_fetch_array($this->Query_ID)) {
                $Results   = $t;
                $this->Row += 1;
            }
        } elseif ($oMethod == 'O') { //返回对象
            if ($t = @mysql_fetch_object($this->Query_ID)) {
                $Results   = $t;
                $this->Row += 1;
            }
        } else {
            $this->free();

            return false;
        }

        //如果没有得到结果集
        if ($this->Row == 0) {
            $this->free();

            return false;
        }

        return $Results;
    }

    //返回包含两行记录的二维数组或对象型数组(默认为数组) //卓军辉添加
    //例：$user = $DB_Product->get_row("SELECT name,email FROM users");
    function get_results($query, $oMethod = 'A')
    {
        $is_success = $this->query($query); //执行SQL
        if ($is_success === false) {
            return false;
        }
        //SQL异常处理
        if (!$this->Query_ID) {
            $this->halt("next_record called with no query pending.");

            return 0;
        }

        if ($oMethod == 'A') { //返回二维数组
            while ($t = @mysql_fetch_array($this->Query_ID)) {
                $Results[] = $t;
                $this->Row += 1;
            }
        } elseif ($oMethod == 'O') { //对象型数组
            while ($t = @mysql_fetch_object($this->Query_ID)) {
                $Results[] = $t;
                $this->Row += 1;
            }
        } else {
            $this->free();

            return false;
        }
        $this->Errno = mysql_errno();
        $this->Error = mysql_error();

        //如果没有得到结果集
        if ($this->Row == 0) {
            $this->free();

            return false;
        }

        return $Results;
    }

    /* public: sequence numbers, edit by Ge Chuanqing */
    //获取一个表格的序列号并且加一
    function nextid($seq_name)
    {

        $this->connect();
        $LinkName = $this->LinkName;
        global $$LinkName;

        if ($this->lock($this->Seq_Table)) {
            /* get sequence number (locked) and increment */
            //
            $q   = sprintf("select nextid from %s where seq_name = '%s'",
                           $this->Seq_Table,
                           $seq_name);
            $id  = @mysql_query($q, $$LinkName);
            $res = @mysql_fetch_array($id);

            /* No current value, make one */
            if (!is_array($res)) {
                $currentid = 0;
                //add by ge, 注意：在加锁前，自动解开以前的锁．
                if ($this->lock("$seq_name")) {
                    $strsql    = "select max(id) as maxid from $seq_name";
                    $rec       = @mysql_query($strsql, $$LinkName);
                    $currentid = @mysql_result($rec, 0, "maxid");
                } else {
                    $this->halt("cannot lock ".$seq_name);
                }
                if ($this->lock($this->Seq_Table)) {
                    $q  = sprintf("insert into %s values('%s', %s)",
                                  $this->Seq_Table,
                                  $seq_name,
                                  $currentid);
                    $id = @mysql_query($q, $$LinkName);
                } else {
                    $this->halt("cannot lock ".$this->Seq_Table);
                }
                //add end
            } else {
                $currentid = $res["nextid"];
            }
            $nextid = $currentid + 1;
            $q      = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
                              $this->Seq_Table,
                              $nextid,
                              $seq_name);
            $id     = @mysql_query($q, $$LinkName);
            $this->unlock();
        } else {
            $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");

            return 0;
        }

        return $nextid;
    }

    /* public: return table metadata */
    //获取某个表结构信息
    function metadata($table = '', $full = false)
    {
        $count = 0;
        $id    = 0;
        $res   = array();

        /*
        * Due to compatibility problems with Table we changed the behavior
        * of metadata();
        * depending on $full, metadata returns the following values:
        *
        * - full is false (default):
        * $result[]:
        * [0]["table"] table name
        * [0]["name"] field name
        * [0]["type"] field type
        * [0]["len"] field length
        * [0]["flags"] field flags
        *
        * - full is true
        * $result[]:
        * ["num_fields"] number of metadata records
        * [0]["table"] table name
        * [0]["name"] field name
        * [0]["type"] field type
        * [0]["len"] field length
        * [0]["flags"] field flags
        * ["meta"][field name] index of field named "field name"
        * The last one is used, if you have a field name, but no index.
        * Test: if (isset($result['meta']['myfield'])) { ...
        */

        // if no $table specified, assume that we are working with a query
        // result
        if ($table) {
            $this->connect();
            $id = @mysql_list_fields($this->Database, $table);
            if (!$id)
                $this->halt("Metadata query failed.");
        } else {
            $id = $this->Query_ID;
            if (!$id)
                $this->halt("No query specified.");
        }

        $count = @mysql_num_fields($id);

        // made this IF due to performance (one if is faster than $count if's)
        if (!$full) {
            for ($i = 0; $i < $count; $i++) {
                $res[$i]["table"] = @mysql_field_table($id, $i);
                $res[$i]["name"]  = @mysql_field_name($id, $i);
                $res[$i]["type"]  = @mysql_field_type($id, $i);
                $res[$i]["len"]   = @mysql_field_len($id, $i);
                $res[$i]["flags"] = @mysql_field_flags($id, $i);
            }
        } else { // full
            $res["num_fields"] = $count;

            for ($i = 0; $i < $count; $i++) {
                $res[$i]["table"]              = @mysql_field_table($id, $i);
                $res[$i]["name"]               = @mysql_field_name($id, $i);
                $res[$i]["type"]               = @mysql_field_type($id, $i);
                $res[$i]["len"]                = @mysql_field_len($id, $i);
                $res[$i]["flags"]              = @mysql_field_flags($id, $i);
                $res["meta"][$res[$i]["name"]] = $i;
            }
        }

        // free the result only if we were called on a table
        if ($table)
            @mysql_free_result($id);

        return $res;
    }

    /* private: error handling */
    //错误处理方法
    function halt($msg)
    {
        $this->Error = @mysql_error($$LinkName);
        $this->Errno = @mysql_errno($$LinkName);
        if ($this->Halt_On_Error == "no")
            return;

        $this->haltmsg($msg);

        if ($this->Halt_On_Error != "report")
            die("Session halted.");
    }

    //显示错误信息
    function haltmsg($msg)
    {
        printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
        printf("<b>MySQL Error</b>: %s (%s)<br>\n",
               $this->Errno,
               $this->Error);
    }

    //显示所有表名称
    function table_names()
    {
        $this->query("SHOW TABLES");
        $i = 0;
        while ($info = mysql_fetch_row($this->Query_ID)) {
            $return[$i]["table_name"]      = $info[0];
            $return[$i]["tablespace_name"] = $this->Database;
            $return[$i]["database"]        = $this->Database;
            $i++;
        }

        return $return;
    }

    //实例注销前需要释放相应的内存空间
    Function close()
    {
        free();
    }

    //关闭数据库联结
    Function close_link()
    {
        $LinkName = $this->LinkName;
        global $$LinkName;

        if ($mylink) {
            if (mysql_close($$LinkName)) {
                $mylink = false;

                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    //以下为liupei新增函数，主要为扩展sql
    function get_db_tables()
    {//得到数据库db_name下的所有表的名字的字符串
        $db_name    = $this->Database;
        $results    = mysql_list_tables($db_name);
        $table_num  = mysql_affected_rows();
        $table_name = "";
        $table_str  = "";
        for ($i = 0; $i < $table_num; $i++) {
            $table_name = mysql_tablename($results, $i);
            $table_str  .= $table_name.",";
        }
        if (trim($table_str) != "") {
            $table_str = ",".$table_str;
        }

        return $table_str;
    }

}

class DB_Plugin extends DB_Sql
{
    var $Host     = '127.0.0.1';
    var $Database = "level";
    var $User     = 'root';
    var $Password = '123456';
    var $LinkName = "conn_ev123_web_plugin";
}
$DB_Plugin = new DB_Plugin;
$DB_Plugin->query("set names latin1");

class DB_Plugin_R extends DB_Sql
{
    var $Host     = '127.0.0.1';
    var $Database = "level";
    var $User     = 'root';
    var $Password = '123456';
    var $LinkName = "conn_ev123_web_plugin_r";
}
$DB_Plugin_R = new DB_Plugin_R;
$DB_Plugin_R->query("set names latin1");