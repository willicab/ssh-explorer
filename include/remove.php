<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$rmPath = str_replace("../", "/", str_replace("//", "/", $_POST["rmPath"]));

$command = "rm -rf '$rmPath'";
$ssh->exec($command);

include_once 'cd.php';
