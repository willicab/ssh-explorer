<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("../", "/", str_replace("//", "/", $config["root"]."/".$_POST["path"]."/"));
$cmd = $_POST["cmd"];

$command = "cd '$path' && $cmd";
$result = $ssh->exec($command);
echo json_encode(array("error"=>0, "data"=>array($command\n\n$result)));
