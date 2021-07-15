<?php
/*
File name: leaguegames.php
Last change: Thu Jan 24 21:24:24 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

mkglobal("id", false);
if (empty($id) || !is_numeric($id))
{
   $league = $TEAM['league'];
   $id = sql_get("SELECT `id` FROM `match_type` WHERE `name` = '$league'", __FILE__, __LINE__);
}
else $league = sql_get("SELECT `name` FROM `match_type` WHERE `id` = ".sqlsafe($id), __FILE__, __LINE__);
$ln = substr($league, 1);
if ($ln == "A.1") $ln = "A";

mkglobal("round", false);
if (empty($round) || !is_numeric($round) || $round < 0)
{
   if ($config['round'] == 0) $round = 1;
   else $round = $config['round'];
}

pagestart(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": {$ln}");
head(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": {$ln}");
form_start("leaguegames.php", "GET");
select("id", "", true);
$dat = sql_query("SELECT `id`, `name` FROM `match_type` WHERE `type` = 'League'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($dat))
{
   $leagnam = substr($row['name'], 1);
   if ($leagnam == "A.1") $leagnam = "A";
   option($row['id'], $leagnam, $row['name'] == $league, true);
}
end_select(true);
input("hidden", "round", $round, "", true);
input("submit", "", SHOW, "", true);
form_end();
br(2);

table_start();
table_startrow();
table_th(FIRST_ROUND);
for ($i = 1; $i <= 15; $i++)
{
   if ($i != $round) table_cell(create_link("leaguegames.php?id={$id}&round={$i}", $i));
   else table_cell($i);
}
table_endrow();
table_startrow();
table_th(SECOND_ROUND);
for ($i = 16; $i <= 30; $i++)
{
   if ($i != $round) table_cell(create_link("leaguegames.php?id={$id}&round={$i}", $i));
   else table_cell($i);
}
table_endrow();
table_end();
br();
$games = sql_query("SELECT `id`, `hometeam`, `awayteam`, `start`, `odds`, `better`, `played`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `home`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `away`, `homescore`, `awayscore`, `played` FROM `match` WHERE `type` = '{$id}' AND `round` = '{$round}' AND `season` = {$config['season']}", __FILE__, __LINE__);
table_start();
table_header(HOME, RESULT." / ".START, AWAY, "1", "X", "2");
while ($row = mysql_fetch_assoc($games))
{
   table_startrow();
   table_cell(create_link("teamdetails.php?id={$row['hometeam']}", $row['home']), 1, "tb", "", "text-align: right;");
   if ($row['played'] == 'yes') table_cell(create_link("matchreport.php?id={$row['id']}", "{$row['homescore']} - {$row['awayscore']}"), 1, "tb", "", "text-align: center;");
   else table_cell($row['start'], 1, "tb", "", "text-align: center;");
   table_cell(create_link("teamdetails.php?id={$row['awayteam']}", $row['away']), 1, "tb", "", "text-align: left;");
   $odds = format_odds($row['odds'], $row['better'], $row['played'], $row['id']);
   table_cell($odds[0], 1, "tb", "", "text-align: center;");
   table_cell($odds[1], 1, "tb", "", "text-align: center;");
   table_cell($odds[2], 1, "tb", "", "text-align: center;");
   table_endrow();
}
table_end();
pageend();
?>
