<?php
require_once('duel.php');
$Duel = new Duel();

$round = array(
	'player1_hp' => 95,
	'player2_hp' => 50,
	'player1_turn' => FALSE,
	'player2_turn' => TRUE,
	'num' => 3
);


//$duel_id = $Userdata['duel']['id'];
//die("<pre>".print_r($URL_PARAMS,true)."</pre>");

if($URL_PARAMS[1]=='list') die(require_once('list.php'));
else if($URL_PARAMS[1]=='init') die(require_once('init.php'));
else if($URL_PARAMS[1]=='play') die(require_once('play.php'));
else if(isset($_GET['create'])) {
	
	$create_errors = array();
	
	$uid1 = $Userdata['id'];
	$player1 = $_DBR->getUserById($uid1);
	//$player1 = $Userdata;
	$duels1 = $_DBR->getUserDuels($uid1);
	$count1 = count($duels1);

	$uid2 = $_GET['uid'];
	$player2 = $_DBR->getUserById($uid2);
	$duels2 = $_DBR->getUserDuels($uid2);
	$count2 = count($duels2);

	if($uid1==$uid2) $create_errors[] = 'Suicide attempt';
	if($count1>0) $create_errors[] = 'Player1#'.$uid1.' got '.$count1.' duels';
	if($count2>0) $create_errors[] = 'Player2#'.$uid2.' got '.$count2.' duels';

	if(count($create_errors)) {
		echo('FAILURE: <pre>'.print_r($create_errors,true).'</pre>');	
	}
	else {
		$Duel->create($uid1,$uid2);
		echo('SUCCESS: '.$Duel->logdata);	
	}

	echo("<br>".$uid1.' ('.$count1.') vs '.$uid2.' ('.$count2.')');
	echo("<pre>".print_r($player1,true).print_r($player2,true)."</pre>");
	//die("<pre>".print_r($Userdata,true)."</pre>");
}
else if(empty($Userdata['duel']) || true) {
	
	$users = $_DBR->query("SELECT * FROM `users`")->fetchAll();
	$Smarty->assign('users',$users);
	//die("<pre>".print_r($Userdata,true)."</pre>");
	$Smarty->display('duel/create.tpl');
}
else if(isset($_GET['reset'])) {

	$Duel = new Duel();
	$duel_id = $Userdata['duel'];
	$duel_db = $_DBR->getDuelById($duel_id);
	$Duel->restoreByData($duel_db);
	$Duel->reset();
	echo "reset";
	echo("<pre>".print_r($Duel->logdata,true)."</pre>");

} else {

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

	$Smarty->display('duel/main.tpl');
}
//else die(require_once('ajax.php'));
?>