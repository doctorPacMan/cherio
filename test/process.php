<?php
//$cmmnd = !fstat(STDIN) ? NULL : fread(STDIN, 4096);
$frequency = 750*1000;
$iteration = 0;
$lifetime = 10*1000;
$inittime = round(microtime(true)*1000);
set_time_limit(round($lifetime/1000)+1);

function process_iteration() {
	global $lifetime;
	global $inittime;
	global $iteration;

	$runtime = round(microtime(true)*1000) - $inittime;
	if($runtime>$lifetime) return false;

	$iteration++;
	
	echo("tick ".$iteration." ".$runtime."\n");
	return true;
}
while(process_iteration()) usleep($frequency);

/*
$stdin_fstat = fstat(STDIN);
$stdin_value = !$stdin_fstat ? NULL : fread(STDIN, 4096);

if($stdin_value=='stop') {
	echo("Stopped\r\n");
	exit;
}
else if($stdin_value=='init') {
	echo("Started\r\n");
}
else if($stdin_value=='time') {
	echo("Time ".date('h:i:s')."\r\n");
}
else echo("Signal ".$sdin_value."\r\n");

function process_iteration() {

	global $inittime;
	global $lifetime;

	$processid = getmypid();
	$stdin_fstat = fstat(STDIN);
	$stdin_value = !$stdin_fstat ? NULL : fread(STDIN, 4096);

	$runtime = (round(microtime(true)*1000) - $inittime);//ms
	if($runtime<$lifetime) echo("ticktack ".date('i:s')."\r\n");
	else return false;

	return true;
}
while(process_iteration()) sleep(1);
echo("timeout!");
*/
?>