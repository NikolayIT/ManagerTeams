<?php
/*
File name: manager.php
Last change: Sun Jan 06 13:13:03 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(MANAGER);
head(MANAGER);
create_special_link("index.php", OVERVIEW, OVERVIEW_TEXT);
create_special_link("messages.php", MESSAGES, MESSAGES_TEXT);
create_special_link("mybets.php", BETS, BETS);
create_special_link("friends.php?do=view", FRIENDS, FRIENDS_TEXT);
create_special_link("profile.php", PROFILE, PROFILE);
create_special_link("pressconference.php", "Пресконференция", "Пресконференция");
create_special_link("history.php", HISTORY, HISTORY_TEXT);
create_special_link("profileviews.php", "Разглеждания", "Последни 50 разглеждания на профила");
create_special_link("othermanagers.php", OTHER_MANAGERS, OTHER_MANAGERS_TEXT);
create_special_link("lottery.php", "Лотария",  "Лотария");
create_special_link("viewnews.php", NEWS,  NEWS);
create_special_link("logout.php", LOG_OUT, LOG_OUT_TEXT);
pageend();
?>
