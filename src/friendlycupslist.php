<?php
/*
File name: friendlycupslist.php
Last change: Sat Jan 19 18:18:58 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function paging($pageNum, $where, $orderBy, $pageadd, $my, $old, $rowsPerPage = 20)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `match_type` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `match_type`.`createdby`) AS `creatorname` FROM `match_type` {$where} {$orderBy} LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   if ($my) table_header(NAME, CREATOR, CREATED_ON, SUBSCRIPTION_FEE, TEAMS, START, FINISHED);
   else table_header(NAME, CREATOR, CREATED_ON, SUBSCRIPTION_FEE, TEAMS, START, FINISHED, SUBSCRIBE);
   while ($row = mysql_fetch_assoc($result))
   {
      if ($row['participants'] < $row['teams']) { $b1 = "<b>"; $b2 = "</b>"; }
      else { $b1 = ""; $b2 = ""; }
      table_startrow();
      table_cell($b1.create_link("friendlycupview.php?id={$row['id']}", $row["password"] == "" ? $row['name'] : "<i>{$row['name']}</i>").$b2);
      table_cell($b1.create_link("viewprofile.php?id={$row['createdby']}", $row['creatorname']).$b2);
      table_cell($b1.$row['created'].$b2);
      table_cell($b1."{$row['fee']} ˆ".$b2);
      table_cell($b1."{$row['participants']} / {$row['teams']}".$b2);
      //table_cell($b1.$row['type'].$b2);
      $start = $row['startat'] < 10 ? "0".$row['startat'].":30" : $row['startat'].":30";
      table_cell($b1.$start.$b2);
      if ($row['finished'] == 'yes') table_cell($b1.div("img_plain_yes", YES).$b2);
      else table_cell($b1.div("img_plain_no", NO).$b2);
      if (!$my) table_cell($b1.create_hide_link("friendlycupsubscribe.php?id={$row['id']}", SUBSCRIBE).$b2);
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
      $prev = " ".create_link("{$pageadd}page={$page}", "[".PREVIOUS_PAGE."]");
      $first = " ".create_link("{$pageadd}page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}");
   return true;
}

mkglobal("my:old");
if ($my)
{
   pagestart(CUPS_WITH_MY_TEAM_TEXT);
   head(CUPS_WITH_MY_TEAM_TEXT);
   $where = "WHERE (`type` = 'Friendly cup' OR `type` = 'Friendly league') AND (`id` = ANY(SELECT `type` FROM `friendly_participants` WHERE `team` = '{$TEAM['id']}'))";
   $orderBy = "ORDER BY `finished` DESC, `created` DESC";
   $pageadd = "friendlycupslist.php?my=1&";
}
else if ($old)
{
   pagestart(FRIENDLY_CUP_HISTORY);
   head(FRIENDLY_CUP_HISTORY);
   $where = "WHERE (`type` = 'Friendly cup' OR `type` = 'Friendly league') AND `finished` = 'yes'";
   $orderBy = "ORDER BY `finished` DESC, `created` DESC";
   $pageadd = "friendlycupslist.php?old=1&";
}
else
{
   pagestart(CUPS_LIST_TEXT);
   head(CUPS_LIST_TEXT);
   $where = "WHERE (`type` = 'Friendly cup' OR `type` = 'Friendly league') AND `finished` = 'no'";
   $orderBy = "ORDER BY `created` ASC";
   $pageadd = "friendlycupslist.php?";
}
mkglobal("page", false);
if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
if (empty($page)) $page = 1;
if (!paging($page, $where, $orderBy, $pageadd, $my, $old, 20)) prnt("No data to display!");
br(2);
create_button("friendlycupcreate.php", CREATE_TEXT);
pageend();
?>
