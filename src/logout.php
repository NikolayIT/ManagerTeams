<?php
/*
File name: logout.php
Last change: Fri Jan 25 08:55:44 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");

setcookie("uid", "", 0x7fffffff, "/");
setcookie("pass", "", 0x7fffffff, "/");
//setcookie("rem", "", 0x7fffffff, "/");

print("<script>window.location=\"".LOGOUT_REDIRECT_TO."\";</script>")
?>
