<?php
/*
File name: playersstats.php
Last change: Mon Jan 28 22:28:01 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function create_stats_table($type)
{
   global $TEAM;
   $players = sql_query("SELECT * FROM `players`, `players_stats` WHERE `players`.`team` = '{$TEAM['id']}' AND `players`.`id` = `players_stats`.`id` ORDER BY `players`.`possition` ASC", __FILE__, __LINE__);
   if (mysql_numrows($players) == 0) info(YOU_DONT_HAVE_ANY_PLAYERS, ERROR, false);
   $arr = array("played", "goals", "yellow", "red", "inj");
   if ($type == "leag") $add = LEAGUE_STATISTICS;
   else if ($type == "cup") $add = CUP_STATISTICS;
   else if ($type == "fr") $add = FRIENDLY_STATISTICS;
   else $add = TOTAL;
   head($add);
   table_start();
   prnt("<tr><th></th><th>".NAME."</th><th colspan=5>".CURRENT_SEASON."</th><th colspan=5>".TOTAL."</th></tr>");
   $head = "<th>#</th><th>".div("statistics_goal")."</th><th>".div("statistics_yellow")."</th><th>".div("statistics_red")."</th><th>".div("statistics_injuries")."</th>";
   prnt("<tr><th colspan=2></th>{$head}{$head}</tr>");
   while ($row = mysql_fetch_assoc($players))
   {
      if ($row['name'])
      {
         table_startrow();
         table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
         table_player_name($row['id'], $row['name'], $row['shortname']);
         if ($type == "leag" || $type == "cup" || $type == "fr")
         {
            foreach($arr as $type2) table_cell($row["cur_{$type}_{$type2}"]);
            foreach($arr as $type2) table_cell($row["all_{$type}_{$type2}"]);
         }
         else if ($type == "all")
         {
            foreach($arr as $type2) table_cell($row["cur_leag_{$type2}"] + $row["cur_cup_{$type2}"] + $row["cur_fr_{$type2}"]);
            foreach($arr as $type2) table_cell($row["all_leag_{$type2}"] + $row["all_cup_{$type2}"] + $row["all_fr_{$type2}"]);
         }
         table_endrow();
         table_player_row($row['id'], 12);
      }
   }
   table_end();
}

pagestart(STATISTICS);
mkglobal("type");
if ($type == "leag" || $type == "cup" || $type == "fr" || $type == "all") create_stats_table($type);
else
{
   head(STATISTICS);
   create_special_link("playersstats.php?type=all", TOTAL, STATS_TOTAL_TEXT);
   create_special_link("playersstats.php?type=leag", LEAGUE, STATS_LEAGUE_TEXT);
   create_special_link("playersstats.php?type=cup", CUP, STATS_CUP_TEXT);
   create_special_link("playersstats.php?type=fr", FRIENDLY, STATS_FRIENDLY_TEXT);
}
pageend();
?>
