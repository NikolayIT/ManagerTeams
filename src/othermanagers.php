<?php
/*
File name: othermanagers.php
Last change: Mon Jan 07 22:07:18 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart(OTHER_MANAGERS);
head(OTHER_MANAGERS);
create_special_link("ranking.php", RANKING, RANKING_TEXT);
create_special_link("invite.php", INVITE_FRIENDS, INVITE_FRIENDS_TEXT);
create_special_link("search.php", SEARCH_MANAGERS, SEARCH_MANAGERS_TEXT);
create_special_link("viewonline.php", ONLINE_MANAGERS, ONLINE_MANAGERS_TEXT);
pageend();
?>
