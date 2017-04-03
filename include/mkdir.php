<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("../", "/", str_replace("//", "/", $config["root"]."/".$_POST["path"]."/".$_POST["file"]));

$command = "mkdir '$path'";
$ssh->exec($command);

include_once 'cd.php';
