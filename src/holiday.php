<?php
/*
File name: holiday.php
Last change: Tue Jan 22 11:27:46 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);

mkglobal("go");
if ($go == 1)
{
   sql_query("UPDATE `users` SET `holiday` = 'yes' WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
   info(YOU_ARE_IN_HOLYDAY_MODE, HOLYDAY_MODE, false);
}

pagestart(HOLYDAY_MODE);
head(HOLYDAY_MODE);
create_button("holiday.php?go=1", ACTIVATE_HOLIDAY_MODE, false, true);
pageend();
?>
