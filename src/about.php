<?php
/*
File name: about.php
Last change: Fri Jan 11 22:10:22 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart(ABOUT." ".SITE_TITLE);
head(ABOUT." ".SITE_TITLE);
bline(ABOUT_GAME_NAME.":", GAME_NAME);
bline(ABOUT_GAME_VERSION.":", GAME_VERSION." (".create_link("changelog.php", CHANGELOG).")");
bline("Season started".":", $config['started']);
br();
bline(ABOUT_GAME_ADDRESS.":", create_link(ADDRESS, ADDRESS));
bline(ABOUT_FORUM_ADDRESS.":", create_link(FORUM_ADDRESS, FORUM_ADDRESS));
br();
bline(ABOUT_INFO_EMAIL.":", create_link("mailto:".EMAIL_INFO, EMAIL_INFO));
bline(ABOUT_TRANSLATIONS_EMAIL.":", create_link("mailto:".EMAIL_TRANSLATIONS, EMAIL_TRANSLATIONS));
bline(ABOUT_ADEVRTISE_EMAIL.":", create_link("mailto:".EMAIL_ADVERTISE, EMAIL_ADVERTISE));
//br();
//bline(ABOUT_OWNERS.":", OWNERS);
br();
bline(ABOUT_CODING.":", create_link(CODER_ADDRESS, CODER_NICKNAME)." (".CODER_NAME.")");
bline(ABOUT_DESIGN.":", create_link(DESIGNER_ADDRESS, DESIGNER_NICKNAME)." (".DESIGNER_NAME.")");
br();
bline(ABOUT_BETA_TESTERS.":", BETA_TESTERS);
br(3);
head(TRANSLATORS);
bline("English:", create_link(CODER_ADDRESS, CODER_NICKNAME));
bline("Bulgarian:", create_link(CODER_ADDRESS, CODER_NICKNAME));
bline("Spanish:", create_link("mailto:d.positive@abv.bg", "PacHo"));
pageend();
?>

