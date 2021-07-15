<?php
/*
File name: stadiumupgrades.php
Last change: Mon Feb 04 17:32:23 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("type", false);
define("IMAGES_PATH", "./images/");
// define("IMAGES_PATH", "./images/");
switch ($type)
{
   case "parkings": $from = 1; $to = 9; $pageabout = PARKINGS; $name = "parkings"; $field = "parking"; $image = IMAGES_PATH."parkings.jpg"; $cache = "{_PARKINGS_}"; break;
   case "bars": $from = 1; $to = 9; $pageabout = BARS; $name = "bars"; $field = "bars"; $image = IMAGES_PATH."bars.jpg"; $cache = "{_BARS_}"; break;
   case "toilets": $from = 1; $to = 9; $pageabout = TOILETS; $name = "toilets"; $field = "toilets"; $image = IMAGES_PATH."toilets.jpg"; $cache = "{_TOILETS_}"; break;
   case "grass": $from = 2; $to = 9; $pageabout = GRASS; $name = "grass"; $field = "grass"; $image = IMAGES_PATH."grass.jpg"; $cache = "{_GRASS_}"; break;
   case "lights": $from = 2; $to = 9; $pageabout = LIGHTS; $name = "lights"; $field = "lights"; $image = IMAGES_PATH."lights.jpg"; $cache = "{_LIGHTS_}"; break;
   case "boards": $from = 2; $to = 9; $pageabout = BOARDS; $name = "boards"; $field = "boards"; $image = IMAGES_PATH."boards.jpg"; $cache = "{_BOARDS_}"; break;
   case "youthcenter": $from = 1; $to = 9; $pageabout = YOUTHCENTER; $name = "youthcenter"; $field = "youthcenter"; $image = IMAGES_PATH."youthcenter.jpg"; $cache = "{_YOUTHCENTER_}"; break;
   case "roof": $from = 1; $to = 9; $pageabout = ROOF; $name = "roof"; $field = "roof"; $image = IMAGES_PATH."roof.jpg"; $cache = "{_ROOF_}"; break;
   case "heater": $from = 1; $to = 9; $pageabout = HEATER; $name = "heater"; $field = "heater"; $image = IMAGES_PATH."stadium.jpg"; $cache = "{_HEATER_}"; break;
   case "sprinkler": $from = 1; $to = 9; $pageabout = SPRINKLER; $name = "sprinkler"; $field = "sprinkler"; $image = IMAGES_PATH."sprinkler.jpg"; $cache = "{_SPRINKLER_}"; break;
   case "fanshop": $from = 1; $to = 9; $pageabout = FAN_SHOP; $name = "fanshop"; $field = "fanshop"; $image = IMAGES_PATH."fanshop.jpg"; $cache = "{_FANSHOP_}"; break;
   case "hospital": $from = 1; $to = 9; $pageabout = HOSPITAL; $name = "hospital"; $field = "hospital"; $image = IMAGES_PATH."hospital.jpg"; $cache = "{_HOSPITAL_}"; break;
   default:
      {
         pagestart(STADIUM);
         head(STADIUM);
         create_special_link("stadium.php", OVERVIEW, STADIUM_OVERVIEW_TEXT);
         create_special_link("changename.php", "Промяна на името", "Промяна на името на отбора и стадиона");
         create_special_link("stadiumseats.php", SEATS, SEATS_TEXT);
         create_special_link("stadiumupgrades.php?type=parkings", PARKINGS, PARKINGS_TEXT);
         create_special_link("stadiumupgrades.php?type=bars", BARS, BARS_TEXT);
         create_special_link("stadiumupgrades.php?type=toilets", TOILETS, TOILETS_TEXT);
         create_special_link("stadiumupgrades.php?type=grass", GRASS, GRASS_TEXT);
         create_special_link("stadiumupgrades.php?type=lights", LIGHTS, LIGHTS_TEXT);
         create_special_link("stadiumupgrades.php?type=boards", BOARDS, BOARDS_TEXT);
         create_special_link("stadiumupgrades.php?type=youthcenter", YOUTHCENTER, YOUTHCENTER_TEXT);
         create_special_link("stadiumupgrades.php?type=roof", ROOF, ROOF_TEXT);
         create_special_link("stadiumupgrades.php?type=heater", HEATER, HEATER_TEXT);
         create_special_link("stadiumupgrades.php?type=sprinkler", SPRINKLER, SPRINKLER_TEXT);
         create_special_link("stadiumupgrades.php?type=fanshop", FAN_SHOP, FAN_SHOP_TEXT);
         create_special_link("stadiumupgrades.php?type=hospital", HOSPITAL, HOSPITAL_TEXT);
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
   prnt(CURRENT." {$pageabout}: ".call_user_func("calculate_{$name}", $curr), true);
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
      prnt(NEXT_LEVEL_PRICE.": ".call_user_func("{$name}_upgrade_price", $next)." €", true);
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
   table_header(LEVEL, $pageabout, PRICE, TIME);
   for ($i = $from; $i <= $to; $i++)
   {
      table_startrow();
      table_cell($i);
      table_cell(call_user_func("calculate_{$name}", $i));
      table_cell(call_user_func("{$name}_upgrade_price", $i)." €");
      table_cell(call_user_func("{$name}_upgrade_time", $i)." "._DAYS);
      table_endrow();
   }
   table_end();
}
pageend();
?>
