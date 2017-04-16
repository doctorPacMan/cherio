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
//$failure = NULL;
//$success = NULL;

if(1) {
	$duel_echo = print_r($Duel->getCurrentState(),true);
	$Duel->setGameState('rock','spock');
	$duel_echo.= print_r($Duel->getGameState(),true);
}
else if(!empty($_GET['spell'])) {
	$reject = FALSE;	
	$pid = $_GET['p'];
	$sid = $_GET['spell'];
	$pn = $Duel->pn($pid);
	$round = $Duel->getCurrentRound();
	$player1turn = $round['p1_turn'];
	$player2turn = $round['p2_turn'];

	if($pn===NULL) $reject = 'BADPLAYER';
	else if($pn=='player1' && $player1turn) $reject = 'DOUBLECAST';
	else if($pn=='player2' && $player2turn) $reject = 'DOUBLECAST';
	else {
		if($pn=='player1') $player1turn = $sid;
		else if($pn=='player2') $player2turn = $sid;

		$Duel->commitSpell($sid,$pid);

		if($player1turn && $player2turn)
			$Duel->setGameState($player1turn, $player2turn);
	}

	if($reject) redirectLocation('./?failure='.$reject);
	else redirectLocation('./?success');
}
else $duel_echo = array_reverse($Duel->getRounds());
//else $duel_echo = print_r($Duel->getCurrentRound(),true);

$Smarty->assign('player1', $player1);
$Smarty->assign('player2', $player2);
$Smarty->assign('duel_echo',$duel_echo);
$Smarty->assign('duel_data',$Duel->data);
$Smarty->assign('duel_text',$Duel->logdata);
$Smarty->display('duel/play.tpl');
?>