<?php
/*
File name: staffcourse.php
Last change: Thu Feb 07 18:04:20 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
if ($TEAM['money'] < 0) info(NOT_ENOUGHT_MONEY, ERROR);
mkglobal("type");
if (empty($type)) info(INVALID_SCTIPT_CALL, ERROR);
$sqltype = sqlsafe($type);
$has = sql_data("SELECT * FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = {$sqltype}", __FILE__, __LINE__);
if (!$has) info(YOU_DONT_HAVE." {$type}", ERR);
if ($has['atcourse'] == 'yes') info(STAFF_PERSON_ALREADY_AT_COURSE, ERROR);
if ($has['rating'] >= 94) info("Не можете да го изпращате на повече курсове!", ERROR);
mkglobal("do");
if ($do == "yes")
{
   sql_query("UPDATE `staff` SET `atcourse` = 'yes', `courseuntil` = ".get_date_time(true, 60*60*24*STAFF_COURSE_TIME)." WHERE `team` = {$TEAM['id']} AND `type` = {$sqltype}", __FILE__, __LINE__);
   $mon = STAFF_COURSE_PRICE;
   sql_query("UPDATE `teams` SET `money` = `money` - {$mon} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
   add_to_money_history("{_STAFF_PERSON_SENT_TO_COURSE_} ({$type})", -$mon, $TEAM['id']);
   info(STAFF_PERSON_SENT_TO_COURSE."<br>".THE_COURSE_WILL_END_ON.": ".get_date_time(true, 60*60*24*STAFF_COURSE_TIME)."<br>".create_link("staff.php", GO_TO_STAFF_OVERVIEW), SUCCESS, false);
}
else info(COURSE_TEXT." ".STAFF_COURSE_TIME." ".COURSE_TEXT2." ".STAFF_COURSE_PRICE."?<br>".create_link("staffcourse.php?type={$type}&do=yes", YES), SEND_TO_COURSE, true);
?>
