<?php
setCorsHeaders('text/plain');
//setCorsHeaders('application/json');
//header('Content-Type: ');

//$actn = $_GET['action'];
//echo("action=".$actn."\r\n");

$uid = $Userdata['id'];
$userduel = $_DBR->getUserDuels($uid);
$dueldata = !empty($userduel) ? $userduel[0] : FALSE;
$filename = TEMPDIR.'duel'.DIRECTORY_SEPARATOR.$dueldata['file'];
$workfrom = $dueldata['player1']==$uid ? 'player1' : 'player2';
echo(print_r($dueldata,true));

if(!empty($_GET['spell'])) {
	$str = $Duel->message($workfrom,'spellcast',$_GET['spell']);

	$file = fopen($filename,'a');
	fwrite($file, PHP_EOL.$str);
	fclose($file);

	echo(PHP_EOL.$str);
}

$file = fopen($filename,'r');
$data = fread($file,filesize($filename));
fclose($file);
$lines = explode(PHP_EOL,$data);

 $p1ova = $p2ova = FALSE;
for($i=1;$i<count($lines);$i++) {

	$b = $Duel->messageRead($lines[$i]);
	if(!$b) continue;
	else echo(PHP_EOL.print_r(array_reverse($b),true));

	if($b['work']=='spellcast'){
		if($b['from']=='player1') $p1ova = TRUE;
		if($b['from']=='player2') $p2ova = TRUE;
	}
	if($p1ova && $p2ova) {
		$p1ova = $p2ova = FALSE;
		echo(PHP_EOL.'ROUND COMMIT'.PHP_EOL);
	}

}

echo("\r\n-------------------\r\n".$data);
?>