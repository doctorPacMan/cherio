<?php
function session_flush() {
	if(session_id()=='') return true;

	$p = session_get_cookie_params();
	setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);

	$_SESSION = array();
	$r = session_destroy();
	session_start();
	return $r;
}
//=================================================================
// Session start
//=================================================================
if(session_id()=='') session_start();
//session_start();
//session_flush();

//echo('<pre>SID: '.session_id().'</pre>');
//echo('<hr><pre>SESSION: '.print_r($_SESSION,true).'</pre>');
//die('<hr>session.php');
?>