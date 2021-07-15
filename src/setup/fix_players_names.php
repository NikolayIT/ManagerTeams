<?php
define("IN_GAME", true);
include("common.php");
/*
$players = sql_query("SELECT `id`, `name` FROM `players` ORDER BY `id`", __FILE__, __LINE__);
while ($player = mysql_fetch_assoc($players))
{
$names = explode(" ", $player['name']);
$name = trim($names[0]) . " " . trim($names[1]);
$name = sqlsafe($name);
sql_query("UPDATE `players` SET `name` = {$name} WHERE `id` = {$player['id']}", __FILE__, __LINE__);
}
*/

$teams = sql_query("SELECT `id`, `name`, `free` FROM `teams` ORDER BY `id`", __FILE__, __LINE__);
while ($theteam = mysql_fetch_assoc($teams))
{
   if ($theteam['free'] == "yes")
   {
      $names = explode(" ", $theteam['name']);
      if ($names[1]) $name = trim(trim($names[0]) . " " . trim($names[1]));
      else $name = trim($theteam['name']);
      $name = sqlsafe($name);
      sql_query("UPDATE `teams` SET `name` = {$name} WHERE `id` = {$theteam['id']}", __FILE__, __LINE__, true);
   }
}
?>
