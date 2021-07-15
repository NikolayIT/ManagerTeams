<?php
/*
File name: playercontracts.php
Last change: Mon Jan 28 08:24:08 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
$players = sql_query("SELECT `id`, `name`, `shortname`, `possition`, `contrtime`, `wage`, `car`, `house`, `winbonus`, `age` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_numrows($players) == 0) info(YOU_DONT_HAVE_ANY_PLAYERS, ERROR, false);
pagestart(PLAYER__CONTRACTS);
head(PLAYER__CONTRACTS);
table_start();
table_header("", NAME, AGE, CONTRACT_LEFT, WAGE, div("img_player_house", HOTEL), div("img_player_car", OFFICIAL_CAR), WIN_BONUS, CONTRACT, SACK);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   table_cell($row['age']);
   table_cell($row['contrtime']);
   table_cell($row['wage']." ˆ");
   if ($row['house'] == 'yes') table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   if ($row['car'] == 'yes') table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   table_cell($row['winbonus']." ˆ");
   table_cell(create_link("offercontract.php?id={$row['id']}", CONTRACT));
   table_cell(create_link("sackplayer.php?id={$row['id']}", SACK));
   table_endrow();
   table_player_row($row['id'], 10);
}
table_end();
pageend();
?>
