<?php
function rootDomainIs($domn) {
    return (substr($_SERVER['HTTP_HOST'],-strlen($domn))==$domn);
}

function setCorsHeaders() {
	$request_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
	header("Access-Control-Allow-Origin: ".$request_origin);
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, HEAD");         
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Max-Age: 3600");// cache for 1 hour
	header("Content-type: application/json;charset=utf-8");
	header("Cache-Control: max-age=604800, public");
	return $request_origin;
}

function session_flush() {
	if(PHP_SESSION_NONE===session_status()) return TRUE;

	$p = session_get_cookie_params();
	setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);

	$_SESSION = array();
	$r = session_destroy();
	session_start();
	return $r ? TRUE : FALSE;
}
?>