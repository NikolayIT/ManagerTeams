<?php
/*
File name: friendlycupcreate.php
Last change: Sat Jan 19 18:11:55 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
function check_cup_name($name)
{
   if (strlen($name) > 32) info(LONG_REALNAME, ERROR);
   if (strlen($name) < 4) info(SHORT_REALNAME, ERROR);
   if (!is_valid_name($name)) info(WRONG_REALNAME, ERROR);
   $name = sqlsafe($name);
   $check = sql_get("SELECT `id` FROM `match_type` WHERE `name` = {$name}", __FILE__, __LINE__);
   if ($check) info(NAME_TAKEN, ERROR);
}
// Only one own cup
$id = sql_get("SELECT `id` FROM `match_type` WHERE `createdby` = {$USER['id']} AND `finished` = 'no'", __FILE__, __LINE__);
if ($id > 0) info(ONLY_ONE_OWN_CUP_ERROR, ERROR);
// Have money?
if ($TEAM['money'] <= 0) info(NOT_ENOUGHT_MONEY, ERROR);

mkglobal("do");
if ($do == "yes")
{
   // No more than 4 cups for users and no more than 8 cups for vips
   $count = sql_get("SELECT COUNT(`id`) FROM `friendly_participants` WHERE `incup` = 'yes' AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
   //if (limit_cover(UC_VIP_USER) && $count > 8) info(TOO_MANY_CUPS_ERROR, ERROR);
   //if (!limit_cover(UC_VIP_USER) && $count > 4) info(TOO_MANY_CUPS_ERROR, ERROR);
   // Cup start time
   mkglobal("start");
   if ($start < 0 || $start > 23) info(INVALID_CUP_TYPE, ERROR);
   $check = sql_get("SELECT `id` FROM `friendly_participants` WHERE `team` = {$TEAM['id']} AND `incup` = 'yes' AND `cupstart` = {$start}", __FILE__, __LINE__);
   if($check) info(CUP_SAME_TIME_ERROR, ERROR);
   // Cup name
   mkglobal("name");
   check_cup_name($name);
   $name = sqlsafe($name);
   // Cup participants
   mkglobal("teams");
   if ($teams < 0 || $teams >= count($friendlycupteams)) info(INVALID_TEAMS_COUNT, ERROR);
   if ($teams > NOTVIP_MAX_TEAMS_IN_CUP  && !limit_cover(UC_VIP_USER)) info(INVALID_TEAMS_COUNT, ERROR);
   $teams = sqlsafe($friendlycupteams[$teams]);
   // Cup fee
   mkglobal("fee");
   if ($fee < 0 || !is_numeric($fee)) info(INVALID_SUBSCRIPTION_FEE, ERROR);
   if ($TEAM['money'] < $fee) info(NOT_ENOUGHT_MONEY, ERROR);
   if ($fee > MAXIMUM_FEE_FOR_FRIENDLY_CUPS) info(INVALID_SUBSCRIPTION_FEE." (".MAXIMUM." ".MAXIMUM_FEE_FOR_FRIENDLY_CUPS.MONEY_SIGN.")", ERROR);
   // Cup type (cup or league)
   mkglobal("type");
   $type  = 0;
   if ($type < 0 || $type >= count($friendlycuptypes)) info(INVALID_CUP_TYPE, ERROR);
   //if ($type == 1 && $teams > 16) info(INVALID_LEAGUE_TYPE, ERROR);
   $type = sqlsafe($friendlycuptypes[$type]);
   // password
   mkglobal("password");
   $password = sqlsafe($password);
   // insert
   sql_query("INSERT INTO `match_type` (`name`, `createdby`, `startat`, `created`, `fee`, `participants`, `teams`, `type`, `started`, `password`) VALUES ({$name}, {$USER['id']}, {$start}, ".get_date_time(true).", {$fee}, '1', '{$teams}', {$type}, 'no', {$password})", __FILE__, __LINE__);
   $id = mysql_insert_id();
   sql_query("INSERT INTO `friendly_participants` (`type`, `team`, `cupstart`) VALUES ('{$id}', '{$TEAM['id']}', {$start})", __FILE__, __LINE__);
   if ($fee > 0) add_to_money_history("{_SUBSCRIPTION_FEE_}: {$name}", -$fee, $TEAM['id'], true);
   info(CUP_SUCCESSFULY_CREATED, SUCCESS);
}
else
{
   //$i = 0;
   //$typeselect = select("type");
   //foreach ($friendlycuptypes as $value) $typeselect .= option($i++, $value);
   //$typeselect .= end_select();
   $i = 0;
   $teamsselect = select("teams");
   foreach ($friendlycupteams as $value) if ($i <= NOTVIP_MAX_TEAMS_IN_CUP || limit_cover(UC_VIP_USER)) $teamsselect .= option($i++, $value);
   $teamsselect .= end_select();

   pagestart(FRIENDLY_CUP_CREATE);
   head(FRIENDLY_CUP_CREATE);
   form_start("friendlycupcreate.php", "POST");
   input("hidden", "do", "yes", "", true);
   prnt(NAME.": ".input("text", "name"), true);
   br();
   prnt(TEAMS.": {$teamsselect}", true);
   br();
   prnt(SUBSCRIPTION_FEE.": ".input("text", "fee")." ".MONEY_SIGN." (".MAXIMUM.": ".MAXIMUM_FEE_FOR_FRIENDLY_CUPS." ".MONEY_SIGN.")", true);
   br();
   prnt(PASSWORD.": ".input("text", "password") . " Ако желаете купата да е само за поканени от Вас менджъри, напишете парола и я кажете на отборите, които желаете да участват в купата.", true);
   br();
   $startcombo = select("start");
   for ($i = 0; $i <= 23; $i++) $startcombo .= option($i, $i < 10 ? "0"."{$i}:30" : "{$i}:30");
   $startcombo .= end_select();
   prnt(START.": {$startcombo} ".TIMEZONE, true);
   br();
   //prnt(TYPE.": {$typeselect}", true);
   //br();
   input("submit", "", CREATE, "", true);
   form_end();
   pageend();
}
?>
