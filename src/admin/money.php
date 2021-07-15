<?php
/*
File name: money.php
Last change: Mon Jun 09 16:04:07 EEST 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$data = sql_query("SELECT *, (SELECT `id` FROM `users` WHERE `team` = `teams`.`id`) AS `user`, (SELECT `username` FROM `users` WHERE `team` = `teams`.`id`) AS `username` FROM `teams` WHERE `daysminus` > 0 AND `free` = 'no' ORDER BY `daysminus` DESC, `money` ASC", __FILE__, __LINE__);
head("Отбори назад с парите");
table_start();
table_header("id", TEAM, OWNER, MONEY, DAYS, DELETE, SEND_MESSAGE);
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell(create_link("teamdetails.php?id={$row['id']}", $row['name']));
   table_cell(create_link("viewprofile.php?id={$row['user']}", $row['username']));
   table_cell($row['money']);
   table_cell($row['daysminus']);
   table_cell(create_link("admin.php?module=delete&theteam={$row['id']}", DELETE));
   table_cell(create_link("messages.php?do=compose&to={$row['username']}", SEND_MESSAGE));
   table_endrow();
}
table_end();
?>
