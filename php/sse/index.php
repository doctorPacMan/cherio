<?php
if(!isset($_GET['echo'])) {
	$Smarty->display('sse.tpl');
	exit;
}
set_time_limit(15);
ob_implicit_flush(true);
session_write_close();// make session read-only
ignore_user_abort(true);// disable default disconnect checks
//header($_SERVER['SERVER_PROTOCOL']." 200 OK", true, 200);
//header("Content-Type: text/event-stream; charset=utf-8");
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
header("Access-Control-Allow-Origin: *");

//echo ":".str_repeat(" ", 2048).PHP_EOL; // 2 kB padding for IE
//echo "retry: 2000".PHP_EOL.PHP_EOL;

$lifetime = 5000;
$frequency = 2000;
$iteration = 0;
$starttime = round(microtime(true)*1000);
while(true) {
	if(connection_aborted()) break;
	
	$iteration++;
	$runtime = (round(microtime(true)*1000) - $starttime);//ms

	$res = "id: ".$iteration.PHP_EOL;
	$res.= "retry: 2000".PHP_EOL;
	$res.= "event: message".PHP_EOL;
	$res.= "data: time is ".date('h:i:s',time()).PHP_EOL;
	$res.= "data: runtime is ".$runtime.PHP_EOL;

	echo($res.PHP_EOL);
	ob_flush();flush();

	if($runtime>=$lifetime)break;
	else usleep($frequency*1000);
}
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