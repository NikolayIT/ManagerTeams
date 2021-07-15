<?php
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$data = sql_query("SELECT *,
(SELECT `username` FROM `users` WHERE `id` = `transfer_reports`.`from`) AS `fromname`
FROM `transfer_reports` ORDER BY `id` DESC", __FILE__, __LINE__);
head(TRANSFERS);
table_start();
table_header("id", "tr_id", TIME, FROMM, REASON, CHECK);
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell($row['transfer']);
   table_cell($row['time']);
   table_cell(create_link("viewprofile.php?id={$row['from']}", $row['fromname']));
   table_cell($row['reason']);
   table_cell(create_link("admin.php?module=checktransfer&id={$row['transfer']}", CHECK));
   table_endrow();
}
table_end();
?>
