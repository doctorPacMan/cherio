<?php
require_once('duel.php');
$round = array(
	'player1_hp' => 95,
	'player2_hp' => 50,
	'player1_turn' => FALSE,
	'player2_turn' => TRUE,
	'num' => 3
);
$spells = array(
	array('id' => 'rock','name' => 'Камень'),
	array('id' => 'scissors','name' => 'Ножницы'),
	array('id' => 'paper','name' => 'Бумага'),
	array('id' => 'lizard','name' => 'Ящерица'),
	array('id' => 'spock','name' => 'Спок')
);


//$duel_id = $Userdata['duel']['id'];
if(empty($Userdata['duel']) || true) {
	
	$users = $_DBR->query("SELECT * FROM `users`")->fetchAll();
	$Smarty->assign('users',$users);
	//die("<pre>".print_r($Userdata,true)."</pre>");
}
else if(isset($_GET['reset'])) {

	$Duel = new Duel();
	$duel_id = $Userdata['duel'];
	$duel_db = $_DBR->getDuelById($duel_id);
	$Duel->restoreByData($duel_db);
	$Duel->reset();
	echo "reset";

	die("<pre>".print_r($Duel->logdata,true)."</pre>");

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

}
//else die(require_once('ajax.php'));

$Smarty->display('duel/main.tpl');
?>