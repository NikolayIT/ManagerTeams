<?php
/*
File name: games.php
Last change: Wed Jan 09 08:53:40 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(GAMES);
head(GAMES);
create_special_link("cl.php", "Champions League", "Champions League");
create_special_link("leagues.php", LEAGUES, LEAGUES_TEXT);
create_special_link("cup.php", CUP, CUP_TEXT);
create_special_link("friendly.php", FRIENDLY_GAMES, FRIENDLY_GAMES_TEXT);
create_special_link("friendlycups.php", FRIENDLY_CUPS, FRIENDLY_CUPS_TEXT);
create_special_link("matchreport.php?id={$config['match_of_week']}", MATCH_OF_THE_WEEK, MATCH_OF_THE_WEEK_TEXT);
pageend();
?>
