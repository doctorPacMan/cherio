<?php
setCorsHeaders('text/plain');
//setCorsHeaders('application/json');

function roundCommit($rdata) {
	//echo(PHP_EOL.print_r($rdata,true));
}
//$actn = $_GET['action'];
//echo("action=".$actn."\r\n");

$uid = $Userdata['id'];
$userduel = $_DBR->getUserDuels($uid);
$dueldata = !empty($userduel) ? $userduel[0] : FALSE;
$filename = TEMPDIR.'duel'.DIRECTORY_SEPARATOR.$dueldata['file'];
$workfrom = $dueldata['player1']==$uid ? 'player1' : 'player2';
echo(print_r($dueldata,true));

if(!empty($_GET['spell'])) {
	$message_spell = $Duel->message($workfrom,'spellcast',$_GET['spell']);
	file_put_contents($filename, PHP_EOL.$message_spell, FILE_APPEND);
	echo(PHP_EOL.$message_spell);
}

$file = fopen($filename,'r');
$data = fread($file,filesize($filename));
fclose($file);
$lines = explode(PHP_EOL,$data);

$p1spell = $p2spell = FALSE;
$rounds = array(array());
$round_num = 0;
for($i=1;$i<count($lines);$i++) {

	$b = $Duel->messageRead($lines[$i]);
	if(!$b) continue;
	//else echo(PHP_EOL.print_r(array_reverse($b),true));

	$rounds[$round_num][] = $b;
	
	if($b['work']!='spellcast') {}
	else if($b['from']=='player1') $p1spell = $b['data'];
	else if($b['from']=='player2') $p2spell = $b['data'];

	if($p1spell && $p2spell) {
		$message_commit = $Duel->message('system','ROUND','commit');
		file_put_contents($filename, PHP_EOL.$message_commit, FILE_APPEND);
		roundCommit($p1spell, $p2spell);
		$p1spell = $p2spell = FALSE;$round_num++;
		//echo(PHP_EOL.'ROUND COMMIT'.PHP_EOL);
	}

}

echo("\r\n-------------------\r\n".$data);
//echo(PHP_EOL.print_r($rounds,true));
?>