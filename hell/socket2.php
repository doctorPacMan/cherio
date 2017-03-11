<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
ob_implicit_flush(true);

define('LIB_PHP_WEBSOCKET',__DIR__.'/lib/php-websocket/');
require_once(LIB_PHP_WEBSOCKET.'server/lib/SplClassLoader.php');
$classLoader = new SplClassLoader('WebSocket',LIB_PHP_WEBSOCKET.'server/lib');
$classLoader->register();

$websocket_host = 'cherio.io';
//$websocket_host = 'dev.cherio.su';
$websocket_port = 9000;
$server = new \WebSocket\Server($websocket_host, $websocket_port, false);
// server settings:
$server->setMaxClients(10);
$server->setCheckOrigin(true);
$server->setAllowedOrigin($websocket_host);
$server->setMaxConnectionsPerIp(10);
$server->setMaxRequestsPerMinute(300);
//$server->registerApplication('status', \WebSocket\Application\StatusApplication::getInstance());
//$server->registerApplication('echo', \WebSocket\Application\EchoApplication::getInstance());
$server->run();
?>