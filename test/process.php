<?php
$processid = getmypid();
$frequency = 500;
$lifetime = 8000;
$iteration = 0;
$init_time = round(microtime(true)*1000);
$done_time = $init_time;

//echo("----- process ".$processid." init at ".$init_time."\r\n");
while (true) {
    $iteration++;
	$nowtime = round(microtime(true)*1000);
	$runtime = ($nowtime - $init_time);//ms
	if($runtime>=$lifetime) break;
	else usleep($frequency*1000);

	$sdin_size = count(fstat(STDIN));
	$sdin_value = fread(STDIN, 4096);
	if($sdin_value=='stop') {
		echo("Stop ".$iteration."\r\n");
		exit;
	}
	else if($sdin_value=='time') {
		echo("Time ".$iteration." ".date('h:i:s')."\r\n");
		exit;
	}
	else echo("Tick ".$iteration." > ".$sdin_value."\r\n");
}
//exit;
//$done_time = round(microtime(true)*1000);
//echo("----- process ".$processid." done at ".$done_time."\r\n");
?>