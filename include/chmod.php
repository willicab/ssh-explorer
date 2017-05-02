<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("//", "/", str_replace("../", "/", $config["root"]."/".$_POST["path"]."/"));
$file = $_POST["file"];
$rights = $_POST["rights"];
$recursive = $_POST["recursive"]==true?"-R":"";

$command = "cd $path && chmod $recursive $rights $file";
$precmd = $command;
$files = $ssh->exec($command);
include_once 'cd.php';

