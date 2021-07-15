<?php
/*
File name: history.php
Last change: Tue Jan 22 11:27:13 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id)) $id = $USER['id'];
$id = sqlsafe($id);
$username = sql_get("SELECT `username` FROM `users` WHERE `id` = {$id}", __FILE__, __LINE__);
$data = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `manager_history`.`manager`) AS `username` FROM `manager_history` WHERE `manager` = {$id} ORDER BY `eventtime` DESC, `id` DESC", __FILE__, __LINE__);
if (mysql_affected_rows() == 0) info(NO_HISTORY_EVENTS, HISTORY);
pagestart(HISTORY);
head(HISTORY." ({$username})");
table_start();
table_header(DATE, SEASON, MANAGER, EVENT);
while ($row = mysql_fetch_assoc($data))
{
   $event = $row['event'];
   $event = str_replace("{_BECAME_THE_MANAGER_OF_THE_TEAM_}", _BECAME_THE_MANAGER_OF_THE_TEAM, $event);
   $event = str_replace("{_STARTS_HIS_HER_CAREER_}", _STARTS_HIS_HER_CAREER, $event);
   $event = str_replace("{_HAS_WON_IN_}", _HAS_WON_IN, $event);
   $event = str_replace("{_HAS_REACHED_SEMIFINALS_IN_THE_}", _HAS_REACHED_SEMIFINALS_IN_THE, $event);
   $event = str_replace("{_WAS_IN_SECOND_PLACE_IN_}", _WAS_IN_SECOND_PLACE_IN, $event);
   table_startrow();
   table_cell($row['eventtime']);
   table_cell($row['season']);
   table_cell(create_link("viewprofile.php?id={$row['manager']}", $row['username']));
   table_cell($event);
   table_endrow();
}
table_end();
pageend();
?>
