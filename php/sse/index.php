<?php
$logfile = __DIR__.DIRECTORY_SEPARATOR."sse.log";
$mesfile = __DIR__.DIRECTORY_SEPARATOR."chat.log";

function messageResponse($id, $cid, $data) {

	$res = ":Just a message".PHP_EOL;
	$res.= "id: ".$id.PHP_EOL;
	$res.= "cid: ".$cid.PHP_EOL;
	$res.= "retry: 2000".PHP_EOL;
	$res.= "event: message".PHP_EOL;
	foreach($data as $key=>$v) {
		$val = trim(preg_replace('/\s\s+/',' ',$v));
		$res.="data: ".$key."=".$val.PHP_EOL;
	}
	return $res;
}

function ftrace($txt) {
	global $client_id;
	global $logfile;

	$text = "#".$client_id." ";
	$text.= "(".date('H:i:s',time()).") ";
	$text.= $txt.PHP_EOL;

	$fsrs = fopen($logfile,'a');
	fwrite($fsrs, $text);
	fclose($fsrs);
}

if(isset($_GET['message'])) {
	header("Content-type: application/json; charset=utf-8");
	
	$dtm = date('H:i:s',time());
	$txt = empty($_REQUEST['data']) ? 'ping' : $_REQUEST['data'];
	$txt = $dtm.'>'.trim(preg_replace('/\s\s+/',' ',$txt));
	$txt = (file_exists($mesfile) ? PHP_EOL : '').$txt;
	$res = file_put_contents($mesfile,$txt,FILE_APPEND);
	
	$response = array(
		'txt' => $txt,
		'res' => $res,
		'size' => filesize($mesfile),
		'time' => date('y/m/d H:i:s',time()),
		'message' => '"Message!"'
	);
	echo(json_encode($response));
	exit;
}
else if(isset($_GET['test'])) {

	header("Content-Type: text/plain; charset=utf-8");

	$initime = round(microtime(true)*1000);
	$time_ts = floor($initime/1000);
	$time_ms = $initime - time()*1000;
	$timetxt = date('y/m/d H:i:s',$time_ts).".".$time_ms;
	$filetxt = date('ymd_His',$time_ts)."_".$time_ms.".txt";
	$filesrc = __DIR__.DIRECTORY_SEPARATOR.$filetxt;

	echo("> ".$filetxt.PHP_EOL);
	echo("> ".$timetxt.PHP_EOL);
	echo("> ".$filesrc.PHP_EOL);
	//echo("> ".$proc_id.PHP_EOL);
	//echo("> ".$initime.PHP_EOL);
	//echo("> ".$starttime.PHP_EOL);
	//echo("> ".time().PHP_EOL);
	//echo("> ".($initime - time()*1000).PHP_EOL);
	//echo("> ".microtime().PHP_EOL);
	//echo("> ".microtime(true).PHP_EOL);
	//echo("> ".date('H_i_s',floor($initime/1000)));

	//$fsrs = fopen($filesrc,'a');
	//fwrite($fsrs, $timetxt);
	//fclose($fsrs);
	
	ftrace($timetxt);
	
	exit;
}
else if(!isset($_GET['echo'])) {
	$Smarty->display('sse.tpl');
	exit;
}

set_time_limit(45);
ob_implicit_flush(true);
session_write_close();// make session read-only
ignore_user_abort(true);// disable default disconnect checks
header($_SERVER['SERVER_PROTOCOL']." 200 OK", true, 200);
header("Content-Type: text/event-stream; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache");

$lifetime = 20000;
$frequency = 2500;
$iteration = 0;
$starttime = round(microtime(true)*1000);
$begintime = date('H:i:s',time());
$client_id = empty($_GET['cid']) ? NULL : $_GET['cid'];
$diereason = 'justdie';

$eventfile = fopen($mesfile,'r');
$filesize = NULL;

ftrace("INIT");
while(true) {

	if(connection_aborted()) {$diereason='aborted';break;}
	
	$iteration++;
	$runtime = (round(microtime(true)*1000) - $starttime);//ms
	
	rewind($eventfile);// get full content
	clearstatcache(false,$mesfile);
	$new_size = filesize($mesfile);
	
	if($filesize != $new_size) {
		$filesize = $new_size;
		$cont = fread($eventfile,$filesize);
		$data = array(
			"time" => date('H:i:s',time()),
			"size" => $filesize,
			"cont" => $cont
		);
		$msg = messageResponse($iteration, $client_id, $data);
		echo($msg.PHP_EOL);
	}

	ob_flush();flush();
	if($runtime>=$lifetime) {$diereason='timeout';break;}
	else usleep($frequency*1000);
}
fclose($eventfile);
ftrace("STOP by ".$diereason);
?>