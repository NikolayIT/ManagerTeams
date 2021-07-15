<?php
/*
File name: teamfixtures.php
Last change: Sat Feb 09 10:46:08 EET 2008
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
$games = sql_query("SELECT `id`, `hometeam`, `awayteam`, `start`, `odds`, `better`, `played`, `type`,
(SELECT `name` FROM `match_type` WHERE `id` = `match`.`type`) AS `typename`,
(SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`,
(SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname`
FROM `match` WHERE `played` = 'no' AND (`hometeam` = '{$id}' OR `awayteam` = '{$id}') ORDER BY `start` ASC LIMIT 15", __FILE__, __LINE__);
if (mysql_affected_rows() == 0) info(DONT_HAVE_FIXTURES, FIXTURES_FOR." {$teamname}", false);
pagestart(FIXTURES_FOR." {$teamname}");
head(FIXTURES_FOR." {$teamname}");
table_start();
if ($id == $TEAM['id']) table_header(TYPE, HOME, START, AWAY, TACTIC);
else table_header(TYPE, HOME, START, AWAY, "1", "X", "2");
while ($row = mysql_fetch_assoc($games))
{
   table_startrow();
   if ($row['typename'] != "")
   {
      if ($row['type'] < $config['cupid']) table_cell(create_link("leagueranking.php?id={$row['type']}", $row['typename']));
      else if ($row['type'] > $config['cupid']) table_cell(create_link("friendlycupview.php?id={$row['type']}", $row['typename']));
      else table_cell(create_link("cupgames.php", $row['typename']));
   }
   else table_cell(FRIENDLY);
   if ($id == $row['hometeam']) table_cell(create_link("teamdetails.php?id={$row['hometeam']}", "<b>{$row['homename']}</b>"), 1, "tb", "", "text-align: right;");
   else table_cell(create_link("teamdetails.php?id={$row['hometeam']}", $row['homename']), 1, "tb", "", "text-align: right;");
   table_cell($row['start'], 1, "tb", "", "text-align: center;");
   if ($id == $row['awayteam']) table_cell(create_link("teamdetails.php?id={$row['awayteam']}", "<b>{$row['awayname']}</b>"), 1, "tb", "", "text-align: left;");
   else table_cell(create_link("teamdetails.php?id={$row['awayteam']}", $row['awayname']), 1, "tb", "", "text-align: left;");
   if ($id == $TEAM['id']) table_cell(create_link("tactics.php?match={$row['id']}", SET_TACTIC));
   if ($id != $TEAM['id'])
   {
      $odds = format_odds($row['odds'], $row['better'], $row['played'], $row['id']);
      table_cell($odds[0]);
      table_cell($odds[1]);
      table_cell($odds[2]);
   }
   table_endrow();
}
table_end();
pageend();
?>
