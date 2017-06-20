<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$command = "ps ux";
$res = $ssh->exec($command);
#$res = implode("\n", $res);
$result = [];
$re = '/[\S]+[\s]+([0-9]+)[\s]+([0-9.]+)[\s]+([0-9.]+)[\s]+[\S]+[\s]+[\S]+[\s]+[\S]+[\s]+[\S]+[\s]+([\S]+)[\s]+([\S]+)[\s]+([^\n]+)/';
preg_match_all($re, $res, $results, PREG_SET_ORDER, 0);
foreach ($results as $k => $v) {
    $result[] = [
        "pid" => $v[1],
        "cpu" => $v[2],
        "mem" => $v[3],
        "start" => $v[4],
        "time" => $v[5],
        "cmd" => $v[6],
    ];
}
echo json_encode(array("error"=>0, "data"=>$result));
