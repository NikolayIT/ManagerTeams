<?php
/*
File name: leaguehistory.php
Last change: Thu Jan 24 21:37:07 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

mkglobal("id", false);
if ($id == "CUP")
{
   $id = sql_get("SELECT `id` FROM `match_type` WHERE `name` = 'CUP'", __FILE__, __LINE__);
   $league = "CUP";
}
else if (empty($id) || !is_numeric($id))
{
   $league = $TEAM['league'];
   $lg = sqlsafe($league);
   $id = sql_get("SELECT `id` FROM `match_type` WHERE `name` = {$lg}", __FILE__, __LINE__);;
}
else $league = sql_get("SELECT `name` FROM `match_type` WHERE id = ".sqlsafe($id), __FILE__, __LINE__);
if ($league == "CUP")
{
   pagestart(CUP_HISTORY);
   head(CUP_HISTORY);
}
else
{
   $ln = substr($league, 1);
   if ($ln == "A.1") $ln = "A";
   pagestart(LEAGUE_HISTORY_FOR." {$ln}");
   head(LEAGUE_HISTORY_FOR." {$ln}");
}
form_start("leaguehistory.php", "GET");
select("id", "", true);
$dat = sql_query("SELECT `id`, `name` FROM `match_type` WHERE `type` = 'League'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($dat))
{
   $leagnam = substr($row['name'], 1);
   if ($leagnam == "A.1") $leagnam = "A";
   option($row['id'], $leagnam, $row['name'] == $league, true);
}
option("CUP", CUP, $league == "CUP", true);
end_select(true);
input("submit", "", SHOW, "", true);
form_end();
br(2);
$replace = array("{_HAS_WON_IN_}", "{_WAS_IN_SECOND_PLACE_IN_}", "(_HAS_REACHED_SEMIFINALS_IN_THE_}");
$with = array(_HAS_WON_IN, _WAS_IN_SECOND_PLACE_IN, _HAS_REACHED_SEMIFINALS_IN_THE);
$data = sql_query("SELECT * FROM `league_history` WHERE `league` = '{$id}' ORDER BY `eventtime` DESC, `id` DESC", __FILE__, __LINE__);
if (mysql_num_rows($data) == 0) prnt("No history events!");
else
{
   table_start();
   table_header(DATE, SEASON, LEAGUE, EVENT);
   $pos = 0;
   while ($row = mysql_fetch_assoc($data))
   {
      table_startrow();
      table_cell($row['eventtime']);
      table_cell($row['season']);
      table_cell($league);
      $event = $row['event'];
      $event = str_replace($replace, $with, $event);
      table_cell($event);
      table_endrow();
   }
   table_end();
}
pageend();
?>
