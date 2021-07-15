<?php
/*
File name: leagues.php
Last change: Wed Jan 09 09:00:55 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(LEAGUES);
head(LEAGUES);
create_special_link("leagueranking.php", RANKING, LEAGUES_RANKING_TEXT);
create_special_link("leaguegames.php", GAMES, LEAGUES_GAMES_TEXT);
create_special_link("leaguetopscorers.php", TOPSCORERS, LEAGUES_TOPSCORERS_TEXT);
create_special_link("leaguecards.php", CARDS, LEAGUES_CARDS_TEXT);
create_special_link("leaguehistory.php", HISTORY, LEAGUES_HISTORY_TEXT);
create_special_link("crosstable.php", CROSS_TABLE, CROSS_TABLE_TEXT);
pageend();
?>
