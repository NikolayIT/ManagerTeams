<?php
/*
File name: viewonline.php
Last change: Sat Feb 09 11:42:46 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
function managerpager($where, $pageNum, $rowsPerPage, $pageadd)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `users` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT `id`, `team`, `class`, `realname`, `ip`, `lastaction`, `lastactionin`, (SELECT `name` FROM `teams` WHERE id = `users`.`team`) AS `teamname`, `username` FROM `users` {$where} ORDER BY `lastaction` DESC LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   if (limit_cover(UC_MODERATOR)) table_header(USERNAME, REAL_NAME, TEAM, "IP", LAST_ACTION, SEND_MESSAGE, PAGE);
   else table_header(USERNAME, REAL_NAME, TEAM, LAST_ACTION, SEND_MESSAGE);
   while ($row = mysql_fetch_assoc($result))
   {
      table_startrow();
      if ($row['class'] >= UC_VIP_USER) $star = "<img src=\"images/star.gif\" alt=\"VIP\">";
      else $star = "";
      table_cell(create_link("viewprofile.php?id={$row['id']}", $row['username']." ".$star));
      table_cell($row['realname']);
      table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
      if (limit_cover(UC_MODERATOR)) table_cell(create_link("admin.php?module=ipinfo&ip={$row['ip']}", $row['ip']));
      table_cell($row['lastaction']);
      table_cell(create_link("messages.php?do=compose&to={$row['username']}", SEND_MESSAGE));
      if (limit_cover(UC_MODERATOR)) table_cell($row['lastactionin']);
      table_endrow();
   }
   table_end();
   $maxPage = ceil($numrows/$rowsPerPage);
   $next = "";
   $last = "";
   $first = "";
   $prev = "";
   if ($pageNum > 1)
   {
      $page = $pageNum - 1;
      $prev = " ".create_link("{$pageadd}?page={$page}", "[".PREVIOUS_PAGE."]");
      $first = " ".create_link("{$pageadd}?page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}?page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}?page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}", true);
   return true;
}
pagestart(ONLINE_MANAGERS);
head(ONLINE_MANAGERS);
mkglobal("page");
if (!empty($page) && !is_numeric($page)) info (INVALID_PAGE_NUMBER, ERROR);
if (empty($page)) $page = 1;
$seconds = 900;
if (is_numeric($_GET['seconds'])) $seconds = $_GET['seconds'];
managerpager("WHERE `lastaction` > " . get_date_time(true, -$seconds), $page, 15, "viewonline.php");
pageend();
?>
