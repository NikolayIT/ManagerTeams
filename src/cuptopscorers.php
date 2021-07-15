<?php
/*
File name: cuptopscorers.php
Last change: Sat Jan 19 10:41:38 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(TOPSCORER_OVERVIEW_CUP);
head(TOPSCORER_OVERVIEW_CUP);
print("Временно този модул няма да работи.");
/*
$data = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players`, `players_stats`
WHERE `players`.`id` = `players_stats`.`id`
ORDER BY `players_stats`.`cur_cup_goals` DESC LIMIT ".BEST_LIMIT, __FILE__, __LINE__);

$pos = 0;
$last = "";
table_start();
table_header("", NAME, GOALS, TEAM);
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   table_startrow();
   if ($row['cur_cup_goals'] != $last) table_cell($pos);
   else table_cell($pos);
   $last = $row['cur_cup_goals'];
   table_player_name($row['id'], $row['name'], $row['shortname']);
   table_cell($row['cur_cup_goals']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
*/
pageend();
?>
