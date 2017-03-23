<?php
function getUserByLogin($login) {
	global $_PDO;
	$query_login = $_PDO->quote($login);
	$query = "SELECT * FROM `users` WHERE `login`=".$query_login;
	$rzlts = $_PDO->query($query)->fetchAll();
	return empty($rzlts) ? NULL : $rzlts[0];
}
function insertNewUser($login, $pass) {
	global $_PDO;
	$query_login = $_PDO->quote($login);
	$query_pass = $_PDO->quote($pass);
	$query_hash = $_PDO->quote(md5($pass));
	$query = "INSERT INTO `users` ";
	$query.="(`login`, `pass`, `hash`) VALUES ";
	$query.="(".$query_login.", ".$query_pass.", ".$query_hash.")";
	$_PDO->query($query);
	return getUserByLogin($login);
}

$auth_action = NULL;
//$Userdata = NULL;
$auth_error = FALSE;
$ff_username = empty($_POST['username']) ? NULL : $_POST['username'];
$ff_password = empty($_POST['userpass']) ? NULL : $_POST['userpass'];

if(isset($_GET['logout'])) $auth_action = 'logout';
else if(!empty($_POST['action'])) $auth_action = $_POST['action'];
//$ff_password = md5($ff_password);

if($auth_action==='logout') {
	$User->destroy();
	header('Location: ./', true, 303);
}
else if($auth_action==='regah') {
	if (!$ff_username || !$ff_password) $auth_error = 'Empty login or password';
	else {
		$user_exists = !empty(getUserByLogin($ff_username)) ? TRUE : FALSE;
		if($user_exists) $auth_error = 'Login '.htmlspecialchars($ff_username).' already in use';
		else {
			$User->insertNewUser($ff_username,$ff_password);
			header('Location: ./', true, 303);
		}
	}
}
else if($auth_action==='login') {
	if (!$ff_username || !$ff_password) $auth_error = 'Empty login or password';
	else {
		$bdata = $User->getUserByLogin($ff_username);
		if(empty($bdata)) $auth_error = 'Wrong login or password';
		else if($bdata['pass']!=$ff_password) $auth_error = 'Wrong password or login';
		else {
			$User->register($bdata);
			header('Location: ./', true, 303);
		}
	}
}
else {}

$Smarty->assign('users',$_PDO->query("SELECT * FROM `users`")->fetchAll());
$Smarty->assign('auth_error',$auth_error);
$Smarty->display('auth/main.tpl');
?>