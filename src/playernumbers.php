<?php
/*
File name: playernumbers.php
Last change: Mon Jan 28 08:38:01 EET 2008
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
         if ($value <= 99 && $value >= 1)
         {
            $key = sqlsafe(str_replace("p_", "", $key));
            $value = sqlsafe($value);
            $has = sql_data("SELECT `id` FROM `players` WHERE `number` = {$value} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
            if (!$has) sql_query("UPDATE `players` SET `number` = {$value} WHERE `id` = {$key} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
         }
         //print ("<b>$key</b> = $value<br>");
      }
   }
   info(NUMBERS_SUCCESSFULY_SET, SUCCESS, true);
}
else
{
   $players = sql_query("SELECT `id`, `name`, `shortname`, `number`, `possition` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
   if (mysql_numrows($players) == 0) info(YOU_DONT_HAVE_ANY_PLAYERS, ERROR, false);
   pagestart(PLAYER_NUMBERS);
   head(PLAYER_NUMBERS);
   form_start("playernumbers.php", "POST");
   table_start();
   table_header("", NAME, NUMBER);
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell(input("text", "p_{$row['id']}", $row['number'], "", false, 30));
      table_endrow();
      table_player_row($row['id'], 3);
   }
   table_end();
   br();
   input("submit", "do", SAVE_NUMBERS, "", true);
   form_end();
   pageend();
}
?>
