<?php
/*
File name: players.php
Last change: Mon Jan 07 22:52:47 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(PLAYERS);
head(PLAYERS);
create_special_link("playersview.php", OVERVIEW, PLAYERS_OVERVIEW_TEXT);
create_special_link("playerratings.php", PLAYER_RATINGS, PLAYER_RATINGS_TEXT);
create_special_link("training.php", TRAINING, TRAINING_TEXT);
create_special_link("playersstats.php", STATISTICS, STATISTICS_TEXT);
create_special_link("playercontracts.php", CONTRACTS, CONTRACTS_TEXT);
create_special_link("playersview.php?type=injured", INJURED, INJURED_TEXT);
create_special_link("playersview.php?type=banned", BANNED, BANNED_TEXT);
create_special_link("playernicknames.php", NICKNAMES, NICKNAMES_TEXT);
create_special_link("playernumbers.php", NUMBERS, NUMBERS_TEXT);
create_special_link("playernotes.php", NOTES, PLAYER_NOTES);
create_special_link("playerpictures.php", PICTURES, PICTURES_TEXT);
pageend();
?>
