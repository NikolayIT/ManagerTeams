<?php
/*
File name: index.php
Last change: Thu Jan 24 11:36:27 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
$frcups = sql_query("SELECT *
FROM `team_history`
WHERE `event` LIKE '1 - %'", __FILE__, __LINE__);
while ($frcup = mysql_fetch_array($frcups))
{
   $name = strip_tags(str_replace("1 - ", "", $frcup['event']));
   add_to_trophy_history($name, "friendly", $frcup['team'], $frcup['season']);
}
$frcups = sql_query("SELECT *
FROM `team_history`
WHERE `event` LIKE '%{_HAS_WON_IN_}%'", __FILE__, __LINE__);
while ($frcup = mysql_fetch_array($frcups))
{
   $name = substr($frcup['event'], strpos($frcup['event'], "{_HAS_WON_IN_} ") + strlen("{_HAS_WON_IN_} "));
   if ($name == "ManagerTeams CUP") $type = "cup";
   else if (strstr($name, "A") !== false)  $type = "league_A";
   else if (strstr($name, "B") !== false)  $type = "league_B";
   else if (strstr($name, "C") !== false)  $type = "league_C";
   else if (strstr($name, "D") !== false)  $type = "league_D";
   else if (strstr($name, "E") !== false)  $type = "league_E";
   else if (strstr($name, "F") !== false)  $type = "league_F";
   else if (strstr($name, "G") !== false)  $type = "league_G";
   else if (strstr($name, "H") !== false)  $type = "league_H";
   add_to_trophy_history($name, $type, $frcup['team'], $frcup['season']);
}
?>
