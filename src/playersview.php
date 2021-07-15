<?php
/*
File name: playersview.php
Last change: Tue Jan 29 20:53:27 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id)) $id = $TEAM['id'];
$id = sqlsafe($id);
$teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$id}", __FILE__, __LINE__);
mkglobal("type", false);
if ($type == "injured")
{
   $where = "WHERE `team` = {$id} AND `injured` > 0";
   $caption = INJURED_PLAYERS." ({$teamname})";
   $errtext = NO_INJURED_PLAYERS;
}
else if ($type == "banned")
{
   $where = "WHERE `team` = {$id} AND (`banleague` > 0 OR `bancup` > 0)";
   $caption = BANNED_PLAYERS." ({$teamname})";
   $errtext = NO_BANNED_PLAYERS;
}
else if ($type == "sell")
{
   $where = "WHERE `team` = {$id}";
   $caption = SELL_PLAYERS." ({$teamname})";
   $errtext = YOU_DONT_HAVE_ANY_PLAYERS;
   $addtext = "<b>".SELL_PLAYERS_INFO."</b><br><br>";
}
else
{
   $where = "WHERE `team` = {$id}";
   $caption = PLAYERS_OVERVIEW." ({$teamname})";
   $errtext = YOU_DONT_HAVE_ANY_PLAYERS;
}
$players = sql_query("SELECT `id`, `name`, `shortname`, `possition`, `number`, `country`, `currentform`, `injured`, `banleague`, `bancup`, `global`, `age`, (SELECT `id` FROM `transfers` WHERE `player` = `players`.`id` AND `available` = 'yes') AS `transfer` FROM `players` {$where} ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_numrows($players) == 0) info($errtext, $caption, false);
pagestart($caption);
head($caption);
prnt($addtext);
table_start();
if ($type == "sell") table_header("", NUMBER, NAME, COUNTRY, AGE, div("img_player_injured", INJURED), FORM, div("img_player_transfer"), RATING, SELL);
else if ($type == "injured") table_header("", NUMBER, NAME, COUNTRY, AGE, div("img_player_injured", INJURED), div("img_player_locked_league", BANNED), div("img_player_locked_cup", BANNED), FORM, div("img_player_transfer", TRANSFER_LIST), RATING, "Heal");
else table_header("", NUMBER, NAME, COUNTRY, AGE, div("img_player_injured", INJURED), div("img_player_locked_league", BANNED), div("img_player_locked_cup", BANNED), FORM, div("img_player_transfer", TRANSFER_LIST), RATING);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
   table_cell($row['number']);
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if(!$countries[$row['country']])
   {
      $country = sql_data("SELECT `flagpic`, `name` FROM `countries` WHERE `id` = {$row['country']}", __FILE__, __LINE__);
      $countries[$row['country']] = create_image("images/flags/{$country['flagpic']}", 20)." {$country['name']}";
   }
   table_cell($countries[$row['country']]);
   table_cell($row['age']);
   if ($row['injured'] > 0)
   {
      $staff = sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'doctor'", __FILE__, __LINE__);
      if (!$staff) table_cell(popup("!!!", "Нямате доктор и затова не можете да виждате дните, за които е контузен играча."));
      else table_cell("<b>".$row['injured']."</b>");
   }
   else table_cell(div("img_plain_no", NO));
   if ($type != "sell") table_cell($row['banleague']);
   if ($type != "sell") table_cell($row['bancup']);
   table_cell($row['currentform']);
   if ($row['transfer']) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   table_cell(create_progress_bar($row['global']));
   if ($type == "sell") table_cell(create_link("sellplayer.php?id={$row['id']}", SELL));
   if ($type == "injured") table_cell(create_link("heal.php?id={$row['id']}", "Heal"));
   table_endrow();
   table_player_row($row['id'], 11);
}
table_end();
pageend();
?>
