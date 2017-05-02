<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("//", "/", str_replace("../", "/", $config["root"]."/".$_POST["path"]."/"));
$orig = str_replace("//", "/", str_replace("../", "/", $config["root"]."/".$_POST["orig"]));
$name = str_replace("//", "/", str_replace("../", "/", $config["root"]."/".$_POST["name"]));

switch ($_POST["type"]) {
    case 'tar.gz':
        $command = "tar -czf $name $orig";
        break;
    case 'tar':
        $command = "tar -cf $name $orig";
        break;
    case 'gz':
        $command = "gzip -9 $orig";
        break;
    case 'zip':
        $command = "zip $name $orig";
        break;
}
$precmd = $command;
$files = $ssh->exec($command);
include_once 'cd.php';
