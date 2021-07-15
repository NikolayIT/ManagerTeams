<?php
/*
File name: leaguetopscorers.php
Last change: Thu Jan 24 22:38:10 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id))
{
   $league = $TEAM['league'];
   $id = sql_get("SELECT `id` FROM `match_type` WHERE `name` = '{$league}'", __FILE__, __LINE__);
}
else $league = sql_get("SELECT `name` FROM `match_type` WHERE `id` = ".sqlsafe($id), __FILE__, __LINE__);
$ln = substr($league, 1);
if ($ln == "A.1") $ln = "A";

pagestart(TOPSCORERS_OF_DIVISION." {$ln}");
head(TOPSCORERS_OF_DIVISION." {$ln}");
form_start("leaguetopscorers.php", "GET");
select("id", "", true);
$dat = sql_query("SELECT `id`, `name` FROM `match_type` WHERE `type` = 'League'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($dat))
{
   $leagnam = substr($row['name'], 1);
   if ($leagnam == "A.1") $leagnam = "A";
   option($row['id'], $leagnam, $row['name'] == $league, true);
}
end_select(true);
input("submit", "", SHOW, "", true);
form_end();
br(1);

//$data = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players`, `players_stats` WHERE `players`.`id` = `players_stats`.`id` AND (SELECT `league` FROM `teams` WHERE `id` = `players`.`team`) = '{$league}' ORDER BY `players_stats`.`cur_leag_goals` DESC LIMIT 15", __FILE__, __LINE__);
$data = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players`, `players_stats` WHERE `players_stats`.`league` = '{$id}' AND `players`.`id` = `players_stats`.`id` ORDER BY `players_stats`.`cur_leag_goals` DESC LIMIT ".BEST_LIMIT, __FILE__, __LINE__);
table_start();
table_header("", NAME, GOALS, TEAM);
$pos = 0;
$last = "";
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   table_startrow();
   
   if ($row['cur_leag_goals'] != $last) table_cell($pos);
   else table_cell($pos);
   $last = $row['cur_leag_goals'];
   table_player_name($row['id'], $row['name'], $row['shortname']);
   table_cell($row['cur_leag_goals']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
pageend();
?>
