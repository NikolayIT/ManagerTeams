<?php
/*
File name: leaguecards.php
Last change: Thu Jan 24 21:05:32 EET 2008
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
pagestart(DIVISION_CARDS." {$ln}");
head(DIVISION_CARDS." {$ln}");
form_start("leaguecards.php", "GET");
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
br(2);

$data = sql_query("SELECT *, (`players_stats`.`cur_leag_red` * 2 + `players_stats`.`cur_leag_yellow`) AS `points`, (SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players`, `players_stats` WHERE `players_stats`.`league` = '{$id}' AND `players`.`id` = `players_stats`.`id` ORDER BY `points` DESC LIMIT ".BEST_LIMIT, __FILE__, __LINE__);
table_start();
table_header("", NAME, div("statistics_red"), div("statistics_yellow"), BAD_POINTS, TEAM);
$pos = 0;
$last = "";
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   table_startrow();
   if ($row['points'] != $last) table_cell($pos);
   else table_cell($pos);
   $last = $row['points'];
   table_player_name($row['id'], $row['name'], $row['shortname']);
   table_cell($row['cur_leag_red']);
   table_cell($row['cur_leag_yellow']);
   table_cell($row['points']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_endrow();
   table_player_row($row['id'], 6);
}
table_end();
pageend();
?>
