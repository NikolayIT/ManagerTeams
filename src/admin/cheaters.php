<?php
/*
File name: cheaters.php
Last change: 
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$data = sql_query("SELECT `ip`, COUNT(`id`) AS `number`
FROM `users` GROUP BY `ip` ORDER BY `number` DESC LIMIT 30", __FILE__, __LINE__);
head("Cheaters");
table_start();
table_header("IP", "Брой потребители с това IP");
while ($row = mysql_fetch_assoc($data))
{
   $totalpaytries =  $row['paytries_success'] + $row['paytries_unsuccess'];
   table_startrow();
   table_cell(create_link("admin.php?module=userssearch&ip={$row['ip']}", $row['ip']));
   table_cell($row['number']);
   table_endrow();
}
table_end();
?>
