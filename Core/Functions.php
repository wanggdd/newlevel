<?php
/**
* ��վ���÷���
*
* @author       aliang
* @version      01
* @copyright    ������ά��������Ƽ����޹�˾
* @date         2016-02-24
* @todo         ��վ���÷�������
* @changelog
* ����
*/


class Functions
{

    /**
     * ���Է���
     */

    /**
     * ����������ô����һ��sql���(ֻ�����ڲ��Ի���ʹ��)
     * @return [type] [description]
     *
     * 2016/02/24 aliang add
     */
    public static function echoSql()
    {
        echo (defined('TESTER') && $GLOBALS['MYSPACE']['SQL_ECHO']) ? $GLOBALS['MYSPACE']['SQL'] : '';
    }

    /**
     * ���php����
     * @return [type] [description]
     *
     * 2016/02/24 aliang add
     */
    public static function debug()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    /**
     * ��ⷽ��
     */

    /**
     * ����ļ���С�Ƿ񳬹�����ֽ���
     * @param  array    $file       �ļ���Ϣ
     * @param  integer  $maxSize    ����ֽ�
     * @return Boole                [description]
     *
     * 2016/02/24 aliang add
     */
    public static function isFileMax($file = array(), $maxSize = 1000000)
    {
        return (int)$file['size'] < $maxSize ? true : false;
    }

