<?php
/**
* 网站公用方法
*
* @author       aliang
* @version      01
* @copyright    北京易维新锐网络科技有限公司
* @date         2016-02-24
* @todo         网站公用方法整理
* @changelog
* 暂无
*/


class Functions
{

    /**
     * 调试方法
     */

    /**
     * 输出方法调用处最后一条sql语句(只可以在测试机上使用)
     * @return [type] [description]
     *
     * 2016/02/24 aliang add
     */
    public static function echoSql()
    {
        echo (defined('TESTER') && $GLOBALS['MYSPACE']['SQL_ECHO']) ? $GLOBALS['MYSPACE']['SQL'] : '';
    }

    /**
     * 输出php错误
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
     * 检测方法
     */

    /**
     * 检测文件大小是否超过最大字节数
     * @param  array    $file       文件信息
     * @param  integer  $maxSize    最大字节
     * @return Boole                [description]
     *
     * 2016/02/24 aliang add
     */
    public static function isFileMax($file = array(), $maxSize = 1000000)
    {
        return (int)$file['size'] < $maxSize ? true : false;
    }

    /**
     * 判断是否是通过手机访问
     * @return boolean [description]
     *
     * 2016/02/24 aliang add
     */
    public static function isMobileClient()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }

        //判断手机发送的客户端标志,兼容性有待提高
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
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
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
     * 字符转码方法
     */

    /**
     * 将urf-8转码为gbk
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
     * gbk转换utf8
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
     * 整理方法
     */

    /**
     * 截取一定长度的完整的中文字符
     * @param  string  $str    字符串
     * @param  integer $strLen 字符串截取的长度
     * @return string          截取后的字符串
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
     * 字符串输出整理
     * 去除转义符，替换空格为html代码，换行前插入 HTML 换行标记
     * @param  [type] $str 字符串
     * @return [type]      [description]
     *
     * 2016/02/24 aliang add
     */
    public static function outputFormat($str = '')
    {
        if (!$str) {
            return '';
        }

        $str = stripslashes($str); // 返回一个去除转义反斜线后的字符串
        $str = preg_replace("  ", "&nbsp;", $str);
        return nl2br($str); // 在字符串所有新行之前插入 HTML 换行标记
    }

    /**
     * 写库处理
     * @param  string  $val         字符串
     * @param  integer $isDelHtml   为真清除html标签
     * @param  integer $isDelJs     为真清楚js代码
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
            $val = strip_tags($val); // 从字符串中去除 HTML 和 PHP 标记
            $val = str_replace('&nbsp;', '', $val);
            $val = preg_replace("@<(.*?)>@is", "", $val);
        }
        if ($isDelJs) {
            $search  = array("'<script[^>]*?>.*?</script>'si");
            $replace = array("");
            $val     = preg_replace($search, $replace, $val);
        }

        $val = addslashes($val);

        return $val; // 转义 SQL 语句中使用的字符串中的特殊字符，并考虑到连接的当前字符集
    }

    /**
     * 将特殊字符转换为HTML实体
     * @param  string $val 变量值
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
     * 自动关闭HTML
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
     * 小数格式整理
     * 如 12.00 =》 12
     *    12.10 =》 12.1
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
     * 价格格式化
     * @param  integer/float            $price 价格
     * @param  integer                  $isWan 如果为真，大于一万，输出“万”
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

        if ($price) { // 小数
            $price = "{$tmpArr[0]}.{$price}";
        } else { // 整数
            $price = $tmpArr[0];
        }

        if ((int)$price >= 10000 && $isWan) {
            $price = ($price / 10000) .'万';
        }

        return $price;
    }

    /**
     * 价格显示
     * @param  integer $price 价格值
     * @return [type]         [description]
     *
     * 2016/02/24 aliang add
     */
    public static function priceShow($price = 0)
    {
        $newPrice='';
        if ($price == '0.00' || $price == 0) {
            $newPrice = '无报价';
        } else {
            $intPrice = (int)$price;
            if (($price-$intPrice) == 0) {
                $newPrice = '￥{$intPrice}';
            } else {
                $newPrice = '￥{$price}';
            }
        }
        return $newPrice;
    }

    /**
     * 转换单位的函数
     * @param  string  $source_str      转换单位的字符串
     * @param  integer $is_unit_convert 为真向上转换进制
     * @return string                   [description]
     *
     * 2016/02/24 aliang add
     *
     * 案例：
     * unitsConvert('60分钟'); // 1小时
     * unitsConvert('1024GB'); // 1TB
     */
    public static function unitsConvert($source_str = '', $is_unit_convert = 1)
    {
        $unit_name_arr = array(
            array('Hz', 'KHz', 'MHz', 'GHz'),
            array('KB', 'MB', 'GB', 'TB'),
            array('um', 'mm', 'cm', 'm', 'km'),
            array('ns', 'ms', 's', '分钟', '小时')
        );

        //单位之间对应的进制描述 ||^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $unit_times_arr = array(
            array(1000, 1000, 1000),
            array(1024, 1024, 1024),
            array(1000, 10, 100, 1000),
            array(1000, 1000, 60, 60),
        );


        //不需要转换就不转换
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

        //需要转换单位的集合定义
        //用正则表达式分解出来单位和其他的数字字母字符串
        if (eregi("^([0-9]+[\.]{0,1}[0-9]+)(.*)$", $source_str, $tmp_arr)) {
            if (is_numeric($tmp_arr[1])) {
                if ((int)$tmp_arr[1] == $tmp_arr[1]) {
                    $tmp_arr[1] = (int)$tmp_arr[1];
                    $source_str = (int)$tmp_arr[1].$tmp_arr[2];
                } else {//去掉后面所有的0
                    $data_arr = split("\.", $tmp_arr[1]);
                    eregi("[0]{0,}$", $data_arr[1], $out);
                    if (strlen($out[0])) {
                        $data_arr[1] = substr($data_arr[1], 0, strlen($data_arr[1])-strlen($out[0]));
                    }
                    $tmp_arr[1] = $data_arr[0].($data_arr[1] ? ".".$data_arr[1] : "");
                    $source_str = $tmp_arr[1].$tmp_arr[2];
                }//end of if

                //判断是否在单位数组里 {{{
                /*  $unit_in_arr =true:有此单位   $unit_in_arr =flase:无此单位    */
                $unit_in_arr = false;
                for ($point=0; $point < count($unit_name_arr); $point++) {
                    $tmp_point = array_search($tmp_arr[2], $unit_name_arr[$point]);
                    /**
                    这里会得到几个值{
                    $unit_in_arr  : 单位存在
                    $point        : 单位存在的第一位置
                    $tmp_point    : 单位存在的第二位置
                    */
                    if (is_numeric($tmp_point)) {
                        $unit_in_arr = true;
                        break;
                    }//end of
                }//end of for
                //判断是否在单位数组里 $unit_in_arr }}}

                if ($unit_in_arr==false) {
                    /**
                    经过查询单位本身并不在被转换单位数组中故而不用转换
                    */
                    return $source_str;
                } else {
                    /**
                    $unit_in_arr  : 单位存在
                    $point        : 单位存在的第一位置
                    $tmp_point    : 单位存在的第二位置
                    */
                    //得到基数后比较
                    if ($tmp_arr[1] < $unit_times_arr[$point][$tmp_point]) {
                        return  $source_str;
                    } else {
                        /**
                        准备参数[$point][$tmp_point]
                        $point不动，定位$tmp_point后循环累加$tmp_point=>$tmp_final_point
                        退出循环的条件是数量$tmp_final_num单位小于进制数[$point][$tmp_final_point]
                        或[$point][$tmp_final_point]数组溢出^^^^^^^^^^^^^^^^^^^^^^
                        */
                        $circle = false;
                        for ($tmp_final_num = $tmp_arr[1], $tmp_final_point = $tmp_point; $tmp_final_point < (count($unit_times_arr[$point])); $tmp_final_point++) {
                            $tmp_final_num = $tmp_final_num / $unit_times_arr[$point][$tmp_final_point];
                            $circle = true;//只有循环一次才可能数组越界，单位是数组未的在上面已经就返回啦！！！
                            //如果形如1000g不要转换成1kg，去掉=号
                            if ($tmp_final_num <= $unit_times_arr[$point][$tmp_final_point]) {
                                break;
                            }
                        }//end of for
                        /**
                        $tmp_final_num      : 最终数量
                        $point              : 单位名称存在的第一位置
                        $tmp_final_point    : 单位存在的第二位置
                        */
                        return  $tmp_final_num.(($unit_name_arr[$point][$tmp_final_point+1] && $circle )? $unit_name_arr[$point][$tmp_final_point+1] : $unit_name_arr[$point][$tmp_final_point]);
                    }//end of
                }//end of if  ($unit_in_arr==false)
            } else {
                //不是数字就不转换
                return $source_str;
            }//end of if
        } else {
            return $source_str;
        }//end of if
    }

    /**
     * 获取客户端ip值
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
     * json_encode支持对中文编码
     * @param  string $var 变量值
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
     * 解析json串
     * @param  string $var 变量值
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
     * 对数组或字符串进行url编码
     * @param  string $var 变量值
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
     * 对数组或字符串进行url解码
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
     * 递归对象转换为数组
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
     * 数组转换成json串
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
         * 当json需要传入前台时,需要去除一些特殊字符
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
         * 当json需要传入前台时,需要去除一些特殊字符
         */
        $json = str_replace(array("\r\n", "\r", "\n", "\t"), '', $json);
        if ($delSpecial) {
            $json = str_replace("\'", "\\\'", $json);
        }

        return $json;
    }

    /**
     * 清除换行
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
    * 百度地图坐标转换为google地图坐标
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
    * 生成随机数
    * @param [int] $length 长度
    */
    public static function randKeys($length = 0)
    {
        $pattern = '12345qwertyuiopasdfgh67890jklmnbvcxzMNBVCZXASDQWERTYHGFUIOLKJP';    //字符池
        for ($i=0; $i < $length; $i++) {
            $key .= $pattern{ mt_rand(0, 62) };//生成php随机数
        }
        return $key;
    }

    /**
     * 转义json中的特殊字符
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
     * 把html代码转换成文本
     * @param  [type] $document [description]
     * @return [type]           [description]
     *
     * 2016/7/6 aliang add
     */
    public static function html2text($document = '', $language_type = '')
    {
        $search = array(
            "'<script[^>]*?>.*?</script>'si", // 去掉 javascript
            "'<style[^>]*?>.*?</style>'si", // 去掉 css
            "'<[/!]*?[^<>]*?>'si", // 去掉 HTML 标记
            "'<!--[/!]*?[^<>]*?>'si", // 去掉 注释标记
            "'&(quot|#34);'i", // 替换 HTML 实体
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
        if ($language_type == 1) {//英文不去除空白
            $out      = str_replace(array("\n", "\r", "&nbsp;","\t"), '', $document);
        } else {
            $out      = str_replace(array("\n", "\r", " ","&nbsp;","\t"), '', $document);
        }
        return $out;
    }

    /**
     * 数组编码转换
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
     * 过滤非法词
     * 解决gbk替换的时候出现乱码问题
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
                    Util::redirect('', 5, "您提交的数据中存在非法关键词,请重新提交!");
                }
            }
        }

        return iconv('utf-8', 'gbk//IGNORE', $tmpWord);
    }

    /**
     * 返回数组或对象数组中指定的一列
     * @param  [type] $array       需要取出数组列的多维数组（或结果集）
     * @param  [type] $columnName  需要返回值的列
     * @param  [type] $columnIndex 作为返回数组的索引/键的列
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
     * 数组中指定的值当key值
     * @param  [type] $array       需要取出数组列的多维数组（或结果集）
     * @param  [type] $columnName  key值
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
     * mysql in 字符串查询
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
     * 验证是否为https访问
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
     * 获取分页的起始值
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
     * 获取完整URL
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
     * 把js时间戳转换为php时间戳
     * 2018/9/5 aliang add
     *
     * php中取时间戳时，大多通过time()方法来获得，它获取到数值是以秒作为单位的，而javascript中从
     * Date对象的getTime()方法中获得的数值是以毫秒为单位 ，所以，要比较它们获得的时间是否是同一天，必须要注意
     * 把它们的单位转换成一样，1秒=1000毫秒
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
    * 通过市场上常用API来获取当时是否是国家法定节假日
    * return  0工作日 1休息日 2节假日
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
     * 求两个日期之间相差的天数
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
     * 小程序授权,跟user_user用户生成唯一token(返回最大29位的数字)
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
     * 聊天接口,生成天为有效期的token
     * 如需要修改,请联系聊天功能维护者(李建伟)
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
     * xss过滤函数
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
        // 抓取指定网页
        curl_setopt($o, CURLOPT_URL, $url);
        // 设置header
        curl_setopt($o, CURLOPT_HEADER, 0);
        // post提交方式
        curl_setopt($o, CURLOPT_POST, 1);
        curl_setopt($o, CURLOPT_POSTFIELDS, http_build_query($param));

        // 缺失项 (增加 HTTP Header（头）里的字段)
        if (is_callable($options)) {
            $options($o);
        } else {
            curl_setopt($o, CURLOPT_RETURNTRANSFER, 1);
        }

        // 终止从服务端进行验证
        curl_setopt($o, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 运行curl
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
