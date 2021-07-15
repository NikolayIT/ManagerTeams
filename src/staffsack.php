<?php
/*
File name: staffsack.php
Last change: Thu Feb 07 18:47:14 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("type");
if (empty($type)) info(INVALID_SCTIPT_CALL, ERROR);
$sqltype = sqlsafe($type);
$has = sql_data("SELECT * FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = {$sqltype}", __FILE__, __LINE__);
if (!$has) info(YOU_DONT_HAVE." {$type}", ERROR);
mkglobal("do");
if ($do == 'yes')
{
   sql_query("UPDATE `staff` SET `team` = 0, `contrtime` = 0, `wage` = 0, `atcourse` = 0, `courseuntil` = '0000-00-00 00:00:00' WHERE `team` = {$TEAM['id']} AND `type` = {$sqltype}", __FILE__, __LINE__);
   info(STAFF_PERSON_SACKED."<br>".create_link("staff.php", GO_TO_STAFF_OVERVIEW), SUCCESS, false);
}
else info(ARE_YOU_SURE_YOU_WANT_TO_SACK." {$has['name']}?<br>".create_link("staffsack.php?type={$type}&do=yes", YES), SACK." {$has['name']}", true);
?>
