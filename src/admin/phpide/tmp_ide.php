<?php
/*
File name: config.php
Last change: Thu Jan 17 21:18:38 EET 2008
Copyright: NRPG (c) 2008
*/
// Database settings
$db_host = "localhost";
$db_name = "nrpginf_manager";
$db_user = "nrpginf_manager";
$db_pass = "manager";

// Global settings
define("GAME_NAME", "ManagerTeams");
define("GAME_VERSION", "0.85-beta");
define("VERSION_FROM", "24 Jan 2007");
define("SITE_TITLE", GAME_NAME." ".GAME_VERSION);
define("CODER_NICKNAME", "NRPG");
define("CODER_NAME", "Nikolay Kostov");
define("CODER_ADDRESS", "http://nrpg.info/");
define("DESIGNER_NICKNAME", "NRPG");
define("DESIGNER_NAME", "Nikolay Kostov");
define("DESIGNER_ADDRESS", "http://nrpg.info/");
define("OWNERS", "Milen Bliznakov and Kalin Ivanov");
define("BETA_TESTERS", "NRPG, bliznaci, Ravenheart, vankata.rusnaka, gnusen, damarus, fedya, Vergoth");
define("ADDRESS", "http://soccerproject.nrpg.info/");
define("EMAIL_ADDRESS", "noreply@soccesproject.nrpg.info");
define("EMAIL_INFO", "info@soccesproject.nrpg.info");
define("EMAIL_TRANSLATIONS", "translations@soccesproject.nrpg.info");
define("EMAIL_ADVERTISE", "adv@soccesproject.nrpg.info");
define("FORUM_ADDRESS", "http://bugs.nrpg.info/");
define("GAME_DESCRIPTION", "ManagerTeams : Online football manager game");
define("GAME_KEYWORDS", "football soccer manager teams managerteams game transfers team club money online web-based");

// Game settings
$startmoney = 1000000;
define("DEFAULT_LANGUAGE_FILE", "english.php");
$defaultnames = "England";
$defaultattribute = 1;
$interest = 0.001;
$formations = array("253", "343", "352", "433", "442", "451", "523", "532", "541");
$tactictypes = array("None", "Passing", "Long ball", "Kick and rush", "Defensive wall");
$friendlystarttime = array(4, 10, 16, 22);
$friendlycuptypes = array("Friendly cup", "Friendly league");
$friendlycupteams = array("4", "8", "16", "32", "64", "128");
define("DEFAULT_FORMATION", 4); // "442"
define("LOANS_MINIMUM_DAYS", 14);
define("TO_TRAIN", 10000);
define("PLAYERS_OLD", 32); // years after born
define("STAFF_OLD", 60); // years after born
define("MAXMIUM", 100);
define("WAGE_ACC", 3.5);
define("STAFF_COURSE_TIME", 14);
define("STAFF_COURSE_PRICE", 15000);
define("STAFF_COURSE_RATING", 5);
define("STAFF_WAGE_Y1", 10);
define("STAFF_WAGE_Y2", 12);
define("STAFF_WAGE_Y3", 15);
define("SELL_OFFER_ACTIVE_TIME", 5); // days
define("DEFAULT_AGGRESSION", 50);
define("DEFAULT_STYLE", 50);
define("DEFAULT_TACTIC_TYPE", 0);
define("TIME_HOUR", 3600);
define("TIME_DAY", TIME_HOUR*24);
define("TIME_WEEK", TIME_DAY*7);
define("SMALL_CLEANUP", TIME_WEEK*3);
define("MEDIUM_CLEANUP", TIME_WEEK*4);
define("BIG_CLEANUP", TIME_WEEK*4*3);
define("BEST_PLAYERS_COUNT", 15);
define("BEST_COUNTRIES_COUNT", 15);
define("MAX_DAYS_FOR_FRIENDLY_INVITATION", 6);
define("MAX_NEWS", 5);
define("BEST_LIMIT", 25);

// Login/Signup settings
$userslimit = 52480;
$allownewusers = true;
$needconfirmation = true;
$username_minlen = 4; // Username minimal length
$realname_minlen = 5; // Real name minimal length
$usernameallowedchars = ".abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ_0123456789"; // Username allowed characters
$namesallowedchars = ".abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ_ 0123456789"; // Username allowed characters
$password_minlen = 6; // Password maximal length
$password_maxlen = 32; // Password minimal length
$teamname_minlen = 4; // Team name minimal length
$stadiumname_minlen = 4; // Stadium name minimal length

// Encoding
define("ENCODING", "windows-1251"); // Charset //define("ENCODING", "utf-8");
define("SQLNAMES", "cp1251"); // MySQL Connection encoding define("SQLNAMES", "utf8");
define("TIMEZONE", "CET"); // The time zone for the whole system
define("ERROR_CACHE_FILE", "./cache/errors.cache");

// Debug information
define("DEBUG", true); // Show debug information
$SHOWSQLQUERIES = false; // $SHOWSQLQUERIES = DEBUG // Show sql queries (for debugging only!)

// Users
define("UC_UNCONFIRMED", 0);
define("UC_USER", 1);
define("UC_PRO_USER", 2);
define("UC_VIP_USER", 3);
define("UC_NEWBIEMODERATOR", 4);
define("UC_MODERATOR", 5);
define("UC_SUPERMODERATOR", 6);
define("UC_NEWBIEADMIN", 7);
define("UC_ADMIN", 8);
define("UC_OWNER", 9);

// GLOBAL VARIABLES - Don't change them!!!
$USER = "";
$TEAM = "";
$STADIUM = "";
$config = "";
define("HIDDEN_PASSWORD", "(C*#)n0;0bs(%_.HU#k");
$pagestarted = false;
$pageended = false;
$queries = 0;
//$querytime = 0;
?>
