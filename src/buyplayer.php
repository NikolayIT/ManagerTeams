<?php
/*
File name: buyplayer.php
Last change: Tue Jan 15 20:47:25 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

$staff = sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'scout'", __FILE__, __LINE__);
if (!$staff) info("Нямате скаут и не можете да продавате или купувате играчи!", ERROR);

if($USER['registred'] > get_date_time(false, -TIME_WEEK)) info("You must be registred before at least 1 week to use the transfers", ERROR);
if ($TEAM['money'] <= 0) info(NOT_ENOUGHT_MONET_TO_BUY_PLAYER, ERROR);

$players_num = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = {$TEAM['id']}", __FILE__, __LINE__);
if ($players_num > MAXIMUM_PLAYERS_IN_TEAM) info(TOO_MANY_PLAYERS, ERROR);

mkglobal("id");
if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
$id = sqlsafe($id);

$player = sql_data("SELECT *, (SELECT `id` FROM `transfers` WHERE `player` = {$id}) AS `transfersid` FROM `players` WHERE `id` = {$id} AND `team` != {$TEAM['id']} AND (SELECT `id` FROM `transfers` WHERE `player` = {$id}) IS NOT NULL AND (SELECT `available` FROM `transfers` WHERE `player` = {$id}) = 'yes' LIMIT 1", __FILE__, __LINE__);
if (!$player) info(WRONG_ID, ERROR);
$name = get_player_name($player['name'], $player['shortname']);
$transferinfo = sql_data("SELECT *,
(SELECT `id` FROM `users` WHERE `team` = `transfers`.`fromteam`) AS `userid`,
(SELECT `name` FROM `teams` WHERE `id` = `transfers`.`fromteam`) AS `fromteamname`,
(SELECT `name` FROM `teams` WHERE `id` = `transfers`.`offerteam`) AS `teamname`
FROM `transfers` WHERE `id` = {$player['transfersid']}", __FILE__, __LINE__);

if ($transferinfo['userid'] != 0)
{
   $sellerips = sql_array("SELECT `ip` FROM `logs` WHERE `uid` = '{$transferinfo['userid']}' GROUP BY `ip` ORDER BY `ip`", __FILE__, __LINE__);
   $cuseripd = sql_array("SELECT `ip` FROM `logs` WHERE `uid` = '{$USER['id']}' GROUP BY `ip` ORDER BY `ip`", __FILE__, __LINE__);
   $common = array_intersect($sellerips, $cuseripd);
   if (count($common) > 0) info("Не можете да предлагате оферти за този играч, защото сте влизали от едно и също IP с продавача!", ERROR);
}

mkglobal("offer:price", true);
$price = (int)$price;
if ($offer && is_integer($price))
{
   if ($TEAM['money'] < $price) info(NOT_ENOUGHT_MONET_TO_BUY_PLAYER, ERROR);
   if ($transferinfo['bestoffer'] >= $price) info(HIGHER_OFFER_TO_BUY_PLAYER, ERROR);
   $price = sqlsafe($price);
   mkglobal("wage:time:winbonus:signbonus:house:car", true);
   pagestart("Buying {$name}");
   $min = $player['global']*2;
   $wage2 = floor($wage / WAGE_ACC);
   $house2 = ($house == "yes" ? 10 : 0);
   $car2 = ($car == "yes" ? 10 : 0);
   $singbonus2 = floor($singbonus / 100.0);
   $winbonus2 = floor($winbonus / 20.0);
   $time2 = $time * 10;
   $curr = $wage2 + $house2 + $car2 + $signbonus2 + $winbonus2 - $time2;
   if ($curr >= $min)
   {
      $sqltime = sqlsafe($time * $config['matchcount']);
      $sqlwage = sqlsafe($wage);
      $sqlwinbonus = sqlsafe($winbonus);
      $sqlcar = sqlsafe(($car == 'yes' ? 'yes' : 'no'));
      $sqlhouse = sqlsafe(($house == 'yes' ? 'yes' : 'no'));
      $sqlsignbonus = sqlsafe($signbonus);
      sql_query("UPDATE `transfers` SET `bestoffer` = {$price}, `offerteam` = {$TEAM['id']}, `contrtime` = {$sqltime}, `wage` = {$sqlwage}, `winbonus` = {$sqlwinbonus}, `car` = {$sqlcar}, `house` = {$sqlhouse}, `signbonus` = {$sqlsignbonus} WHERE `id` = {$transferinfo['id']}", __FILE__, __LINE__);
      $data = sql_query("SELECT * FROM `shortlist` WHERE `player` = {$id}", __FILE__, __LINE__);
      while ($row = mysql_fetch_assoc($data))
      {
         if ($row['user'] != $USER['id']) send_game_message($row['user'], "Transfer offer for {$name}", "A new bid {$price} has been made on player {$name} of team [url=teamdetails.php?id={$TEAM['id']}]{$TEAM['name']}![/url]\n[url=buyplayer.php?id={$id}]Click here to offer higher offer[/url]");
      }
      send_game_message($transferinfo['userid'], "Transfer offer for {$name}", "A new bid {$price} has been made on player {$name} of team [url=teamdetails.php?id={$TEAM['id']}]{$TEAM['name']}![/url]");
      $duplicate = sql_get("SELECT `id` FROM `shortlist` WHERE `user` = {$USER['id']} AND `player` = {$id}", __FILE__, __LINE__);
      if (!$duplicate) sql_query("INSERT INTO `shortlist` (`user`, `player`) VALUES ({$USER['id']}, {$id})", __FILE__, __LINE__);
      info(SUCCESSFULLY_MADE_OFFER." {$name}", SUCCESS, true);
   }
   else
   {
      $text = "{$name} ".NOT_LIKE_CURRENT_OFFER."<br>";
      $text .= MAY_BE_HE_WILL_ACCEPT_ONE_OF_THESE_OFFERS.":<br>";
      $text .= OFFER." 1: ".WAGE.": " . ceil(($min + 10) * WAGE_ACC + 10) . " ".FOR_1_SEASON."<br>";
      $text .= OFFER." 2: ".WAGE.": " . ceil(($min + 20) * WAGE_ACC + 10) . " ".FOR_2_SEASONS."<br>";
      $text .= OFFER." 3: ".WAGE.": " . ceil(($min + 50) * WAGE_ACC + 10) . " ".FOR_3_SEASONS." ".WITH_HOTEL_AND_OFFICIAL_CAR."<br>";
      info($text, SORRY, true);
   }
}
else
{
   pagestart(BUYING." {$name}");
   head(BUYING." {$name}");
   table_start();
   table_startrow();
   table_th(NAME);
   table_player_name($player['id'], $player['name'], $player['shortname']);
   table_th(AGE);
   table_cell($player['age']);
   table_endrow();
   table_player_row($player['id'], 4);
   table_row2(FORM, $player['currentform'], POSSITION, $player['possition']);
   table_row(RATING, create_progress_bar($player['global']), 3);
   if ($transferinfo['offerteam']) table_row(RATING, create_progress_bar($player['global']), 3);
   if ($transferinfo['offerteam'])
   {
      $newoffer = $transferinfo['bestoffer'] + 1000;
      table_row2(BEST_OFFER, $transferinfo['bestoffer'], OFFER_FROM, create_link("teamdetails.php?id={$transferinfo['offerteam']}", $transferinfo['teamname']));
   }
   else
   {
      $newoffer = $transferinfo['bestoffer'];
      table_row2(ASKING_PRICE, $transferinfo['bestoffer'], OFFERS, NO_OFFERS);
   }
   table_row2(VALID_UNTIL, $transferinfo['until'], FROM_TEAM, create_link("teamdetails.php?id={$transferinfo['fromteam']}", $transferinfo['fromteamname']));
   table_end();
   br();
   form_start("buyplayer.php", "POST");
   input("hidden", "offer", "1", "", true);
   input("hidden", "id", $id, "", true);
   table_start();
   table_row(OFFER, input("textbox", "price", $newoffer)." €", 3);
   table_row2(WAGE, input("textbox", "wage", $player['wage'])." € / ".GAME_, CONTRACT, select("time").option("1", FOR_1_SEASON, true).option("2", FOR_2_SEASONS).option("3", FOR_3_SEASONS).end_select());
   table_row2(WIN_BONUS, input("textbox", "winbonus", $player['winbonus'])." €", HOTEL, select("house").option("yes", YES, $player['house'] == 'yes').option("no", NO, $player['house'] == 'no').end_select()." € 30,00 / ".DAY_);
   table_row2(SIGN_BONUS, input("textbox", "signbonus", "0")." €", OFFICIAL_CAR, select("car").option("yes", YES, $player['car'] == 'yes').option("no", NO, $player['car'] == 'no').end_select()." € 30,00 / ".DAY_);
   table_end();
   br();
   input("submit", "", OFFER."!", "", true);
   form_end();
   pageend();
}
?>
