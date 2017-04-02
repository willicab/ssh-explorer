<?php

    $config = array();
    $config["root"] = "/var/www/html";

    include_once 'Net/SSH2.php';
    $host = $_POST["host"];
    $port = $_POST["port"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    //$ssh = new Net_SSH2($host, $port); //185.69.53.40
    if (!($ssh = new Net_SSH2($host, $port))) {
        exit('{"error":101, "data":[{"message":"Network is unreachable", "host":"'.$host.'", "port":"'.$port.'", "username":"'.$username.'", "password":"'.$password.'"}]}');
    }
    if (!$ssh->login($username, $password)) {
        exit('{"error":1, "data":[{"message":"login error", "host":"'.$host.'", "port":"'.$port.'", "username":"'.$username.'", "password":"'.$password.'"}]}');
    }

