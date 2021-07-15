<?php
/*
File name: sackplayer.php
Last change: Wed Jan 30 10:30:07 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("sack", true);
if ($sack)
{
   mkglobal("id", true);
   if (empty($id)) info(WRONG_ID, ERROR);
   $id = sqlsafe($id);
   $player = sql_data("SELECT `name`, `team`, `wage`,
   (SELECT `id` FROM `transfers` WHERE `player` = `players`.`id` AND `available` = 'yes') AS `transferid`
   FROM `players` WHERE `id` = '{$id}' AND `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
   if (!$player || $player['transferid'] > 0) info(WRONG_ID, ERROR);
   pagestart(SACK_PLAYER." {$player['name']}");
   head(SACK_PLAYER." {$player['name']}");
   $mon = SACK_FINE;
   add_to_money_history("{_SACK_PLAYER_} {$player['name']}", -$mon, $player['team'], true);
   sql_query("DELETE FROM `players` WHERE `id` = {$id}", __FILE__, __LINE__);
   sql_query("DELETE FROM `players_stats` WHERE `id` = {$id}", __FILE__, __LINE__);
   prnt(YOU_HAVE_SACKED.": {$player['name']}!<br>".HE_RETIRED_FROM_FOOTBALL, true);
   create_link("playercontracts.php", GO_BACK_TO_PLAYER_CONTRACTS, true);
   pageend();
}
else
{
   mkglobal("id", false);
   if (empty($id)) info(WRONG_ID, ERROR);
   $id = sqlsafe($id);
   $player = sql_data("SELECT `name`, `wage` FROM `players` WHERE `id` = '{$id}' AND `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
   if (!$player) info(WRONG_ID, ERROR);
   pagestart(SACK_PLAYER." {$player['name']}");
   head(SACK_PLAYER." {$player['name']}");
   $mon = SACK_FINE;
   prnt(ARE_YOU_SURE_YOU_WANT_TO_SACK." {$player['name']}?<br>".SACK_INFORMATION." {$mon} ˆ!", true);
   form_start("sackplayer.php", "POST");
   input("hidden", "id", $id, "", true);
   input("hidden", "sack", 1, "", true);
   input("submit", "", SACK, "", true);
   form_end();
   pageend();
}
?>
