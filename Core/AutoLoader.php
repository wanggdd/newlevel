<?php

if (!defined('EV_ACCESS')) die('NOT ACCESS!');

class AutoLoader
{
    public static $regClass = array();

    public static function autoLoad($className = '')
    {
        if ($className == 'Smarty_Autoloader') {
            return false;
        }

		if (defined('APP_BASE')) {
            if (strpos($className,'_') !== false) {
                // 命名空间特殊处理
                if (strpos($className, 'Model\\') === 0) {
                    $className = str_replace('Model_', '', $className);
                    $fileName  = str_replace('\\', '/', $className).'.php';
                } elseif (strpos($className, 'Job\\') === 0) {
                    $className = str_replace('Job_', '', $className);
                    $fileName  = str_replace('\\', '/', $className).'.php';
                } elseif (strpos($className, 'Support\\') === 0) {
                    $className = str_replace('Support_', '', $className);
                    $fileName  = str_replace('\\', '/', $className).'.php';
                } else {
                    $fragment = explode(DS, self::$regClass[$className]);
                    $fileList = explode('_', array_pop($fragment));
                    $file     = array_pop($fileList);
                    $fileName = implode(DS, $fragment).DS.$file;
                }
			} else {
				$fileName = self::$regClass[$className];
			}
			if (!$fileName) {
                return false;
            }

			$filePath = APP_BASE . DS . $fileName;

            if (file_exists($filePath)) {
				include_once($filePath);
			} else{
				//die("参数错误，文件不存在：{$filePath} ...");
			}
		}
    }

	public static function register($classPath = '')
	{
		if (strpos($classPath, '.')) {
            $classList = explode('.', $classPath);
            $className = array_pop($classList);
            $classPath = str_replace('.', DS, $classPath);
		} else {
			$className = $classPath;
		}
		self::$regClass[$className] = $classPath.'.php';
	}

}

function import($classPath = ''){
	if ($classPath) {
		AutoLoader::register($classPath);
		return true;
	}
	return false;
}
?>