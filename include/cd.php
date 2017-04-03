<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';

$path = str_replace("../", "/", str_replace("//", "/", $config["root"]."/".$_POST["path"]."/"));

$command = "[ -d '$path' ] && paste -d '' <(stat -c \"%N#-#%A#-#%U#-#%G#-#%s#-#%x#-#%y#-#\" '$path'*) <(file -b --mime-type '$path'*) || echo \"not found\"";
$files = $ssh->exec($command);
if (str_replace("\n", "", $files) == "not found") {
    exit('{"error":1, "data":[{"message":"Directory '.$path.' not found"}]}');
}
$arrayFiles = array();
$arrayFolders = array();
$files = explode("\n", $files);
$mime = false;
$i = 0;
foreach($files as $k=>$v) {
    $file = explode("#-#", $v);
    if (count($file)<8) continue;
    $arrayFile = explode(" -> ", str_replace("'", "", str_replace($path, "", $file[0])));
    $name = $arrayFile[0];
    $link = count($arrayFile) > 1 ? $arrayFile[1] : "";
    $array = array(
        "name" => $name,
        "link" => $link,
        "rights" => $file[1],
        "owner" => $file[2],
        "group" => $file[3],
        "size" => $file[4],
        "accessDate" => explode(".", $file[5])[0],
        "modificationDate" => explode(".", $file[6])[0],
        "mimeType" => $file[7]
    );
    if ($file[7] == 'inode/directory') {
        array_push($arrayFolders, $array);
    } else {
        array_push($arrayFiles, $array);
    }
}

#print_r($array);
echo json_encode(array("error"=>0, "data"=>array_merge($arrayFolders, $arrayFiles)));
