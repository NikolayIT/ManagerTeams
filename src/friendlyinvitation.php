<?php
/*
File name: friendlyinvitation.php
Last change: Sun Jan 20 10:53:22 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("to");
if (empty($to) || !is_numeric($to))
{
   $to = 0;
   $toname = _ANY_OTHER_TEAM;
}
else $toname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$to}", __FILE__, __LINE__);
if ($to == $TEAM['id'] || !$toname) info(INVALID_TEAM, ERROR);

mkglobal("do");
if ($do == 1)
{
   //print_post_content();
   mkglobal("stad", true);
   if ($stad == 1) $stad = "home";
   else $stad = "away";
   $type = sqlsafe($stad);
   mkglobal("date", true);
   if (empty($date) || $date < 1) $date = 1;
   if ($date > MAX_DAYS_FOR_FRIENDLY_INVITATION) $date = MAX_DAYS_FOR_FRIENDLY_INVITATION;
   $dateclear = substr(get_date_time(false, TIME_DAY*$date), 0, 10);
   $date = sqlsafe($dateclear);
   mkglobal("time");
   if (!$time || $time < 0) $time = 0;
   if ($time >= count($friendlystarttime)) $time = count($friendlystarttime) - 1;
   $time = sqlsafe($time);

   $check1 = sql_get("SELECT `id` FROM `friendly_invitations` WHERE `fromteam` = {$TEAM['id']} AND `date` = {$date} AND `time` = '{$time}'", __FILE__, __LINE__);
   if ($to != 0) $check2 = sql_get("SELECT `id` FROM `friendly_invitations` WHERE (`toteam` = {$to} OR `fromteam` = {$to}) AND `date` = {$date} AND `time` = '{$time}'", __FILE__, __LINE__);
   if ($check1 || $check2) info(DATETIME_UNAVAILABLE_FOR_FRIENDLY, ERROR);

   sql_query("INSERT INTO `friendly_invitations` (`fromteam`, `toteam`, `date`, `time`, `type`, `accepted`) VALUES ('{$TEAM['id']}', '$to', {$date}, '{$time}', {$type}, 'no')", __FILE__, __LINE__);
   $id = mysql_insert_id();
   $to = sql_get("SELECT `id` FROM `users` WHERE `team` = {$to}", __FILE__, __LINE__);
   if ($to != 0) send_game_message($to, "{_FRIENDLY_MATCH_OFFERED_FROM_} {$TEAM['name']}", "{$TEAM['name']} {_OFFERED_YOU_A_FRIENDLY_MATCH_ON_} {$dateclear} {$friendlystarttime[$time]}:00 h. (CET)!\n{_TO_ACCEPT_IT_CLICK_} [url=friendlyaccept.php?id={$id}]{_HERE_}[/url]\n{_TO_REJECT_IT_CLICK_} [url=friendlyreject.php?id={$id}]{_HERE_}[/url]!");
   info(FRIENDLY_SUCCESFULLY_OFFERED, SUCCESS);
}
else
{
   pagestart(OFFER_INVITATION_TO." {$toname}");
   head(OFFER_INVITATION_TO." {$toname}");
   form_start("friendlyinvitation.php", "POST");
   input("hidden", "to", $to, "", true);
   input("hidden", "do", "1", "", true);
   prnt(FRIENDLYINVITATION_TEXT." {$toname}", true);
   br();
   prnt(STADIUM.": ".radio_box("stad", "1", true).HOME.radio_box("stad", "2").AWAY, true);
   br();
   prnt(DATE.": ");
   select("date", "", true);
   for ($i = 1; $i <= MAX_DAYS_FOR_FRIENDLY_INVITATION; $i++) option($i, date("Y-m-d", time() + 60*60*24*$i), false, true);
   end_select(true);
   prnt(TIME.": ");
   select("time", "", true);
   for ($i = 0; $i < count($friendlystarttime); $i++)
   {
      $val = $friendlystarttime[$i];
      if ($val < 10) option($i, "0"."{$val}:00 h. (CET)", false, true);
      else option($i, "{$val}:00 h. (CET)", false, true);
   }
   end_select(true);
   br(2);
   input("submit", "", OFFER_INVITAITION, "", true);
   form_end();
   pageend();
}
?>