    /**
     * �ж��Ƿ���ͨ���ֻ�����
     * @return boolean [description]
     *
     * 2016/02/24 aliang add
     */
    public static function isMobileClient()
    {
        // �����HTTP_X_WAP_PROFILE��һ�����ƶ��豸
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //���via��Ϣ����wap��һ�����ƶ��豸,���ַ����̻����θ���Ϣ
        if (isset($_SERVER['HTTP_VIA'])) {
            //�Ҳ���Ϊflase,����Ϊtrue
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }

        //�ж��ֻ����͵Ŀͻ��˱�־,�������д����
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // ��HTTP_USER_AGENT�в����ֻ�������Ĺؼ���
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //Э�鷨����Ϊ�п��ܲ�׼ȷ���ŵ�����ж�
        if (isset($_SERVER['HTTP_ACCEPT'])) {
        // ���ֻ֧��wml���Ҳ�֧��html��һ�����ƶ��豸
        // ���֧��wml��html����wml��html֮ǰ�����ƶ��豸
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
            && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
            (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') <
            strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * �ַ�ת�뷽��
     */

    /**
     * ��urf-8ת��Ϊgbk
     * 2016/3/5 aliang add
     *
     * @param string $str
     *
     * @return string
     */
    public static function iconvUtfToGbk($str = '')
    {
        if (!$str) {
            return '';
        }

        return iconv('UTF-8', 'GBK//IGNORE', $str);
    }

    /**
     * gbkת��utf8
     * 2018/11/26 aliang add
     *
     * @param string $str
     *
     * @return string
     */
    public static function iconvGbkToUtf($str = '')
    {
        if (!$str) {
            return '';
        }

        return iconv('GBK', 'UTF-8//IGNORE', $str);
    }

    /**
     * ������
     */

    /**
     * ��ȡһ�����ȵ������������ַ�
     * @param  string  $str    �ַ���
     * @param  integer $strLen �ַ�����ȡ�ĳ���
     * @return string          ��ȡ����ַ���
     *
     * 2016/02/24 aliang add
     */
    public static function gbkSubstr($str = '', $strLen = 10)
    {
        if (!$str) {
            return '';
        }

        return mb_substr($str, 0, $strLen, 'GBK');
    }

    /**
     * �ַ����������
     * ȥ��ת������滻�ո�Ϊhtml���룬����ǰ���� HTML ���б��
     * @param  [type] $str �ַ���
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public static function outputFormat($str = '')
    {
        if (!$str) {
            return '';
        }

        $str = stripslashes($str); // ����һ��ȥ��ת�巴б�ߺ���ַ���
        $str = preg_replace("  ", "&nbsp;", $str);
        return nl2br($str); // ���ַ�����������֮ǰ���� HTML ���б��
    }

    /**
     * д�⴦��
     * @param  string  $val         �ַ���
     * @param  integer $isDelHtml   Ϊ�����html��ǩ
     * @param  integer $isDelJs     Ϊ�����js����
     * @return [type]               [description]
     *
     * 2016/02/24 aliang add
     */
    public static function formatMysqlInsert($val = '', $isDelHtml = 1, $isDelJs = 1)
    {
        $val = trim($val);

        if (!$val) {
            return $val;
        }

        if ($isDelHtml) {
            $val = strip_tags($val); // ���ַ�����ȥ�� HTML �� PHP ���
            $val = str_replace('&nbsp;', '', $val);
            $val = preg_replace("@<(.*?)>@is", "", $val);
        }
        if ($isDelJs) {
            $search  = array("'<script[^>]*?>.*?</script>'si");
            $replace = array("");
            $val     = preg_replace($search, $replace, $val);
        }

        $val = addslashes($val);

        return $val; // ת�� SQL �����ʹ�õ��ַ����е������ַ��������ǵ����ӵĵ�ǰ�ַ���
    }

    /**
     * �������ַ�ת��ΪHTMLʵ��
     * @param  string $val ����ֵ
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public static function entitiesHtmlCode($data = array())
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[htmlspecialchars($key, ENT_COMPAT, "ISO-8859-1", false)] = self::entitiesHtmlCode($value);
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->{htmlspecialchars($key, ENT_COMPAT, "ISO-8859-1", false)} = self::entitiesHtmlCode($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, "ISO-8859-1", false);
        }
        return $data;
    }

    /**
     * �Զ��ر�HTML
     * @param  string $html [description]
     * @return [type]       [description]
     *
     * 2016/02/24 aliang add
     */
    public static function closeHtmlTags($html = '')
    {
        if (!$html) {
            return '';
        }

        #put all opened tags into an array
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        # close tags
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }

    /**
     * С����ʽ����
     * �� 12.00 =�� 12
     *    12.10 =�� 12.1
     * @param  integer $decimals [description]
     * @return [type]            [description]
     *
     * 2016/3/16 aliang add
     */
    public static function decimalsFormat($decimals = 0)
    {
        return preg_replace("/(\.\d+?)0+$/", "$1", $decimals) * 1;
    }

    /**
     * �۸��ʽ��
     * @param  integer/float            $price �۸�
     * @param  integer                  $isWan ���Ϊ�棬����һ���������
     * @return integer/float/string     [description]
     *
     * 2016/02/24 aliang add
     */
    public static function priceFormat($price = 0, $isWan = 0)
    {
        if (!$price) {
            return 0;
        }

        $tmpArr = explode('.', $price);
        $price  = preg_replace("'0+$'", '', $tmpArr[1]);
        $end    = substr($price, -1);

        if ($end == '.') {
            $price = 0;
        }

        if ($price) { // С��
            $price = "{$tmpArr[0]}.{$price}";
        } else { // ����
            $price = $tmpArr[0];
        }

        if ((int)$price >= 10000 && $isWan) {
            $price = ($price / 10000) .'��';
        }

        return $price;
    }

    /**
     * �۸���ʾ
     * @param  integer $price �۸�ֵ
     * @return [type]         [description]
     *
     * 2016/02/24 aliang add
     */
    public static function priceShow($price = 0)
    {
        $newPrice='';
        if ($price == '0.00' || $price == 0) {
            $newPrice = '�ޱ���';
        } else {
            $intPrice = (int)$price;
            if (($price-$intPrice) == 0) {
                $newPrice = '��{$intPrice}';
            } else {
                $newPrice = '��{$price}';
            }
        }
        return $newPrice;
    }

    /**
     * ת����λ�ĺ���
     * @param  string  $source_str      ת����λ���ַ���
     * @param  integer $is_unit_convert Ϊ������ת������
     * @return string                   [description]
     *
     * 2016/02/24 aliang add
     *
     * ������
     * unitsConvert('60����'); // 1Сʱ
     * unitsConvert('1024GB'); // 1TB
     */
    public static function unitsConvert($source_str = '', $is_unit_convert = 1)
    {
        $unit_name_arr = array(
            array('Hz', 'KHz', 'MHz', 'GHz'),
            array('KB', 'MB', 'GB', 'TB'),
            array('um', 'mm', 'cm', 'm', 'km'),
            array('ns', 'ms', 's', '����', 'Сʱ')
        );

        //��λ֮���Ӧ�Ľ������� ||^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $unit_times_arr = array(
            array(1000, 1000, 1000),
            array(1024, 1024, 1024),
            array(1000, 10, 100, 1000),
            array(1000, 1000, 60, 60),
        );


        //����Ҫת���Ͳ�ת��
        if ($is_unit_convert == 0) {
            if (eregi("^([0-9]+[\.]{0,1}[0-9]+)(.*)$", $source_str, $tmp_arr)) {
                $arr = explode('.', $tmp_arr[1]);
                if (strlen($arr[1]) < 2) {
                    return $source_str;
                }
                if (is_numeric($tmp_arr[1])) {
                    if ((int)$tmp_arr[1] == $tmp_arr[1]) {
                        $tmp_arr[1] = (int)$tmp_arr[1];
                        $source_str = (int)$tmp_arr[1].$tmp_arr[2];
                    } else {
                        $data_arr = split("\.", $tmp_arr[1]);
                        eregi("[0]{0,}$", $data_arr[1], $out);
                        if (strlen($out[0])) {
                            $data_arr[1] = substr($data_arr[1], 0, strlen($data_arr[1])-strlen($out[0]));
                        }
                        $tmp_arr[1] = $data_arr[0].($data_arr[1] ? ".".$data_arr[1] : "");
                        $source_str = $tmp_arr[1].$tmp_arr[2];
                    }//end of if
                }
            }
            return  $source_str;
        }

        //��Ҫת����λ�ļ��϶���
        //��������ʽ�ֽ������λ��������������ĸ�ַ���
        if (eregi("^([0-9]+[\.]{0,1}[0-9]+)(.*)$", $source_str, $tmp_arr)) {
            if (is_numeric($tmp_arr[1])) {
                if ((int)$tmp_arr[1] == $tmp_arr[1]) {
                    $tmp_arr[1] = (int)$tmp_arr[1];
                    $source_str = (int)$tmp_arr[1].$tmp_arr[2];
                } else {//ȥ���������е�0
                    $data_arr = split("\.", $tmp_arr[1]);
                    eregi("[0]{0,}$", $data_arr[1], $out);
                    if (strlen($out[0])) {
                        $data_arr[1] = substr($data_arr[1], 0, strlen($data_arr[1])-strlen($out[0]));
                    }
                    $tmp_arr[1] = $data_arr[0].($data_arr[1] ? ".".$data_arr[1] : "");
                    $source_str = $tmp_arr[1].$tmp_arr[2];
                }//end of if

                //�ж��Ƿ��ڵ�λ������ {{{
                /*  $unit_in_arr =true:�д˵�λ   $unit_in_arr =flase:�޴˵�λ    */
                $unit_in_arr = false;
                for ($point=0; $point < count($unit_name_arr); $point++) {
                    $tmp_point = array_search($tmp_arr[2], $unit_name_arr[$point]);
                    /**
                    �����õ�����ֵ{
                    $unit_in_arr  : ��λ����
                    $point        : ��λ���ڵĵ�һλ��
                    $tmp_point    : ��λ���ڵĵڶ�λ��
                    */
                    if (is_numeric($tmp_point)) {
                        $unit_in_arr = true;
                        break;
                    }//end of
                }//end of for
                //�ж��Ƿ��ڵ�λ������ $unit_in_arr }}}

                if ($unit_in_arr==false) {
                    /**
                    ������ѯ��λ�������ڱ�ת����λ�����йʶ�����ת��
                    */
                    return $source_str;
                } else {
                    /**
                    $unit_in_arr  : ��λ����
                    $point        : ��λ���ڵĵ�һλ��
                    $tmp_point    : ��λ���ڵĵڶ�λ��
                    */
                    //�õ�������Ƚ�
                    if ($tmp_arr[1] < $unit_times_arr[$point][$tmp_point]) {
                        return  $source_str;
                    } else {
                        /**
                        ׼������[$point][$tmp_point]
                        $point��������λ$tmp_point��ѭ���ۼ�$tmp_point=>$tmp_final_point
                        �˳�ѭ��������������$tmp_final_num��λС�ڽ�����[$point][$tmp_final_point]
                        ��[$point][$tmp_final_point]�������^^^^^^^^^^^^^^^^^^^^^^
                        */
                        $circle = false;
                        for ($tmp_final_num = $tmp_arr[1], $tmp_final_point = $tmp_point; $tmp_final_point < (count($unit_times_arr[$point])); $tmp_final_point++) {
                            $tmp_final_num = $tmp_final_num / $unit_times_arr[$point][$tmp_final_point];
                            $circle = true;//ֻ��ѭ��һ�βſ�������Խ�磬��λ������δ���������Ѿ��ͷ�����������
                            //�������1000g��Ҫת����1kg��ȥ��=��
                            if ($tmp_final_num <= $unit_times_arr[$point][$tmp_final_point]) {
                                break;
                            }
                        }//end of for
                        /**
                        $tmp_final_num      : ��������
                        $point              : ��λ���ƴ��ڵĵ�һλ��
                        $tmp_final_point    : ��λ���ڵĵڶ�λ��
                        */
                        return  $tmp_final_num.(($unit_name_arr[$point][$tmp_final_point+1] && $circle )? $unit_name_arr[$point][$tmp_final_point+1] : $unit_name_arr[$point][$tmp_final_point]);
                    }//end of
                }//end of if  ($unit_in_arr==false)
            } else {
                //�������־Ͳ�ת��
                return $source_str;
            }//end of if
        } else {
            return $source_str;
        }//end of if
    }

    /**
     * ��ȡ�ͻ���ipֵ
     * @return [type] [description]
     *
     * 2016/02/24 aliang add
     */
    public static function getIPVal()
    {
        static $realip;
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    /**
     * json_encode֧�ֶ����ı���
     * @param  string $var ����ֵ
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public static function varJsonEncode($var = '')
    {
        if (!$var) {
            return '';
        }
        $_var = self::varUrlencode($var);
        return json_encode($_var);
    }

    /**
     * ����json��
     * @param  string $var ����ֵ
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public static function varJsonDecode($var = '')
    {
        if (!$var) {
            return '';
        }

        return self::varUrldecode(json_decode($var, true));
    }

    /**
     * ��������ַ�������url����
     * @param  string $var ����ֵ
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public static function varUrlencode($var = '')
    {
        if (empty($var)) {
            return false;
        }

        if (is_array($var)) {
            foreach ($var as $k => $v) {
                if (is_scalar($v)) {
                    $var[$k] = urlencode($v);
                } else {
                    $var[$k] = self::varUrlencode($v);
                }
            }
        } else {
            $var = urlencode($var);
        }

        return $var;
    }

    /**
     * ��������ַ�������url����
     * @param  string $var [description]
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public function varUrldecode($var = '')
    {
        if (!$var) {
            return false;
        }

        if (is_array($var)) {
            foreach ($var as $k => $v) {
                if (is_scalar($v)) {
                    $var[$k] = urldecode($v);
                } else {
                    $var[$k] = self::varUrldecode($v);
                }
            }
        } else {
            $var = urldecode($var);
        }
        return $var;
    }

    /**
     * �ݹ����ת��Ϊ����
     * @param  [type] $arr [description]
     * @return [type]      [description]
     *
     * 2017/2/24 aliang add
     */
    public static function objectToArray($arr = array())
    {
        if (!is_array($arr) && !is_object($arr)) {
            return $arr;
        }

        $arr = (array)$arr;

        foreach ($arr as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $arr[$k] = (array)self::objectToArray($v);
            }
        }

        return $arr;
    }

    /**
     * ����ת����json��
     * @param [type]  $arr        [description]
     * @param integer $delSpecial [description]
     *
     * 2017/2/24 aliang add
     */
    public static function JSON($arr = array(), $delSpecial = 1, $isRestore = 0)
    {
        if (!$arr) {
            return false;
        }
        if (!is_array($arr) && !is_object($arr)) {
            return $arr;
        }

        $arr = self::objectToArray($arr);

        array_walk_recursive(
            $arr,
            function (&$item, &$key) {
                $item = urlencode(iconv("UTF-8" ,"GBK//IGNORE" ,addslashes(iconv("GBK","UTF-8//IGNORE",$item))));
                $key  = urlencode(iconv("UTF-8" ,"GBK//IGNORE" ,addslashes(iconv("GBK","UTF-8//IGNORE",$key))));
            }
        );

        $json = json_encode($arr);
        $json = urldecode($json);

        if ($isRestore) {
            array_walk_recursive(
                $arr,
                function (&$item, &$key) {
                    $item = urldecode(iconv("GBK" ,"UTF-8" ,$item));
                    $key  = urldecode(iconv("GBK" ,"UTF-8" ,$key));
                }
            );
        }

        /**
         * ��json��Ҫ����ǰ̨ʱ,��Ҫȥ��һЩ�����ַ�
         */
        $json = str_replace(array("\r\n", "\r", "\n", "\t"), '', $json);
        if ($delSpecial) {
            $json = str_replace("\'", "\\\'", $json);
        }

        return $json;
    }

    public static function JSONUtf8($arr = array(), $delSpecial = 1)
    {
        if (!$arr) {
            return false;
        }
        if (!is_array($arr) && !is_object($arr)) {
            return $arr;
        }

        $arr = self::objectToArray($arr);

        array_walk_recursive(
            $arr,
            function(&$item, &$key) {
                $item = Functions::iconvGbkToUtf($item);
                $key  = Functions::iconvGbkToUtf($key);
            }
        );

        $json = json_encode($arr);

        /**
         * ��json��Ҫ����ǰ̨ʱ,��Ҫȥ��һЩ�����ַ�
         */
        $json = str_replace(array("\r\n", "\r", "\n", "\t"), '', $json);
        if ($delSpecial) {
            $json = str_replace("\'", "\\\'", $json);
        }

        return $json;
    }

    /**
     * �������
     * 2018/6/6 aliang add
     *
     * @param string $str
     *
     * @return mixed
     */
    public static function clearLineFeed($str = '')
    {
        return str_replace(array("\r\n", "\r", "\n", "\t"), '', $str);
    }

    /**
    * �ٶȵ�ͼ����ת��Ϊgoogle��ͼ����
    * @param [type] $lat [description]
    * @param [type] $lng [description]
    */
    public static function Convert_BD09_To_GCJ02($lat = 0, $lng = 0)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.0057;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
    }

    /**
    * ���������
    * @param [int] $length ����
    */
    public static function randKeys($length = 0)
    {
        $pattern = '12345qwertyuiopasdfgh67890jklmnbvcxzMNBVCZXASDQWERTYHGFUIOLKJP';    //�ַ���
        for ($i=0; $i < $length; $i++) {
            $key .= $pattern{ mt_rand(0, 62) };//����php�����
        }
        return $key;
    }

    /**
     * ת��json�е������ַ�
     * @param  [type] $value [description]
     * @return [type]        [description]
     *
     * 2016/6/20 aliang add
     */
    public static function escapeJsonString($value = '')
    {
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }

    /**
     * ��html����ת�����ı�
     * @param  [type] $document [description]
     * @return [type]           [description]
     *
     * 2016/7/6 aliang add
     */
    public static function html2text($document = '', $language_type = '')
    {
        $search = array(
            "'<script[^>]*?>.*?</script>'si", // ȥ�� javascript
            "'<style[^>]*?>.*?</style>'si", // ȥ�� css
            "'<[/!]*?[^<>]*?>'si", // ȥ�� HTML ���
            "'<!--[/!]*?[^<>]*?>'si", // ȥ�� ע�ͱ��
            "'&(quot|#34);'i", // �滻 HTML ʵ��
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
        );

        $replace = array (
            "",
            "",
            "",
            "",
            "\1",
            "\"",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
        );

        $document = preg_replace($search, $replace, $document);
        if ($language_type == 1) {//Ӣ�Ĳ�ȥ���հ�
            $out      = str_replace(array("\n", "\r", "&nbsp;","\t"), '', $document);
        } else {
            $out      = str_replace(array("\n", "\r", " ","&nbsp;","\t"), '', $document);
        }
        return $out;
    }

    /**
     * �������ת��
     * @param  [type] $in_charset  [description]
     * @param  [type] $out_charset [description]
     * @param  [type] $data        [description]
     * @return [type]              [description]
     *
     * 2016/08/29 aliang add
     */
    public static function multIconv($in_charset = '', $out_charset = '', $data = array())
    {
        if (substr($out_charset, -8) == '//IGNORE') {
            $out_charset = substr($out_charset, 0, -8);
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $key = iconv($in_charset, $out_charset.'//IGNORE', $key);
                    $rtn[$key] = self::multIconv($in_charset, $out_charset, $value);
                } elseif (is_string($key) || is_string($value)) {
                    if (is_string($key)) {
                        $key = iconv($in_charset, $out_charset.'//IGNORE', $key);
                    }
                    if (is_string($value)) {
                        $value = iconv($in_charset, $out_charset.'//IGNORE', $value);
                    }
                    $rtn[$key] = $value;
                } else {
                    $rtn[$key] = $value;
                }
            }
        } elseif (is_string($data)) {
            $rtn = iconv($in_charset, $out_charset.'//IGNORE', $data);
        } else {
            $rtn=$data;
        }
        return $rtn;
    }


    /**
     * ���˷Ƿ���
     * ���gbk�滻��ʱ�������������
     * @param  string $word [description]
     * @return [type]       [description]
     *
     * 2016/08/28 aliang add
     */
    public static function filterFeifaWord($word = '')
    {
        if (!$word) {
            return '';
        }

        global $FEIFA_ARR;

        if (!$FEIFA_ARR) {
            return $word;
        }

        if (is_array($word)) {
            return $word;
        }

        $KEYWORD_FILT_REPLACE = '';
        if (KEYWORD_FILT_REPLACE) {
            $KEYWORD_FILT_REPLACE = iconv('gbk', 'utf-8', KEYWORD_FILT_REPLACE);
        }

        $tmpFeifaArr = self::multIconv('gbk', 'utf-8', $FEIFA_ARR);
        $word        = iconv('gbk', 'utf-8', $word);
        $tmpWord     = str_replace($tmpFeifaArr, $KEYWORD_FILT_REPLACE, $word);

        if (IS_KEYWORD_FILT === 1) {
            if ($word != $tmpWord) {
                if ((defined('AJAX') && AJAX) || (defined('XCX_FLAG') && XCX_FLAG)) {
                    if (defined('TOKEN_ID') && TOKEN_ID == 44) {
                        echo 100000;
                        exit();
                    } else {
                        exit();
                    }
                } else {
                    Util::redirect('', 5, "���ύ�������д��ڷǷ��ؼ���,�������ύ!");
                }
            }
        }

        return iconv('utf-8', 'gbk//IGNORE', $tmpWord);
    }

    /**
     * ������������������ָ����һ��
     * @param  [type] $array       ��Ҫȡ�������еĶ�ά���飨��������
     * @param  [type] $columnName  ��Ҫ����ֵ����
     * @param  [type] $columnIndex ��Ϊ�������������/������
     * @return [type]              [description]
     *
     * 2017/2/19 aliang add
     */
    public static function arrayColumn($array = array(), $columnName = '', $columnIndex = null)
    {
        if (!is_array($array) || is_object($array) || !$array) {
            return false;
        }

        $newArray = array();

        foreach ($array as $element) {
            if (is_object($element)) {
                if ($columnIndex === null || !$element->$columnIndex) {
                    $newArray[] = $element->$columnName;
                } else {
                    $newArray[$element->$columnIndex] = $element->$columnName;
                }
            } else {
                if ($columnIndex === null || !$element[$columnIndex]) {
                    $newArray[] = $element[$columnName];
                } else {
                    $newArray[$element[$columnIndex]] = $element[$columnName];
                }
            }
        }

        return $newArray;
    }

    /**
     * ������ָ����ֵ��keyֵ
     * @param  [type] $array       ��Ҫȡ�������еĶ�ά���飨��������
     * @param  [type] $columnName  keyֵ
     * @return [type]              [description]
     *
     * 2017/2/19 aliang add
     */
    public static function arrayComBine($array = array(), $columnName = null)
    {
        if (!is_array($array) || is_object($array) || !$array) {
            return false;
        }

        if (!$columnName) {
            return $array;
        }

        foreach ($array as $element) {
            if (is_object($element)) {
                if (!isset($element->$columnName)) {
                    return $array;
                }
            } else {
                if (!isset($element[$columnName])) {
                    return $array;
                }
            }

            break;
        }

        $newArray = array();

        foreach ($array as $element) {
            if (is_object($element)) {
                $newArray[$element->$columnName] = $element;
            } else {
                $newArray[$element[$columnName]] = $element;
            }
        }

        return $newArray;
    }

    /**
     * mysql in �ַ�����ѯ
     * 2017/10/13 aliang add
     *
     * @param string $str
     *
     * @return mixed|string
     */
    public static function mysqlInStringFormat($str = '')
    {
        if (!$str) {
            return '';
        }

        $str = '\''. str_replace(',', '\',\'', $str) .'\'';

        return $str;
    }

    /**
     * ��֤�Ƿ�Ϊhttps����
     * 2017/12/12 aliang add
     *
     * @return boolean
     */
    public static function isHttps() {
        if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443 ){
            return true;
        } else {
            return false;
        }
    }

    /**
     * ��ȡ��ҳ����ʼֵ
     * 2017/2/24 aliang add
     *
     * @param  integer $page     [description]
     * @param  integer $pagerows [description]
     * @return [type]            [description]
     */
    public static function offset($page = 0, $pagerows = 0)
    {
        if ((int)$page <= 0) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $pagerows;
        }

        return $offset;
    }

    /**
     * ��ȡ����URL
     * 2018/7/31 aliang add
     *
     * @return string
     */
    public static function curPageURL()
    {
        $pageURL = 'http';

        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

    /**
     * ��jsʱ���ת��Ϊphpʱ���
     * 2018/9/5 aliang add
     *
     * php��ȡʱ���ʱ�����ͨ��time()��������ã�����ȡ����ֵ��������Ϊ��λ�ģ���javascript�д�
     * Date�����getTime()�����л�õ���ֵ���Ժ���Ϊ��λ �����ԣ�Ҫ�Ƚ����ǻ�õ�ʱ���Ƿ���ͬһ�죬����Ҫע��
     * �����ǵĵ�λת����һ����1��=1000����
     *
     * @param int $jsTimestap
     *
     * @return float|int
     */
    public static function jsTimestampToPhpTimestamp($jsTimestap = 0)
    {
        $phpTimestap = 0;

        if ($jsTimestap) {
            $phpTimestap = $jsTimestap / 1000;
        }

        return $phpTimestap;
    }

    /*
    * ͨ���г��ϳ���API����ȡ��ʱ�Ƿ��ǹ��ҷ����ڼ���
    * return  0������ 1��Ϣ�� 2�ڼ���
    */
    public static function validateHoliday($date = '')
    {
        if (!$date) {
            $date = date("Ymd",time());
        }
        $url = "http://api.goseek.cn/Tools/holiday?date=".$date;
        $res = file_get_contents($url);
        $res = json_decode($res,true);
        return $res['data'];
    }

    /**
     * ����������֮����������
     * 2018/9/26 aliang add
     *
     * @param $day1
     * @param $day2
     *
     * @return float|int
     */
    public static function diffBetweenTwoDays($day1 = 0, $day2 = 0)
    {
        $day1 = strtotime($day1);
        $day2 = strtotime($day2);

        if ($day1 < $day2) {
            $tmp  = $day2;
            $day2 = $day1;
            $day1 = $tmp;
        }

        return floor(($day1 - $day2) / 86400);
    }

    /**
     * С������Ȩ,��user_user�û�����Ψһtoken(�������29λ������)
     * 2018/11/28 aliang add
     *
     * @param int $UUId
     *
     * @return string
     */
    public static function UUToken($UUId = 0)
    {
        return md5($UUId.uniqid().rand(10000, 99999)."!@)(*&$%&!@!");
    }

    /**
     * ����ӿ�,������Ϊ��Ч�ڵ�token
     * ����Ҫ�޸�,����ϵ���칦��ά����(�ΰ)
     *
     * 2018/12/11 aliang add
     *
     * @param int $UID
     * @param int $UUId
     *
     * @return string
     */
    public static function chatToken($UID = 0, $UUId = 0)
    {
        return md5("{$UID}_{$UUId}_".date('Y-m-d').'@!%%^@!EEWdf');
    }


    /**
     * mysql like search
     * 2018/3/30 aliang add
     *
     * @param string $fieldName
     * @param string $val
     * @param string $symbol
     *
     * @return string
     */
    public static function mysqlLike($fieldName = '', $val = '', $symbol = '&&')
    {
        if (!$fieldName || !$val || !$symbol) {
            return '';
        }

        return " {$symbol} BINARY ucase({$fieldName}) like ucase('%{$val}%') ";
    }

    /**
     * xss���˺���
     *
     * @param string $string
     *
     * @return string|string[]|null
     */
    public static function removeXss($string = '')
    {
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

        $parm1 = ['javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'];

        $parm2 = [
            'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload',
        ];

        $parm = array_merge($parm1, $parm2);

        for ($i = 0; $i < sizeof($parm); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($parm[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                    $pattern .= '|(&#0([9][10][13]);?)?';
                    $pattern .= ')?';
                }
                $pattern .= $parm[$i][$j];
            }
            $pattern .= '/i';
            $string  = preg_replace($pattern, ' ', $string);
        }

        return $string;
    }

    /**
     * curl post
     * @param $url
     * @param $param
     * @param null $options
     * @return bool|string
     */
    public static function post($url, $param, $options = null)
    {
        if (!$url || !$param) {
            return false;
        }

        $o = curl_init();
        // ץȡָ����ҳ
        curl_setopt($o, CURLOPT_URL, $url);
        // ����header
        curl_setopt($o, CURLOPT_HEADER, 0);
        // post�ύ��ʽ
        curl_setopt($o, CURLOPT_POST, 1);
        curl_setopt($o, CURLOPT_POSTFIELDS, http_build_query($param));

        // ȱʧ�� (���� HTTP Header��ͷ������ֶ�)
        if (is_callable($options)) {
            $options($o);
        } else {
            curl_setopt($o, CURLOPT_RETURNTRANSFER, 1);
        }

        // ��ֹ�ӷ���˽�����֤
        curl_setopt($o, CURLOPT_SSL_VERIFYHOST, FALSE);
        // ����curl
        $data = curl_exec($o);
        curl_close($o);

        return $data;
    }

    public static function curlGet($url, $options= []){

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($options) {
            foreach ($options as $k => $option) {
                curl_setopt($curl, CURLOPT_USERAGENT, $option);
            }
        }

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}
