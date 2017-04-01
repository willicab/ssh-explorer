<?php
    include_once 'Net/SSH2.php';
    $host = $_GET["host"];
    $port = $_GET["port"];
    $username = $_GET["username"];
    $password = $_GET["password"];

    if (!($ssh = new Net_SSH2($host, $port))) {
        exit('{"error":101, "data":[{"message":"Network is unreachable", "host":"'.$host.'", "port":"'.$port.'", "username":"'.$username.'", "password":"'.$password.'"}]}');
    }
    if (!$ssh->login($username, $password)) {
        exit('{"error":1, "data":[{"message":"login error", "host":"'.$host.'", "port":"'.$port.'", "username":"'.$username.'", "password":"'.$password.'"}]}');
    }

    $config = array();
    $config["root"] = "/var/www/html";
