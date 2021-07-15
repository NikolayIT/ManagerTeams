<?php
    function loadini($path) {
    $fp = fopen($path, "r");
    $fpcontents = fread($fp, filesize($path));
    fclose($fp);
    return $fpcontents;
    }
    function readini($filename, $key) {
    return rfi($filename,$key,TRUE);
    }
    function rfi($filename, $key, $just_value) {
    $filecontents=loadini($filename);
    $key .= "=";
    $currentkey = strstr($filecontents, $key);
    if (!$currentkey)
    return($empty);
    $endpos = strpos($currentkey, "\r\n");
    if (!$endpos)  $endpos = strlen($currentkey);
    if ($just_value)  $currentkey = trim(substr($currentkey, strlen($key), $endpos-strlen($key)));
    else $currentkey = trim(substr($currentkey, 0, $endpos));
    return ($currentkey);
    }
?>

