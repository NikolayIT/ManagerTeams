<?php
/*
File name: shortlist.php
Last change: Mon Feb 04 09:48:58 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("do");
if ($do == "remove")
{
   mkglobal("id");
   if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
   $id = sqlsafe($id);
   sql_query("DELETE FROM `shortlist` WHERE `id` = {$id} AND `user` = '{$USER['id']}'", __FILE__, __LINE__);
   info(PLAYER_REMOVED_FROM_YOUR_SHORTLIST, SUCCESS);
}
else if ($do == "add")
{
   mkglobal("id");
   if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
   $id = sqlsafe($id);
   sql_query("INSERT INTO `shortlist` (`user`, `player`) VALUES ('{$USER['id']}', $id)", __FILE__, __LINE__);
   info(PLAYER_ADDED_TO_YOUR_SHORTLIST, SUCCESS);
}
else
{
   $data = sql_query("SELECT *,
   (SELECT `id` FROM `transfers` WHERE `player` = `shortlist`.`player`) AS `transferid`,
   (SELECT `until` FROM `transfers` WHERE `player` = `shortlist`.`player`) AS `transferuntil`,
   (SELECT `global` FROM `players` WHERE `id` = `shortlist`.`player`) AS `global`,
   (SELECT `name` FROM `players` WHERE `id` = `shortlist`.`player`) AS `name`,
   (SELECT `shortname` FROM `players` WHERE `id` = `shortlist`.`player`) AS `shortname`,
   (SELECT `possition` FROM `players` WHERE `id` = `shortlist`.`player`) AS `possition`
   FROM `shortlist` WHERE `user` = {$USER['id']}", __FILE__, __LINE__);
   if (mysql_num_rows($data) == 0) info(NO_PLAYERS_IN_SHORTLIST, SHORTLIST);
   pagestart(SHORTLIST);
   head(SHORTLIST);
   table_start();
   table_header("", NAME, RATING, BUY, VALID_UNTIL, REMOVE);
   while ($row = mysql_fetch_assoc($data))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['player'], $row['name'], $row['shortname']);
      table_cell(create_progress_bar($row['global']));
      if ($row['transferid']) table_cell(create_link("buyplayer.php?id={$row['player']}", BUY));
      else table_cell("N/A");
      if ($row['transferid']) table_cell($row['transferuntil']);
      else table_cell("N/A");
      table_cell(create_link("shortlist.php?do=remove&id={$row['id']}", REMOVE));
      table_endrow();
      table_player_row($row['player'], 3);
   }
   table_end();
   br();
   prnt(SHORTLIST_INFO);
   pageend();
}
?>
