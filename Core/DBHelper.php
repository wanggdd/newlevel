<?php
/**********************
*���ݲ�ѯ��
*date:2011-6-16
*author:sunxiaoting
*����ʵ����
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

if (!defined('EV_ACCESS')) {
    die('NOT ACCESS!');
}

class DBHelper
{
    private $db;
    private $sql;
    private $sqlArray;
    private $limitArray;
    public $debug;
    public $rs;

    public function __construct($db)
    {
        $this->db =  $db instanceof DB_Sql ? $db : null;
        $this->sqlArray = array('where'=>'','group'=>'','order'=>'','having'=>'');
    }

    public function init()
    {
        unset($this->sql);
        $this->limitArray = array();
        foreach ($this->sqlArray as $key => $value) {
            $this->sqlArray[$key] = '';
        }
    }
    /*
    *˵����ʵ�����������Ҫ���õĵ�һ����
    *������$tableҪ��ѯ�ı�$fieldsΪ$table���е��ֶι�������,����Ϊ�ֶα�������ֵΪ�ֶ�;
    *      $table�ĸ�ʽΪ��table B �� database.table B,����B�Ǳ�ı�������ҪҪ��.
    **/
    public function from($table, $fields)
    {
        $this->init();
        if (false === $tableData = $this->parseTable($table)) {
            return false;
        }

        $fieldString = $this->getFieldString($fields, $tableData['alias']);

        if ($fieldString === false) {
            //echo "Finder::from(table,fields) ����2-�ֶΣ�fields��Ҫ�������飡<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }
        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql = 'select '.$fieldString.' from '.$tmp_sql.' as '.$tableData['alias'];
    }
    /*
    *˵�����÷���Ϊ�������������ĳ����
    *������$tableҪ���ӵı�$fieldsΪ$table���е��ֶι�������,����Ϊ�ֶα�������ֵΪ�ֶ�;,$condition ��������
    *      $table�ĸ�ʽΪ��table B �� database.table B,����B�Ǳ�ı�������ҪҪ��.
    **/
    public function leftJoin($table, $fields, $condition)
    {
        if (false === $tableData = $this->parseTable($table)) {
            return false;
        }

        $fieldString = $this->getFieldString($fields, $tableData['alias']);

        if ($fieldString === false) {
            //echo "Finder::leftJoin(table,fields,condition) ����2-�ֶΣ�fields��Ҫ�������飡<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }

        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql.= ' left join '.$tmp_sql.' as '.$tableData['alias'].' on '.$condition;
        $this->sql = preg_replace('/select(.+?)from/', "select\\1,$fieldString from", $this->sql, 1);
    }
    /*
    *˵�����÷���Ϊ�������������ĳ����
    *������$tableҪ���ӵı�$fieldsΪ$table���е��ֶι�������,����Ϊ�ֶα�������ֵΪ�ֶ�;
    *      $condition ��������
    **/
    public function rightJoin($table, $fields, $condition)
    {
        if (false === $tableData = $this->parseTable($table)) {
            return false;
        }

        $fieldString = $this->getFieldString($fields, $tableData['alias']);

        if ($fieldString === false) {
            //echo "Finder::rightJoin(table,fields,condition) ����2-�ֶΣ�fields��Ҫ�������飡<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }

        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql.= ' right join '.$tmp_sql.' as '.$tableData['alias'].' on '.$condition;
        $this->sql = preg_replace('/select(.+?)from/', "select\\1,$fieldString from", $this->sql, 1);
    }

    public function innerJoin($table, $fields, $condition)
    {
        if (false === $tableData = $this->parseTable($table)) {
            return false;
        }

        $fieldString = $this->getFieldString($fields, $tableData['alias']);

        if ($fieldString === false) {
            //echo "Finder::rightJoin(table,fields,condition) ����2-�ֶΣ�fields��Ҫ�������飡<br>";
            //return false;
            $fieldString = $tableData['alias'].'.*';
        }

        $tmp_sql = !$tableData['database'] ? $tableData['table'] : $tableData['database'].'.'.$tableData['table'];
        $this->sql.= ' inner join '.$tmp_sql.' as '.$tableData['alias'].' on '.$condition;
        $this->sql = preg_replace('/select(.+?)from/', "select\\1,$fieldString from", $this->sql, 1);
    }

    /*
    *˵�����÷���Ϊ���where������䣬������and��ʽ��Ӷ��
    *������$condition ��������
    **/
    public function addAndWhere($condition)
    {
        if ($condition) {
            $this->sqlArray['where'].= preg_match('/where/i', $this->sqlArray['where']) ? ' and '.$condition : ' where '.$condition;
        }
    }
    /*
    *˵�����÷���Ϊ���where������䣬������or��ʽ��Ӷ��
    *������$condition ��������
    **/
    public function addOrWhere($condition)
    {
        if ($condition) {
            $this->sqlArray['where'].= preg_match('/where/i', $this->sqlArray['where']) ? ' or '.$condition : ' where '.$condition;
        }
    }
    /*
    *˵�����÷���Ϊ��ӷ������
    *������$field ��Ϊ������ֶ�
    **/
    public function addGroupBy($field)
    {
        if ($field) {
            $this->sqlArray['group'].= preg_match('/group by/i', $this->sqlArray['group']) ? ','.$field : ' group by '.$field;
        }
    }
    /*
    *˵�����÷���Ϊ��ӷ������
    *������$field ��Ϊ������ֶ�
    **/
    public function addOrderBy($field, $mothod = 'asc')
    {
        if ($field) {
            $this->sqlArray['order'].= preg_match('/order by/i', $this->sqlArray['order']) ? ','.$field.' '.$mothod : ' order by '.$field.' '.$mothod;
        }
    }
    /*
    *˵�����÷���Ϊ���having������䣬����and��ʽ��Ӷ��
    *������$having having����
    **/
    public function addAndHaving($having)
    {
        if ($having) {
            $this->sqlArray['having'].= preg_match('/having/i', $this->sqlArray['having']) ? ' and '.$having : ' having '.$having;
        }
    }
    /*
    *˵�����÷���Ϊ���having������䣬����or��ʽ��Ӷ��
    *������$having having����
    **/
    public function addOrHaving($having)
    {
        if ($having) {
            $this->sqlArray['having'].= preg_match('/having/i', $this->sqlArray['having']) ? ' or '.$having : ' having '.$having;
        }
    }
    /*
    *˵����ΪSQL��Ӳ�ѯ��������
    *������$start �ӵͶ�������¼��ʼ��ȡ��$length ��ȡ��������
    *      ��������$lengthʱ��Ĭ�ϴӵ�һ����ȡ$start����¼��
    */
    public function setLimiter($start = 0, $length)
    {
        $this->limitArray['start']  = (int)$start;
        $this->limitArray['length'] = (int)$length;
    }
    /*
    *˵����ִ��SQL��ѯ�����ز�ѯ���
    *������fetchObject BOOLEAN���ͣ�true���ض���,false���ع������飬Ĭ��Ϊfalse
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
        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $this->getSQL();
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($this->getSQL());
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $this->getSQL();
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($this->getSQL());
        }

        if ($this->limitArray['start']==0 && $this->limitArray['length']==1) {
            $this->rs = $this->db->get_row($this->getSQL(), $fetchMethod);
        } else {
            $this->rs = $this->db->get_results($this->getSQL(), $fetchMethod);
        }
        return $this->rs;
    }

    /*
    *˵����ͳ�Ʋ�ѯ����
    *�������ͣ���ֵ��
    */
    public function count()
    {
        unset($this->limitArray);
        $sql = preg_replace('/select(.+?)from/i', "select count(*) as cnt from", $this->getSQL(), 1);

        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo $sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }

        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
        }

        return (int)$this->db->get_var($sql);
    }

    public function numRows()
    {
        return (int)$this->db->num_rows($this->getSQL());
    }


    public function addSqlFun($fun = "count(*) as cnt")
    {
        $this->sql = preg_replace('/select(.+? )from/i', "select $fun,\\1 from", $this->sql, 1);
    }
    /*
    *˵������ȡ������SQL���
    *�������ͣ��ַ�����
    */
    private function getSQL()
    {
        $tmp_sql = '';
        if ($this->limitArray) {
            $tmp_sql = ' limit '.$this->limitArray['start'];
            $tmp_sql.= $this->limitArray['length'] ? ','.$this->limitArray['length'] : '';
        }

        return $this->sql.$this->sqlArray['where'].$this->sqlArray['group'].$this->sqlArray['having'].$this->sqlArray['order'].$tmp_sql;
    }

    /*
    *˵�����Դ���ı��ַ������н�����
    *������$tableҪ�����ı��ַ���
    *�������ͣ�����ʱ����FALSE,���򷵻�����
    */
    private function parseTable($table)
    {
        $tableInfo = preg_split('/\s+/', $table);
        if (!is_string($table) || !trim($table)) {
            echo "���������Ǵ��ڵı�����Ϊ�ַ�������!<br>";
            return false;
        }
        $results = array('alias'=>$tableInfo[1], 'table'=>$tableInfo[0]);
        if (strpos($table, '.') !== false) {
            $datafragment        = explode('.', $tableInfo[0]);
            $results['database'] = $datafragment[0];
            $results['table']    = $datafragment[1];
        }
        if (!isset($tableInfo[1])) {
            echo "��Ϊ $table �������������ʽ�磺$table  B<br>";
            return false;
        }
        return $results;
    }
    /*
    *˵������Ҫ��ѯ���ֶι�������ת��Ϊ�ַ�����
    *������$fields �ֶι������顣
    *�������ͣ��ַ�����
    */
    private function getFieldString($fields, $tableAlias = 'DF')
    {
        if (!is_array($fields) || empty($fields)) {
            return false;
        }
        $tableAlias = is_string($tableAlias) ? trim($tableAlias) : 'DF';
        $final_fields = array();
        foreach ($fields as $key => $field) {
            array_push($final_fields, "$tableAlias.$field as $key");
        }
        return $tmp_sql = !empty($final_fields) ? join(',', $final_fields) : '';
    }

    public function insert($table, $field_array = array(), $replace = false)
    {
        if (empty($field_array) || !is_array($field_array)) {
            return false;
        }
        $fields    = implode(',', array_keys($field_array));
        $valuesArr = array_values($field_array);
        foreach ($valuesArr as &$_v) {
            $_v = "'$_v'";
        }
        $values = implode(',', $valuesArr);
        $keyword = $replace === true ? 'replace' : 'insert';
        $sql = $keyword.' into '.$table.'('.$fields.') values('.$values.')';

        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
        }

        $this->db->query($sql);
        return $this->db->last_insert_id();
    }

    public function insertMulti($table, $fieldList = array(), $replace = false)
    {
        if (empty($fieldList) || !is_array($fieldList)) {
            return false;
        }

        $fieldsNum   = count($fieldList);
        $keyCount    = 1;
        $fields      = '';
        $multiValues = '';

        foreach ($fieldList as $field_array) {
            if (empty($field_array) || !is_array($field_array)) {
                return false;
            }

            if (!$fields) {
                $fields = implode(',', array_keys($field_array));
            }

            $valuesArr = array_values($field_array);
            foreach ($valuesArr as &$_v) {
                $_v = "'{$_v}'";
            }
            $values = implode(',', $valuesArr);

            $multiValues .= ($fieldsNum == $keyCount) ? "({$values});" : "({$values}),";

            $keyCount++;
        }

        $keyword = $replace === true ? 'replace' : 'insert';
        $sql = $keyword.' into '.$table.'('.$fields.') values '.$multiValues;

        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
        }

        $this->db->query($sql);
        return $this->db->last_insert_id();
    }

    public function update($table, $field_array = array(), $where = '')
    {
        if (empty($field_array) || !is_array($field_array)) {
            return false;
        }
        $tmp_sql = $concat = '';
        foreach ($field_array as $key => $value) {
            $concat .= $key."='$value',";
        }
        $new_fields = substr($concat, 0, -1);
        $tmp_sql = (is_string($where) && $where ) ? ' where '.$where : '';
        $sql = 'update '.$table.' set '.$new_fields.$tmp_sql;

        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        if (defined("FORMAL_SERVER")) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }

        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
        }

        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function delete($table, $where = '')
    {
        $tmp_sql = (is_string($where) && $where) ? ' where '.$where : '';
        $sql = 'delete from '.$table.$tmp_sql;

        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
        }

        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function sqlQuery($sql, $tag, $oMethod = 'A')
    {
        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo 'SQL:'.$sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
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
        if (strtolower($tag)=='insert') {
            $this->db->query($sql);
            return $this->db->last_insert_id();
        }
        if (strtolower($tag)=='update') {
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return  $this->db->query($sql);
    }

    public function sum($field, $type = 'int')
    {
        unset($this->limitArray);
        $sql = preg_replace('/select(.+?)from/i', "select sum({$field}) as sum from", $this->getSQL(), 1);
        if ($GLOBALS['MYSPACE']['SQL_ECHO'] && $this->debug==1) {
            echo $sql.'<br>';
        }
        if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
        }
        /**
         * ��־��¼sql
         * 2017/11/16 aliang add
         */
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['PC_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['PC_COPY_OBJ']->writeLog($sql);
        }
        if ($GLOBALS['LOG']['IS_OPEN'] && $GLOBALS['LOG']['WAP_COPY_OBJ']) {
            $GLOBALS['MYSPACE']['SQL'] = $sql;
            $GLOBALS['LOG']['WAP_COPY_OBJ']->writeLog($sql);
        }

        if ($type == 'float') {
            return $this->db->get_var($sql);
        } else {
            return (int)$this->db->get_var($sql);
        }
    }
}
