<?php
/*
File name: activetransfers.php
Last change: Sat Jan 12 10:22:22 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("all", false);
mkglobal("from", false);
mkglobal("to", false);
mkglobal("best", false);
if ($all)
{
   $where = "WHERE `offerteam` != 0 AND `fromteam` != 0";
   $orderby = "ORDER BY `until` DESC";
   $limit = "";
   $caption = TRANSFER_HISTORY;
}
else if ($from)
{
   $from = sqlsafe($from);
   $teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$from}", __FILE__, __LINE__);
   $where = "WHERE `fromteam` = {$from}";
   $orderby = "ORDER BY `until` DESC";
   $limit = "";
   $caption = SELLING_PLAYERS." ({$teamname})";
}
else if ($to)
{
   $to = sqlsafe($to);
   $teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$to}", __FILE__, __LINE__);
   $where = "WHERE `offerteam` = {$to}";
   $orderby = "ORDER BY `until` DESC";
   $limit = "";
   $caption = BUYING_PLAYERS." ({$teamname})";
}
else if ($best == 1)
{
   $where = "WHERE `offerteam` != 0 AND `fromteam` != 0";
   $orderby = "ORDER BY `bestoffer` DESC";
   $limit = "LIMIT 25";
   $caption = BEST_TRANSFERS_PRICE;
}
else if ($best == 2)
{
   $where = "";
   $orderby = "ORDER BY `global` DESC";
   $limit = "LIMIT 25";
   $caption = BEST_TRANSFERS_RATING;
}
else info(INVALID_SCTIPT_CALL, ERROR);

$offers = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `transfers`.`fromteam`) AS `seller`, (SELECT `name` FROM `teams` WHERE `id` = `transfers`.`offerteam`) AS `buyer`, (SELECT `global` FROM `players` WHERE `id` = `transfers`.`player`) AS `global`, (SELECT `possition` FROM `players` WHERE `id` = `transfers`.`player`) AS `possition`, (SELECT `name` FROM `players` WHERE `id` = `transfers`.`player`) AS `name`, (SELECT `shortname` FROM `players` WHERE `id` = `transfers`.`player`) AS `shortname` FROM `transfers` {$where} {$orderby} {$limit}", __FILE__, __LINE__);
if (mysql_num_rows($offers) == 0) info(ACTIVETRANSFERS_NO_RESULT, SORRY);

pagestart($caption);
head($caption);
prnt("<b>".SELL_PLAYERS_INFO."</b><br>", true);
table_start();
if(limit_cover(UC_ADMIN))
{
   if ($all || $best) table_header("", NAME, RATING, SELLER, BUYER, PRICE, ACTIVE, BUY, MAKE_A_REPORT, CHECK);
   else table_header("", NAME, RATING, SELLER, BUYER, PRICE, ACTIVE, BUY, MAKE_A_REPORT, VALID_UNTIL, CHECK);
}
else
{
   if ($all || $best) table_header("", NAME, RATING, SELLER, BUYER, PRICE, ACTIVE, BUY, MAKE_A_REPORT);
   else table_header("", NAME, RATING, SELLER, BUYER, PRICE, ACTIVE, BUY, MAKE_A_REPORT, VALID_UNTIL);
}
while ($row = mysql_fetch_assoc($offers))
{
   table_startrow();
   table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['player'], $row['name'], $row['shortname']);
   table_cell(create_progress_bar($row['global'], 100));
   table_cell(create_link("teamdetails.php?id={$row['fromteam']}", $row['seller']));
   table_cell(create_link("teamdetails.php?id={$row['offerteam']}", $row['buyer']));
   table_cell($row['bestoffer']." ˆ");
   if ($row['available'] == 'yes') table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   table_cell(create_link("buyplayer.php?id={$row['player']}", BUY));
   table_cell(create_link("transferreport.php?id={$row['player']}", MAKE_A_REPORT));
   if (limit_cover(UC_ADMIN)) table_cell(create_link("admin.php?module=checktransfer&id={$row['id']}", CHECK));
   if (!$all && !$best) table_cell($row['until']);
   table_endrow();
   table_player_row($row['player'], 8);
}
table_end();
pageend();
?>
