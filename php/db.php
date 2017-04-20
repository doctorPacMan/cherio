<?php
class DataBaseRequests {
private $PDO;
function __construct($pdo) {
	$this->PDO = $pdo;
}
public function query($query) {
	return $this->PDO->query($query);
}
private function quote($qtext) {
	return $this->PDO->quote($qtext);
}
private function queryFetch($query) {
	return $this->PDO->query($query)->fetch();
}
private function queryFetchAll($query) {
	return $this->PDO->query($query)->fetchAll();
}
public function getDuelsList() {
	$query = "SELECT * FROM `duels`";
	$duels = $this->queryFetchAll($query);
	return $duels ?: array();
}
public function getUserDuels($uid) {
	$query = "SELECT * FROM `duels` WHERE duels.player1=".$uid." OR duels.player2=".$uid;
	$duels = $this->queryFetchAll($query);
	return $duels ?: array();
}
public function getUserById($uid) {
	$query = "SELECT * FROM `users` WHERE users.id=".$uid;
	$user = $this->queryFetch($query);
	return $user ?: NULL;
}
public function getAllUsers() {
	$query = "SELECT * FROM `users`";
	$users = $this->queryFetchAll($query);
	return $users ?: NULL;
}
public function getUserByToken($token) {
	$query_token = $this->quote($token);
	$query = "SELECT * FROM `users` WHERE users.hash=".$query_token;
	$user = $this->queryFetch($query);
	return $user ?: NULL;
}
public function getUserByLogin($login) {
	$query_login = $this->quote($login);
	$query = "SELECT * FROM `users` WHERE users.login=".$query_login;
	$user = $this->queryFetch($query);
	return $user ?: NULL;
}
public function setUserTimeHash($time, $hash) {
	global $_PDO;

	$user['ua'] = $_SERVER['HTTP_USER_AGENT'];
	$user['ip'] = $_SERVER['REMOTE_ADDR'];

	$hash = md5($user['id'] . $user['ip'] . $user['ua']);
	$hash = base64_encode(pack('H*',sha1($hash.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	$user['hash'] = $hash;

	$user['time'] = $time = date('Y-m-d H:i:s',time());
	$id = $user['id'];

	//$query = "UPDATE `users` SET time=NOW()";
	$query = "UPDATE `users` SET time=".$this->quote($time);
	$query.= ", `hash`=".$this->quote($hash);
	$query.= " WHERE users.id=".$id;
	$this->query($query);

	//$query = "SELECT * FROM `users` WHERE users.id=".$id;
	//$_user = $this->query($query)->fetch();
	//echo('<pre>'.print_r($_user,true).'</pre>');

	setcookie('token',$hash,strtotime('+1 days'),'/',$_SERVER['HTTP_HOST']);

	$_SESSION['user'] = $user;
	return $this->initialize($user);
}
public function insertNewUser($login, $pass) {
	$query_login = $this->quote($login);
	$query_pass = $this->quote($pass);
	$query = "INSERT INTO `users` ";
	$query.="(`login`, `pass`) VALUES ";
	$query.="(".$query_login.", ".$query_pass.")";
	$this->query($query);

	$bdata = $this->getUserByLogin($login);
	return $bdata;
}
public function updateDuelReset($did, $time=NULL) {

	$time = $time ? strtotime($time) : time();
	$query_time = $this->quote(date('Y-m-d H:i:s',$time));

	$query = "UPDATE `duels` SET";
	$query.= " `init_at` = ".$query_time;
	$query.= ", `winner` = NULL";
	$query.= ", `complete` = '0'";
	$query.= ", `combatlog` = NULL";
	$query.= " WHERE duels.id = ".$this->quote($did);
	$pdost = $this->query($query);
	$error = $pdost->errorCode();
	if($error!='00000') return FALSE;

	return $time;
}
public function updateDuelResult($did, $win=NULL, $data) {

	$query_data = $this->quote($data);
	$query_time = $this->quote(date('Y-m-d H:i:s',time()));
	$query_win = $win===NULL ? 'NULL' : $this->quote($win);

	$query = "UPDATE `duels` SET";
	$query.= " `done_at` = ".$query_time;
	$query.= ", `winner` = ".$query_win;
	$query.= ", `complete` = '1'";
	$query.= ", `combatlog` = ".$query_data;
	$query.= " WHERE duels.id = ".$this->quote($did);
	$pdost = $this->query($query);
	$error = $pdost->errorCode();
	if($error!='00000') return FALSE;
	return TRUE;
}
public function deleteDuelById($did) {
	
	//$duel = $this->getDuelById($did);
	$query_did = $this->quote($did);

	$query = "DELETE FROM `duels` WHERE duels.id=".$query_did;
	$pdost = $this->query($query);
	$error = $pdost->errorCode();
	if($error!='00000') return FALSE;

	$query = "UPDATE `users` SET duel=0 WHERE users.duel=".$query_did;
	$pdost = $this->query($query);
	$error = $pdost->errorCode();
	if($error!='00000') return FALSE;

	return TRUE;
}
public function getDuelById($did) {
	$query = "SELECT * FROM `duels` WHERE duels.id=".$did;
	$bdata = $this->queryFetch($query);
	return $bdata ?: NULL;
}
public function insertNewDuel($player1, $player2, $file) {
	$query_pid1 = $this->quote($player1);
	$query_pid2 = $this->quote($player2);
	$query_file = $this->quote($file);
	$query = "INSERT INTO `duels` ";
	$query.="(`player1`, `player2`, `file`) VALUES ";
	$query.="(".$query_pid1.", ".$query_pid2.", ".$query_file.")";
	$this->query($query);
	
	$query = "SELECT * FROM `duels`";
	$query.= " WHERE duels.player1=".$query_pid1;
	$query.= " AND duels.player2=".$query_pid2;
	$_duel = $this->queryFetch($query);
	
	$query = "UPDATE `users` SET duel=".$this->quote($_duel['id']);
	$query.= " WHERE users.id=".$query_pid1;
	$query.= " OR users.id=".$query_pid2;
	$this->query($query);

	return $_duel ?: NULL;
}
}
?>