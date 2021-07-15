<?php
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("do:id", false);
$id = sqlsafe($id);
additionaldata(true);
$player = sql_data("SELECT * FROM `players` WHERE `id` = {$id}", __FILE__, __LINE__);
if ($player['injured'] < 1) info("Your player is not injured!", ERROR);
if ($STADIUM['hospital'] < 1) info(HEAL_CANT, ERROR);

if ($do)
{
   sql_query("UPDATE `players` SET `injured` = 0 WHERE `id` = {$id}", __FILE__, __LINE__);
   sql_query("UPDATE `stadiums` SET `hospital` = `hospital` - 1 WHERE `id` = {$STADIUM['id']}", __FILE__, __LINE__);
   info(HEAL_SUCCESS, SUCCESS);
}
else info(HEAL_INFO."<br>".create_link("heal.php?id={$id}&do=1", HEAL_YES), HEAL);
?>
