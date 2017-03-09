<?php
$db_name = "u440306";
$qr_list = Array();
function dbquery($qn){

	global $db_name, $qr_list;

	$lnk = mysql_connect() or die("MySQL > Could not connect : " . mysql_error());

	if($qn!='newdb') mysql_select_db($db_name) or die("MySQL > Could not select database : " . mysql_error());

	$query = $qr_list[$qn];//echo($query);
	$req = mysql_query($query) or die("MySQL > Query failed : " . mysql_error());

	//mysql_free_result($req);
	mysql_close($lnk);

	echo "<pre>".print_r($query,true)."</pre><hr>";
}

$qr_list['newdb'] = "
CREATE DATABASE IF NOT EXISTS `".$db_name."`
 CHARACTER SET utf8
 COLLATE utf8_general_ci";

$qr_list['users'] = "
CREATE TABLE IF NOT EXISTS `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `pass` varchar(32) NOT NULL,
 `ssid` varchar(32) NOT NULL,
 `hash` char(32) NOT NULL,
 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `name` (`name`),
 PRIMARY KEY (`id`)
)";

dbquery('newdb');
dbquery('users');
die('rake done');
?>