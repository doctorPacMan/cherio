<?php
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
   1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать 
   2 => array("file", "error-output.txt", "a") // stderr - файл для записи
);

$descriptorspec = array(
   0 => array("file", "piper.txt", "r"),  // stdin - канал, из которого дочерний процесс будет читать
   1 => array("file", "pipew.txt", "w"),  // stdout - канал, в который дочерний процесс будет записывать 
   2 => array("file", "error.txt", "a") // stderr - файл для записи
);

$DS = DIRECTORY_SEPARATOR;
$phpexe = __DIR__.'/../../../xampp/php/php.exe';
$phpexe = realpath(str_replace('/',DIRECTORY_SEPARATOR,$phpexe));
$script = __DIR__.'/process.php';
$script = realpath(str_replace('/',DIRECTORY_SEPARATOR,$script));
$komand = $phpexe.' '.$script;
//die($komand);
//mkdir($cwd, 0777);
file_put_contents("piper.txt","");
file_put_contents("pipew.txt","");
file_put_contents("error.txt","");

$cwd = __DIR__.'/tmp';
$env = array('some_option' => 'aeiou');

$process = proc_open($komand, $descriptorspec, $pipes, $cwd, $env);
echo("process ".$process."\n");
echo("pipes ".print_r($pipes,true)."\n");

if (is_resource($process)) {
    // $pipes теперь выглядит так:
    // 0 => записывающий обработчик, подключенный к дочернему stdin
    // 1 => читающий обработчик, подключенный к дочернему stdout
    // Вывод сообщений об ошибках будет добавляться в /tmp/error-output.txt

    fwrite($pipes[0], '<?php print_r($_ENV); ?>');
    fclose($pipes[0]);

    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // Важно закрывать все каналы перед вызовом
    // proc_close во избежание мертвой блокировки
    $return_value = proc_close($process);
    echo "proc_close return > ".$return_value."\n";
}
?>