<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("//", "/", $config["root"]."/".$_POST["path"]);
$mimetype = $_POST["mimetype"];

$command = "echo \"data:$mimetype;base64,$(cat $path | base64 -w 0)\"";
$res = $ssh->exec($command);
echo json_encode(array("error"=>0, "data"=>array($res)));
