<?php
if (!defined('EV_ACCESS')) die('NOT ACCESS!');
class Request{

    private $_clean = array();

    public function __construct()
	{
		 //$this->_clean = $_REQUEST;
    }

	public static function isPost()
	{
	     return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public static function isGet()
	{
	     return $_SERVER['REQUEST_METHOD'] == 'GET';
	}

	public function get($name = '',$html=1)
	{
		 if(!$html)$_REQUEST[$name]=htmlspecialchars(strip_tags($_REQUEST[$name]), ENT_COMPAT, "ISO-8859-1");
	     return array_key_exists($name,$_REQUEST) ? $_REQUEST[$name] : "";

	}

	public function set($name = '', $value = '')
	{
	     $clone = clone $this;
	     $clone->_clean[$name] = $value;
		 return $clone;
	}

	public function getRequest()
	{
	     return $this->_clean;
	}

	public function getController()
	{
		 $controller = trim($this->get('c'));
		 return $controller ? ucwords($controller) : 'Default';
	}

	public function getAction()
	{
		 $action = trim($this->get('a'));
		 return $action ? ucwords($action) : 'Default';
	}

	public function getEvent()
	{
		 return ucwords($this->get('e'));
	}
}
?>
