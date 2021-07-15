<?php
/*
File name: stadium.php
Last change: Tue Jan 08 11:19:11 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id) || $id == $TEAM['id'])
{
   additionaldata(true);
   $id = $STADIUM['id'];
   $thestadiun = $STADIUM;
   $theteam = $TEAM;
   $my = true;
}
else
{
   $id = sqlsafe($id);
   $thestadiun = sql_data("SELECT * FROM `stadiums` WHERE `id` = {$id}", __FILE__, __LINE__);;
   $theteam = sql_data("SELECT * FROM `teams` WHERE `stadium` = {$id}", __FILE__, __LINE__);;
   $my = false;
}
$array = array("eastseats", "westseats", "northseats", "southseats", "vipseats", "parkings", "bars", "toilets", "grass", "lights", "boards", "youthcenter", "roof", "heater", "sprinkler", "fanshop");
pagestart(STADIUM.": {$thestadiun['name']}");
head(STADIUM.": {$thestadiun['name']}");
table_start();
table_header(NAME, LEVEL, "Info", NEXT_LEVEL_PRICE, NEXT_LEVEL_DAYS_NEEDED);
foreach ($array as $value)
{
   $info = stadium_modules($value);
   $level = $thestadiun[$info['field']];
   table_startrow();
   if ($my) table_cell(create_link("{$info['script']}?type={$value}", $info['name']));
   else table_cell($info['name']);
   table_cell($level);
   table_cell(call_user_func("calculate_{$info['functionsid']}", $level));
   table_cell(call_user_func("{$info['functionsid']}_upgrade_price", $level+1)." ˆ");
   table_cell(call_user_func("{$info['functionsid']}_upgrade_time", $level+1)." "._DAYS);
   table_endrow();
}
table_end();
pageend();
?>
