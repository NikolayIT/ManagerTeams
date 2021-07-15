<?php
/*
File name: takelogin.php
Last change: Sat Feb 09 09:11:58 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");

mkglobal("login:pass", true);
if (empty($login) || empty($pass)) info(MISSING_DATA, ERROR);

$login = sqlsafe($login);
$usr = sql_data("SELECT `id`, `passhash`, `secret`, `class` FROM `users` WHERE `username` = {$login}", __FILE__, __LINE__);
if (!$usr) info(WRONG_USER, ERROR);

$validpass = gen_pass_hash($pass, $usr['secret']);
if ($validpass != $usr['passhash']) info(WRONG_PASS, ERROR);

if ($usr['class'] == 0) info(NOT_CONFIRMED."<br>".EMAIL_CONFIRMATION, ERROR, true);

if ($_POST['rem'] == 'yes') $rem = true;
else $rem = false;
logincookie($usr['id'], $usr['passhash'], $rem);

prnt("<script>window.location=\"index.php\";</script>");
?>
