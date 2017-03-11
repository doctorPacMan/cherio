<?php
$socket = NULL;
$addr = 'dev.cherio.su';$port = 9000;
//$addr = 'cherio.io';$port = 9000;

echo("socket_create  > ");
	$rs = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error())."\r\n");
	else echo("ok\r\n"); 
	$socket = $rs;

echo("socket_option  > ");
	$rs = socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1); //разрешаем использовать один порт для нескольких соединений
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error($socket))."\r\n");
	else echo("ok\r\n");

	//socket_set_nonblock($socket);
echo("socket_bind    > ".$addr.":".$port." > ");
	$rs = socket_bind($socket, $addr, $port); //привязываем его к указанным ip и порту
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error($socket))."\r\n");
	else echo("ok\r\n");
	
echo("socket_listen  > ");
	$rs = socket_listen($socket, 3);//слушаем сокет
	if($rs==FALSE) exit("error: ".socket_strerror(socket_last_error($socket))."\r\n");
	else echo("ok\r\n");

$frequency = 1000;
$lifetime = 10000;
$sockets = array($socket);

$starttime = round(microtime(true)*1000);
while(true) {

	// проверим все сокеты на изменение статуса
	echo("socket_select  > ");
	$ns = socket_select($sockets, $write, $exceptions, NULL);
	if($ns==FALSE) exit("error: ".socket_strerror(socket_last_error())."\r\n");
	else {
		$mssg = "ns:".$ns;
		$mssg.= ", read: ".sizeof($sockets);
		$mssg.= ", write: ".sizeof($write);
		$mssg.= ", exceptions: ".sizeof($exceptions);
		echo($mssg."\r\n");
	}

	// запустить следующий цикл, либо прервать по таймауту
	$runtime = (round(microtime(true)*1000) - $starttime);//ms
	echo("runtime: ".$runtime."ms\r\n");
	if($runtime>=$lifetime) break;
	else usleep($frequency*1000);
}

?>
