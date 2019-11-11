<?php
/**********************
 *数据查询类
 *date:2011-6-16
 *author:sunxiaoting
 *操作实例：
 *$finder =new Finder($DB_Ev123);
 *$fields_array = array('id'=>'id','uid'=>'userid','classnm'=>'name');
 *$fields_array2 = array('uname'=>'username','cdate'=>'creatdate');
 *$finder->from('ev_user_class C',$fields_array);
 *$finder->leftJoin('user U',$fields_array2,'C.userid=U.userid');
 *$finder->addAndWhere('C.userid=1');
 *$finder->addorWhere('C.id<15');
 *$finder->addOrderBy('C.userid', 'desc');
 *$finder->addGroupBy('C.userid');
 *$finder->setLimiter(10,500);
 ************************/

if (!defined('EV_ACCESS')) die('NOT ACCESS!');

class DBHelper
{
    private $db;
    private $sql;
    private $sqlArray;
    private $limitArray;
    public  $debug;
    public  $rs;

    public function __construct($db = null)
    {
        $this->db = $db instanceof DB_PDO ? $db : NULL;
        $this->sqlArray = array('where'=>'','group'=>'','order'=>'','having'=>'');
    }
    public function init()
    {
        unset($this->sql);
        $this->limitArray = array();
        foreach($this->sqlArray as $key=>$value){
            $this->sqlArray[$key] = '';
        }
    }
    /*
    *说明：实例化该类后需要调用的第一方法
    *参数：$table要查询的表，$fields为$table表中的字段关联数组,键名为字段别名，键值为字段;
    *      $table的格式为：table B 或 database.table B,其中B是表的别名，必要要有.
    **/
    public function from($table = null,$fields = null)
    {
        $this->init();
        if(false === $tableData = $this->parseTable($table)) {
            return false;
        }

        $fieldString = $this->getFieldString($fields,$tableData['alias']);

        if($fieldString === false){
            //echo "Finder::from(table,fields) 参数2-字段：fields需要关联数组！<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }
        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql = 'select '.$fieldString.' from '.$tmp_sql.' as '.$tableData['alias'];
    }
    /*
    *说明：该方法为左连接相关联的某个表
    *参数：$table要连接的表，$fields为$table表中的字段关联数组,键名为字段别名，键值为字段;,$condition 连接条件
    *      $table的格式为：table B 或 database.table B,其中B是表的别名，必要要有.
    **/
    public function leftJoin($table = null,$fields = null,$condition = null)
    {
        if(false === $tableData = $this->parseTable($table))return false;

        $fieldString = $this->getFieldString($fields,$tableData['alias']);

        if($fieldString === false){
            //echo "Finder::leftJoin(table,fields,condition) 参数2-字段：fields需要关联数组！<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }

        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql.= ' left join '.$tmp_sql.' as '.$tableData['alias'].' on '.$condition;
        $this->sql = preg_replace('/select(.+?)from/',"select\\1,$fieldString from",$this->sql,1);
    }

    /*
      *说明：该方法为右连接相关联的某个表
      *参数：$table要连接的表，$fields为$table表中的字段关联数组,键名为字段别名，键值为字段;
      *      $condition 连接条件
      **/
    public function rightJoin($table = null,$fields = null,$condition = null)
    {
        if(false === $tableData = $this->parseTable($table))return false;

        $fieldString = $this->getFieldString($fields,$tableData['alias']);

        if($fieldString === false){
            //echo "Finder::rightJoin(table,fields,condition) 参数2-字段：fields需要关联数组！<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }

        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql.= ' right join '.$tmp_sql.' as '.$tableData['alias'].' on '.$condition;
        $this->sql = preg_replace('/select(.+?)from/',"select\\1,$fieldString from",$this->sql,1);
    }

    /*
      *说明：该方法为右连接相关联的某个表
      *参数：$table要连接的表，$fields为$table表中的字段关联数组,键名为字段别名，键值为字段;
      *      $condition 连接条件
      **/
    public function innerJoin($table = null,$fields = null,$condition = null)
    {
        if(false === $tableData = $this->parseTable($table))return false;

        $fieldString = $this->getFieldString($fields,$tableData['alias']);

        if($fieldString === false){
            //echo "Finder::rightJoin(table,fields,condition) 参数2-字段：fields需要关联数组！<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }

        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql.= ' inner join '.$tmp_sql.' as '.$tableData['alias'].' on '.$condition;
        $this->sql = preg_replace('/select(.+?)from/',"select\\1,$fieldString from",$this->sql,1);
    }

    /*
    *说明：该方法为添加where条件语句，并且以and形式添加多个
    *参数：$condition 连接条件
    **/
    public function addAndWhere($condition = null)
    {
        if($condition){
            $this->sqlArray['where'].= preg_match('/where/i',$this->sqlArray['where']) ? ' and '.$condition : ' where '.$condition;

        }
    }
    /*
    *说明：该方法为添加where条件语句，并且以or形式添加多个
    *参数：$condition 连接条件
    **/
    public function addOrWhere($condition = null)
    {
        if($condition){
            $this->sqlArray['where'].= preg_match('/where/i',$this->sqlArray['where']) ? ' or '.$condition : ' where '.$condition;
        }
    }
    /*
    *说明：该方法为添加分组语句
    *参数：$field 作为分组的字段
    **/
    public function addGroupBy($field = null)
    {
        if($field){
            $this->sqlArray['group'].= preg_match('/group by/i',$this->sqlArray['group']) ? ','.$field : ' group by '.$field;
        }
    }
    /*
    *说明：该方法为添加分组语句
    *参数：$field 作为排序的字段
    **/
    public function addOrderBy($field = null,$mothod='asc')
    {
        if($field){
            $this->sqlArray['order'].= preg_match('/order by/i',$this->sqlArray['order']) ? ','.$field.' '.$mothod : ' order by '.$field.' '.$mothod;
        }
    }
    /*
    *说明：该方法为添加having条件语句，并以and形式添加多个
    *参数：$having having条件
    **/
    public function addAndHaving($having = null)
    {
        if($having){
            $this->sqlArray['having'].= preg_match('/having/i',$this->sqlArray['having']) ? ' and '.$having : ' having '.$having;
        }
    }
    /*
    *说明：该方法为添加having条件语句，并以or形式添加多个
    *参数：$having having条件
    **/
    public function addOrHaving($having = null)
    {
        if($having){
            $this->sqlArray['having'].= preg_match('/having/i',$this->sqlArray['having']) ? ' or '.$having : ' having '.$having;
        }
    }
    /*
    *说明：为SQL添加查询条数限制
    *参数：$start 从低多少条记录开始读取，$length 读取的条数，
    *      当不设置$length时，默认从第一条读取$start条记录。
    */
    public function setLimiter($start = 0, $length = null, $type = 0)
    {
        $this->limitArray['start']  = (int)$start;
        $this->limitArray['length'] = (int)$length;
        $this->limitArray['type']   = (int)$type;
    }

    /*
    *说明：执行SQL查询并返回查询结果
    *参数：fetchObject BOOLEAN类型，true返回对象,false返回关联数组，默认为false
    */
    public function query($fetchObject = true)
    {
        $fetchMethod = $fetchObject === true ? 'O' : 'A';
        if ($this->debug && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            echo $this->getSQL().'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $this->getSQL();
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $this->getSQL();
        }

        if(isset($this->limitArray['start']) && isset($this->limitArray['length']) &&
           $this->limitArray['start']==0 && $this->limitArray['length']==1 && !$this->limitArray['type']){
            $this->rs = $this->db->get_row($this->getSQL(),$fetchMethod);
        } else {
            $this->rs = $this->db->get_results($this->getSQL(),$fetchMethod);
        }
        return $this->rs;
    }
    /*
    *说明：统计查询数量
    *返回类型：数值。
    */
    public function count()
    {
        unset($this->limitArray);
        $sql = preg_replace('/select(.+?)from/i',"select count(*) as cnt from",$this->getSQL(),1);
        if($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1){
            echo $sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }

        return (int)$this->db->get_var($sql);
    }

    public function numRows(){
        return (int)$this->db->num_rows($this->getSQL());
    }


    public function addSqlFun($fun="count(*) as cnt")
    {
        $this->sql = preg_replace('/select(.+?)from/i',"select $fun,\\1 from",$this->sql,1);
    }
    /*
    *说明：获取完整的SQL语句
    *返回类型：字符串。
    */
    private function getSQL()
    {
        $tmp_sql = '';
        if(isset($this->limitArray) && $this->limitArray){
            $tmp_sql = ' limit '.$this->limitArray['start'];
            $tmp_sql.= $this->limitArray['length'] ? ','.$this->limitArray['length'] : '';
        }
        return $this->sql.$this->sqlArray['where'].$this->sqlArray['group'].$this->sqlArray['having'].$this->sqlArray['order'].$tmp_sql;
    }

    /*
    *说明：对传入的表字符串进行解析。
    *参数：$table要解析的表字符串
    *返回类型：出错时返回FALSE,否则返回数组
    */
    private function parseTable($table = null)
    {
        $tableInfo = preg_split('/\s+/',$table);
        if( !is_string($table) || !trim($table) ){
            echo "参数必须是存在的表，并且为字符串类型!<br>";
            return false;
        }
        $results = array(
            'alias'    => $tableInfo[1],
            'table'    => $tableInfo[0],
            'database' => '',
        );
        if(strpos($table,'.') !== false){
            $datafragment = explode('.',$tableInfo[0]);
            $results['database'] = $datafragment[0];
            $results['table']    = $datafragment[1];
        }
        if(!isset($tableInfo[1])){
            echo "请为 $table 表起个别名，格式如：$table  B<br>";
            return false;
        }
        return $results;
    }
    /*
    *说明：将要查询的字段关联数组转化为字符串。
    *参数：$fields 字段关联数组。
    *返回类型：字符串。
    */
    private function getFieldString($fields = null,$tableAlias='DF')
    {
        if(!is_array($fields) || empty($fields))return false;
        $tableAlias = is_string($tableAlias) ? trim($tableAlias) : 'DF';
        $final_fields = array();
        foreach($fields as $key=>$field){
            array_push($final_fields,"$tableAlias.$field as $key");
        }
        return $tmp_sql = !empty($final_fields) ? join(',',$final_fields) : '';
    }

    public function insert($table = null,$field_array = array(),$replace = false){
        if( empty($field_array) || !is_array($field_array) )return false;
        $fields = implode(',',array_keys($field_array));
        $valuesArr = array_values($field_array);
        foreach($valuesArr as &$_v){
            $_v = "'$_v'";
        }
        $values = implode(',',$valuesArr);
        $keyword = $replace === true ? 'replace' : 'insert';
        $sql = $keyword.' into '.$table.'('.$fields.') values('.$values.')';
        if($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1){
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        $this->db->query($sql);
        return $this->db->last_insert_id();
    }

    public function update($table = null,$field_array = array(),$where = ''){
        if( empty($field_array) || !is_array($field_array) )return false;
        $tmp_sql = $concat = '';
        foreach($field_array as $key=>$value){
            $concat .= $key."='$value',";
        }
        $new_fields = substr($concat,0,-1);
        $tmp_sql = (is_string($where) && $where ) ? ' where '.$where : '';
        $sql = 'update '.$table.' set '.$new_fields.$tmp_sql;

        if($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1){
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        $is_success=$this->db->query($sql);
        $up_rows=$this->db->affected_rows();
        $is_success=$up_rows?$up_rows:$is_success;
        return $is_success;
    }

    public function delete($table = null,$where = ''){
        $tmp_sql = (is_string($where) && $where) ? ' where '.$where : '';
        $sql = 'delete from '.$table.$tmp_sql;
        if($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1){
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }

        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }

        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function sqlQuery($sql = null, $tag = null, $oMethod = 'A')
    {
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (strtolower($tag)=='get_var') {
            return  $this->db->get_var($sql);
        }
        if (strtolower($tag)=='get_row') {
            return  $this->db->get_row($sql, $oMethod);
        }
        if (strtolower($tag)=='get_results') {
            return  $this->db->get_results($sql, $oMethod);
        }

        return  $this->db->query($sql);
    }

    public function sum($field = null, $type = 'int')
    {
        unset($this->limitArray);
        $sql = preg_replace('/select(.+?)from/i',"select sum({$field}) as sum from",$this->getSQL(),1);
        if($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1){
            echo $sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if ($type == 'float') {
            return $this->db->get_var($sql);
        } else {
            return (int)$this->db->get_var($sql);
        }
    }
}

?>
