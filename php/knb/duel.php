<?php
class Duel {
public $id;
public $data;
private $logsdir = TEMPDIR.'duel'.DIRECTORY_SEPARATOR;
private $logfile;
public $logdata;
public $player1;
public $player2;
function __construct() {

	$this->id = 0;
	$this->_log = array();

	//$this->create();
}
public function restore($id) {
	global $_DBR;
	$_data = $_DBR->getDuelById($id);

	$this->data = $_data;
	$this->logfile = $_data['file'];
	$this->player1 = $_data['player1'];
	$this->player2 = $_data['player2'];

	$floc = $this->logsdir.$this->logfile;
	$this->logdata = file_get_contents($floc);

	$this->log($this->logdata);
}
public function create($pid1, $pid2) {
	global $_DBR;
	
	$this->player1 = $pid1;
	$this->player2 = $pid2;
	$this->logfile = 'duel_'.$pid1.'vs'.$pid2.'.log';

	$lead = 'duel '.$this->player1.' vs '.$this->player2.' at '.date('d/m/y H:i:s',time());
	$floc = $this->logsdir.$this->logfile;
	$file = fopen($floc, 'w'); fwrite($file, $lead); fclose($file);

	$this->logdata = file_get_contents($floc);

	$q = $_DBR->insertNewDuel($this->player1, $this->player2, $this->logfile);
	$this->log($q);

}
public function message($from,$work,$data) {
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