<?php
/*
File name: friendlycupsubscribe.php
Last change: Sat Jan 19 18:27:30 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);

$pass = "";
mkglobal("id:pass");
if (!$id || !is_numeric($id)) info(WRONG_ID, ERROR);
$id = sqlsafe($id);
$cup = sql_data("SELECT * FROM `match_type` WHERE (`type` = 'Friendly cup' OR `type` = 'Friendly league') AND `id` = {$id}", __FILE__, __LINE__);
// Errors
if (!$cup) info(WRONG_ID, ERROR);
$check = sql_get("SELECT COUNT(`id`) FROM `friendly_participants` WHERE `type` = {$id} AND `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
if ($check > 0) info(ALREADY_REGISTRED_FOR_TOURNAMENT, ERROR);
if ($cup['fee'] > 0 && $TEAM['money'] < $cup['fee']) info(NOT_ENOUGHT_MONEY, ERROR);
if ($cup['participants'] == $cup['teams']) info(THE_TOURNAMENT_IS_FULL, ERROR);
// No more than 4 cups for users and no more than 8 cups for vips
$count = sql_get("SELECT COUNT(`id`) FROM `friendly_participants` WHERE `incup` = 'yes' AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
if (limit_cover(UC_VIP_USER) && $count > 8) info(TOO_MANY_CUPS_ERROR, ERROR);
if (!limit_cover(UC_VIP_USER) && $count > 4) info(TOO_MANY_CUPS_ERROR, ERROR);
// Cup start time
$check = sql_get("SELECT `id` FROM `friendly_participants` WHERE `team` = {$TEAM['id']} AND `incup` = 'yes' AND `cupstart` = {$cup['startat']}", __FILE__, __LINE__);
if($check) info(CUP_SAME_TIME_ERROR, ERROR);
// Check IPs
$cuseripd = sql_array("SELECT `ip` FROM `logs` WHERE `uid` = '{$USER['id']}' GROUP BY `ip` ORDER BY `ip`", __FILE__, __LINE__);
$partisipants = sql_array("SELECT (SELECT `id` FROM `users` WHERE `team` = `friendly_participants`.`team`) AS `users` FROM `friendly_participants` WHERE `type` = {$id}", __FILE__, __LINE__);
foreach ($partisipants as $value)
{
   if ($value != 0)
   {
      $partips = sql_array("SELECT `ip` FROM `logs` WHERE `uid` = '{$value}' GROUP BY `ip` ORDER BY `ip`", __FILE__, __LINE__);
      $common = array_intersect($cuseripd, $partips);
      if (count($common) > 0) info("Не можете да се запишете в тази купа, защото в нея има участник, който е влизал от вашето IP! (id: {$value})", ERROR);
   }
}

if ($cup['password'] && !$pass)
{
	pagestart();
	head("Записване за купа {$cup['name']}");
	form_start("friendlycupsubscribe.php");
	input("hidden", "id", $id, "", true);
	prnt("Парола: " . input("text", "pass", "", ""));
	br(2);
	input("submit", "do", "Запиши се", "", true);
	form_end();
	pageend();
}
else
{
	if ($pass != $cup['password']) info(WRONG_PASS, ERROR);
	sql_query("UPDATE `match_type` SET `participants` = `participants` + 1 WHERE `id` = {$id}", __FILE__, __LINE__);
	sql_query("INSERT INTO `friendly_participants` (`type`, `team`, `cupstart`) VALUES ('{$id}', '{$TEAM['id']}', {$cup['startat']})", __FILE__, __LINE__);
	if ($cup['fee'] > 0) add_to_money_history("{_SUBSCRIPTION_FEE_}: <a href=\"friendlycupview.php?id={$cup['id']}\">{$cup['name']}</a>", -$cup['fee'], $TEAM['id'], true);
	info(SUCCESSFULLY_SUBSCRIBED_FOR_TOURNAMENT, SUCCESS);
}
?>
