<?php
require_once('duel.php');
require_once('duel.ext.php');
$Duel = new Duel();
$did = $Userdata['duel']; 

$action = !empty($URL_PARAMS[1]) ? $URL_PARAMS[1] : 'main';

if($action=='list') die(require_once('list.php'));
else if($action=='init') die(require_once('init.php'));
else if($action=='play' && $did) die(require_once('play.php'));
else if($action=='result') {
	$duel_id = !empty($URL_PARAMS[2]) ? $URL_PARAMS[2] : 0;
	$duel_db = $_DBR->getDuelById($duel_id);
	$Smarty->assign('duel',$duel_db);
	$Smarty->display('duel/result.tpl');
}
else {
/*
	$duel_id = $Userdata['duel'];
	$duel_db = $_DBR->getDuelById($duel_id);
	$player1 = $_DBR->getUserById($duel_db['player1']);
	$player2 = $_DBR->getUserById($duel_db['player2']);
	$player1['spells'] = $spells;
	$player2['spells'] = $spells;

	$Smarty->assign('player1', $player1);
	$Smarty->assign('player2', $player2);
	$Smarty->assign('round', $round);
	$Smarty->assign('duel',$duel_db);
*/
	$Smarty->display('duel/main.tpl');
}
//else die(require_once('ajax.php'));
?>