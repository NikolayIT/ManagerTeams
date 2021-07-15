<?php
/*
File name: moneyhistory.php
Last change: Sun Jan 27 22:12:11 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function paging($pageNum, $where, $pageadd, $rowsPerPage = 15)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `money_history` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `money_history`.`team`) AS `teamname` FROM `money_history` {$where} ORDER BY `eventtime` DESC, `id` DESC LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   //table_header(DATE, SEASON, TEAM, MONEY, EVENT);
   table_header(DATE, SEASON, MONEY, EVENT);
   while ($row = mysql_fetch_assoc($result))
   {
      $event = $row['event'];
      $find = array("{_DAILY_PLAYERS_SALARY_}", "{_DAILY_STAFF_SALARY_}", "{_SPONSORSHIP_}", "{_ACCEPTED_}", "{_SUBSCRIPTION_FEE_}", "{_SUCCESSFULLY_GOT_LOAN_}", "{_SIGN_BONUS_FOR_}", "{_SACK_PLAYER_}", "{_EAST_SEATS_}", "{_WEST_SEATS_}", "{_NORTH_SEATS_}", "{_SOUTH_SEATS_}", "{_VIP_SEATS_}", "{_UPDATED_}", "{_PARKINGS_}", "{_BARS_}", "{_TOILETS_}", "{_GRASS_}", "{_LIGHTS_}", "{_BOARDS_}", "{_YOUTHCENTER_}", "{_ROOF_}", "{_HEATER_}", "{_SPRINKLER_}", "{_FANSHOP_}", "{_HOSPITAL_}", "{_STAFF_PERSON_SENT_TO_COURSE_}", "{_TRANSFER_PRICE_FOR_BUYING_}", "{_TRANSFER_PRICE_FOR_SELLING_}", "{_PAYED_PART_}", "{_FOR_LOAN_}", "{_MONEY_FROM_MATCH_BET_}", "{_LOAN_RETURNED_}", "{_MONEY_FOR_VOTE_}", "{_HAS_WON_IN_}", "{_WAS_IN_SECOND_PLACE_IN_}", "{_PRIZE_FOR_THE_TOPSCORER_IN_THE_LEAGUE_}", "{_FINE_FOR_THE_ROUGH_PLAYER_IN_THE_LEAGUE_}", "{_TV_RIGHTS_}", "{_WITHDRAW_}", "{_DEPOSIT_}");
      $repl = array(DAILY_PLAYERS_SALARY, DAILY_STAFF_SALARY, SPONSORSHIP, _ACCEPTED, SUBSCRIPTION_FEE, SUCCESSFULLY_GOT_LOAN, SIGN_BONUS_FOR, SACK_PLAYER, EAST_SEATS, WEST_SEATS, NORTH_SEATS, SOUTH_SEATS, VIP_SEATS, _UPDATED, PARKINGS, BARS, TOILETS, GRASS, LIGHTS, BOARDS, YOUTHCENTER, ROOF, HEATER, SPRINKLER, FAN_SHOP, HOSPITAL, STAFF_PERSON_SENT_TO_COURSE, TRANSFER_PRICE_FOR_BUYING, TRANSFER_PRICE_FOR_SELLING, PAYED_PART, _FOR_LOAN, MONEY_FROM_MATCH_BET, LOAN_RETURNED, MONEY_FOR_VOTE, _HAS_WON_IN, _WAS_IN_SECOND_PLACE_IN, PRIZE_FOR_THE_TOPSCORER_IN_THE_LEAGUE, FINE_FOR_THE_ROUGH_PLAYER_IN_THE_LEAGUE, TV_RIGHTS, WITHDRAW, DEPOSIT);
      $event = str_replace($find, $repl, $event);
      table_startrow();
      table_cell($row['eventtime']);
      table_cell($row['season']);
      //table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
      table_cell($row['money']);
      table_cell($event);
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
      $prev = " ".create_link("{$pageadd}?page={$page}", "[".PREVIOUS_PAGE."]");
      $first = " ".create_link("{$pageadd}?page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}?page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}?page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}");
   return true;
}
mkglobal("page", false);
if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
if (empty($page)) $page = 1;
pagestart(MONEY. " (".$TEAM['name'].")");
head(MONEY. " (".$TEAM['name'].")");
prnt(YOU_HAVE." {$TEAM['money']} ˆ", true);
if (!paging($page, "WHERE `team` = {$TEAM['id']}", "moneyhistory.php", 15)) prnt(DONT_HAVE_MONEY_HISTORY);
pageend();
?>
