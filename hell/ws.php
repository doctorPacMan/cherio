<?php
function trace($str) {echo $str;}

header('Content-Type: text/plain;');

trace("WebSockets ".(extension_loaded('sockets') ? "OK" : "UNAVAILABLE"));
?>