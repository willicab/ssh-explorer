<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("//", "/", $config["root"]."/".$_GET["path"]);

$command = "cd ".$path." && ls -la --time-style=long-iso --group-directories-first $path | awk '{print$1, $3, $4, $5, $6, $7, $8, $10}' && echo '__MIME__' && ls -la --group-directories-first $path | awk '{print$9}' | xargs -d '\n' -n 1 file --mime-type";
$files = $ssh->exec($command);
$array = array();
$cmd = explode("__MIME__", $files);
$files = explode("\n", $cmd[0]);
$mimes = explode("\n", $cmd[1]);
$mime = false;
$i = 0;
foreach($files as $k=>$v) {
    if ($k < 3 || $k == (count($files) - 1) || $v == "__MIME__") continue;
    $file = explode(" ", $v);
    array_push($array, array(
        "mimetype" => explode(" ", $mimes[$k + 1])[1],
        "perms" => substr($file[0], 1),
        "owner" => $file[1],
        "group" => $file[2],
        "size" => $file[3],
        "date" => $file[4],
        "time" => $file[5],
        "file" => $file[6],
        "link" => $file[7],
    ));
}

#print_r($array);
echo json_encode(array("error"=>0, "data"=>$array));
