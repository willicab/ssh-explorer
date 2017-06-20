<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$processes = $_POST["processes"];

$command = "kill -9 $processes";
$ssh->exec($command);

include_once 'ps.php';
