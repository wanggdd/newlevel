<?php
/****************************
	smarty ÅäÖÃÎÄ¼þ
*****************************/

if(!class_exists('Smarty_GuestBook')){
    class Smarty_GuestBook extends Smarty {

        function __construct()
        {
            // Class Constructor.
            // These automatically get set with each new instance.

            parent::__construct();
            $this->setTemplateDir(SYSTEM_ROOT.'/templates/');
            $this->setCompileDir(SYSTEM_ROOT.'/templates_c/');


            $this->setLeftDelimiter("<{");
            $this->setRightDelimiter("}>");
            $this->debugging = false;
            $this->caching   = false;
            // $this->cache_lifetime = false;
            $this->compile_check = true;
        }
    }

    $smarty = new Smarty_GuestBook();
}
