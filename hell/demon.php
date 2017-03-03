<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
ob_implicit_flush(true);
set_time_limit(120);

$lib_phpws = __DIR__.'/lib/php-websocket/';
require_once($lib_phpws.'server/lib/SplClassLoader.php');
$classLoader = new SplClassLoader('WebSocket',$lib_phpws.'server/lib');
$classLoader->register();

$websocket_host = 'cherio.io';
$websocket_port = 889;
$server = new \WebSocket\Server($websocket_host, $websocket_port, false);
// server settings:
$server->setMaxClients(10);
$server->setCheckOrigin(true);
$server->setAllowedOrigin($websocket_host);
$server->setMaxConnectionsPerIp(10);
$server->setMaxRequestsPerMinute(2000);
$server->registerApplication('status', \WebSocket\Application\StatusApplication::getInstance());
$server->registerApplication('echo', \WebSocket\Application\EchoApplication::getInstance());
$server->run();
exit('>>>>> demon.php done');
?>