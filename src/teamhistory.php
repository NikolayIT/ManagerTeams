<?php
/*
File name: teamhistory.php
Last change: Sat Feb 09 11:01:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id)) $id = $TEAM['id'];
$id = sqlsafe($id);
$teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$id}", __FILE__, __LINE__);
pagestart(TEAM_HISTORY." ({$teamname})");
head(TEAM_HISTORY." ({$teamname})");
$data = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `team_history`.`team`) AS `teamname` FROM `team_history` WHERE `team` = {$id} ORDER BY `eventtime` DESC, `id` DESC", __FILE__, __LINE__);
if (mysql_affected_rows() == 0) info(NO_TEAM_HISTORY_EVENTS, TEAM_HISTORY);
table_start();
table_header(DATE, SEASON, TEAM, EVENT);
while ($row = mysql_fetch_assoc($data))
{
   $event = $row['event'];
   $find = array("{_BECAME_THE_MANAGER_OF_THE_TEAM_}", "{_FROM_}", "{_TO_}", "{_HAS_WON_IN_}", "{_HAS_REACHED_SEMIFINALS_IN_THE_}", "{_WAS_IN_SECOND_PLACE_IN_}");
   $repl = array(_BECAME_THE_MANAGER_OF_THE_TEAM, _FROM, _TO, _HAS_WON_IN, _HAS_REACHED_SEMIFINALS_IN_THE, _WAS_IN_SECOND_PLACE_IN);
   $event = str_replace($find, $repl, $event);
   table_startrow();
   table_cell($row['eventtime']);
   table_cell($row['season']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_cell($event);
   table_endrow();
}
table_end();
pageend();
?>
