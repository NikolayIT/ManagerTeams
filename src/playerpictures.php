<?php
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
         $value = sqlsafe(strip_tags(stripslashes($value)));
         sql_query("UPDATE `players` SET `picture` = {$value} WHERE `id` = {$key} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
         //print ("<b>$key</b> = $value<br>");
      }
   }
   info(PICTURES_SUCCESSFULY_SET, SUCCESS, true);
}
else
{
   $players = sql_query("SELECT `id`, `name`, `shortname`, `picture`, `possition` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
   if (mysql_numrows($players) == 0) info(YOU_DONT_HAVE_ANY_PLAYERS, ERROR, false);
   pagestart(PLAYER_PICTURES);
   head(PLAYER_PICTURES);
   prnt(PLAYER_PICTURES_INFO);
   br(2);
   form_start("playerpictures.php", "POST");
   table_start();
   table_header("", NAME, PICTURE);
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell(input("text", "p_{$row['id']}", $row['picture'], "", false, 60));
      table_endrow();
      table_player_row($row['id'], 3);
   }
   table_end();
   br();
   input("submit", "do", SAVE_PICTURES, "", true);
   form_end();
   pageend();
}
?>
