<?php

if (!defined('EV_ACCESS')) die('NOT ACCESS!');

class Factory
{
    private static $_objects = array();
    public static function S($className = '', $args = array())
    {
		/*if (!defined ('APP_BASE')) {
            die('未定义应用程序根目录');
        }*/
        if ( $className ) {
	        if (isset(self::$_objects[$className]) && self::$_objects[$className]) {
	        	return self::$_objects[$className];
	        }else{
	            if( empty($args) ){
                    $newObj    = new $className();
                }else{
                    $newObj    = new $className($args);
                }
	        	self::$_objects[$className]	= $newObj;
	        	return $newObj;
	        }
        } else {
			 trigger_error("未指定要调用的类");
		}
    }

    public static function N($className = '', $args = array())
    {
        /*if (!defined ('APP_BASE')) {
            die('未定义应用程序根目录');
        }*/

        if ( $className ) {
            if( empty($args) ){
                $obj = new $className();
            }else{
                $obj = new $className($args);
            }
        	return $obj;
        } else {
        	trigger_error("未指定要调用的类");
        }
    }

	private static function arrayToStr($col = array() ){
	    $str    = "";
	    if( is_array($col) ){
    	    foreach ($col as $item){
    	        $str    .= "'".$item."',";
    	    }
    	    $str = substr($str,0,strlen($str)-1);
    	    return $str;
	    }else{
	        return $col;
	    }
	}

    public static function DB($name = '')
    {
        return Ebase::getDb($name);
    }
}
?>