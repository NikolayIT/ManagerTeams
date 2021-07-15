<?php
/*
File name: sellplayer.php
Last change: Wed Jan 30 11:27:34 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

$staff = sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'scout'", __FILE__, __LINE__);
if (!$staff) info("Нямате скаут и не можете да продавате или купувате играчи!", ERROR);

if($USER['registred'] > get_date_time(false, -TIME_WEEK)) info("You must be registred before at least 1 week to use the transfers", ERROR);
$players_num = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = {$TEAM['id']}", __FILE__, __LINE__);
if ($players_num < MINIMUM_PLAYERS_IN_TEAM) info(TOO_FEW_PLAYERS, ERROR);
mkglobal("id");
if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
$id = sqlsafe($id);
$player = sql_data("SELECT * FROM `players` WHERE `id` = {$id} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
if (!$player) info(WRONG_ID, ERROR);
$inlist = sql_get("SELECT `id` FROM `transfers` WHERE `player` = {$id}", __FILE__, __LINE__);
if ($inlist) info(ALREADY_IN_TRANFER_LIST, SELL_PLAYER);
$name = get_player_name($player['name'], $player['shortname']);
mkglobal("do");
if ($do == 'yes')
{
   mkglobal("price");
   if ($price < 0 || $price > 100000000000) info(INVALID_PRICE, ERROR);
   $price = sqlsafe($price);
   $until = get_date_time(true, +60*60*24*SELL_OFFER_ACTIVE_TIME);
   sql_query("INSERT INTO `transfers` (`player`, `fromteam`, `bestoffer`, `until`) VALUES ('{$id}', '{$TEAM['id']}', {$price}, {$until})", __FILE__, __LINE__);
   info(PLAYER_SUCCESSFULLY_ADDED_TO_TRANFER_LIST, SUCCESS);
}
pagestart(SELL_PLAYER." {$name}");
head(SELL_PLAYER." {$name}");
prnt(ARE_YOU_SURE_ADD_PLAYER_IN_TRANSFER_LIST, true);
form_start("sellplayer.php?do=yes&id={$id}", "POST");
prnt(ASKING_PRICE.": ".input("text", "price", 0)." €", true);
prnt(PLAYER_SELL_INFO, true);
prnt(THE_OFFER_WILL_BE_AVAILABLE_FOR." ".SELL_OFFER_ACTIVE_TIME." "._DAYS."!", true);
input("submit", "", ADD_TO_TRANSFER_LIST, "", true);
form_end();
pageend();
?>
