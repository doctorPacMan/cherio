<?
require('service.php');
$duel = new cDuel(1);


//echo('DUEL<br><pre>'.print_r($duel->data,true).'</pre>');

$Smarty->assign('player1', $duel->data['player1']);
$Smarty->assign('player2', $duel->data['player2']);
$Smarty->assign('player1_pets', $duel->data['pets1']);
$Smarty->assign('player2_pets', $duel->data['pets2']);
$Smarty->display('duel/main.tpl');

/*
if(!empty($_REQUEST)) {
	header("Content-type:application/json;charset=utf-8");
	$og = setCorsHeaders();
	//$rw = $_SERVER['HTTP_X_REQUESTED_WITH'];
	echo('RRR og:'.$og);
}
else 
*/
?>