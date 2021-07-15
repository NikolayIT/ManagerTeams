<?php
/*
File name: friendlycupview.php
Last change: Sat Jan 19 19:00:30 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

mkglobal("id");
if (!$id || !is_numeric($id)) info(WRONG_ID, ERR);
$id = sqlsafe($id);
$cup = sql_data("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `match_type`.`createdby`) AS `creatorname` FROM `match_type` WHERE (`type` = 'Friendly cup' OR `type` = 'Friendly league') AND `id` = {$id}", __FILE__, __LINE__);
if (!$cup) info(WRONG_ID, ERR);

pagestart(CUP_VIEW." - {$cup['name']}");

if(limit_cover(UC_ADMIN)) head(CUP_VIEW." - {$cup['name']} (<a href='friendlycupdelete.php?id={$cup['id']}' onclick=\"return confirm('Потвърждение за изтриване на купата?');\">".DELETE."</a>)");
else head(CUP_VIEW." - {$cup['name']}");
table_start();
if ($cup['password'] && (limit_cover(UC_ADMIN) || $cup['createdby'] == $USER['id'])) table_row(NAME, "<b>{$cup['name']}</b> password: <i>{$cup['password']}</i>");
else table_row(NAME, "<b>{$cup['name']}</b>");
table_row(CREATOR, create_link("viewprofile.php?id={$cup['createdby']}", $cup['creatorname']));
table_row(CREATED_ON, $cup['created']);
table_row(SUBSCRIPTION_FEE, "{$cup['fee']} ".MONEY_SIGN);
table_row(TEAMS, "{$cup['participants']} / {$cup['teams']}");
table_row(TYPE, $cup['type']);
table_row(START, $row['startat'] < 10 ? "0".$row['startat'].":30" : $row['startat'].":30");
if($cup['participants'] < $cup['teams']) table_row(SUBSCRIBE, create_hide_link("friendlycupsubscribe.php?id={$cup['id']}", SUBSCRIBE));
table_end();
br();
head(MATCHES_IN." {$cup['name']}");
$matches = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname` FROM `match` WHERE `type` = {$id}", __FILE__, __LINE__);
if (mysql_num_rows($matches) == 0) prnt(TOURNEY_NOT_STARTED, true);
else
{
   $last = 0;
   $rounds = sql_get("SELECT CAST(`teams` AS SIGNED) FROM `match_type` WHERE `id` = {$id}", __FILE__, __LINE__);
   $rounds += 1;
   table_start();
   table_header(HOME, START_RESULT, AWAY, "1", "X", "2");
   while ($row = mysql_fetch_assoc($matches))
   {
      if ($row['round'] != $last)
      {
         $last = $row['round'];
         table_startrow();
         if ($last != $rounds) table_cell("<b>".ROUND." ".$row['round']."</b>", 6);
         else table_cell("<b>".FFINAL."</b>", 6);
         table_endrow();
      }
      table_startrow();
      if ($row['hometeam'] > MAX_TEAMS) table_cell(WINNER_FROM_MATCH." {$row['hometeam']}");
      else table_cell(create_link("teamdetails.php?id={$row['hometeam']}", $row['homename']));
      if ($row['played'] == 'yes') table_cell(create_link("matchreport.php?id={$row['id']}", "{$row['homescore']} - {$row['awayscore']}"));
      else table_cell($row['start']);
      if ($row['awayteam'] > MAX_TEAMS) table_cell(WINNER_FROM_MATCH." {$row['awayteam']}");
      else table_cell(create_link("teamdetails.php?id={$row['awayteam']}", $row['awayname']));
      $odds = format_odds($row['odds'], $row['better'], $row['played'], $row['id']);
      table_cell($odds[0]);
      table_cell($odds[1]);
      table_cell($odds[2]);
      table_endrow();
   }
   table_end();
}
br();
head(PARTISIPANTS_IN." {$cup['name']}");
$participants = sql_query("SELECT *,
(`goalsscored` - `goalsconceded`) AS `goaldiff`,
(SELECT `name` FROM `teams` WHERE `id` = `friendly_participants`.`team`) AS `teamname`,
(SELECT `league` FROM `teams` WHERE `id` = `friendly_participants`.`team`) AS `league`,
(SELECT `id` FROM `match_type` WHERE `name` = `league`) AS `leagueid`
FROM `friendly_participants` WHERE `type` = {$id} ORDER BY `wins` DESC, `goaldiff` DESC, `goalsscored` DESC", __FILE__, __LINE__);
$i = 0;
table_start();
if ($cup['type'] == "Friendly cup") table_header("", TEAM, LEAGUE, TOTAL, WINS, LOSES, GOAL_DIFFERENCE);
else table_header("", TEAM, LEAGUE, TOTAL, POINTS, WINS, DRAWS, LOSES, GOAL_DIFFERENCE);
while ($row = mysql_fetch_assoc($participants))
{
   table_startrow();
   $i++;
   table_cell("{$i}.");
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_cell("<a href='leagueranking.php?id={$row['leagueid']}'>".substr($row['league']."</a>", 1));
   table_cell($row['total']);
   if ($cup['type'] == "Friendly league") table_cell($row['points']);
   table_cell($row['wins']);
   if ($cup['type'] == "Friendly league") table_cell($row['draws']);
   table_cell($row['loses']);
   table_cell("{$row['goalsscored']} - {$row['goalsconceded']}");
   table_endrow();
}
table_end();
pageend();
?>
