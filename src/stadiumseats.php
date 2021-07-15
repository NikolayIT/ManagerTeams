<?php
/*
File name: stadiumseats.php
Last change: Mon Feb 04 17:32:28 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("type", false);
define("IMAGES_PATH", "./images/");
switch ($type)
{
   case "eastseats": $name = "seats"; $from = 2; $to = 9; $pageabout = EAST_SEATS; $field = "eastseats"; $image = IMAGES_PATH."stadium.jpg"; $cache = "{_EAST_SEATS_}"; break;
   case "westseats": $name = "seats"; $from = 2; $to = 9; $pageabout = WEST_SEATS; $field = "westseats"; $image = IMAGES_PATH."stadium.jpg"; $cache = "{_WEST_SEATS_}"; break;
   case "northseats": $name = "seats"; $from = 2; $to = 9; $pageabout = NORTH_SEATS; $field = "northseats"; $image = IMAGES_PATH."stadium.jpg"; $cache = "{_NORTH_SEATS_}"; break;
   case "southseats": $name = "seats"; $from = 2; $to = 9; $pageabout = SOUTH_SEATS; $field = "southseats"; $image = IMAGES_PATH."stadium.jpg"; $cache = "{_SOUTH_SEATS_}"; break;
   case "vipseats": $name = "vipseats"; $from = 1; $to = 9; $pageabout = VIP_SEATS; $field = "vipseats"; $image = IMAGES_PATH."stadium.jpg"; $cache = "{_VIP_SEATS_}"; break;
   default:
      {
         pagestart(SEATS);
         head(SEATS);
         create_special_link("stadiumseats.php?type=eastseats", EAST_SEATS, EAST_SEATS_TEXT);
         create_special_link("stadiumseats.php?type=westseats", WEST_SEATS, WEST_SEATS_TEXT);
         create_special_link("stadiumseats.php?type=northseats", NORTH_SEATS, NORTH_SEATS_TEXT);
         create_special_link("stadiumseats.php?type=southseats", SOUTH_SEATS, SOUTH_SEATS_TEXT);
         create_special_link("stadiumseats.php?type=vipseats", VIP_SEATS, VIP_SEATS_TEXT);
         pageend();
      }
}
additionaldata(true);
$updating = sql_data("SELECT * FROM `updates` WHERE `table` = 'stadiums' AND `field` = '{$field}' AND `whereid` = '{$STADIUM['id']}'", __FILE__, __LINE__);
$curr = $STADIUM[$field];
if ($curr < $to) $next = $curr + 1;
else $next = 0;
pagestart($pageabout);
mkglobal("upgrade", false);
if ($upgrade == 1)
{
   if ($next == 0) info(YOU_CANT_UPDATE_THIS_ANYMORE, ERROR);
   if ($updating) info(SAME_UPGRADE_IS_CURRENTLY_IN_PROGRESS, ERROR);
   $neededmoney = call_user_func("{$name}_upgrade_price", $next);
   $neededtime = call_user_func("{$name}_upgrade_time", $next);
   $until = get_date_time(true, 60*60*24*$neededtime);
   if ($TEAM['money'] < $neededmoney) info(NOT_ENOUGHT_MONEY, ERROR);
   sql_query("UPDATE `teams` SET `money` = `money` - '{$neededmoney}' WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
   add_to_money_history("{$cache} - {_UPDATED_}!", -$neededmoney, $TEAM['id']);
   sql_query("INSERT INTO `updates` (`table`, `field`, `type`, `value`, `whereid`, `until`) VALUES ('stadiums', '{$field}', '+', '1', '{$STADIUM['id']}', {$until})", __FILE__, __LINE__);
   info(UPGRADE_STRATED_AND_WILL_BE_COMPLETED_ON.": {$until}", SUCCESS);
}
else
{
   head($pageabout);
   prnt(CURRENT_LEVEL.": {$curr}", true);
   prnt(CURRENT." {$name}: ".call_user_func("calculate_{$name}", $curr), true);
   prnt("", true);
   if ($updating)
   {
      prnt("<b>".UPGRADE_IN_PROCESS.":</b>", true);
      prnt(LEVEL.": {$next}", true);
      prnt(NEXT_LEVEL." {$name}: ".call_user_func("calculate_{$name}", $next), true);
      prnt(WILL_COMPLETE_AT.": {$updating['until']}", true);
      br();
   }
   else if ($next > 0)
   {
      prnt(NEXT_LEVEL.": {$next}", true);
      prnt(NEXT_LEVEL." {$name}: ".call_user_func("calculate_{$name}", $next), true);
      prnt(NEXT_LEVEL_PRICE.": ".call_user_func("{$name}_upgrade_price", $next)." ˆ", true);
      prnt(NEXT_LEVEL_DAYS_NEEDED.": ".call_user_func("{$name}_upgrade_time", $next)." "._DAYS, true);
      prnt("<b>".create_hide_link("{$_SERVER['PHP_SELF']}?type={$type}&upgrade=1", UPGRADE_TO_THE_NEXT_LEVEL)."</b>", true);
      br();
   }
   if ($image)
   {
      create_image($image, 0, true);
      br(2);
   }
   head(UPGRADE_LEVELS);
   table_start();
   table_header(LEVEL, SEATS, PRICE, TIME);
   for ($i = $from; $i <= $to; $i++)
   {
      table_startrow();
      table_cell($i);
      table_cell(call_user_func("calculate_{$name}", $i));
      table_cell(call_user_func("{$name}_upgrade_price", $i)." ˆ");
      table_cell(call_user_func("{$name}_upgrade_time", $i)." "._DAYS);
      table_endrow();
   }
   table_end();
}
pageend();
?>
