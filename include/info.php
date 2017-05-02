<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$lsb = $ssh->exec("lsb_release -a");
preg_match_all('/Description:[ ]*([^\n]*)\n/', $lsb, $matches);
$os = $matches[1][0];
$uptime = $ssh->exec("uptime -p");
$kernel = $ssh->exec("uname -r");
$arch = $ssh->exec("uname -m");
$hostname = $ssh->exec("uname -n");
$lang = str_replace("\n", "", explode("=", $ssh->exec("locale | grep LANG="))[1]);
$memory = $ssh->exec("cat /proc/meminfo");
preg_match_all('/MemTotal:[ ]*([^\n]*)\n/', $memory, $matches);
$memtotal = $matches[1][0];
preg_match_all('/MemFree:[ ]*([^\n]*)\n/', $memory, $matches);
$memfree = $matches[1][0];
preg_match_all('/SwapTotal:[ ]*([^\n]*)\n/', $memory, $matches);
$swaptotal = $matches[1][0];
preg_match_all('/SwapFree:[ ]*([^\n]*)\n/', $memory, $matches);
$swapfree = $matches[1][0];
$disk = $ssh->exec("df -h --total | grep total");
preg_match_all('/total[ ]*([^\ ]*)[ ]*([^\ ]*)[ ]*([^\ ]*)[ ]*([^\ ]*)/', $disk, $matches);
$disktotal = $matches[1][0];
$diskused = $matches[2][0];
$diskfree = $matches[3][0];
$diskperc = $matches[3][0];

echo json_encode(array(
    "error"=>0,
    "data"=>array(
        "os"=>$os,
        "uptime"=>$uptime,
        "kernel"=>$kernel,
        "arch"=>$arch,
        "hostname"=>$hostname,
        "lang"=>$lang,
        "memtotal"=>$memtotal,
        "memfree"=>$memfree,
        "swaptotal"=>$swaptotal,
        "swapfree"=>$swapfree,
        "disktotal"=>$disktotal,
        "diskused"=>$diskused,
        "diskfree"=>$diskfree,
        "diskperc"=>$diskperc
    )
));
