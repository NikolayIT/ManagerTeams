<?php
/*
File name: ranking.php
Last change: Tue Jan 29 21:42:26 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("country", false);
function paging($pageNum, $where, $pageadd, $rowsPerPage = 25)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `users` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT `id`, `username`, `realname`, `country`, `team`, `goalsscored`, `goalsconceded`,
   (SELECT `name` FROM `countries` WHERE `id` = `users`.`country`) AS `countryname`,
   (SELECT `flagpic` FROM `countries` WHERE `id` = `users`.`country`) AS `flagpic`,
   (SELECT `name` FROM `teams` WHERE `id` = `users`.`team`) AS `teamname`,
   `wins`, `draws`, `loses`, `points`, `class` FROM `users` {$where} ORDER BY `points` DESC LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   table_header("", USERNAME, REAL_NAME, COUNTRY, TEAM, "+ / = / -", GOAL_DIFFERENCE, POINTS);
   $id = $offset + 1;
   while ($row = mysql_fetch_assoc($result))
   {
      if ($row['class'] >= UC_VIP_USER) $star = "<img src=\"images/star.gif\" alt=\"VIP\">";
      else $star = "";
      table_startrow();
      table_cell($id);
      table_cell(create_link("viewprofile.php?id={$row['id']}", $row['username']." ".$star));
      table_cell($row['realname']);
      table_cell(create_link("ranking.php?country={$row['country']}", create_image("images/flags/{$row['flagpic']}")." {$row['countryname']}"));
      table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
      table_cell("{$row['wins']} / {$row['draws']} / {$row['loses']}");
      table_cell("{$row['goalsscored']} - {$row['goalsconceded']}");
      table_cell($row['points']);
      table_endrow();
      $id++;
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
      $prev = " ".create_link("{$pageadd}&page={$page}", "[".PREVIOUS_PAGE."]");
      $first = " ".create_link("{$pageadd}&page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}&page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}&page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}", true);
   return true;
}
mkglobal("page", false);
if (!empty($page) && !is_numeric($page)) info (INVALID_PAGE_NUMBER, ERROR);
if (empty($page)) $page = 1;
pagestart(MANAGER_RANKING);
head(MANAGER_RANKING);
form_start("ranking.php", "GET", "signup", "return a()");
$ct_r = sql_query("SELECT `id`, `name` FROM `countries` ORDER BY `name`", __FILE__, __LINE__);
select("country", "country", true);
while ($ct_a = mysql_fetch_assoc($ct_r)) option($ct_a['id'], $ct_a['name'], $country == $ct_a['id'], true);
end_select(true);
nbsp();
input("submit", "", SHOW, "", true);
form_end();
create_button("ranking.php", SHOW_ALL);
br(2);
if (is_numeric($country) && $country > 0)
{
   $country2 = sqlsafe($country);
   $q = "WHERE `country` = {$country2}";
}
else
{
   $q = "";
   $country = 0;
}
if (!paging($page, $q, "ranking.php?country={$country}", 20)) info(NO_MANAGERS_FROM_THIS_COUNTRY, MANAGER_RANKING, false);
pageend();
?>
