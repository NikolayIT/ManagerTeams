<?php
/*
File name: search.php
Last change: Wed Jan 30 11:13:18 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function pager($where, $pageNum, $rowsPerPage, $pageadd, $table, $q2, $orderby)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `{$table}` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("{$q2} FROM `{$table}` {$where} ORDER BY `{$orderby}` LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   table_header(USERNAME, REAL_NAME, TEAM, SEND_MESSAGE);
   while ($row = mysql_fetch_assoc($result))
   {
      if ($table == "users")
      {
         table_startrow();
         table_cell(create_link("viewprofile.php?id={$row['id']}", $row['username']));
         table_cell($row['realname']);
         table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
         table_cell(create_link("messages.php?do=compose&to={$row['username']}", SEND_MESSAGE));
         table_endrow();
      }
      else
      {
         table_startrow();
         table_cell(create_link("viewprofile.php?id={$row['userid']}", $row['username']));
         table_cell($row['realname']);
         table_cell(create_link("teamdetails.php?id={$row['id']}", $row['teamname']));
         table_cell(create_link("messages.php?do=compose&to={$row['username']}", SEND_MESSAGE));
         table_endrow();
      }
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
mkglobal("query:type", false);
if ($type == "manager")
{
   pagestart(SEARCH_FOR_MANAGERS);
   head(SEARCH_FOR_MANAGERS);
   $query2 = sqlsafe($query);
   prnt(RESULT_OF_SEARCHING_FOR.": {$query2}", true);
   $query2 = sqlsafe("%" . str_replace(" ", "%", $query) . "%");
   $q = "WHERE `username` LIKE {$query2} OR `realname` LIKE {$query2}";
   $q2 = "SELECT `id`, `team`, `realname`, (SELECT `name` FROM `teams` WHERE `id` = `users`.`team`) AS `teamname`, `username`";
   mkglobal("page");
   if (!empty($page) && !is_numeric($page)) info (INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   if(!pager($q, $page, 15, "search.php?type={$type}&query={$query}", "users", $q2, "realname")) info(NOTHING_FOUND, SEARCH_FOR_MANAGERS);
   pageend();
}
else if ($type == "team")
{
   pagestart(SEARCH_FOR_TEAMS);
   head(SEARCH_FOR_TEAMS);
   $query2 = sqlsafe($query);
   prnt(RESULT_OF_SEARCHING_FOR.": {$query2}", true);
   $query2 = sqlsafe("%" . str_replace(" ", "%", $query) . "%");
   $q = "WHERE `name` LIKE {$query2}";
   $q2 = "SELECT `id`, `name` AS `teamname`, (SELECT `username` FROM `users` WHERE `team` = `teams`.`id`) AS `username`, (SELECT `id` FROM `users` WHERE `team` = `teams`.`id`) AS `userid`, (SELECT `realname` FROM `users` WHERE `team` = `teams`.`id`) AS `realname`";
   mkglobal("page");
   if (!empty($page) && !is_numeric($page)) info (INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   if (!pager($q, $page, 15, "search.php?type={$type}&query={$query}", "teams", $q2, "name")) info(NOTHING_FOUND, SEARCH_FOR_TEAMS);
   pageend();
}
else
{
   pagestart(SEARCH);
   head(SEARCH_FOR_MANAGERS);
   form_start("search.php", "GET");
   input("hidden", "type", "manager", "", true);
   prnt(ENTER_SEARCH_TEXT.": ".input("textbox", "query"));
   input("submit", "", SEARCH, "", true);
   form_end();
   br(2);
   head(SEARCH_FOR_TEAMS);
   form_start("search.php", "GET");
   input("hidden", "type", "team", "", true);
   prnt(ENTER_SEARCH_TEXT.": ".input("textbox", "query"));
   input("submit", "", SEARCH, "", true);
   form_end();
   br(2);
   pageend();
}
?>
