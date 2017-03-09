<?
function rootDomainIs($domn) {
    return (substr($_SERVER['HTTP_HOST'],-strlen($domn))==$domn);
}
//=================================================================
// MySQL
//=================================================================
if(rootDomainIs('cherio.su')) {
	define('DB_HOST', 'u440306.mysql.masterhost.ru');
	define('DB_USER', 'u440306');
	define('DB_PASS', 'vI_7.O3ieS');
} else {
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', '');
}
ini_set('mysql.default_host',DB_HOST);
ini_set('mysql.default_user',DB_USER);
ini_set('mysql.default_password',DB_PASS);

//=================================================================
// Session
//=================================================================
ini_set('session.save_path', ROOT.'tmp/session');
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.auto_start', '0');

//=================================================================
// Smarty
//=================================================================
require(ROOT.'lib/smarty/libs/Smarty.class.php');
$Smarty = new Smarty;
$Smarty->compile_dir = ROOT.'tmp/smarty/compile/';
$Smarty->cache_dir = ROOT.'tmp/smarty/cache/';
$Smarty->template_dir = ROOT.'tpl/';
$Smarty->config_dir = ROOT.'tpl/cfg/';

//mkdir($Smarty->compile_dir, 0777);
//mkdir($Smarty->cache_dir, 0777);
//chmod($Smarty->compile_dir, 0777);
//chmod($Smarty->cache_dir, 0777);

?>