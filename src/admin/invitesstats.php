<?php
/*
File name: smsstats.php
Last change: 
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$data = sql_query("SELECT *, (SELECT `id` FROM `users` WHERE `email` = `invitetries`.`tomail`) AS `invid`, (SELECT `username` FROM `users` WHERE `email` = `invitetries`.`tomail`) AS `invname` FROM `invitetries` ORDER BY `id` DESC LIMIT 100", __FILE__, __LINE__);
head("Invites stats");
table_start();
table_header("id", "time", "userid", "ip", "fromname", "frommail", "toname", "tomail", "touser");
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell($row['time']);
   table_cell($row['userid']);
   table_cell($row['ip']);
   table_cell($row['fromname']);
   table_cell($row['frommail']);
   table_cell($row['toname']);
   table_cell($row['tomail']);
   table_cell("<a href=\"viewprofile.php?id={$row['invid']}\">{$row['invname']}</a>");
   table_endrow();
}
table_end();
?>
