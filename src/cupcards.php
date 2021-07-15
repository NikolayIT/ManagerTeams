<?php
/*
File name: cupcards.php
Last change: Fri Jan 18 11:06:52 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(RED_YELLOW_CARDS_CUP);
head(RED_YELLOW_CARDS_CUP);

$data = sql_query("SELECT *, (`players_stats`.`cur_cup_red` * 2 + `players_stats`.`cur_cup_yellow`) AS `points`, (SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players`, `players_stats` WHERE `players`.`id` = `players_stats`.`id` AND (`players_stats`.`cur_cup_red` * 2 + `players_stats`.`cur_cup_yellow`) > 0 ORDER BY `points` DESC LIMIT ".BEST_LIMIT, __FILE__, __LINE__);

$pos = 0;
$last = "";
table_start();
table_header("", NAME, div("statistics_red"), div("statistics_yellow"), BAD_POINTS, TEAM);
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   table_startrow();
   if ($row['points'] != $last) table_cell($pos);
   else table_cell($pos);
   $last = $row['points'];
   table_player_name($row['id'], $row['name'], $row['shortname']);
   table_cell($row['cur_cup_red']);
   table_cell($row['cur_cup_yellow']);
   table_cell($row['points']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_endrow();
   table_player_row($row['id'], 6);
}
table_end();
pageend();
?>
