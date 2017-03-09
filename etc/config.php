<?php
if($_SERVER['SERVER_NAME']==='dev.cherio.su') {
	define('DB_HOST', 'u440306.mysql.masterhost.ru');
	define('DB_USER', 'u440306');
	define('DB_PASS', 'vI_7.O3ieS');
	define('DB_NAME', 'u440306');
}
else if($_SERVER['SERVER_NAME']==='cherio.su') {
	define('DB_HOST', 'u440306.mysql.masterhost.ru');
	define('DB_USER', 'u440306');
	define('DB_PASS', 'vI_7.O3ieS');
	define('DB_NAME', 'u440306');
} else {
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_NAME', 'u440306');
}
?>