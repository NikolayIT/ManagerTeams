<?php
define("IN_GAME", true);
include("common.php");
limit();
pagestart(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": Champions League");

$leagues = array("A", "B", "C", "D", "E", "F", "G", "H");

for($i = 0; $i < count($leagues); $i++)
{
	$league = $leagues[$i];
	head(SEASON." {$config['season']}: Champions League: Group {$league}");
	
	
	$data = sql_query("SELECT *,
	(SELECT `name` FROM `teams` WHERE `id` = `champions_league`.`team`) AS `name`,
	(`goalsscored` - `goalsconceded`) AS `goaldiff`
	FROM `champions_league` WHERE `group` = '{$league}'
	ORDER BY `points` DESC, `goaldiff` DESC, `goalsscored` DESC, `wins` DESC, `id` DESC", __FILE__, __LINE__);
	table_start();
	table_header("", NAME, TOTAL, POINTS, WINS, DRAWS, LOSES, "+", "-", GOAL_DIFFERENCE);
	$pos = 0;
	while ($row = mysql_fetch_assoc($data))
	{
	   $pos++;
	   if ($pos == 1 || $pos == 2) $style = "tb2";
	   else $style = "tb3";
	   table_startrow();
	   table_cell($pos, 1, $style);
	   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['name']), 1, $style);
	   table_cell($row['total'], 1, $style);
	   table_cell($row['points'], 1, $style);
	   table_cell($row['wins'], 1, $style);
	   table_cell($row['draws'], 1, $style);
	   table_cell($row['loses'], 1, $style);
	   table_cell($row['goalsscored'], 1, $style);
	   table_cell($row['goalsconceded'], 1, $style);
	   table_cell($row['goaldiff'] > 0 ? "+".$row['goaldiff'] : $row['goaldiff'], 1, $style);
	   table_endrow();
	}
	table_end();
}

head(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": Champions League");
$games = sql_query("SELECT `id`, `hometeam`, `awayteam`, `start`, `odds`, `better`, `played`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `home`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `away`, `homescore`, `awayscore`, `played` FROM `match`
	WHERE `type` = '30000' AND `round` <= '6' AND `season` = {$config['season']} ORDER BY `start` ASC", __FILE__, __LINE__);
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