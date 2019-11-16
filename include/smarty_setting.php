<?php
/****************************
	smarty 配置文件
*****************************/
$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->caching = false;
$smarty->cache_lifetime = 3600*4;
$smarty->debugging = false;
$smarty->left_delimiter="<{";
$smarty->right_delimiter="}>";
$smarty->template_dir = SYSTEM_ROOT."templates";//设置模板目录
$smarty->compile_dir = SYSTEM_ROOT."templates_c"; //设置编译目录
