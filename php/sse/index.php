<?php
function ftrace($txt) {
	global $client_id;
	$filesrc = __DIR__.DIRECTORY_SEPARATOR."sse.log";

	$text = "#".$client_id." ";
	$text.= "(".date('H:i:s',time()).") ";
	$text.= $txt.PHP_EOL;

	$fsrs = fopen($filesrc,'a');
	fwrite($fsrs, $text);
	fclose($fsrs);
}

if(isset($_GET['test'])) {

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
header("Cache-Control: no-cache");
header("Access-Control-Allow-Origin: *");

$lifetime = 10000;
$frequency = 2500;
$iteration = 0;
$starttime = round(microtime(true)*1000);
$begintime = date('H:i:s',time());
$client_id = empty($_GET['cid']) ? NULL : $_GET['cid'];
$stoprezon = 'justdie';

ftrace("INIT");

while(true) {
	$stoprezon = '?';
	if(connection_aborted()) {$stoprezon='aborted';break;}
	
	$iteration++;
	$runtime = (round(microtime(true)*1000) - $starttime);//ms

	$res = "id: ".$iteration.PHP_EOL;
	$res.= "retry: 2000".PHP_EOL;
	$res.= "event: message".PHP_EOL;

	$data = array(
		"cid" => $client_id,
		"time" => date('h:i:s',time()),
		"start" => $begintime,
		"runtime" => $runtime
	);
	//$res.= "data: ".implode(PHP_EOL."data: ",$data).PHP_EOL;
	foreach($data as $key=>$v) {
		$val = trim(preg_replace('/\s\s+/',' ',$v));
		$res.="data: ".$key."=".$val.PHP_EOL;
	}

	echo($res.PHP_EOL);
	ob_flush();flush();

	if($runtime>=$lifetime) {$stoprezon='timeout';break;}
	else usleep($frequency*1000);
}
ftrace("STOP by ".$stoprezon);

/*
$iteration = 0;
$lifetime = 5000;
$frequency = 1000;
$starttime = round(microtime(true)*1000);
while(true) {

	if(connection_aborted()) exit;

	$iteration++;
	$runtime = (round(microtime(true)*1000) - $starttime);//ms

	$res = "retry: 5000".PHP_EOL;
	//$res.= "event: time".PHP_EOL;
	$res.= "data: time is ".date('h:i:s',time()).PHP_EOL;
	$res.= "id: ".$iteration.PHP_EOL;
	echo($res.PHP_EOL);
	//flush();

	if($runtime>=$lifetime)break;
	else usleep($frequency*1000);
}
*/
		
/*
		// set headers for stream
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");

        // Is this a new stream or an existing one?
        $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
        if ($lastEventId == 0) {
            $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
        }

		echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
		echo "retry: 2000\n";

		// start stream
if(false) while(true) {

			if(connection_aborted()) exit();

			// here you will want to get the latest event id you have created on the server, but for now we will increment and force an update
				$latestEventId = $lastEventId+1;

				if($lastEventId < $latestEventId) {

					echo "id: " . $latestEventId . "\n";
					echo "data: Howdy (".$latestEventId.") \n\n";
					$lastEventId = $latestEventId;
					ob_flush();
					flush();

				}

				else{
				
					// no new data to send
					echo ": heartbeat\n\n";
					ob_flush();
					flush();
					
				}

		sleep(2);
}
*/			
?>