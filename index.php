<?php
define('BASEDIR',__DIR__.DIRECTORY_SEPARATOR);
define('TEMPDIR',BASEDIR.'tmp'.DIRECTORY_SEPARATOR);
require_once(BASEDIR.'php/utils.php');
require_once(BASEDIR.'etc/init.php');

//=================================================================
// User
//=================================================================
require_once(BASEDIR.'php/user.php');
$User = new AppUser();
$Userdata = $User->userdata;
$Smarty->assign('User',$Userdata);

//=================================================================
// Router
//=================================================================
$Smarty->assign('URL',$URL_PARAMS);
if($URL_PARAMS[0]=='users') {
	$users = $_PDO->query("SELECT * FROM `users`")->fetchAll();
	$Smarty->assign('echo',print_r($users,true));
	$Smarty->display('index.tpl');
}
else if($URL_PARAMS[0]=='knb') require_once(BASEDIR.'php/knb/index.php');
else if($URL_PARAMS[0]=='sse') require_once(BASEDIR.'php/sse/index.php');
else if($URL_PARAMS[0]=='auth') require_once(BASEDIR.'php/auth/index.php');
else if($URL_PARAMS[0]=='chat') require_once(BASEDIR.'php/chat/index.php');
else if($URL_PARAMS[0]=='tick') require_once(BASEDIR.'php/tick/index.php');
else if($URL_PARAMS[0]=='duel') require_once(BASEDIR.'php/duel/index.php');
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