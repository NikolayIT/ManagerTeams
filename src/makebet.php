<?php
/*
File name: index.php
Last change: Thu Jan 24 11:36:27 EET 2008
Copyright: NRPG (c) 2008
*/
// show bets
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("do:match:type", false);
if(!is_numeric($match) || !is_numeric($type) || $type < 0 || $type > 2) info(INVALID_SCTIPT_CALL, ERROR);
$match = sqlsafe($match);
$match = sql_data("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `hometeamname`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayteamname` FROM `match` WHERE `id` = {$match}", __FILE__, __LINE__);
if (!$match['id'] || $match['played'] == 'yes' || $match['better'] == 0) info(INVALID_MATCH, ERROR);
if ($match['hometeam'] == $TEAM['id'] || $match['awayteam'] == $TEAM['id']) info(NO_BET_OWN_MATCHES, ERROR);
$check = sql_get("SELECT `id` FROM `bets` WHERE `teamid` = {$TEAM['id']} AND `matchid` = {$match['id']}", __FILE__, __LINE__);
if ($check) info(ALREADY_BET, ERROR);
$coef = format_odds($match['odds'], $match['better'], $match['played'], $match['id'], false);
$coefic = $coef[$type];
if ($type == 0) $winn = _WIN_FOR." {$match['hometeamname']}";
else if ($type == 1) $winn = _DRAW;
else if ($type == 2) $winn = _WIN_FOR." {$match['awayteamname']}";
if ($do)
{
   mkglobal("value", true);
   if (!is_numeric($value)) info(INVALID_SCTIPT_CALL, ERROR);
   $bets = sql_get("SELECT COUNT(`id`) FROM `bets` WHERE `teamid` = {$TEAM['id']} AND `payed` = 'no'", __FILE__, __LINE__);
   if ($bets >= MAXINUM_BETS) info(A_LOT_OF_BETS_MADE. "(".MAXIMUM.": ".MAXINUM_BETS.")", ERROR);
   if ($value > MAXIMUM_BET) info(L_MAXIMUM_BET.": ".MAXIMUM_BET.MONEY_SIGN, ERROR);
   if ($value < MINIMUM_BET) info(L_MINIMUM_BET.": ".MINIMUM_BET.MONEY_SIGN, ERROR);
   $win = floor($value * $coefic);
   $value = sqlsafe($value);
   sql_query("INSERT INTO `bets` (`teamid`, `matchid`, `value`, `coefic`, `result`, `time`) VALUES ({$TEAM['id']}, {$match['id']}, {$value}, {$coefic}, '{$type}', ".get_date_time().")", __FILE__, __LINE__);
   add_to_money_history(MATCH_BET.": <a href=\"matchreport.php?id={$match['id']}\">{$match['hometeamname']} - {$match['awayteamname']}</a> ({$winn})", -$value, $TEAM['id'], true);
   $balance = sql_get("SELECT `value` FROM `config` WHERE `name` = 'bets_balance'", __FILE__, __LINE__);
   $balance += $value;
   sql_query("UPDATE `config` SET `value` = {$balance} WHERE `name` = 'bets_balance'", __FILE__, __LINE__);
   sql_query("UPDATE `teams` SET `odds_balance` = `odds_balance` - {$value} WHERE `id` = '{$TEAM['id']}'", __FILE__, __LINE__);
   info(BET_SUCCESS."<br>".IF_YOUR_BET_SUCCEED_YOU_WILL_WIN.": {$win}".MONEY_SIGN, SUCCESS, false);
}
else
{
   pagestart();
   head(MATCH_BET.": {$match['hometeamname']} - {$match['awayteamname']}");
   prnt(YOU_WANT_TO_BET_FOR." {$winn} "._WITH_COEFICIENT." {$coefic}<br><br>");
   form_start("makebet.php?match={$match['id']}&type={$type}", "POST");
   prnt(VALUE.": "); input("textbox", "value", "", "", true); prnt(" ".L_MAXIMUM_BET.": ".MAXIMUM_BET.MONEY_SIGN.", ".L_MINIMUM_BET.": ".MINIMUM_BET.MONEY_SIGN);
   br(2);
   input("submit", "do", MAKE_BET, "", true);
   form_end();
   pageend();
}
?>
