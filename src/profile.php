<?php
/*
File name: profile.php
Last change: Sun May 25 15:53:42 EEST 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(PROFILE);
head(PROFILE);
create_special_link("viewprofile.php", VIEW_PROFILE, VIEW_PROFILE_TEXT);
create_special_link("editprofile.php", EDIT_PROFILE, EDIT_PROFILE_TEXT);
create_special_link("passchange.php", CHANGE_PASSWORD, CHANGE_PASSWORD_TEXT);
create_special_link("holiday.php", HOLYDAY, HOLYDAY_TEXT);
pageend();
?>
