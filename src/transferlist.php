<?php
/*
File name: transferlist.php
Last change: Sat Feb 09 12:30:46 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("possition");
$possitionsform = get_possitions_form("transferlist.php", "possition", $possition, SHOW, "GET");
if (empty($possition) || strlen($possition) > 3) info("<b>".SELL_PLAYERS_INFO."</b><br><br>{$possitionsform}<br>".PLEASE_SELECT_POSSITION."!", TRANSFER_LIST, false);
$possition = sqlsafe($possition);
$where = "WHERE (SELECT `possition` FROM `players` WHERE `id` = `transfers`.`player`) = {$possition} AND `available` = 'yes'";
$offers = sql_query("SELECT *,
(SELECT `global` FROM `players` WHERE `id` = `transfers`.`player`) AS `global`,
(SELECT `name` FROM `players` WHERE `id` = `transfers`.`player`) AS `name`,
(SELECT `shortname` FROM `players` WHERE `id` = `transfers`.`player`) AS `shortname`
FROM `transfers` {$where} ORDER BY `global` DESC", __FILE__, __LINE__);
if (mysql_num_rows($offers) == 0) info("{$possitionsform}<br>".NO_ACTIVE_TRANSFERS_FOR." {$possition}!", TRANSFER_LIST);
pagestart(TRANSFER_LIST);
head(TRANSFER_LIST);
prnt("<b>".SELL_PLAYERS_INFO."</b><br>", true);
prnt($possitionsform);
br();
table_start();
table_header(NAME, RATING, VALID_UNTIL, PRICE, BUY);
while ($row = mysql_fetch_assoc($offers))
{
   table_startrow();
   table_player_name($row['player'], $row['name'], $row['shortname']);
   table_cell(create_progress_bar($row['global']));
   table_cell($row['until']);
   table_cell($row['bestoffer']." ˆ");
   table_cell(create_link("buyplayer.php?id={$row['player']}", BUY));
   table_endrow();
   table_player_row($row['player'], 5);
}
prnt("</table>");
pageend();
?>
