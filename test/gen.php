<?php
$keys = 0;
$iteration = 0;
function play_stop() {

	global $keys;
	global $iteration;
	
	$iteration++;
	$sdin_size = count(fstat(STDIN));
	$reply = "\r\nPS[".$iteration."][".$keys."] ".$sdin_size;
	$stop = true;

	if($sdin_size==0) {
		$reply.="\r\n\tcount stdin_stat_arr is 0";
		$stop = true;
	}
	else {
		$val_in = fread(STDIN,4096);
		$reply.="\r\n\tstdin_value is ".$val_in;
		$stop = false;
	}

	if(false) switch($val_in) {
		case "hello": echo $reply;
			return false;
		break;
		case "time": echo "TIME";
			return false;
		break;
		case "start": echo "Started";
			return false;
		break;
		case "stop": echo "Stopped";
			return false;
		break;
		default: echo("undefined ".$val_in);
			return true;
		break;
	}

	echo $reply;
	return $stop;
}

while(true) {
	if(play_stop()) break;
	else usleep(1000);
}
?>