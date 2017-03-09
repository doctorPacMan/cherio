<?php
define('BASEDIR',__DIR__.'/');
require_once(BASEDIR.'etc/init.php');
require_once(BASEDIR.'php/utils.php');

//=================================================================
// Other
//=================================================================
if($URL_PARAMS[0]=='users') {
	$users = $_PDO->query("SELECT * FROM `users`")->fetchAll();
	$Smarty->assign('echo',print_r($users,true));
	$Smarty->display('index.tpl');
}
else if($URL_PARAMS[0]=='auth') require_once(BASEDIR.'php/auth/index.php');
else if($URL_PARAMS[0]=='html') {

	$Smarty->display('index.tpl');

}
else {
	//header("Content-type:text/plain;charset=utf-8");
	$echo = "path: ".implode('/',$URL_PARAMS)."/\n";
	$echo.= "sid:  ".session_id()."\n";
	$Smarty->assign('echo',$echo);
	$Smarty->display('index.tpl');
}
?>