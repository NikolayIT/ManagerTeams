<?php
/*
File name: goto.php
Last change: Fri Jan 11 21:15:24 EET 2008
Copyright: NRPG (c) 2008
*/
$address = $_GET['address'];
if (!isset($address) || $address == "") $address = "index.php";
header("Location: {$address}");
?>
