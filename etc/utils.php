<?php
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
?>