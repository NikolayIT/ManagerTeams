<?php
/*
File name: friendlypool.php
Last change: Sun Jan 20 11:06:00 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("type");
if ($type == "tome")
{
   $where = "WHERE `toteam` = {$TEAM['id']}";
   $caption = INVITATIONS_FOR_ME;
}
else if ($type == "fromme")
{
   $where = "WHERE `fromteam` = {$TEAM['id']}";
   $caption = MY_INVITATIONS;
}
else
{
   $where = "WHERE `accepted` = 'no' AND `toteam` = 0";
   $caption = FRIENDLY_POOL;
}

$data = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `friendly_invitations`.`fromteam`) AS `fromteamname`, (SELECT `name` FROM `teams` WHERE `id` = `friendly_invitations`.`toteam`) AS `toteamname` FROM `friendly_invitations` {$where} ORDER BY `date` DESC, `time` DESC", __FILE__, __LINE__);
if (mysql_num_rows($data) == 0) info(NO_INVITATIONS_IN_THIS_LIST, $caption, false);

pagestart($caption);
head($caption);
table_start();
if ($type == "fromme") table_header(FROM_TEAM, TO_TEAM, DATE, TIME, TYPE, ACCEPT);
else if ($type == "tome") table_header(FROM_TEAM, TO_TEAM, DATE, TIME, TYPE, ACCEPTED, ACCEPT);
else table_header(FROM_TEAM, TO_TEAM, DATE, TIME, TYPE, ACCEPT);
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell(create_link("teamdetails.php?id={$row['fromteam']}", $row['fromteamname']));
   if ($row['toteam'] == 0) table_cell(ALL);
   else table_cell(create_link("teamdetails.php?id={$row['toteam']}", $row['toteamname']));
   table_cell($row['date']);
   $val = $friendlystarttime[$row['time']];
   if ($val < 10) table_cell("0{$val}:00 h. (CET)");
   else table_cell("{$val}:00 h. (CET)");
   table_cell($row['type']);
   if ($type == "fromme" || $type == "tome") table_cell($row['accepted']);
   if ($type != "fromme")
   {
       if ($row['accepted'] == 'no') table_cell(create_link("friendlyaccept.php?id={$row['id']}", ACCEPT));
       else table_cell(ACCEPT);
   }
   table_endrow();
}
table_end();
pageend();
?>
