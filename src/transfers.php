<?php
/*
File name: transfers.php
Last change: Tue Jan 08 10:14:30 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(TRANSFERS);
head(TRANSFER_HISTORY);
create_special_link("transferlist.php", TRANSFER_LIST, TRANSFER_LIST_TEXT);
create_special_link("playersview.php?type=sell", SELL_PLAYER, SELL_PLAYER_TEXT);
create_special_link("activetransfers.php?from={$TEAM['id']}", SELLING_PLAYERS, SELLING_PLAYERS_TEXT);
create_special_link("activetransfers.php?to={$TEAM['id']}", BUYING_PLAYERS, BUYING_PLAYERS_TEXT);
create_special_link("activetransfers.php?all=1", TRANSFER_HISTORY, TRANSFER_HISTORY_TEXT);
create_special_link("activetransfers.php?best=1", BEST_TRANSFERS_PRICE_SHORT, BEST_TRANSFERS_PRICE);
create_special_link("activetransfers.php?best=2", BEST_TRANSFERS_RATING_SHORT, BEST_TRANSFERS_RATING);
create_special_link("shortlist.php", SHORTLIST, SHORTLIST_TEXT);
pageend();
?>
