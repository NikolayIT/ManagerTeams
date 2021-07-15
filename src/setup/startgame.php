<?php
die();
define("IN_GAME", true);
include("common.php");
pagestart("Test");

set_time_limit(0);
flush();
ob_flush();

start_game();

pageend();
?>