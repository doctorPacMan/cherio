<?php
$rzlt = NULL;

if(!empty($_GET['create'])) {

	$reject = FALSE;

	$uid1 = $Userdata['id'];
	$uid2 = $_GET['create'];
	$rzlt = 'Create '.$uid1.' vs '.$uid2.' ';

	$pp2 = $_DBR->getUserById($uid2);
	$uid2 = $pp2 ? $pp2['id'] : NULL;
	
	if(!$uid2) $reject = 'Unknown player ('.$_GET['create'].')';
	else if($uid1==$uid2) $reject = 'Suicide attempt';
	else {
		$d1 = $uid1 ? count($_DBR->getUserDuels($uid1)) : 0;
		$d2 = $uid2 ? count($_DBR->getUserDuels($uid2)) : 0;
		if($d1>0 || $d2>0) $reject = 'Too much duels: '.$uid1.'('.$d1.') '.$uid2.'('.$d2.')';
	}
	$rzlt.= $reject ? ('not allowed: '.$reject) : 'allowed';
	
	if(!$reject) $Duel->create($uid1,$uid2);
}
else if(!empty($_GET['delete'])) {

	$reject = FALSE;
	
	$id = $_GET['delete'];
	$rzlt = 'Delete #'.$id.' result:';
	$duel = $_DBR->getDuelById($id);
	if(!$duel) $rzlt.= 'duel not found';
	else {

		$file = DUELDIR.$duel['file'];
		//$uid1 = $duel['player1'];
		//$uid2 = $duel['player2'];

		$res_rm = @unlink($file);
		$res_db = $_DBR->deleteDuelById($id);

		$rzlt.= ' unlink>'.($res_rm?'success':'failure');
		$rzlt.= ' dtbase>'.($res_db?'success':'failure');
	}
}
else if(!empty($_GET['reset'])) {

	$id = $_GET['reset'];
	$rzlt = 'Reset #'.$id.' result: ';
	$duel = $_DBR->getDuelById($id);

	if(!$duel) $rzlt.= 'duel not found';
	else {
		$file = DUELDIR.$duel['file'];
		$uid1 = $duel['player1'];
		$uid2 = $duel['player2'];

		$res_rm = unlink($file);

		$db_rewrite = false;
		if($res_rm) {
			if($db_rewrite) $res_db = $_DBR->deleteDuelById($id);
			$Duel->create($uid1, $uid2, $db_rewrite);
		}

		$rzlt.= PHP_EOL.'delete: '.$res_rm.' & '.$res_db;
		$rzlt.= PHP_EOL.'create: '.PHP_EOL.$Duel->logdata;
	}
}

$Smarty->assign('duels',$_DBR->getDuelsList());
$Smarty->assign('rzlt',$rzlt);
$Smarty->display('duel/list.tpl');
?>