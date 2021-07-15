<?php
/*
File name: cupgames.php
Last change: Sat Jan 19 10:37:11 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("round", false);
function paging($pageNum, $where, $pageadd, $rowsPerPage = 16)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `match` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   $result = sql_query("SELECT `id`, `hometeam`, `awayteam`, `start`, `odds`, `better`, `played`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `home`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `away`, `homescore`, `awayscore`, `played` FROM `match` {$where} LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   table_header(HOME, RESULT." / ".START, AWAY, "1", "X", "2");
   while ($row = mysql_fetch_assoc($result))
   {
      table_startrow();
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
   $maxPage = ceil($numrows/$rowsPerPage);
   $next = "";
   $last = "";
   $first = "";
   $prev = "";
   if ($pageNum > 1)
   {
      $page = $pageNum - 1;
      $prev =  " ".create_link("{$pageadd}&page={$page}", "[".PREVIOUS_PAGE."]")." ";
      $first = " ".create_link("{$pageadd}&page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}&page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}&page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}");
   return true;
}
if (empty($round) || !is_numeric($round) || $round < 1 || $round > 18)
{
   if ($config['cupround'] == 0) $round = 1;
   else $round = $config['cupround'];
}
pagestart(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": ".CUP_ROUND." {$round}");
head(SEASON." {$config['season']} - ".FIXTURES_AND_RESULTS.": ".CUP_ROUND." {$round}");
// Header
table_start();
table_startrow();
table_th(ROUND.":");
for ($i = 1; $i <= 18; $i++)
{
   if ($i != $round) table_cell(create_link("cupgames.php?round={$i}", $i));
   else table_cell($i);
}
table_endrow();
table_end();
br();
mkglobal("page", false);
if (!empty($page) && !is_numeric($page)) info (INVALID_PAGE_NUMBER, ERROR);
if (empty($page)) $page = 1;
$cupid = sql_get("SELECT `id` FROM `match_type` WHERE `name` = 'CUP'", __FILE__, __LINE__);
paging($page, "WHERE `type` = '{$cupid}' AND `round` = '{$round}' AND `season` = {$config['season']}", "cupgames.php?round={$round}", 16);
pageend();
?>
