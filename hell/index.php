<?php
if(!isset($_GET['echo'])) echo('Welcome to hell');
else require_once(dirname(__FILE__).'/ws.php');
?>
