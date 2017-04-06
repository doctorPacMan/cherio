<?php
$rzlt = NULL;
$create_result = NULL;
$create_errors = array();

if(isset($_GET['id'])) {

	$uid1 = intval($Userdata['id']);
	$player1 = $_DBR->getUserById($uid1);
	$duels1 = $_DBR->getUserDuels($uid1);
	$count1 = count($duels1);

	$uid2 = intval($_GET['id']);
	$player2 = $_DBR->getUserById($uid2);
	$duels2 = $_DBR->getUserDuels($uid2);
	$count2 = count($duels2);

	if($uid1==$uid2) $create_errors[] = 'Suicide attempt';
	if($count1>0) $create_errors[] = 'Player1#'.$uid1.' got '.$count1.' duels';
	if($count2>0) $create_errors[] = 'Player2#'.$uid2.' got '.$count2.' duels';

	if(count($create_errors)) {
		$create_result = FALSE;
	}
	else {
		$duel = $Duel->create($uid1,$uid2);
		header('Location: ./?success='.$duel['id'], true, 303);
	}

	//echo("<br>".$uid1.' ('.$count1.') vs '.$uid2.' ('.$count2.')');
	//echo("<pre>".print_r($player1,true).print_r($player2,true)."</pre>");
	//die("<pre>".print_r($Userdata,true)."</pre>");
}

$Smarty->assign('users',$_DBR->getAllUsers());
$Smarty->assign('create_result',$create_result);
$Smarty->assign('create_errors',$create_errors);
$Smarty->display('duel/init.tpl');
?>