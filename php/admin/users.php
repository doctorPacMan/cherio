<?
$action = !empty($_POST) ? $_POST['action'] : 'main';
$result = null;
$message = null;
if('rgstr'===$action) {
	
	$ff_login = $_POST['login'];
	$ff_psswd = $_POST['password'];

	if(empty($result) && empty($_POST['login'])) $result = 'Empty login';
	if(empty($result) && empty($_POST['password'])) $result = 'Empty password';
	if(empty($result)) {
		$res = $DB->getData('users',"`login`='".$ff_login."'");
		if(!empty($res)) $result = 'Login '.$ff_login.' has already been taken';
	}
	if(empty($result)) {
		$DB->addData('users', array (
			"login"			=> "'".$ff_login."'",
			"password" 		=> "'".md5($ff_psswd)."'",
			"modified_at"	=> "NOW()",
			"pass"	 		=> "'".$ff_psswd."'",
			"ssid"			=> "'".session_id()."'"
		));
		$result = 'Register success! '.$ff_login.'@'.$ff_psswd;
	}
	$Smarty->assign('message',$result);
}

$data = $DB->getData('users');
$Smarty->assign('dbdata',$data);
$Smarty->assign('message',$result);
$Smarty->display('admin/main.tpl');
?>