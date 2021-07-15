<?php
define("IN_GAME", true);
include("common.php");
limit();
pagestart(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": Champions League");

for($round = 7; $round <= 10; $round++)
{
	if ($round == 7) head(SEASON." {$config['season']}: Champions League: 1/8 finals");
	if ($round == 8) head(SEASON." {$config['season']}: Champions League: 1/4 finals");
	if ($round == 9) head(SEASON." {$config['season']}: Champions League: 1/2 finals");
	if ($round == 10) head(SEASON." {$config['season']}: Champions League: Final");
	
	$result = sql_query("SELECT `id`, `hometeam`, `awayteam`, `start`, `odds`, `better`, `played`,
	(SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `home`,
	(SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `away`,
	`homescore`, `awayscore`, `played`
	FROM `match` WHERE `type` = '30000' AND `round` = '{$round}' AND `season` = {$config['season']}", __FILE__, __LINE__);
	table_start();
	table_header(ID, HOME, RESULT." / ".START, AWAY, "1", "X", "2");
	while ($row = mysql_fetch_assoc($result))
	{
		table_startrow();
		table_cell($row['id'], 1, "tb", "", "text-align: center;");
		if ($row['hometeam'] <= MAX_TEAMS) table_cell(create_link("teamdetails.php?id={$row['hometeam']}", $row['home']), 1, "tb", "", "text-align: right;");
		else table_cell(WINNER_FROM_MATCH." {$row['hometeam']}", 1, "tb", "", "text-align: right;");
		if ($row['played'] == 'yes') table_cell(create_link("matchreport.php?id={$row['id']}", "{$row['homescore']} - {$row['awayscore']}"), 1, "tb", "", "text-align: center;");
		else table_cell($row['start'], 1, "tb", "", "text-align: center;");
		if ($row['awayteam'] <= MAX_TEAMS) table_cell(create_link("teamdetails.php?id={$row['awayteam']}", $row['away']), 1, "tb", "", "text-align: left;");
		else table_cell(WINNER_FROM_MATCH." {$row['awayteam']}", 1, "tb", "", "text-align: left;");
		$odds = format_odds($row['odds'], $row['better'], $row['played'], $row['id']);
		table_cell($odds[0], 1, "tb", "", "text-align: center;");
		table_cell($odds[1], 1, "tb", "", "text-align: center;");
		table_cell($odds[2], 1, "tb", "", "text-align: center;");
		table_endrow();
	}
	table_end();
	br();
}
pageend();
?>