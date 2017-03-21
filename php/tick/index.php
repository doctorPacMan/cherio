<?php

if(!empty($_SESSION['user'])) {
	$Userdata = $_SESSION['user'];
	$Smarty->assign('User',$Userdata);
}

$Smarty->display('tick/main.tpl');

?>