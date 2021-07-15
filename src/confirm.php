<?php
/*
File name: confirm.php
Last change: Fri Jan 18 09:43:53 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");

mkglobal("user:id", false);
$user = sqlsafe($user);
$usr = sql_data("SELECT `id`, `username`, `secret`, `class` FROM `users` WHERE `username` = {$user} LIMIT 1", __FILE__, __LINE__);
if ($usr['class'] != 0) info(ALREADY_ACTIVATED, ERROR, false);
if ($id == gen_confirmation_id($usr['username'], $usr['secret']))
{
   if (FREE_VIP) $class = UC_VIP_USER;
   else $class = UC_USER;
   $inviter = 0 + $_COOKIE["inviter"];
   if ($inviter > 0)
   {
      $inviterip = sql_get("SELECT `ip` FROM `users` WHERE `id` = {$inviter}", __FILE__, __LINE__);
      if ($inviterip == getip()) info("You have the same IP as your inviter!", ERROR);
      sql_query("INSERT INTO `friends` (`user1`, `user2`) VALUES ('{$inviter}', '{$usr['id']}')", __FILE__, __LINE__);
      sql_query("INSERT INTO `friends` (`user1`, `user2`) VALUES ('{$usr['id']}', '{$inviter}')", __FILE__, __LINE__);
      $teamid = sql_get("SELECT `team` FROM `users` WHERE `id` = {$inviter}", __FILE__, __LINE__);
      $inviter = ", `invitedby` = '{$inviter}'";
      add_to_money_history("User invited: <a href=\"viewprofile.php?id={$usr['id']}\">{$usr['username']}</a>", INVITATION_BONUS, $teamid, true);
   }
   else $inviter = "";
   sql_query("UPDATE `users` SET `class` = {$class}{$inviter} WHERE `id` = {$usr['id']} LIMIT 1", __FILE__, __LINE__);
   info(ACCOUNT_ACTIVATED, SUCCESS, false);
}
else info(INVALID_CONFIRMATION_ID, ERROR, false);
?>
