<?php
$descriptorspec = array(
	0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
	1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать 
	2 => array("file", "error-output.txt", "a") // stderr - файл для записи
);

$phpexe_src = "D:/xampp/php/php.exe";
//$phpexe_src = "C:/Program Apps/XAMPP/php/php.exe";
$script_src = __DIR__."/process.php";
//$script_src = __DIR__."/gen.php";
$wrkdir_src = __DIR__."/tmp";

$phpexe_src = str_replace('/',DIRECTORY_SEPARATOR,$phpexe_src);
$phpexe = realpath($phpexe_src) ?: FALSE;
$script_src = str_replace('/',DIRECTORY_SEPARATOR,$script_src);
$script = realpath($script_src) ?: FALSE;
$wrkdir_src = str_replace('/',DIRECTORY_SEPARATOR,$wrkdir_src);
$wrkdir = realpath($wrkdir_src) ?: FALSE;
$files_log = "wrkdir: ".$wrkdir_src." ".(!$wrkdir?'not found':'exists at '.$wrkdir)."\r\n";
$files_log.= "phpexe: ".$phpexe_src." ".(!$phpexe?'not found':'exists at '.$phpexe)."\r\n";
$files_log.= "script: ".$script_src." ".(!$script?'not found':'exists at '.$script)."\r\n";
if(!$wrkdir) {
	mkdir($wrkdir_src, 0777);
	$wrkdir = realpath($wrkdir_src);
	$files_log.= "wrkdir: created at ".$wrkdir."\r\n";
}

$file_read = $wrkdir.DIRECTORY_SEPARATOR.'pipe_r.txt';
$file_write = $wrkdir.DIRECTORY_SEPARATOR.'pipe_w.txt';
$file_error = $wrkdir.DIRECTORY_SEPARATOR.'errors.txt';
fclose(fopen($file_read,'w'));
fclose(fopen($file_write,'w'));
fclose(fopen($file_error,'a'));
echo($files_log."------: okay\r\n");

//die(str_replace(" ", "\\ ", $phpexe)."\n".escapeshellcmd($phpexe)."\n".escapeshellarg($phpexe));

$cwd = $wrkdir;
$env = array('some_option' => 'aeiou');
$dsc = array(// descriptors
	//0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
	0 => array("file", $file_read, "r"),  // stdin - канал, из которого дочерний процесс будет читать	
	1 => array("file", $file_write, "w"),  // stdout - канал, в который дочерний процесс будет записывать 
	2 => array("file", $file_error, "a") // stderr - файл для записи
);
$dsc = array(// descriptors
	0 => array("pipe", "r"),
	1 => array("pipe", "w"),
	//2 => array("pipe", "a")
	2 => array("file", $file_error, "a")
);

$cmd = escapeshellarg($phpexe).' '.$script;

file_put_contents($file_error,date('h:i:s')." proc_open attempt > ".$cmd."\r\n");

echo("proc_open attempt > ".$cmd."\r\n");
$process = proc_open($cmd, $dsc, $pipes, $cwd, $env);
echo("proc_open ".(is_resource($process)?'success ':'failure ').$process."\r\n");

//proc_close($process);
//echo("pipes ".print_r($pipes,true)."\r\n");
if (is_resource($process)) {

	sleep(1);
	
	echo("\r\ninit >");
	fwrite($pipes[0], "init");// send
	echo(fgets($pipes[1], 4096));//get answer

	sleep(1);
	
	//print_r(proc_get_status($process));
	echo("\r\ntime >");
	fwrite($pipes[0], "time");// send
	echo(fgets($pipes[1], 4096));//get answer

	//fwrite($pipes[0], "stop");// send
	//echo("\r\nstop >".fgets($pipes[1],4096));//get answer

	//fwrite($pipes[0], NULL);// send
	//echo("\r\nnull >".fgets($pipes[1],4096));//get answer


	sleep(2);
	echo("\r\nproc_get_status: ".print_r(proc_get_status($process),true));
	fclose($pipes[0]); fclose($pipes[1]);
	echo "\r\nproc_close return value ".proc_close($process)."\r\n";
}
?>