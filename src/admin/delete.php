<?php
/*
File name: delete.php
Last change: 
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
mkglobal("do:theteam:theuser:theip");
if ($do && $theteam)
{
   sql_query("UPDATE `teams` SET `free` = 'yes', `daysminus` = 0, `money` = 0 WHERE `id` = {$theteam} LIMIT 1", __FILE__, __LINE__);
   sql_query("DELETE FROM `users` WHERE `team` = {$theteam} LIMIT 1", __FILE__, __LINE__);
   info("Отборът и потребителя са изтрити.", SUCCESS);
}
if ($theteam)
{
   $teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$theteam}", __FILE__, __LINE__);
   info("Сигурни ли сте че искате да изтриете {$teamname}?<br><br>".create_link("admin.php?module=delete&do=1&theteam={$theteam}", "Да, изтрий този отбор и потребителя"), "Триене на отбор");
}
?>
