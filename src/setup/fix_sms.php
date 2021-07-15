<?php
include ("./config.php");
include ("./include/cleanup.php");
include ("./include/functions.php");
include ("./include/sql.php");
include ("./include/adv.php");
include ("./include/interface.php");
include ("./include/players.php");
include ("./include/stadium.php");

date_default_timezone_set(TIMEZONE);
mysqlconnect();

$smss = sql_query("SELECT *, (SELECT `team` FROM `users` WHERE `id` = `paytries2`.`user`) AS `team`  FROM `paytries2`", __FILE__, __LINE__);
while ($sms = mysql_fetch_assoc($smss))
{
   if ($sms['type'] == "money")
   {
      add_to_money_history("SMS -> money", 2000000, $sms['team'], true);
   }
   else
   {
      $time = sql_get("SELECT ADDTIME((SELECT IF(`vipuntil` < NOW(), NOW(), `vipuntil`) FROM `users` WHERE `id` = '{$sms['user']}'), '30 0:0:0')", __FILE__, __LINE__);
      if ($time) sql_query("UPDATE `users` SET `class` = '3', `vipuntil` = '{$time}' WHERE `id` = '{$sms['user']}'", __FILE__, __LINE__);
   }
}
?>
