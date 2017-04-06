<?php

$spells = array(
	array('id' => 'rock','name' => 'Камень'),
	array('id' => 'scissors','name' => 'Ножницы'),
	array('id' => 'paper','name' => 'Бумага'),
	array('id' => 'lizard','name' => 'Ящерица'),
	array('id' => 'spock','name' => 'Спок')
);

$round = array(
	'player1_hp' => 95,
	'player2_hp' => 50,
	'player1_turn' => FALSE,
	'player2_turn' => TRUE,
	'num' => 3
);

	$duel_data = $duel_id = 'id: '.$Userdata['duel'];
/*
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
$Smarty->assign('duel_data',$duel_data);
$Smarty->display('duel/play.tpl');
?>