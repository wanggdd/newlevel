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

class DB_PDO
{
    protected $host;
    protected $username;
    protected $password;
    protected $post;
    protected $database;
    protected $charset;
    protected $tablepre;
    protected $db;
    protected $errors       = array();
    private   $affectedRows = 0; // 更新条数

    public function __construct($host = '', $database = '', $username = '', $password = '', $post = '', $charset = '', $tablepre = '')
    {
        $this->tablepre = '';

        try {
            $this->db       = new PDO("mysql:dbname={$this->database};host={$this->host};port={$this->post};charset={$this->charset}", $this->username, $this->password);
            $this->db->exec("SET sql_mode='';");
        } catch (PDOException $e) {
            exit();
        }
    }

    public function close_link()
    {
        return true;
    }

    /**
     * 返回带前缀表名
     *
     * @param $table 表名
     *
     * @return string
     */
    public function tablename($table = null)
    {
        return $this->tablepre.$table;
    }

    /**
     * 支持写操作的更新删除,不能查询
     *
     * @param       $sql
     * @param array $params
     *
     * @return bool|int
     */
    public function query($sql = null, $params = array())
    {
        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            $msc = microtime(true);
        }
        $this->affectedRows = 0;

        if (empty($params)) {
            $sql = $this->prepare($sql, 2);
            $this->affectedRows = $this->db->exec($sql);
            return $this->affectedRows;
        }
        $statement = $this->prepare($sql);
        $result    = $statement->execute($params);

        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            echo microtime(true) - $msc ."<br />";
        }
        if (defined("FORMAL_SERVER") || (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])) {
            $this->errorInfo($statement, $params);
        }

        if (!$result) {
            return false;
        } else {
            $this->affectedRows = $statement->rowCount();

            return $this->affectedRows;
        }
    }


    // 最后一次SQL操作,影响的数据库行数,比如对成功执行了delete,insert 等，select则和num_rows()一致
    // 如果是select,则返回0
    // 如果没有where字句,返回也是0
    public function affected_rows()
    {
        return $this->affectedRows;
    }

    /*
     * 返回数据第一条第N列
     */
    public function get_var($sql = null, $params = array(), $column = 0)
    {
        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            $msc = microtime(true);
        }
        $statement = $this->prepare($sql);
        $result    = $statement->execute($params);

        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            echo microtime(true) - $msc ."<br />";
        }
        if (defined("FORMAL_SERVER") || (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])) {
            $this->errorInfo($statement, $params);
        }

        if (!$result) {
            return false;
        } else {
            $data = $statement->fetchColumn($column);

            return $data;
        }
    }

    /**
     * 查询一条数据
     *
     * @param       $sql
     * @param string $oMethod  A 数组 O 对象
     * @param array $params
     *
     * @return bool|mixed
     */
    public function get_row($sql = null, $oMethod = 'A', $params = array())
    {
        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            $msc = microtime(true);
        }

        $statement = $this->prepare($sql);
        $result    = $statement->execute($params);

        if (defined("FORMAL_SERVER") || (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])) {
            $this->errorInfo($statement, $params);
        }

        if (!$result) {
            if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
                echo microtime(true) - $msc ."<br />";
            }

            return false;
        } else {
            $data = $statement->fetch($oMethod == 'O' ? PDO::FETCH_OBJ : PDO::FETCH_ASSOC);

            if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
                echo microtime(true) - $msc ."<br />";
            }

            return $data;
        }
    }

    public function get_new_row($sql = null, $oMethod = 'A', $params = array())
    {
        return $this->get_row($sql, $oMethod, $params);
    }

    /**
     * 查询多条数据
     *
     * @param        $sql
     * @param string $oMethod  A 数组 O 对象
     * @param array  $params
     * @param string $keyfield
     *
     * @return array|bool
     */
    public function get_results($sql = null, $oMethod = 'A', $params = array(), $keyfield = '')
    {
        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            $msc = microtime(true);
        }

        $statement = $this->prepare($sql);
        $result    = $statement->execute($params);

        if (defined("FORMAL_SERVER") || (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])) {
            $this->errorInfo($statement, $params);
        }

        if (!$result) {
            return false;
        } else {
            if (empty($keyfield)) {
                $result = $statement->fetchAll($oMethod == 'O' ? PDO::FETCH_OBJ : PDO::FETCH_ASSOC);
            } else {
                $temp   = $statement->fetchAll($oMethod == 'O' ? PDO::FETCH_OBJ : PDO::FETCH_ASSOC);
                $result = array();
                if (!empty($temp)) {
                    foreach ($temp as $key => &$row) {
                        if ($oMethod == 'O') {
                            if (isset($row->$keyfield)) {
                                $result[$row->$keyfield] = $row;
                            } else {
                                return false;
                            }
                        } else {
                            if (isset($row[$keyfield])) {
                                $result[$row[$keyfield]] = $row;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            }

            if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
                echo microtime(true) - $msc ."<br />";
            }

            return $result;
        }
    }

    /*
     * 插入数据id
     */
    public function last_insert_id()
    {
        return $this->db->lastInsertId();
    }

    /**
     * 检测表是否为空
     *
     * @param       $tablename
     * @param array $params
     *
     * @return bool
     */
    public function exists($tablename = null, $params = array())
    {
        $row = $this->get($tablename, $params);
        if (empty($row) || !is_array($row) || count($row) == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 启动一个事务
     */
    public function begin()
    {
        $this->db->beginTransaction();
    }

    /**
     * 提交一个事务
     */
    public function commit()
    {
        $this->db->commit();
    }

    /**
     * 回滚一个事务
     */
    public function rollback()
    {
        $this->db->rollBack();
    }

    /*
     * 执行多条sql语句,建表常用
     * $sql sql语句
     * $stuff 数据表的前缀
     * */
    public function run($sql = null, $stuff = 'ims_')
    {
        if (!isset($sql) || empty($sql))
            return;
        $sql = str_replace("\r", "\n", str_replace(' '.$stuff, ' '.$this->tablepre, $sql));
        $sql = str_replace("\r", "\n", str_replace(' `'.$stuff, ' `'.$this->tablepre, $sql));
        $ret = array();
        $num = 0;
        $sql = preg_replace("/\;[ \f\t\v]+/", ';', $sql);
        foreach (explode(";\n", trim($sql)) as $query) {
            $ret[$num] = '';
            $queries   = explode("\n", trim($query));
            foreach ($queries as $query) {
                $ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
            }
            $num++;
        }
        unset($sql);
        foreach ($ret as $query) {
            $query = trim($query);
            if ($query) {
                $this->query($query, array());
            }
        }
    }

    /**
     * 检测表中是否存在某个字段
     *
     * @param $tablename
     * @param $fieldname
     *
     * @return bool
     */
    public function fieldexists($tablename = null, $fieldname = null)
    {
        $isexists = $this->get_row("DESCRIBE ".$this->tablename($tablename)." `{$fieldname}`", 'A', array());

        return !empty($isexists) ? true : false;
    }

    /**
     * 检测表是否存在
     *
     * @param $table
     *
     * @return bool
     */
    public function tableexists($table = null)
    {
        if (!empty($table)) {
            $data = $this->get_row("SHOW TABLES LIKE '{$this->tablepre}{$table}'", 'A', array());
            if (!empty($data)) {
                $data      = array_values($data);
                $tablename = $this->tablepre.$table;
                if (in_array($tablename, $data)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 检测表中是否存在某个索引
     *
     * @param $tablename
     * @param $indexname
     *
     * @return bool
     */
    public function indexexists($tablename = null, $indexname = null)
    {
        if (!empty($indexname)) {
            $indexs = $this->get_results("SHOW INDEX FROM ".$this->tablename($tablename), 'A', array(), '');
            if (!empty($indexs) && is_array($indexs)) {
                foreach ($indexs as $row) {
                    if ($row['Key_name'] == $indexname) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function prepare($sql = null, $type = 1)
    {
        if (defined("TESTER") && isset($_GET['echo_all_sql']) && $_GET['echo_all_sql']) {
            echo $sql .'<br />';
        }

        $pos = strpos(strtolower($sql), 'from user where username is not null');
        if ($pos !== false) {
            return false;
        }

        $pos = strpos(strtolower($sql), ' or 1');
        if ($pos !== false) {
            return 0;
        }

        $pos = strpos(strtolower($sql), '0xbf27');
        if ($pos !== false) {
            return 0;
        }

        $pos = strpos(strtolower($sql), '0xbf5c27');
        if ($pos !== false) {
            return 0;
        }

        $pos = strpos(strtolower($sql), 'update ');

        if ($pos === 0) {
            $pos = strpos(strtolower($sql), 'where');
            if ($pos === false) {
                return 0;
            }
        }

        $pos = strpos(strtolower($sql), 'delete ');
        if ($pos === 0) {
            $pos = strpos(strtolower($sql), 'where');
            if ($pos === false) {
                return 0;
            }
        }

        $sql = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $sql);

        if ($type == 2) {
            return $sql;
        }

        //$sql       = $this->db->quote($sql);
        $statement = $this->db->prepare($sql);

        return $statement;
    }

    public function errorInfo($statement = null, $params = [])
    {
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL_PARAM'] = $statement->errorInfo();
        } else {
            $GLOBALS['MYSPACE']['SQL_PARAM'] = $statement->errorInfo();
        }


        return true;
    }
}


class DB_Plugin extends DB_PDO
{
    protected $host     = '127.0.0.1';
    protected $username = 'root';
    protected $password = 'fdasfQEW1231';
    protected $post     = 3306;
    protected $database = "web_plugin";
    protected $charset  = "latin1";
}
$DB_Plugin = new DB_Plugin;
$DB_Plugin->query("set names latin1");

class DB_Plugin_W extends DB_PDO
{
    protected $host     = '127.0.0.1';
    protected $username = 'root';
    protected $password = 'fdasfQEW1231';
    protected $post     = 3306;
    protected $database = "web_plugin";
    protected $charset  = "latin1";
}