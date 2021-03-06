<?php
error_reporting(E_ALL);
require_once('config.php');

// ================================================================
// Set locale
// ================================================================
setlocale(LC_ALL, "ru_CN.UTF-8");
setlocale(LC_NUMERIC, "C");

date_default_timezone_set("Asia/Novosibirsk");

ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

//=================================================================
// URL params
//=================================================================
if(isset($_SERVER["REQUEST_URI"])) {
	$rquri = explode("?", $_SERVER["REQUEST_URI"]);
	$rquri = preg_split("/\//", $rquri[0], -1, PREG_SPLIT_NO_EMPTY);
}
$URL_PARAMS = empty($rquri) ? array('index') : $rquri;
//die(print_r($URL_PARAMS,true));

//=================================================================
// Session
//=================================================================
$session_save_path = BASEDIR.'tmp/session';
if(!is_dir($session_save_path)) mkdir($session_save_path, 0777, true);
else if(!is_writable($session_save_path)) chmod($session_save_path, 0777);

ini_set('session.save_path', $session_save_path);
ini_set('session.auto_start', '1');
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
if(PHP_SESSION_NONE===session_status()) session_start();

//=================================================================
// MySQL
//=================================================================
require_once(BASEDIR.'php/db.php');
ini_set('mysql.default_host',DB_HOST);
ini_set('mysql.default_user',DB_USER);
ini_set('mysql.default_password',DB_PASS);
$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
$opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => FALSE];
try {
	$_PDO = new PDO($dsn, DB_USER, DB_PASS, $opt);
} catch (PDOException $e) {
	$emsg = iconv('CP1251','UTF-8',$e->getMessage());
	throwError500("Подключение не удалось.\r\nPDOException: ".$emsg);
}
$_DBR = new DataBaseRequests($_PDO ?: NULL);
//=================================================================
// Smarty
//=================================================================
require(BASEDIR.'lib/smarty/libs/Smarty.class.php');
$Smarty = new Smarty;
$Smarty->compile_dir = BASEDIR.'tmp/smarty_compile/';
$Smarty->cache_dir = BASEDIR.'tmp/smarty_cache/';
$Smarty->template_dir = BASEDIR.'tpl/';
$Smarty->config_dir = BASEDIR.'tpl/cfg/';

if(!is_dir($Smarty->compile_dir)) mkdir($Smarty->compile_dir, 0777, true);
else if(!is_writable($Smarty->compile_dir)) chmod($Smarty->compile_dir, 0777);
if(!is_dir($Smarty->cache_dir)) mkdir($Smarty->cache_dir, 0777, true);
else if(!is_writable($Smarty->cache_dir)) chmod($Smarty->cache_dir, 0777);

//=================================================================
// 
//=================================================================
?>