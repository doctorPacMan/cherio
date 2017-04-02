<?php
class AppUser {
function __construct() {
	$userdata = NULL;
	$bdata = NULL;
	$sdata = !empty($_SESSION['user']) ? $_SESSION['user'] : NULL;
	$token = !empty($_COOKIE['token']) ? $_COOKIE['token'] : NULL;

	if($sdata) $userdata = $this->initialize($sdata);
	else if($token) {
		$bdata = $this->getUserByToken($token);
		if($bdata) {
			$userdata = $this->register($bdata);
		}
	}
	$this->userdata = $userdata;
	//die('<pre>user: '.print_r($this->userdata,true).'</pre>');
}
public function getUserById($id) {
	global $_PDO;

	$query = "SELECT * FROM `users` WHERE users.id=".$id;
	$user = $_PDO->query($query)->fetch();
	return $user ?: NULL;
}
public function getUserByToken($token) {
	global $_PDO;

	$query_token = $_PDO->quote($token);
	$query = "SELECT * FROM `users` WHERE users.hash=".$query_token;
	$user = $_PDO->query($query)->fetch();
	return $user ?: NULL;
}
public function getUserByLogin($login) {
	global $_PDO;

	$query_login = $_PDO->quote($login);
	$query = "SELECT * FROM `users` WHERE users.login=".$query_login;
	$user = $_PDO->query($query)->fetch();
	return $user ?: NULL;
}
public function initialize($user) {
	//$user['duel'] = 'duel10000111';
	
	return $user;
}
public function destroy() {
	unset($_SESSION['user']);
	setcookie('token','',strtotime('-1 days'),'/',$_SERVER['HTTP_HOST']);
}
public function register($user) {
	global $_PDO;
	global $_DBR;

	$uid = $user['id'];
	$user['ua'] = $_SERVER['HTTP_USER_AGENT'];
	$user['ip'] = $_SERVER['REMOTE_ADDR'];

	$user['duels'] = $_DBR->getUserDuels($user['id']);

	$hash = md5($user['id'] . $user['ip'] . $user['ua']);
	$hash = base64_encode(pack('H*',sha1($hash.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	$user['hash'] = $hash;

	$time = date('Y-m-d H:i:s',time());
	$user['time'] = $time;

	//$query = "UPDATE `users` SET time=NOW()";
	$query = "UPDATE `users` SET `time`=".$_PDO->quote($time);
	$query.= ", `hash`=".$_PDO->quote($hash);
	$query.= " WHERE users.id=".$user['id'];
	$_PDO->query($query);

	setcookie('token',$hash,strtotime('+1 days'),'/',$_SERVER['HTTP_HOST']);

	$_SESSION['user'] = $user;
	return $this->initialize($user);
}
public function insertNewUser($login, $pass) {
	global $_DBR;
	$_DBR->insertNewUser($login, $pass);
	$bdata = $this->getUserByLogin($login);
	return $this->register($bdata);
}
}
?>