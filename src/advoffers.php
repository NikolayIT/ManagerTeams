<?php
/*
File name: advoffers.php
Last change: Sat Jan 12 15:19:54 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

additionaldata(true);
$maxslot = calculate_boards($STADIUM['boards']);
mkglobal("slot");
if ($slot < 1 || $slot > $maxslot) info(INVALID_SLOT, ERROR);
$adv = sql_get("SELECT `adv` FROM `advboards` WHERE `team` = {$TEAM['id']} AND `board` = {$slot}", __FILE__, __LINE__);
if ($adv != 0) info(SLOT_ALREADY_IN_USE, ERROR);

mkglobal("acc");
if ($acc == "yes")
{
   mkglobal("offer");
   if (empty($offer) || !is_numeric($offer)) info(INVALID_OFFER, ERROR);
   $off = sql_data("SELECT * FROM `advertising` WHERE `id` = '{$offer}'", __FILE__, __LINE__);
   if (!$off) info(INVALID_OFFER, ERROR);
   sql_query("UPDATE `teams` SET `money` = `money` + {$off['money']} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
   add_to_money_history("{_SPONSORSHIP_} (<a href='{$off['url']}'>{$off['name']}</a>) {_ACCEPTED_}", $off['money'], $TEAM['id']);
   sql_query("UPDATE `advboards` SET `adv` = '{$off['id']}', `left` = '{$off['days']}' WHERE `team` = {$TEAM['id']} AND `board` = {$slot}", __FILE__, __LINE__);
   info(SPONSORSHIP." "._ACCEPTED."!<br>".create_link("advboards.php", GO_TO_SPORSORSHIP_OVERVIEW), SUCCESS, false);
}

$advs = sql_query("SELECT * FROM `advertising`", __FILE__, __LINE__);
$exist = sql_get("SELECT COUNT(`id`) FROM `advboards` WHERE `team` = {$TEAM['id']} AND `adv` != 0", __FILE__, __LINE__);
if (mysql_num_rows($advs) == $exist) info(NO_AVAILABLE_OFFERS, SPONSORSHIP_OFFERS);
pagestart(SPONSORSHIP_OFFERS);
head(SPONSORSHIP_OFFERS);
table_start();
table_header(NAME, BANNER, MONEY, DAYS, VALID_UNTIL, ACCEPT);
while ($advinfo = mysql_fetch_assoc($advs))
{
   $exist = sql_data("SELECT `id` FROM `advboards` WHERE `team` = {$TEAM['id']} AND `adv` = '{$advinfo['id']}'", __FILE__, __LINE__);
   if ($exist == 0)
   {
      table_startrow();
      table_cell(create_link($advinfo['url'], $advinfo['name']));
      table_cell(create_link($advinfo['url'], create_image($advinfo['img'])));
      table_cell($advinfo['money']);
      table_cell($advinfo['days']);
      table_cell($advinfo['until']);
      table_cell(create_hide_link("advoffers.php?slot={$slot}&acc=yes&offer={$advinfo['id']}", ACCEPT));
      table_endrow();
   }
}
table_end();
pageend();
?>
