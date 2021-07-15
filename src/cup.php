<?php
/*
File name: cup.php
Last change: Wed Jan 09 09:12:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(CUP);
head(CUP);
create_special_link("cupgames.php", GAMES, CUP_GAMES_TEXT);
create_special_link("cuptopscorers.php", TOPSCORERS, CUP_TOPSCORERS_TEXT);
create_special_link("cupcards.php", CARDS, CUP_CARDS_TEXT);
create_special_link("leaguehistory.php?id=CUP", HISTORY, CUP_HISTORY_TEXT);
pageend();
?>
