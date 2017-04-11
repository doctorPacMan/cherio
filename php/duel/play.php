<?php
$spells = array(
	array('id' => 'rock','name' => 'Камень'),
	array('id' => 'scissors','name' => 'Ножницы'),
	array('id' => 'paper','name' => 'Бумага'),
	array('id' => 'lizard','name' => 'Ящерица'),
	array('id' => 'spock','name' => 'Спок')
);
$duel_id = $Userdata['duel']; 
$duel_db = $_DBR->getDuelById($duel_id);
$player1 = $_DBR->getUserById($duel_db['player1']);
$player2 = $_DBR->getUserById($duel_db['player2']);
$player1['spells'] = $spells;
$player2['spells'] = $spells;
$Duel->restoreByData($duel_db);

$duel_echo = 'Hello';

if(!empty($_GET['spell'])) {
	$pid = $_GET['p'];
	$sid = $_GET['spell'];
	
	//$duel_echo = $Duel->getCurrentRound();

	$p1_turn = 0;
	$p2_turn = 0;
	$duel_echo.= PHP_EOL.'p1turn: '.$p1_turn.', p2turn: '.$p2_turn;
	$duel_echo.= PHP_EOL.$Duel->message_spellCast($sid,$pid);
}
else $duel_echo = print_r($Duel->getCurrentRound(),true);

$Smarty->assign('player1', $player1);
$Smarty->assign('player2', $player2);
$Smarty->assign('duel_echo',$duel_echo);
$Smarty->assign('duel_data',$Duel->data);
$Smarty->assign('duel_text',$Duel->logdata);
$Smarty->display('duel/play.tpl');
?>