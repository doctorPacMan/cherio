<?
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

$Smarty->assign('message',$message);
$Smarty->assign('result', $result);
$Smarty->display('auth/main.tpl');
?>