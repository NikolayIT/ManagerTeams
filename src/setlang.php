<?php
/*
File name: setlang.php
Last change: Wed Jan 30 11:33:21 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
mkglobal("id:retto", false);
$languages = sql_get("SELECT COUNT(`id`) FROM `languages`", __FILE__, __LINE__);
if (!is_numeric($id) || $id <= 0 || $id > $languages) info(WRONG_ID, ERROR);
setcookie("lang", $id, time()+9999999999);
$id = sqlsafe($id);
if ($USER) sql_query("UPDATE `users` SET `language` = {$id} WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
if ($retto == "" || substr_count($retto, "setlang") > 0) print("<script>window.location=\"index.php\";</script>");
else print("<script>window.location=\"{$retto}\";</script>");
?>
