<?php
function daemonTick($pid, $cnt) {
	echo("daemonTick: ".$cnt);

    global $Socket;
    $sockets = array($Socket);
	$num_sockets = socket_select($sockets, $write=NULL,$exceptions=NULL,NULL); // проверим все сокеты на изменение статуса по сравнению с прошлым разом
	foreach($sockets as $sk) {
		ws_getbuffer($sk);
	}
	
	echo("\r\n");
};
function daemonDone($pid, $cnt) {
	echo("daemonDone: ".$cnt."\r\n");
};
function trace($str) {echo($str);return $str;};

function ws_getbuffer($ws_resource) {
	
	$client = socket_accept($ws_resource);
	echo "\r\n\tsocket_accept ".($client===FALSE ? "failure" : "success")." ".$client;
	//return $ws_resource." client:".$client;
	
	$result = "\r\n\t";
	$buffer = 'BUFFER';
	$bytes = socket_recv($ws_resource, $buffer, 4096, MSG_WAITALL); // получаем информацию от сокета
	
	if ($bytes===FALSE) $result.= "socket_recv failure: ".socket_strerror(socket_last_error());
	else if($bytes===0) $result.= "socket_recv failure: 0 bytes recived";
	else {
    	$result.= "socket_recv success: ".$bytes." bytes recived";
	}
	echo $result;
	//socket_close($socket);
};
function ws_handshake($ws_resource) {
	$upgrade = "HTTP/1.1 101 Switching Protocols\r\n";
	$upgrade.= "Upgrade: websocket\r\n";
	$upgrade.= "Connection: Upgrade\r\n";
	$upgrade.= "Sec-WebSocket-Accept: s3pPLMBiTxaQ9kYGzzhZRbK+xOo=\r\n";
	$upgrade.= "\r\n";

	return $ws_resource;
};
function getWebsocket() {

	$socket = NULL;
	$addr = '0.0.0.0';$port = 0;
	$addr = '127.0.0.1';$port = 889;
	//$addr = 'art.cn.bender.inetra.ru';$port = 10001;

	trace("socket_create > ");
	$rs = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error())."\r\n");
	else trace("ok\r\n"); 
	$socket = $rs;

	//socket_set_nonblock($socket);

	trace("socket_bind ".$addr.":".$port." > ");
	$rs = socket_bind($socket, $addr, $port); //привязываем его к указанным ip и порту
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error($socket))."\r\n");
	else trace("ok\r\n");

	
	trace("socket_set_option > ");
	$rs = socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1); //разрешаем использовать один порт для нескольких соединений
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error($socket))."\r\n");
	else trace("ok\r\n");

	trace("socket_listen > ");
	$rs = socket_listen($socket, 3);//слушаем сокет
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error($socket))."\r\n");
	else trace("ok\r\n");

	return $socket;
};

$Socket = getWebsocket();

set_time_limit(15);
ob_implicit_flush(true);
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
	//echo("pid:".$processid." iteration:".$iteration." runtime:".$runtime."ms\r\n");

	if($runtime>=$lifetime) {
		daemonDone($processid, $iteration);
		break;
	}
	else {
   		daemonTick($processid, $iteration);
		usleep($frequency*1000);
	}
}
$done_time = round(microtime(true)*1000);
echo("------------------ process ".$processid." done at ".$done_time."\r\n");

if(isset($Socket)) {
	echo "Closing connection... ";
	@socket_shutdown($Socket);
	socket_close($Socket);
	echo "done\r\n";
}

//echo(proc_get_status($processid));
?>