<?php
/*
File name: mybets.php
Last change: Sun May 25 15:16:07 EEST 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
$data = sql_query("SELECT * FROM `bets` WHERE `teamid` = {$TEAM['id']} ORDER BY `time` DESC", __FILE__, __LINE__);
if (mysql_affected_rows() == 0) info(YOU_DONT_HAVE_BETS, BETS);
pagestart(BETS);
head(BETS);
prnt(YOUR_BALANCE.": ".number_format($TEAM['odds_balance'], 2, ".", ""));
br();
prnt(SYSTEM_BALANCE.": ".number_format($config['bets_balance'], 2, ".", ""));
br(2);
table_start();
table_header(TIME, HOME, AWAY, RESULT." / ".START, MONEY, COEFICIENT, TYPE);
while ($row = mysql_fetch_assoc($data))
{
   $home = sql_data("SELECT `hometeam` AS `id`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `name` FROM `match` WHERE `id` = {$row['matchid']}", __FILE__, __LINE__);
   $away = sql_data("SELECT `awayteam` AS `id`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `name` FROM `match` WHERE `id` = {$row['matchid']}", __FILE__, __LINE__);
   $match = sql_data("SELECT `played`, `start`, `homescore`, `awayscore` FROM `match` WHERE `id` = {$row['matchid']}", __FILE__, __LINE__);
   table_startrow();
   table_cell($row['time']);
   table_cell(create_link("teamdetails.php?id={$home['id']}", $home['name']));
   table_cell(create_link("teamdetails.php?id={$away['id']}", $away['name']));
   if ($match['played'] == 'no') table_cell($match['start']);
   else table_cell(create_link("matchreport.php?id={$row['matchid']}", "{$match['homescore']} - {$match['awayscore']}"));
   table_cell($row['value']);
   table_cell($row['coefic']);
   if ($row['result'] == 0) table_cell("1");
   if ($row['result'] == 1) table_cell("X");
   if ($row['result'] == 2) table_cell("2");
   table_endrow();
}
table_end();
pageend();
?>
