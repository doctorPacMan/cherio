<?
$action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : null;
$errors = array();
$result = null;
function sessionDestroy() {
	$params = session_get_cookie_params();
    setcookie(session_name(),'',strtotime('01-01-2000 00:00:00'),
        $params["path"],$params["domain"],
        $params["secure"],$params["httponly"]
    );
	unset($_SESSION['user']);
	session_destroy();
}
function getUserData($name,$pass) {
	
	global $DB;
	
	$name = mysql_real_escape_string($name);
	$pass = mysql_real_escape_string($pass);
	
	$req = "`name`='".$name."' AND `pass`='".md5($pass)."'";
	$res = $DB->getData('users',$req);
	
	return $res ? $res[0] : null;
}

function renewUserData($user) {

	global $DB;

	$sid = session_id();

	$usr = $DB->editData('users','id='.$user['id'],array(
		'ssid'=>'\''.$sid.'\'',
		'time'=>'NOW()'
	));
	$usr = $usr[0];
	
	$usr['data'] = print_r($usr,true);
	return $usr;
}

if($action=='logout') sessionDestroy();

if($action=='regster') {
	
	$name = mysql_real_escape_string($_REQUEST['login']);
	$pass = mysql_real_escape_string($_REQUEST['password']);
	
	if(empty($name)) array_push($errors, 'Empty username');
	if(empty($pass)) array_push($errors, 'Empty password');
	if(empty($errors)) {
		if(!preg_match("/^[a-zA-Z0-9]+$/", $name)) array_push($errors, 'Incorrect login mask');
    	if(strlen($name)<3 || strlen($name)>20) array_push($errors, 'Incorrect login size');
    }
	if(empty($errors)) {
		$res = $DB->getData('users',"`name`='".$name."'");
		if(!empty($res)) array_push($errors, 'Login ['.$name.'] has already been taken');
		else {
			$DB->addData('users', array (
				"name" => "'".$name."'",
				"pass" => "'".md5($pass)."'"
			));
			$result = 'Register success! '.$name.'@'.$pass;
		}
		$action = 'login';
	}
}

if($action=='login') {
	
	$name = mysql_real_escape_string($_REQUEST['login']);
	$pass = mysql_real_escape_string($_REQUEST['password']);
	
	if(empty($name)) array_push($errors, 'Empty username');
	if(empty($pass)) array_push($errors, 'Empty password');

	if(empty($errors)) {
		$result = getUserData($name, $pass);
		if(!$result) array_push($errors, 'Incorrect login or password: '.$name.'@'.$pass);
		else $_SESSION['user'] = renewUserData($result);
	}
}

if(!empty($_SESSION['user'])) $Smarty->assign('User',$_SESSION['user']);
else $Smarty->assign('User',null);

$res = array(
	'action' => $action,
	'errors' => $errors,
	'result' => $result
);
$Smarty->assign('action',$action);
$Smarty->assign('errors',$errors);
$Smarty->assign('result',print_r($result,true));
$Smarty->display('account/user.tpl');
?>