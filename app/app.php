<?php
namespace APP;

//const TMPDIR = (__DIR__).DIRECTORY_SEPARATOR.'..';
const DIRTMP = __DIR__.'/../tmp/app';
const COMTXT = DIRTMP.'/command.txt';


class SSEClient {
public $cid = 'cid';
private $pid;
private $_state = 'IDLE';
//$lifetime = 20000;
//$frequency = 2500;
//$iteration = 0;
private $com_size;
private $filename;
private $filelink;
private $starttime;
private $startdate;
private $frequency = 1500;
private $lifetime = 20000;
private $runtime = 0;
private $running = FALSE;
function __construct($cid=NULL) {
	$this->startdate = date('H:i:s',time());
	$this->starttime = round(microtime(true)*1000);
	$this->com_size = filesize(COMTXT);
	$this->cid = $cid;
	$this->pid = uniqid('pid');
	$this->state('INIT');
}
public function run() {
	if($this->running) return FALSE;
	else $this->running = TRUE;
	
	$this->runtime = 0;
	$this->file(true);
	$this->state('START');
	while($this->tick()) usleep($this->frequency*1000);
}
public function state($st=NULL, $message=NULL) {

	if(!is_string($st)) return $this->_state;
	else if($st == $this->_state) return $st;
	else $this->_state = $st;
	
	$this->file($st.($message ? ' '.$message : ''));

	$echo = $this->getMessage($st,'status');
	echo $echo.PHP_EOL;
	ob_flush();flush();

}
public function file($action=false) {

	$filename = realpath(DIRTMP).DIRECTORY_SEPARATOR.$this->pid;
	
	if($action===true) {
		$this->filename = $filename;
		$this->filelink = fopen($this->filename,'a');
	}
	else if(!$this->filelink) {}
	else if($action===false) {
		fclose($this->filelink);
		rename($this->filename,$this->filename.'.log');
	}
	else if(is_string($action)) {
		$log = '['.date('H:i:s',time()).']';
		$log.= ' '.$action.PHP_EOL;
		fwrite($this->filelink,$log);
	}
}
public function stop($reason='police') {
	if(!$this->running) return FALSE;
	else $this->running = FALSE;
	$this->state('STOP',$reason);
	$this->file(false);
}
public function tick() {
	$stop = TRUE;
	$updates = $this->getUpdates();
	$this->runtime = round(microtime(true)*1000) - $this->starttime;

	if(!$this->running) {
		$this->stop('stopped');	
		$stop = TRUE;
	}
	else if($this->runtime>=$this->lifetime) {
		$this->stop('timeout');	
		$stop = TRUE;
	}
	else if($updates) {
		$this->echoMessage('NEWS');
		$stop = FALSE;
	}
	else {
		//$this->echoMessage('TICK at '.str_pad($this->runtime,4,'0',STR_PAD_LEFT));
		$stop = FALSE;
	}
	return !$stop;
}
private function getUpdates() {
	
	clearstatcache(false, COMTXT);
	$fs = filesize(COMTXT);
	
	if($this->com_size == $fs) return false;

	$this->com_size = $fs;

	return true;
}
private function getMessage($data=NULL,$event='message') {

	$echo = ":Just a message ".$this->runtime.PHP_EOL;
	$echo.= "id: "."id".PHP_EOL;
	$echo.= "pid: ".$this->pid.PHP_EOL;
	$echo.= "retry: 3000".PHP_EOL;
	$echo.= "event: ".$event.PHP_EOL;
	
	if(is_array($data)) {
		foreach($data as $key=>$v) {
			$val = trim(preg_replace('/\s\s+/',' ',$v));
			$echo.="data: ".$key."=".$val.PHP_EOL;
		}
	}
	else if(is_string($data)) {
		$data = trim(preg_replace('/\s\s+/',' ',$data));
		$echo.="data: ".$data.PHP_EOL;
	}
	
	return $echo;
}
private function echoMessage($text='void') {

	$echo = $this->getMessage($text);
	echo $echo.PHP_EOL;
	ob_flush();flush();
}
}
?>