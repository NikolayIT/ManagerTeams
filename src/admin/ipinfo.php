<?php
/*
File name: ipinfo.php
Last change: Sun Aug 03 13:38:31 EEST 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);

mkglobal("ip");
$sqlip = sqlsafe($ip);
$ipinfo = sql_data("SELECT * FROM `ips` WHERE `ip` = {$sqlip}", __FILE__, __LINE__);
mkglobal("doban:reason");
if ($doban)
{
   $reason = sqlsafe($reason);
   sql_query("UPDATE `ips` SET `banned` = 'yes', `banreason` = {$reason} WHERE `id` = {$ipinfo['id']}", __FILE__, __LINE__);
   prnt("<b>IP-то е баннато успешно!</b><br><br>");
}

head("Информация за това IP");
table_start();
foreach ($ipinfo as $key => $value) if (!is_numeric($key)) table_row($key, $value);
table_end();
prnt("<br><br>");

head("Потребители влизали от това IP");
$data = sql_query("SELECT `uid`, `ip`,
(SELECT `username` FROM `users` WHERE `id` = `logs`.`uid`) AS `username`,
(SELECT `email` FROM `users` WHERE `id` = `logs`.`uid`) AS `email`,
(SELECT `class` FROM `users` WHERE `id` = `logs`.`uid`) AS `class`,
(SELECT `team` FROM `users` WHERE `id` = `logs`.`uid`) AS `team`,
(SELECT `name` FROM `teams` WHERE `id` = `team`) AS `teamname`
FROM `logs` WHERE `ip` = '{$ipinfo['id']}' GROUP BY `uid` ORDER BY `uid`", __FILE__, __LINE__);
table_start();
table_header(USERNAME, EMAIL, TEAM, "IP", CLASSS);
while ($row = mysql_fetch_assoc($data))
{
   if ($row['uid'] == 0) continue;
   table_startrow();
   table_cell(create_link("viewprofile.php?id={$row['uid']}", $row['username']));
   table_cell($row['email']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_cell(create_link("admin.php?module=ipinfo&ip={$ipinfo['ip']}", $ipinfo['ip']));
   table_cell(create_link("admin.php?module=userssearch&class={$row['class']}", get_user_class_name($row['class'])));
   table_endrow();
}
table_end();
prnt("<br><br>");
/*

$data = sql_query("SELECT *, `invitedby` AS `inviter`, (SELECT `name` FROM `teams` WHERE `id` = `users`.`team`) AS `teamname`, (SELECT `username` FROM `users` WHERE `id` = `inviter`) AS `invitername` FROM `users` WHERE `ip` = {$sqlip} ORDER BY `username` ASC", __FILE__, __LINE__);
table_start();
table_header("ID", USERNAME, EMAIL, TEAM, "Поканен от", "IP", CLASSS);
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell(create_link("viewprofile.php?id={$row['id']}", $row['username']));
   table_cell($row['email']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   if ($row['invitedby']) table_cell(create_link("viewprofile.php?id={$row['invitedby']}", $row['invitername']));
   else table_cell("Никой");
   table_cell(create_link("admin.php?module=ipinfo&ip={$row['ip']}", $row['ip']));
   table_cell(create_link("admin.php?module=userssearch&class={$row['class']}", get_user_class_name($row['class'])));
   table_endrow();
}
table_end();
prnt("<br><br>");
*/

head("Всички SMS-и от това IP");
table_start();
$logs = sql_query("SELECT * FROM `paytries` WHERE `ip` = {$sqlip} ORDER BY `time` DESC", __FILE__, __LINE__);
$been = false;
while($log = mysql_fetch_assoc($logs))
{
   if (!$been)
   {
      $been = true;
      table_startrow();
      foreach ($log as $key => $value) if (!is_numeric($key)) table_th($key);
      table_endrow();
   }
   table_startrow();
   foreach ($log as $value) if (!is_numeric($key)) table_cell($value);
   table_endrow();
}
table_end();
prnt("<br><br>");

head("Бан на това IP");
form_start("admin.php?module=ipinfo&ip={$ip}", "POST");
table_start(false);
table_row("Причина за бана", textarea("", "reason", 50, 6, "", false));
table_th(input("submit", "doban", "Бан на {$ip}"), 2);
table_endrow();
table_end();
form_end();

head("Последните 25 заявки от това IP");
table_start();
$logs = sql_query("SELECT * FROM `logs` WHERE `ip` = '{$ipinfo['id']}' ORDER BY `datetime` DESC LIMIT 25", __FILE__, __LINE__);
$been = false;
while($log = mysql_fetch_assoc($logs))
{
   if (!$been)
   {
      $been = true;
      table_startrow();
      foreach ($log as $key => $value) if (!is_numeric($key)) table_th($key);
      table_endrow();
   }
   table_startrow();
   foreach ($log as $value) if (!is_numeric($key)) table_cell($value);
   table_endrow();
}
table_end();
prnt("<br><br>");

?>
