<?php
/*
File name: staffview.php
Last change: Thu Feb 07 18:54:49 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function paging($pageNum, $where, $pageadd, $rowsPerPage = 15)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `staff` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT * FROM `staff` {$where} ORDER BY `rating` DESC, `id` DESC LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   table_header(NAME, RATING, AGE, CONTRACT);
   while ($row = mysql_fetch_assoc($result))
   {
      table_startrow();
      table_cell($row['name']);
      table_cell(create_progress_bar($row['rating']));
      table_cell($row['age']);
      table_cell(create_link("staffcontract.php?id={$row['id']}", CONTRACT));
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
mkglobal("type");
switch ($type)
{
   case "coach": $name = COACHES; $names = "coaches"; $field = "coach"; break;
   case "doctor": $name = DOCTORS; $names = "coctors"; $field = "doctor"; break;
   case "scout": $name = SCOUTS; $names = "scouts"; $field = "scout"; break;
   case "accountant": $name = "—четоводител"; $names = "accountant"; $field = "accountant"; break;
   default: info(INVALID_SCTIPT_CALL, ERROR); break;
}
pagestart($name);
head($name);
mkglobal("page", false);
if (!empty($page) && !is_numeric($page)) info (INVALID_PAGE_NUMBER, ERROR);
if (empty($page)) $page = 1;
if(!paging($page, "WHERE `type` = '{$field}' AND `team` = 0", "staffview.php?type={$type}", 15)) info(INVALID_SCTIPT_CALL, ERROR, true);
pageend();
?>
