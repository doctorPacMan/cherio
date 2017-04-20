<?php
class Duel {
public $id;
public $data;
private $time;
private $logsdir = DUELDIR;
private $logfile;
public $logdata;
public $player1;
public $player2;
public $complete = FALSE;
private $roundtime = 45;
private $_state = array(
	'complete' => FALSE,
	'winner' => 0,
	'player1' => 60,
	'player2' => 60,
	'mood1' => 5,
	'mood2' => 5
);
function __construct() {
	//$this->id = 0;
	$this->_log = array();
	//$this->create();
}
public function restoreByData($data) {
	$this->data = $data;
	$this->id = $data['id'];
	$this->logfile = $data['file'];
	$this->player1 = $data['player1'];
	$this->player2 = $data['player2'];
	$this->complete = !!$data['complete'];
	$this->time = strtotime($data['init_at']);
	$this->filesrc = realpath(DUELDIR.$this->logfile);
	$this->logdata = file_get_contents($this->filesrc);
	
	//$this->getCurrentState();
}
public function pn($uid) {
	if(empty($uid)) return NULL;
	else if($uid == $this->player1) return 'player1';
	else if($uid == $this->player2) return 'player2';
}
public function restore($id) {
	global $_DBR;
	$data = $_DBR->getDuelById($id);
	$this->restoreByData($data);
}
private function setGameComplete() {
	global $_DBR;
	$win = $this->_state['winner'];
	$this->complete = TRUE;
	$this->commitGameComplete();
	$this->logdata = file_get_contents($this->filesrc);
	$_DBR->updateDuelResult($this->id, $win, $this->logdata);
}
public function delete($id) {
	global $_DBR;

	$res = 'DESTROY ';
	$failure = FALSE;

	$this->restore($id);
	$res.= $this->id.' '.$this->player1.'vs'.$this->player2.'file: '.$this->filesrc;
	$res.= ' time: '.date('Y-m-d H:i:s',$this->time);

	$res_rm = @unlink($this->filesrc);
	if($res_rm===FALSE) $failure = 'file unlink failure';
	$res_db = $_DBR->deleteDuelById($id);
	if($res_db===FALSE) $failure = 'database rm failure';
	
	return $failure ?: TRUE;
}
public function reset($id) {
	global $_DBR;
	$failure = FALSE;
	$this->restore($id);

	$init_time = $_DBR->updateDuelReset($id);
	if($init_time) $this->time = $init_time;
	else $failure = 'init time update failure';
	
	// rewrite logfile
	$this->commitGameStart();
	$this->logdata = file_get_contents($this->filesrc);
	
	return $failure ?: TRUE;
}
public function create($pid1, $pid2) {
	global $_DBR;
	
	$this->player1 = $pid1;
	$this->player2 = $pid2;
	$this->logfile = 'duel_'.$pid1.'vs'.$pid2.'.log';

	file_put_contents(DUELDIR.$this->logfile,'empty');
	$this->filesrc = realpath(DUELDIR.$this->logfile);

	$bdata = $_DBR->insertNewDuel($this->player1, $this->player2, $this->logfile);

	$this->commitGameStart();
	$this->logdata = file_get_contents($this->filesrc);

	return $bdata;
}
private function commitGameStart() {
	$data = $this->player1.' vs '.$this->player2.' at '.date('y/m/d H:i:s',$this->time);
	$data = $this->message('system','START',$data);
	file_put_contents($this->filesrc, $data);
	$this->commitRound();
}
private function commitGameComplete($winner) {
	$msg = $this->message('system','COMPLETE',$this->_state['winner']);
	$this->messageWrite($msg);
	return $msg;
}
public function commitRound() {
	$json = $this->getGamestate();
	$msg = $this->message('system','ROUND',$json);
	$this->messageWrite($msg);
	return $msg;
}
public function commitSpell($sid,$uid) {
	$from = 'player0';
	if($uid==$this->player1) $from = 'player1';
	if($uid==$this->player2) $from = 'player2';
	$msg = $this->message($from,'spellcast',$sid);
	$this->messageWrite($msg);

	return;
}
public function setGamestate($p1sid, $p2sid) {
	$state = $this->getCurrentState();
	$this->_state = changeGameState($state, $p1sid, $p2sid);
	$this->commitRound();
	
	if($this->_state['complete']) $this->setGameComplete();

	return TRUE;
}
public function getGamestate() {
	$json = json_encode($this->_state);
	return $json;
}
public function getRounds() {
	$data = file_get_contents($this->filesrc);
	$lines = explode(PHP_EOL,$data);

	$rounds = array();
	$round_num = 0;
	$timeout = FALSE;
	$p1turn = $p2turn = FALSE;
	$now = time();
	for($i=1;$i<count($lines);$i++) {
		$m = $this->messageRead($lines[$i]);
		if(!$m) continue;

		if(empty($rounds[$round_num])) $rounds[$round_num] = array(
			'time' => NULL,
			'result'=>NULL,
			'before'=>NULL,
			'p1_turn' => FALSE,
			'p2_turn' => FALSE,
			'num' => $round_num
		);

		if($m['work']=='ROUND') {

			$timeini = floatval($m['time']);
			$timeout = $timeini + $this->roundtime;
			
			$rounds[$round_num]['time'] = $timeini;
			$rounds[$round_num]['timeini'] = date('H:i:s',$timeini);
			$rounds[$round_num]['timeout'] = date('H:i:s',$timeout);
			$rounds[$round_num]['before'] = $m['data'];
			if(!empty($rounds[$round_num-1]))
				$rounds[$round_num-1]['result'] = $m['data'];
		}
		else if($m['work']=='spellcast') {
			//$rounds[$round_num]['lines'][] = $lines[$i];
			if($m['from']=='player1') $rounds[$round_num]['p1_turn'] = $p1turn = $m['data'];
			if($m['from']=='player2') $rounds[$round_num]['p2_turn'] = $p2turn = $m['data'];
		}

		if($p1turn && $p2turn) {
			$p1turn = $p2turn = FALSE;
			$round_num++;
		}
	}
	return $rounds;

}
public function getCurrentRound() {
	$rounds = $this->getRounds();
	$k = count($rounds) - 1;
	return !empty($rounds[$k]) ? $rounds[$k] : NULL;
}
public function getCurrentState() {
	$round = $this->getCurrentRound();
	$json = $round['before'];
	return json_decode($json,true);
}
public function readGamestate() {
	$data = file_get_contents($this->filesrc);
	$lines = explode(PHP_EOL,$data);

	$round_num = 0;
	$rounds = array(array());
	$p1move = $p2move = FALSE;

	for($i=1;$i<count($lines);$i++) {

		$m = $this->messageRead($lines[$i]);
		if(!$m) continue;
		
		$rounds[$round_num][] = $m;

		if($m['work']!='spellcast') {}
		else if($m['from']=='player1') $p1move = $m['data'];
		else if($m['from']=='player2') $p2move = $m['data'];

		if($p1move && $p2move) {
			$p1move = $p2move = FALSE;$round_num++;
			//echo(PHP_EOL.'ROUND COMMIT'.PHP_EOL);
		}
	}
	return $rounds;
}
public function message($from,$work,$data='') {
	$mktm = microtime(true);
	$time = str_pad($mktm,15,'0');
	$a = array(
		'time' => $time,
		'from' => $from,
		'work' => $work,
		'data' => $data
	);
	return implode(' > ',$a);
}
public function messageWrite($msg) {

	file_put_contents($this->filesrc,PHP_EOL.$msg,FILE_APPEND);
	$this->logdata = file_get_contents($this->filesrc);

}
public function messageRead($line) {

	$data = explode(' > ',$line);
	
	if(count($data)<3) return FALSE;

	list($a['time'], $a['from'], $a['work'], $a['data']) = $data;
	$a['date'] = date('y/m/d H:i:s',$a['time']);
	return $a;
}
public function log($mssg=NULL) {
	if($mssg) {
		$tt = date('H:i:s',time()).'.'.round(1000*(microtime(true)-time()));
		$this->_log[$tt] = $mssg;
	}
	return $this->_log;
}
public function load() {
	return;
}
public function save() {
	return;
}
public function getState() {
	return $this->_state;
}
}
?>