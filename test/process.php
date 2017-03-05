<?php
$processid = getmypid();
$frequency = 1000;
$lifetime = 10000;
$iteration = 0;
$init_time = round(microtime(true)*1000);
$done_time = $init_time;

echo("------------------ process ".$processid." init at ".$init_time."\r\n");
while (true) {
    $iteration++;
	$nowtime = round(microtime(true)*1000);
	$runtime = ($nowtime - $init_time);//ms
	if($runtime>=$lifetime) break;

	echo("Tick".$iteration."\r\n");
	usleep($frequency*1000);
}
$done_time = round(microtime(true)*1000);
echo("------------------ process ".$processid." done at ".$done_time."\r\n");
?>