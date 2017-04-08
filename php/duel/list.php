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

	$id = $_GET['delete'];
	$res = $Duel->delete($id);
	if($res===TRUE) $User->updateData('duel',0);
	
	if($res===TRUE) redirectLocation('./?delete_success='.$id);
	else $rzlt = 'Delete #'.$id.' failure: '.$res;
}
else if(!empty($_GET['reset'])) {

	$id = $_GET['reset'];
	$res = $Duel->reset($id);
	if($res===TRUE) redirectLocation('./?reset_success='.$id);
	else $rzlt = 'Reset #'.$id.' failure: '.$res;

}

$Smarty->assign('duels',$_DBR->getDuelsList());
$Smarty->assign('rzlt',$rzlt);
$Smarty->display('duel/list.tpl');
?>