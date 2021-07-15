<?php
/*
File name: friendlycups.php
Last change: Wed Jan 09 09:22:49 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(FRIENDLY_CUPS);
head(FRIENDLY_CUPS);
create_special_link("friendlycupcreate.php", CREATE, CREATE_TEXT);
create_special_link("friendlycupslist.php", CUPS_LIST, CUPS_LIST_TEXT);
create_special_link("friendlycupslist.php?old=1", HISTORY, FRIENDLY_CUP_HISTORY);
create_special_link("friendlycupslist.php?my=1", CUPS_WITH_MY_TEAM, CUPS_WITH_MY_TEAM_TEXT);
create_special_link("friendlyfixtures.php", FIXTURES, FRIENDLY_FIXTURES_TEXT);
create_special_link("friendlyresults.php", RESULTS, FRIENDLY_RESULTS_TEXT);
pageend();
?>
