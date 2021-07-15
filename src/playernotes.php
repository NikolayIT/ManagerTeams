<?php
/*
File name: playernotes.php
Last change: Sun Jun 01 09:17:07 EEST 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("do");
if ($do)
{
   foreach ($_POST as $key => $value)
   {
      if (substr($key, 0, 2) == "p_")
      {
         $key = sqlsafe(str_replace("p_", "", $key));
         $value = sqlsafe($value);
         $own = sql_get("SELECT `team` FROM `players` WHERE `id` = {$key}", __FILE__, __LINE__);
         if ($own == $TEAM['id'] && strlen($value) > 2)
         {
            $has = sql_get("SELECT `id` FROM `players_notes` WHERE `player` = {$key}", __FILE__, __LINE__);
            if ($has > 0) sql_query("UPDATE `players_notes` SET `text` = {$value}, `fromid` = {$USER['id']}, `time` = ".get_date_time()." WHERE `id` = {$has}", __FILE__, __LINE__);
            else sql_query("INSERT INTO `players_notes` (`player`, `text`, `fromid`, `time`) VALUES ({$key}, {$value}, {$USER['id']}, ".get_date_time().")", __FILE__, __LINE__);
         }
         //print ("<b>{$key}</b> = {$value}<br>");
      }
   }
   info(NOTES_SUCCESSFULY_SET, SUCCESS, true);
}
else
{
   $players = sql_query("SELECT `id`, `name`, `shortname`, `number`, `possition`, (SELECT `text` FROM `players_notes` WHERE `player` = `players`.`id`) AS `note` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
   if (mysql_numrows($players) == 0) info(YOU_DONT_HAVE_ANY_PLAYERS, ERROR, false);
   pagestart(PLAYER_NOTES);
   head(PLAYER_NOTES);
   form_start("playernotes.php", "POST");
   table_start();
   table_header("", NAME, NOTE);
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell(input("text", "p_{$row['id']}", $row['note'], "", false, 50));
      table_endrow();
      table_player_row($row['id'], 3);
   }
   table_end();
   br();
   input("submit", "do", SAVE_NOTES, "", true);
   form_end();
   pageend();
}
?>
