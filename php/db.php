<?
//require(ROOT.'etc/class.db.php');
$Db = new DB('u440306');
$dta = $Db->getDataAll('database');
//$Db->killConnect();
print_r($dta);
?>
db.php