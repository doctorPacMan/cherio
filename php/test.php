<?
$sid = 'eoc0h7drqbgdni0ces0quv3d92';

if(0) {
	session_destroy();
	session_id($sid);
	session_start();
}
else if(0){
	$_SESSION['var'] = 'sv';
	$_SESSION['sid'] = session_id();
}
else {
	//$_SESSION = array();
	if(ini_get("session.use_cookies")) {
    	$p = session_get_cookie_params();
    	setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
	}
	session_destroy();
	session_start();
}

echo("SID: ".session_id());
echo("<hr><pre>SESSION: ".print_r($_SESSION,true)."</pre>");
?>