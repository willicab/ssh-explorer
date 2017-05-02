<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("../", "/", str_replace("//", "/", $config["root"]."/".$_POST["path"]));
$text = str_replace('"', '\"', str_replace('$', '\$', str_replace("\\", "\\\\", $_POST["text"])));

$command = "echo \"$text\" > '$path'";
$res = $ssh->exec($command);
echo $command;
echo $res;
//echo json_encode(array("error"=>0, "data"=>array($res), "cmd"=>$command));
