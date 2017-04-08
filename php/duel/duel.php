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
private $_state = array(
	'player1' => 100,
	'player2' => 100,
	'round' => 0
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
	$this->time = strtotime($data['init_at']);
	$this->filesrc = realpath(DUELDIR.$data['file']);

	$this->logdata = file_get_contents($this->filesrc);
	$this->readLogfile();
}
public function readLogfile() {
	
	$data = $this->logdata;
	$lines = explode(PHP_EOL,$data);

	$round_num = 0;
	$rounds = array();
	$p1move = $p2move = FALSE;

	for($i=1;$i<count($lines);$i++) {

		$m = $this->messageRead($lines[$i]);if(!$m) continue;
		
		if($m['work']=='ROUND') {
			$prev_res = isset($rounds[$round_num]) ? $rounds[$round_num]['result'] : NULL;
			$rn = ++$round_num;
			$ro = array(
				'time'=>date('y/m/d H:i:s',$m['time']),
				'spell1'=>FALSE,
				'spell2'=>FALSE,
				'commit'=>FALSE,
				'number'=>$rn,
				'before'=>$prev_res,
				'result'=>$m['data']
				//'_works'=>NULL
				);
			$rounds[$round_num] = $ro;
			continue;
		}
		else $rn = $round_num;

		if($m['work']!='spellcast') {
			$w = $m['work'].' > '.$m['data'];
			if(empty($rounds[$rn]['_works'])) $rounds[$rn]['_works'] = array($w);
			else $rounds[$rn]['_works'][] = $w;
		}
		else if($m['from']=='player1') $rounds[$rn]['spell1'] = $m['data'];
		else if($m['from']=='player2') $rounds[$rn]['spell2'] = $m['data'];
		if($rounds[$rn]['spell1'] && $rounds[$rn]['spell2']) $rounds[$rn]['commit'] = 1;

/*
		$rounds[$round_num][] = $m;

		if($m['work']!='spellcast') {}
		else if($m['from']=='player1') $p1move = $m['data'];
		else if($m['from']=='player2') $p2move = $m['data'];

		if($p1move && $p2move) {
			$p1move = $p2move = FALSE;$round_num++;
			//echo(PHP_EOL.'ROUND COMMIT'.PHP_EOL);
		}
*/

	}
	return $rounds;
}
public function restore($id) {
	global $_DBR;
	$data = $_DBR->getDuelById($id);
	$this->restoreByData($data);
}
private function commitGameStart() {
	
	$data = $this->player1.' vs '.$this->player2.' at '.date('y/m/d H:i:s',$this->time);
	$start = $this->message('system','START',$data);
	$data = $this->getGamestate();
	$round = $this->message('system','ROUND',$data);

	$newdata = $start.PHP_EOL.$round;
	file_put_contents($this->filesrc, $newdata);

	return $newdata;
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

	$res = 'RESET ';
	$failure = FALSE;

	$this->restore($id);
	$res.= $this->id.' '.$this->player1.'vs'.$this->player2.'file: '.$this->filesrc;
	$res.= ' time: '.date('Y-m-d H:i:s',$this->time);

	$init_time = $_DBR->updateDuelInitTime($id);
	$res.= PHP_EOL.'update init_at '.($init_time?date('Y-m-d H:i:s',$init_time):'failure');
	if($init_time) $this->time = $init_time;
	else $failure = 'init time update failure';
	
	// rewrite logfile
	$res.= PHP_EOL.'--- old ---'.PHP_EOL.$this->logdata;
	$this->commitGameStart();
	$this->logdata = file_get_contents($this->filesrc);
	$res.= PHP_EOL.'--- new ---'.PHP_EOL.$this->logdata;
	
	return $failure ?: TRUE;
}
private function getCurrentRound() {


	//file_get_contents($floc)
	return 'state';
}
public function create($pid1, $pid2, $dbr=true) {
	global $_DBR;
	
	$this->player1 = $pid1;
	$this->player2 = $pid2;
	$this->logfile = 'duel_'.$pid1.'vs'.$pid2.'.log';

	$floc = $this->logsdir.$this->logfile;
	$file = fopen($floc, 'w');

	$data = $this->player1.' vs '.$this->player2;
	$start = $this->message('system','START',$data);
	$data = $this->getGamestate();
	$state = $this->message('system','ROUND',$data);

	fwrite($file, $start.PHP_EOL.$state);
	fclose($file);

	$this->logdata = file_get_contents($floc);

	$bdata = $_DBR->insertNewDuel($this->player1, $this->player2, $this->logfile);
	return $bdata;
}
public function getGamestate() {

	$state = array(
		'player1' => 100,
		'player2' => 100
	);
	$json = json_encode($state);

	$this->player1.' vs '.$this->player2;
	return $json;
}
public function readGamestate() {
	$floc = $this->logsdir.$this->logfile;
	$data = file_get_contents($floc);
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
	return $this->player1.' vs '.$this->player2;
}
}
?>