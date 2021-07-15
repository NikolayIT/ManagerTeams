<?php
/*
File name: team.php
File path: /team.php
Last change: Mon Jan 07 22:07:32 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(TEAM);
head(TEAM);
create_special_link("teamdetails.php", OVERVIEW, TEAM_OVERVIEW_TEXT);
create_special_link("teamresults.php", RESULTS, RESULTS_TEXT);
create_special_link("teamfixtures.php", FIXTURES, FIXTURES_TEXT);
create_special_link("youthcenter.php", YOUTHCENTER, GET_A_NEW_PLAYER_FROM_THE_YOUTHCENTER);
create_special_link("staff.php", STAFF, STAFF_TEXT);
create_special_link("moneyhistory.php", ECONOMY, ECONOMY_TEXT);
create_special_link("advboards.php", TEAM_SPONSORS, TEAM_SPONSORS_TEXT);
create_special_link("loans.php", LOANS, LOANS_TEXT);
create_special_link("bank.php", BANK, BANK_TEXT);
create_special_link("changeanthem.php", "Химн на отбора", "Качване на химн на отбора");
create_special_link("teamkits.php", KITS, KITS_TEXT);
create_special_link("changename.php", "Промяна на името", "Промяна на името на отбора и стадиона");
create_special_link("teamhistory.php", HISTORY, TEAM_HISTORY_TEXT);
create_special_link("search.php", OTHER_TEAMS, OTHER_TEAMS_TEXT);
pageend();
?>
