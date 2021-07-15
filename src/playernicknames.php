<?php
/*
File name: playernicknames.php
Last change: Mon Jan 28 08:35:19 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("do");
if ($do == "1")
{
   foreach ($_POST as $key => $value)
   {
      if (substr($key, 0, 2) == "p_")
      {
         $key = sqlsafe(str_replace("p_", "", $key));
         $value = sqlsafe($value);
         sql_query("UPDATE `players` SET `shortname` = {$value} WHERE `id` = {$key} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
         //print ("<b>$key</b> = $value<br>");
      }
   }
   info(NICKNAMES_SUCCESSFULY_SET, SUCCESS, true);
}
else
{
   $players = sql_query("SELECT `id`, `name`, `shortname`, `possition` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
   if (mysql_numrows($players) == 0) info(YOU_DONT_HAVE_ANY_PLAYERS, ERROR, false);
   pagestart(PLAYER_NICKNAMES);
   head(PLAYER_NICKNAMES);
   form_start("playernicknames.php?do=1", "POST");
   table_start();
   table_header("", NAME, REAL_NAME, NICKNAME);
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['name']);
      table_cell(input("text", "p_{$row['id']}", $row['shortname'], "", false, 30));
      table_endrow();
      table_player_row($row['id'], 4);
   }
   table_end();
   br();
   input("submit", "", SAVE_NICKNAMES, "", true);
   form_end();
   pageend();
}
?>
