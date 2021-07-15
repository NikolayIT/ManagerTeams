<?php
/*
File name: offercontract.php
Last change: Sun Jan 27 23:03:53 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
if ($TEAM['money'] <= 0) info(NOT_ENOUGHT_MONEY, ERROR);
mkglobal("offer:id", true);
if ($offer)
{
   $id = sqlsafe($id);
   $player = sql_data("SELECT `name`, `shortname`, `global` FROM `players` WHERE `id` = {$id} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
   if (!$player) info(WRONG_ID, ERROR);
   $name = get_player_name($player['name'], $player['shortname']);
   mkglobal("wage:time:winbonus:signbonus:house:car", true);
   pagestart(OFFER_CONTRACT_FOR.": {$name}");
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
      head(OFFER_SUCCESS);
      prnt(YOU_HAVE_SIGNED_CONTRACT_WITH." {$name} "._FOR." {$time} "._SEASONS, true);
      create_link("playercontracts.php", GO_BACK_TO_PLAYER_CONTRACTS, true);
      $sqltime = sqlsafe($time * $config['matchcount']);
      $sqlwage = sqlsafe($wage);
      $sqlwinbonus = sqlsafe($winbonus);
      $sqlcar = sqlsafe(($car == 'yes' ? 'yes' : 'no'));
      $sqlhouse = sqlsafe(($house == 'yes' ? 'yes' : 'no'));
      $sqlsignbonus = sqlsafe($singbonus);
      if ($singbonus > 0)
      {
         sql_query("UPDATE `teams` SET `money` = `money` - {$sqlsignbonus} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
         add_to_money_history("{_SIGN_BONUS_FOR_} {$name}", -$singbonus, $TEAM['id']);
      }
      sql_query("UPDATE `players` SET `team` = {$TEAM['id']}, `contrtime` = {$sqltime}, `wage` = {$sqlwage}, `winbonus` = {$sqlwinbonus}, `car` = {$sqlcar}, `house` = {$sqlhouse}, `training` = '6' WHERE `id` = {$id}", __FILE__, __LINE__);
   }
   else
   {
      $text = "{$name} ".NOT_LIKE_CURRENT_OFFER."<br>";
      $text .= MAY_BE_HE_WILL_ACCEPT_ONE_OF_THESE_OFFERS.":<br>";
      $text .= OFFER." 1: ".WAGE.": " . ceil(($min + 10) * WAGE_ACC + 100) . " ".FOR_1_SEASON."<br>";
      $text .= OFFER." 2: ".WAGE.": " . ceil(($min + 20) * WAGE_ACC + 100) . " ".FOR_2_SEASONS."<br>";
      $text .= OFFER." 3: ".WAGE.": " . ceil(($min + 50) * WAGE_ACC + 100) . " ".FOR_3_SEASONS." ".WITH_HOTEL_AND_OFFICIAL_CAR."<br>";
      info($text, OFFER_RESULTS, true);
   }
   pageend();
}
else
{
   mkglobal("id", false);
   if (empty($id)) info(WRONG_ID, ERROR);
   $id = sqlsafe($id);
   $player = sql_data("SELECT `name`, `shortname`, `currentform`, `possition`, `global`, `wage`, `winbonus`, `house`, `car`, `age` FROM `players` WHERE `id` = {$id} AND (`team` = {$TEAM['id']} OR `team` = 0)", __FILE__, __LINE__);
   if (!$player) info(WRONG_ID, ERROR);
   $name = get_player_name($player['name'], $player['shortname']);
   pagestart(OFFER_CONTRACT_FOR." {$name}");
   head(OFFER_CONTRACT_FOR." {$name}");
   table_start();
   table_row2(NAME, $name, AGE, $player['age']);
   table_row2(FORM, $player['currentform'], POSSITION, $player['possition']);
   table_row(RATING, create_progress_bar($player['global']), 3);
   table_startrow();
   table_th(OFFER_FOR_PLAYER, 4);
   table_endrow();
   form_start("offercontract.php", "POST");
   input("hidden", "offer", "1", "", true);
   input("hidden", "id", $id, "", true);
   table_row2(WAGE, input("textbox", "wage", $player['wage'])." ˆ / ".GAME_, CONTRACT, select("time").option("1", FOR_1_SEASON, true).option("2", FOR_2_SEASONS).option("3", FOR_3_SEASONS).end_select());
   table_row2(WIN_BONUS, input("textbox", "winbonus", $player['winbonus'])." ˆ", HOTEL, select("house").option("yes", YES, $player['house'] == 'yes').option("no", NO, $player['house'] == 'no').end_select()." ˆ 30,00 / ".DAY_);
   table_row2(SIGN_BONUS, input("textbox", "signbonus", "0")." ˆ", OFFICIAL_CAR, select("car").option("yes", YES, $player['car'] == 'yes').option("no", NO, $player['car'] == 'no').end_select()." ˆ 30,00 / ".DAY_);
   table_end();
   br();
   input("submit", "", OFFER."!", "", true);
   form_end();
   pageend();
}
?>
