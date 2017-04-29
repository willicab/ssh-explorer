<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$origPath = str_replace("../", "/", str_replace("//", "/", $_POST["origPath"]));
$destPath = str_replace("../", "/", str_replace("//", "/", $_POST["destPath"]));

$command = "cp -rf '$origPath' '$destPath'";
$ssh->exec($command);

include_once 'cd.php';
