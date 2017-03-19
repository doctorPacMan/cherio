<?php
set_time_limit(45);
ini_set('error_reporting',E_ALL);
ini_set('html_errors',FALSE);
ob_implicit_flush(true);
session_write_close();// make session read-only
ignore_user_abort(true);// disable default disconnect checks

define('APPDIR',__DIR__.'/../../app');
require_once(APPDIR.'/app.php');

header($_SERVER['SERVER_PROTOCOL']." 200 OK", true, 200);
header("Content-Type: text/plain; charset=utf-8");
header("Cache-Control: no-cache");

if(isset($_GET['echo'])) {
	header("Content-Type: text/event-stream; charset=utf-8");
	$sse = new APP\SSEClient('CID');
	$sse->run();
}
else if(isset($_GET['send'])) {
	$xfile_name = __DIR__.'/../../tmp/app/command.txt';
	$xfile_link = fopen($xfile_name,'a');
	fwrite($xfile_link,"command".PHP_EOL);
	fclose($xfile_link);
	
	echo(realpath($xfile_name).PHP_EOL);
	echo('>>>'.PHP_EOL.file_get_contents($xfile_name).'<<<');
}
else {

}
?>