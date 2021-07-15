<?php
/*
File name: friendlyaccept.php
Last change: Sat Jan 19 17:08:56 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("id");
if (empty($id) || !is_numeric($id) || $id < 1) info(WRONG_ID, ERROR);

$friendly = sql_data("SELECT * FROM `friendly_invitations` WHERE `id` = {$id}", __FILE__, __LINE__);
if (!$friendly) info(INVITATION_ALREADY_REJECTED, ERROR);
if ($friendly['toteam'] != $TEAM['id'] && $friendly['toteam'] != 0) info(INVITATION_NOT_FOR_YOU, ERROR);
if ($friendly['date'] <= substr(get_date_time(false),0,10)) info("Можете да приемате приятелски срещи поне ден преди предложения ден", ERROR);
if ($TEAM['id'] == $friendly['fromteam']) info(INVITATION_NOT_FOR_YOU, ERROR);
if ($friendly['accepted'] == 'yes') info(INVITATION_ALREADY_ACCEPTED, ERROR);

$cant1 = sql_get("SELECT `id` FROM `friendly_invitations` WHERE (`fromteam` = {$friendly['fromteam']} OR `fromteam` = {$TEAM['id']}) AND `accepted` = 'yes' AND `date` = '{$friendly['date']}' AND `time` = '{$friendly['time']}'", __FILE__, __LINE__);
$cant2 = sql_get("SELECT `id` FROM `friendly_invitations` WHERE (`toteam` = {$friendly['fromteam']} OR `toteam` = {$TEAM['id']}) AND `accepted` = 'yes' AND `date` = '{$friendly['date']}' AND `time` = '{$friendly['time']}'", __FILE__, __LINE__);
if ($cant1 || $cant2) info(ANOTHER_MATCH_AT_THE_SAME_TIME, ERROR);
sql_query("UPDATE `friendly_invitations` SET `accepted` = 'yes', `toteam` = '{$TEAM['id']}' WHERE `id` = {$id}", __FILE__, __LINE__);
if ($friendly['type'] == "home")
{
   $home = $friendly['fromteam'];
   $away = $TEAM['id'];
}
else
{
   $home = $TEAM['id'];
   $away = $friendly['fromteam'];
}
$time = $friendlystarttime[$friendly['time']];
if ($time < 10) $time = "0"."{$time}";
$start = sqlsafe("{$friendly['date']} {$time}:00:00");
sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES (0, {$config['season']}, 0, {$start}, {$home}, {$away}, 'frmatch')", __FILE__, __LINE__);
$to = sql_get("SELECT `id` FROM `users` WHERE `team` = {$friendly['fromteam']}", __FILE__, __LINE__);
send_game_message($to, "{_FRIENDLY_MATCH_ACCEPTED_FROM_} {$TEAM['name']}", "{$TEAM['name']} {_ACCEPTED_YOUR_FRIEDNLY_MATCH_}!");
info(INVITATION_ACCEPTED, SUCCESS);
?>
