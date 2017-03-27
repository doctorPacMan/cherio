<?php
require_once('duel.php');

$Duel = new Duel();
//$Duel->create(7,8);die();

if(!empty($_GET)) die(require_once('ajax.php'));

$duel = NULL;
$player1 = NULL;
$player2 = NULL;
$logdata = NULL;

if(!empty($Userdata)) {

	$userduel = $_DBR->getUserDuels($Userdata['id']);
	$dueldata = !empty($userduel) ? $userduel[0] : FALSE;

	if($dueldata) {
		$Duel->restore($dueldata['id']);
		$duel = $Duel->data;
		$player1 = $_DBR->getUserById($Duel->player1);
		$player2 = $_DBR->getUserById($Duel->player2);
		$logdata = $Duel->logdata;
	}

}

$Smarty->assign('duel',$duel);
$Smarty->assign('player1',$player1);
$Smarty->assign('player2',$player2);
$Smarty->assign('logdata',$logdata);

/*

$Duel = new Duel(7, 8);
die("<pre>".print_r($Duel->log(),true)."</pre>");

$pid1 = $dueldata['player1'];
$pid2 = $dueldata['player2'];
$player1 = $_DBR->getUserById($pid1);
$player2 = $_DBR->getUserById($pid2);


$duel_id = $Userdata['duel'];
$Duel = new Duel($pid1, $pid2);

//$echo = array($player1,$player2);

$duel = $dueldata;
$duel['player1'] = $player1;
$duel['player2'] = $player2;

$Smarty->assign('echo',$echo);
*/

$Smarty->assign('spells',array('rock','scissors','paper','lizard','spock'));
$Smarty->display('knb/index.tpl');
?>