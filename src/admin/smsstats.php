<?php
/*
File name: smsstats.php
Last change: 
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$data = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `paytries`.`user`) AS `username` FROM `paytries` ORDER BY `id` DESC", __FILE__, __LINE__);
head("SMS Stats");
table_start();
table_header("id", "time", "ip", "user", "text", "success", "type");
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell($row['time']);
   table_cell(create_link("admin.php?module=userssearch&ip={$row['ip']}", $row['ip']));
   table_cell(create_link("viewprofile.php?id={$row['user']}", $row['username']));
   table_cell($row['text']);
   table_cell($row['success']);
   table_cell($row['type']);
   table_endrow();
}
table_end();
?>
