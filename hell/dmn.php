<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
ob_implicit_flush(true);
set_time_limit(15*60);

define('LIB_PHP_WEBSOCKET',__DIR__.'/lib/php-websocket/');
require_once(LIB_PHP_WEBSOCKET.'server/lib/SplClassLoader.php');
$classLoader = new SplClassLoader('WebSocket',LIB_PHP_WEBSOCKET.'server/lib');
$classLoader->register();

$websocket_host = 'dev.cherio.su';
$websocket_port = 889;
$websocket_host = 'cherio.io';
$websocket_port = 9000;
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
exit('>>>>> dmn.php done');
?>