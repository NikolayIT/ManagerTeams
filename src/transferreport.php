<?php
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id:do");
$id = sqlsafe($id);
$transfer = sql_data("SELECT * FROM `transfers` WHERE `player` = {$id}", __FILE__, __LINE__);
if (!$transfer) info(INVALID_PLAYER, ERROR);
if ($do)
{
   mkglobal("reason");
   $reason = sqlsafe($reason);
   sql_query("INSERT INTO `transfer_reports` (`transfer`, `from`, `reason`, `time`) VALUES ({$transfer['id']}, {$USER['id']}, {$reason}, ".get_date_time().")", __FILE__, __LINE__);
   info(TRANSFER_REPORTED, SUCCESS, false);
}
else
{
   $playername = sql_data("SELECT `name`, `shortname` FROM `players` WHERE `id` = {$id}", __FILE__, __LINE__);
   $playername = get_player_name($playername['name'], $playername['shortname']);
   pagestart(MAKE_A_REPORT_FOR_TRANSFER." ({$playername})");
   head(MAKE_A_REPORT_FOR_TRANSFER." ({$playername})");
   form_start("transferreport.php?id={$id}", "POST");
   table_start(false);
   table_row(REASON, textarea("", "reason", 50, 6, "", false));
   table_startrow();
   table_th(input("submit", "do", SEND), 2);
   table_endrow();
   table_end();
   form_end();
   pageend();
}
?>
