<?php
/*
File name: advboards.php
Last change: Sat Jan 12 14:13:13 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(TEAM_SPONSORS);
head(TEAM_SPONSORS);
additionaldata(true);
table_start();
table_header("#", NAME, BANNER, DAYS_LEFT, MONEY);
for ($i = 1; $i <= calculate_boards($STADIUM['boards']); $i++)
{
   $advdata = sql_data("SELECT * FROM `advboards` WHERE `team` = {$TEAM['id']} AND `board` = {$i}", __FILE__, __LINE__);
   table_startrow();
   table_cell($i);
   if ($advdata['adv'] != 0)
   {
      $advinfo = sql_data("SELECT * FROM `advertising` WHERE `id` = '{$advdata['adv']}'", __FILE__, __LINE__);
      table_cell(create_link($advinfo['url'], $advinfo['name']));
      table_cell(create_link($advinfo['url'], create_image($advinfo['img'])));
      table_cell("{$advdata['left']} / {$advinfo['days']}");
      table_cell($advinfo['money']);
   }
   else table_cell("<center>".create_link("advoffers.php?slot={$i}", VIEW_SPONSORSHIP_OFFERS)."</center>", 4);
   table_endrow();
}
table_end();
pageend();
?>
