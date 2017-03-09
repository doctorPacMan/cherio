<?php
if(!isset($_GET['echo'])) require(dirname(__FILE__).'/index.html');
else require(dirname(__FILE__).'/ws.php');
?>