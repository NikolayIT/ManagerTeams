<?php
/*
File name: friendlyresults.php
Last change: Sun Jan 20 11:17:50 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id))
{
   $teamname = $TEAM['name'];
   $id = $TEAM['id'];
}
else $teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = '{$id}'", __FILE__, __LINE__);
$games = sql_query("SELECT `id`, `rules`, `start`, `hometeam`, `awayteam`, `homescore`, `awayscore`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname` FROM `match` WHERE `played` = 'yes' AND (`hometeam` = '{$id}' OR `awayteam` = '{$id}') AND (`rules` = 'frmatch' OR `rules` = 'frcup' OR `rules` = 'frleague') ORDER BY `start` DESC LIMIT 15", __FILE__, __LINE__);
if (mysql_affected_rows() == 0) info(DONT_HAVE_MATCHES_PLAYED, FRIENDLY_RESULTS_FOR." {$teamname}", false);
pagestart(FRIENDLY_RESULTS_FOR." {$teamname}");
head(FRIENDLY_RESULTS_FOR." {$teamname}");
table_start();
table_header(TYPE, HOME, RESULT, AWAY, START);
while ($row = mysql_fetch_assoc($games))
{
   table_startrow();
   $typename = "";
   if ($row['rules'] == "frmatch") $typename = FRIENDLY_MATCH;
   else if ($row['rules'] == "frcup") $typename = FRIENDLY_CUP;
   else if ($row['rules'] == "frleague") $typename = FRIENDLY_LEAGUE;
   table_cell($typename);
   if ($id == $row['hometeam']) table_cell(create_link("teamdetails.php?id={$row['hometeam']}", "<b>{$row['homename']}</b>"), 1, "tb", "", "text-align: right;");
   else table_cell(create_link("teamdetails.php?id={$row['hometeam']}", $row['homename']), 1, "tb", "", "text-align: right;");
   table_cell(create_link("matchreport.php?id={$row['id']}", "{$row['homescore']} - {$row['awayscore']}"), 1, "tb", "", "text-align: center;");
   if ($id == $row['awayteam']) table_cell(create_link("teamdetails.php?id={$row['awayteam']}", "<b>{$row['awayname']}</b>"), 1, "tb", "", "text-align: left;");
   else table_cell(create_link("teamdetails.php?id={$row['awayteam']}", $row['awayname']), 1, "tb", "", "text-align: left;");
   table_cell($row['start'], 1, "tb", "", "text-align: center;");
   table_endrow();
}
table_end();
pageend();
?>
