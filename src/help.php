<?php
/*
File name: help.php
Last change: Wed Jan 09 11:57:28 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(HELP);
head(HELP);
create_special_link(FORUM_ADDRESS, FORUM, FORUM_TEXT);
create_special_link(USER_GUIDE_ADDRESS, USER_GUIDE, USER_GUIDE);
create_special_link("rules.php", RULES, RULES_TEXT);
create_special_link("invite.php", INVITE_FRIENDS, INVITE_FRIENDS_TEXT);
create_special_link("changelog.php", CHANGELOG, CHANGELOG_TEXT);
create_special_link("about.php", ABOUT, ABOUT_TEXT);
pageend();
?>
