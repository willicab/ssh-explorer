<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("//", "/", str_replace("../", "/", $config["root"]."/".$_POST["path"]."/"));
$name = str_replace("//", "/", str_replace("../", "/", $_POST["name"]));

switch ($_POST["type"]) {
    case 'tar.gz':
        $command = "cd $path && tar -xzf $name";
        break;
    case 'tar':
        $command = "cd $path && tar -xf $name";
        break;
    case 'gz':
        $command = "cd $path && gzip -d $name";
        break;
    case 'zip':
        $command = "cd $path && unzip $name";
        break;
    default:
        $command = "cd $path && ls $name";
        break;
}

$precmd = $command;
$files = $ssh->exec($command);
include_once 'cd.php';
