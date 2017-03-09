<?php
//$users = $_PDO->query("SELECT * FROM `users`")->fetchAll();
//$Smarty->assign('users',$users);

$echo = 'nothing';
$form_eror = NULL;
$form_warn = NULL;
$authfailure = NULL;
$ff_username = empty($_POST['username']) ? NULL : $_POST['username'];
$ff_password = empty($_POST['userpass']) ? NULL : $_POST['userpass'];
//$ff_password = md5($ff_password);

if ($ff_username && $ff_password) {
	$query_login = $ff_username;
	$query_pass = $ff_password;//$query_pass = md5($query_pass);
	$query = "SELECT * FROM `users` WHERE ";
	$query.= "`login`='".$query_login."' AND `pass`='".$query_pass."'";
	$rezlt = $_PDO->query($query)->fetchAll();
	if(!empty($rezlt)) $Smarty->assign('User',$rezlt[0]);
	else $Smarty->assign('echo','incorrect login or password');
} 
elseif ($ff_username || $ff_password) {

	$Smarty->assign('echo','empty login or password');

}
else {
	$echo = !empty($_POST['username']) ?: 'noname';
	$echo.= !empty($_POST['userpass']) ?: 'nopass';
	$Smarty->assign('echo',$echo);
}

//$Smarty->display('index.tpl');
//$Smarty->assign('message',$message);
//$Smarty->assign('result', $result);
$Smarty->display('auth/main.tpl');

/*
$action = empty($_POST['action']) ? false : $_POST['action'];
$result = false;
$message = 'action: '.$action;

if($action=='login') {
	
	$ff_name = $_POST['login'];
	$ff_pass = $_POST['password'];

	$req = "`name`='".$ff_name."' AND `pass`='".md5($ff_pass)."'";
	$req = "`name`='".$ff_name."'";
	$res = $DB->getData('users',$req);

	if(!$res) {// register
	
		$rn = array(
			"name" => "'".$ff_name."'",
			"pass" => "'".md5($ff_pass)."'",
			"ssid" => "'".session_id()."'"
			);
		$q = $DB->addData('users', $rn);

		$message.=' register:'.$ff_name.'@'.$ff_pass.' query:'.$q;

		$res = $DB->getData('users',$req);
		$result = $res[0];

		$result['ssid'] = session_id();
		$_SESSION['user'] = $result;
		$User->register($result);
	}
	else {// login
		$result = $res[0];

		if($result['pass']==md5($ff_pass)) {
			$rn = array("ssid"=>"'".session_id()."'");
			$DB->editData('users', $req, $rn);
			$result['ssid'] = session_id();
			$_SESSION['user'] = $result;
			$User->register($result);
		}
		else {
			$result = false;
			$message.=' Incorrect password: "'.$ff_pass.'"';
		}
	}
}
else if($action=='logout') {

	unset($_SESSION['user']);
	//$User->destroy();
	$Smarty->assign('User',array());
	$message = 'logout success';
	$result = true;
}
*/
?>