<?php
/*
File name: settraining.php
Last change: Mon Feb 04 09:34:30 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("auto", false);
if (!empty($auto))
{
   $staff = sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'coach'", __FILE__, __LINE__);
   if ($staff) automatic_training($TEAM['id']);
   else info("Нямате треньор и не можете да използвате автоматичната тренировка!", ERROR);
}
else foreach ($_POST as $key => $value)
{
   if (substr($key, 0, 2) == "p_")
   {
      $key = sqlsafe(str_replace("p_", "", $key));
      $value = sqlsafe($value);
      sql_query("UPDATE `players` SET `training` = {$value} WHERE `id` = {$key} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
   }
}
info(TRAINING_SUCCESSFULY_SET, SUCCESS);
?>
