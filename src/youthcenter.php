<?php
/*
File name: youthcenter.php
Last change: Tue Jun 24 15:27:54 EEST 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("do:possition");
additionaldata(true);
if ($STADIUM['youthcenter'] <= 0) info("Yougthcenter not built!", ERROR);
if($do && !(array_search($possition, $possitions, false) === false))
{
   if ($TEAM['money'] < GET_PLAYER_FROM_YOUTHCENTER_PRICE) info(NOT_ENOUGHT_MONEY, ERROR);
   // get first free number
   $nums = array();
   $numbers = sql_array("SELECT `number` FROM `players` WHERE `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
   foreach ($numbers as $num) $nums[$num] = true;
   $number = 0;
   for($i = 1; $i <= 99; $i++) if (!$nums[$i]) { $number = $i; break; }
   // create player
   $possition = sqlsafe($possition);
   $lid = sql_get("SELECT `id` FROM `match_type` WHERE `name` = ".sqlsafe($TEAM['league']), __FILE__, __LINE__);
   $name = get_random_name($USER['country']);
   generate_player($USER['country'], $name, 18, $possition, $TEAM['id'], get_youthcenter($STADIUM['youthcenter']), $number, 0, $lid, $TEAM['cup']);
   add_to_money_history("New player from the yougthcenter", -GET_PLAYER_FROM_YOUTHCENTER_PRICE, $TEAM['id'], true);
   info("Player bougth successfuly!<br>The name of your new player is {$name}", SUCCESS);
}
pagestart(GET_A_NEW_PLAYER_FROM_THE_YOUTHCENTER);
head(GET_A_NEW_PLAYER_FROM_THE_YOUTHCENTER);
prnt("Играч от ДЮШ идва в началото на всеки шампионат безплатно.<br>
Ако не ви се чака от тук имате възможност да вземете играч от ДЮШ на поста, на който искате, срещу определена сума пари.<br>
Взимането на играч от тук няма да попречи на безплатното му получаване (в началото на всеки шампионат).<br>
Цената на взимането на нов играч от ДЮШ е ".GET_PLAYER_FROM_YOUTHCENTER_PRICE." ".MONEY_SIGN, true);
br();
prnt(POSSITION.": ".get_possitions_form("youthcenter.php?do=1", "possition", $possition, "Get a new player", "POST"));
form_end();
pageend();
?>
