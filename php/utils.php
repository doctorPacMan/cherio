<?php
function rootDomainIs($domn) {
    return (substr($_SERVER['HTTP_HOST'],-strlen($domn))==$domn);
}

function throwError500($text=FALSE) {
	header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error', true, 500);
	header('Content-Type: text/plain; charset=utf-8');
    exit($text ?: '500 Internal Server Error');
}

function setCorsHeaders() {
	$request_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
	$request_heders = isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']) ? $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] : '*';
	header("Access-Control-Allow-Origin: ".$request_origin);
	header("Access-Control-Allow-Headers: ".$request_heders);
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, HEAD");         
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Max-Age: 86400");// cache for 1 day
	return $request_origin;
}

function session_flush() {
	if(PHP_SESSION_NONE===session_status()) return TRUE;

	$p = session_get_cookie_params();
	setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);

	$_SESSION = array();
	$r = session_destroy();
	return $r ? TRUE : FALSE;
}
?>