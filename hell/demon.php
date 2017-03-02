<?php
/*
$data = "GET / HTTP/1.1";
$data.="\r\nHost: cherio.io:889";
$data.="\r\nConnection: Upgrade";
$data.="\r\nPragma: no-cache";
$data.="\r\nCache-Control: no-cache";
$data.="\r\nUpgrade: websocket";
$data.="\r\nOrigin: http://cherio.io";
$data.="\r\nSec-WebSocket-Version: 13";
$data.="\r\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
$data.="\r\nAccept-Encoding: gzip, deflate, sdch";
$data.="\r\nAccept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4";
$data.="\r\nCookie: _ga=GA1.2.909379193.1471585713";
$data.="\r\nSec-WebSocket-Key: VtatAXIadIv94WX/Tt3nuA==";
$data.="\r\nSec-WebSocket-Extensions: permessage-deflate; client_max_window_bits\r\n\r\n";
*/
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
function ws_getheaders($data) {

	// check for valid http-header:
    //if(!preg_match('/\AGET (\S+) HTTP\/1.1\z/', $lines[0], $matches))

	$lines = preg_split("/\r\n/",rtrim($data));
	$lines[0] = "info: ".$lines[0];
	
	$headers = array();
	foreach($lines as $line)
		if(preg_match('/\A(\S+): (.*)\z/', rtrim($line), $matches)) $headers[$matches[1]] = $matches[2];

	return $headers;
};
function ws_getbuffer($ws_resource) {
	
	$socket = $ws_resource;
	$client = socket_accept($ws_resource);
	echo "\r\n\tsocket_accept ".($client===FALSE ? "failure" : "success")." ".$client;
	//return $socket." client:".$client;
	
	$result = "\r\n\t";
	$buffer = NULL;
	$bytes = @socket_recv($client, $buffer, 4096, MSG_WAITALL); // получаем информацию от сокета
	
	if ($bytes===FALSE) $result.= "socket_recive failure: ".socket_strerror(socket_last_error());
	else if($bytes===0) $result.= "socket_recive failure: 0 bytes recived";
	else {
    	$result.= "socket_recv success: ".$bytes." bytes recived";
    	//echo("BUFFER >\r\n".$buffer."\r\n< BUFFER\r\n");
	};

	if(!$bytes) socket_close($socket);
	else {
    	$headers = ws_getheaders($buffer);
    	$response = ws_handshake($headers['Sec-WebSocket-Key']);
		$rs = socket_write($client, $response, strlen($response));
		$result.= "\r\n\tsocket_write handshake ".$rs;
		echo("\r\n>>>\r\n".$response."<<<<");
	};
	echo $result;
	return $buffer;
};
function ws_handshake($key) {
	$hash = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11'; 
	$hash = base64_encode(sha1($hash,true));
	$upgrade = "HTTP/1.1 101 Switching Protocols\r\n";
	$upgrade.= "Upgrade: websocket\r\n";
	$upgrade.= "Connection: Upgrade\r\n";
	$upgrade.= "Sec-WebSocket-Accept: ".$hash."\r\n";
	$upgrade.= "Sec-WebSocket-Version: 13\r\n";
	$upgrade.= "\r\n";
	return $upgrade;
};
function getWebsocket() {

	$socket = NULL;
	//$addr = '0.0.0.0';$port = 0;
	//$addr = '127.0.0.1';$port = 889;
	$addr = 'cherio.io';$port = 889;

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