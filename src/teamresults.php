<?php
/*
File name: teamresults.php
Last change: Sat Feb 09 10:53:09 EET 2008
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
$games = sql_query("SELECT `id`, `start`, `hometeam`, `awayteam`, `homescore`, `awayscore`, (SELECT `name` FROM `match_type` WHERE `id` = `match`.`type`) AS `typename`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname` FROM `match` WHERE `played` = 'yes' AND (`hometeam` = '{$id}' OR `awayteam` = '{$id}') ORDER BY `start` DESC LIMIT 15", __FILE__, __LINE__);
if (mysql_affected_rows() == 0) info(DONT_HAVE_ANY_MATCHES_PLAYED, RESULTS_FOR." {$teamname}", false);
pagestart(RESULTS_FOR." {$teamname}");
head(RESULTS_FOR." {$teamname}");
table_start();
table_header(TYPE, HOME, RESULT, AWAY, START);
while ($row = mysql_fetch_assoc($games))
{
   table_startrow();
   if ($row['typename'] != "") table_cell($row['typename']);
   else table_cell(FRIENDLY);
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
