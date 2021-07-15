<?php
define("IN_GAME", true);
include("common.php");
limit();

function get_minutes_select($id, $default)
{
   $formform = select($id);
   for ($i = 1; $i <= 90; $i++) $formform .= option($i, MINUTE." ".$i, $i == $default);
   $formform .= end_select();
   return $formform;
}
function get_players_select($fieldname, $default)
{
   global $TEAM;
   $players = sql_query("SELECT `id`, `name`, `shortname`, `possition`, `number` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition`", __FILE__, __LINE__);
   $ret = "<select name='{$fieldname}' id='{$fieldname}'>".option(0, "&nbsp;");
   $last = "";
   while ($player = mysql_fetch_assoc($players))
   {
      if ($player['possition'] != $last)
      {
         if ($last != "") $ret .= "</optgroup>";
         $last = $player['possition'];
         $ret .= "<optgroup label='{$player['possition']}'>";
      }
      $name = "{$player['number']}. ({$player['possition']}) ".get_player_name($player['name'], $player['shortname']);
      $ret .= option($player['id'], $name, $player['id'] == $default);
   }
   $ret .= "</optgroup>".end_select();
   return $ret;
}
mkglobal("id");
mkglobal("back", false);
$id = sqlsafe($id);
$tactic = sql_data("SELECT * FROM `tactics` WHERE `id` = {$id}", __FILE__, __LINE__);
pagestart(SUBSTITUTIONS);
head(SUBSTITUTIONS);
form_start("settactics_substitutions.php", "POST");
input("hidden", "id", $id, "", true);
input("hidden", "back", $back, "", true);
table_start();
table_header(SUBSTITUTION, MINUTE, PLAYER_OUT, PLAYER_IN);
for ($i = 1; $i <= 3; $i++)
{
   table_startrow();
   table_cell(SUBSTITUTION . " {$i}");
   table_cell(get_minutes_select("sub{$i}_min", $tactic["sub{$i}_min"]));
   table_cell(get_players_select("sub{$i}_out", $tactic["sub{$i}_out"]));
   table_cell(get_players_select("sub{$i}_in", $tactic["sub{$i}_in"]));
}

table_end();
br();
input("submit", "", SAVE_TACTIC, "", true);
form_end();
$back = htmlspecialchars($back);
create_button($back, GO_BACK);
pageend();
?>
