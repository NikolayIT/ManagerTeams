<?php
/*
File name: passchange.php
Last change: Mon Jan 28 08:12:25 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("edit", true);
additionaldata(true);
if ($edit == 1)
{
   mkglobal("currentpass:newpass:confirmpass", true);
   if (empty($currentpass) || empty($newpass) || empty($confirmpass)) info(MISSING_DATA, ERROR);
   if ($USER['passhash'] != gen_pass_hash($currentpass, $USER['secret'])) info(WRONG_PASS, ERROR);
   if ($newpass != $confirmpass) info(PASS_NOT_MATCH, ERROR);
   if (strlen($newpass) < $password_minlen) info(SHORT_PASS, ERROR);
   if (strlen($newpass) > $password_maxlen) info(LONG_PASS, ERROR);
   $newsecret = mksecret(16);
   $newhash = gen_pass_hash($newpass, $newsecret);
   $newsecret2 = sqlsafe($newsecret);
   $newhash2 = sqlsafe($newhash);
   sql_query("UPDATE `users` SET `secret` = {$newsecret2}, `passhash` = {$newhash2} WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
   logincookie($USER['id'], $newhash, $_COOKIE["rem"], false);
   info(PASSWORD_CHANGED, SUCCESS, false);
}
else
{
   pagestart(CHANGE_PASSWORD);
   head(CHANGE_PASSWORD);
   form_start("passchange.php", "POST");
   input("hidden", "edit", "1", "", true);
   table_start(false);
   table_row(CURRENT_PASS, input("password", "currentpass"));
   table_row(NEW_PASSWORD, input("password", "newpass"));
   table_row(PASS_AGAIN, input("password", "confirmpass"));
   table_startrow();
   table_th(input("submit", "", CHANGE."!"), 2);
   table_endrow();
   table_end();
   form_end();
   pageend();
}
?>
