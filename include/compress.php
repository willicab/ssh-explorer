<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("//", "/", str_replace("../", "/", $config["root"]."/".$_POST["path"]."/"));
$orig = str_replace("//", "/", $_POST["orig"]);
$name = str_replace("//", "/", $_POST["name"]);

switch ($_POST["type"]) {
    case 'tar.gz':
        $command = "cd $path && tar -czf $name $orig";
        break;
    case 'tar':
        $command = "cd $path && tar -cf $name $orig";
        break;
    case 'gz':
        $command = "cd $path && gzip -9 $orig";
        break;
    case 'zip':
        $command = "cd $path && zip $name $orig";
        break;
    default:
        $command = "cd $path && ls $name";
        break;
}
$precmd = $command;
$files = $ssh->exec($command);
include_once 'cd.php';
