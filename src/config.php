<?php
// Database settings
$db_host = "localhost";
$db_name = "managerteams";
$db_user = "managerteams";
$db_pass = "(C*#)n0;0bs(%_.HU#k";

// Global settings
define("GAME_NAME", "ManagerTeams");
define("GAME_VERSION", "1.68");
define("VERSION_FROM", "24 September 2011");
define("SITE_TITLE", GAME_NAME." ".GAME_VERSION);
define("CODER_NICKNAME", "Nikolay Kostov");
define("CODER_NAME", "Nikolay Kostov");
define("CODER_ADDRESS", "http://nikolay.it/");
define("DESIGNER_NICKNAME", "Ivan Hristov");
// define("DESIGNER_NAME", "Ivan Hristov and Denitsa Atanasova");
define("DESIGNER_NAME", "Ivan Hristov");
define("DESIGNER_ADDRESS", "mailto:ivanhristovbg@gmail.com");
// define("OWNERS", "Milen Bliznakov and Kalin Ivanov");
define("BETA_TESTERS", "NRPG, bliznaci, Ravenheart, vankata.rusnaka, gnusen, damarus, fedya, Vergoth, alexz, PacHo");
define("ADDRESS", "http://managerteams.com/");
define("EMAIL_ADDRESS", "admin@managerteams.com");
define("EMAIL_INFO", "admin@managerteams.com");
define("EMAIL_TRANSLATIONS", "admin@managerteams.com");
define("EMAIL_ADVERTISE", "admin@managerteams.com");
define("FORUM_ADDRESS", "http://forum.managerteams.com/");
define("USER_GUIDE_ADDRESS", "http://wiki.managerteams.com/");
define("GAME_DESCRIPTION", "ManagerTeams is an online soccer manager simulation, which gives the ability to thousands of people to participate in an unique interactive manager world.");
define("GAME_KEYWORDS", "football, soccer, manager, teams, managerteams, game, transfers, team, club, money, online, web-based");
define("CUP_NAME", GAME_NAME." ".CUP);
define("LOGOUT_REDIRECT_TO", "http://prepishi.com/");
//define("LOGOUT_REDIRECT_TO", FORUM_ADDRESS);
define("FREE_VIP", false);

// Game settings
$startmoney = 2000000;
define("DEFAULT_LANGUAGE_FILE", "english.php");
define("DEFAULT_MATCH_LANGUAGE_FILE", "english_match.php");
$defaultnames = "England";
$defaultattribute = 1;
$interest = 0.0002;
$formations = array("253", "343", "352", "433", "442", "451", "523", "532", "541");
$possitions = array('GK', 'LB', 'CB', 'RB', 'LBM', 'CBM', 'RBM', 'LM', 'CM', 'RM', 'LFM', 'CFM', 'RFM', 'LF', 'CF', 'RF');
define("MAX_TEAMS", 52480); // 52480
define("DEFAULT_FORMATION", 4); // "442"
define("LOANS_MINIMUM_DAYS", 14);
define("LOANS_RETURN_TAX_PERCENT", 0.05);
define("TO_TRAIN", 100000);
define("PLAYERS_OLD", 34); // years
define("STAFF_OLD", 62); // years after born
define("MAXMIUM", 100);
define("WAGE_ACC", 33);
define("STAFF_COURSE_TIME", 14);
define("STAFF_COURSE_PRICE", 150000);
define("STAFF_COURSE_RATING", 5);
define("STAFF_WAGE_Y1", 25);
define("STAFF_WAGE_Y2", 30);
define("STAFF_WAGE_Y3", 35);
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
define("MAX_NEWS", 15);
define("MAX_BEST_MANAGERS", 10);
define("BEST_LIMIT", 25);
define("SACK_FINE", 500000);
$friendlystarttime = array(4, 10, 16, 22);
$friendlycuptypes = array("Friendly cup", "Friendly league");
$friendlycupteams = array("4", "8", "16", "32", "64");
define("FRCUP_START_AFTER", 1); // minimum 1
define("NOTVIP_MAX_TEAMS_IN_CUP", 0); // 0 - 4, 1 - 8, 2 - 16...
define("MINIMUM_PLAYERS_IN_TEAM", 13);
define("MAXIMUM_PLAYERS_IN_TEAM", 60);
define("MAXIMUM_FEE_FOR_FRIENDLY_CUPS", 2000000);
define("LEAGUE_FIRST_BONUS", 1000000);
define("LEAGUE_SECOND_BONUS", 500000);
define("CL_WINNER_BONUS", 5000000);
define("CL_SECOND_BONUS", 2000000);
define("CL_SEMIFINAL_BONUS", 1000000);
define("CUP_WINNER_BONUS", 2000000);
define("CUP_SECOND_BONUS", 1000000);
define("CUP_SEMIFINAL_BONUS", 500000);
define("MAXIMUM_BET", 10);
define("MINIMUM_BET", 10);
define("MAXINUM_BETS", 3);
define("MONEY_WARN", -500000);
define("WON_FROM_TRANSFER", 0.90);
define("GET_PLAYER_FROM_YOUTHCENTER_PRICE", 20000000);
define("VOTE_MONEY", 25000);
define("TOPSCORER_PRIZE", 500000);
define("ROUGH_PLAYER_FINE", 600000);
define("LOTTERY_TICKET", 100000);
define("LOTTERY_WIN", 0.90);
$press = array(
	0 => array("question" => "Как се чувствате в отбора?", "type" => "select", "options" => array("не знам", "като аматьор", "задоволително", "много добре", "отлично")),
	1 => array("question" => "Какви цели ще преследвате през новия сезон?", "type" => "select", "options" => array("не знам", "ще обигравам отбора", "ще инвестирам в модулите към стадиона", "ще подменям изцяло отбора с нови играчи", "ще гледам да се спася от изпадане", "ще атакувам първото или второто място", "според конкурентите ще реша какво да правя")),
	2 => array("question" => "Доволен ли сте от наличните ви футболисти?", "type" => "select", "options" => array("не знам", "по-скоро да", "по-скоро не")),
	3 => array("question" => "Колко футболиста ще закупите от трансферния пазар?", "type" => "select", "options" => array("не знам", "само един", "между 2-3", "между 4-5", "между 5-7", "повече от 7", "няма да купувам играчи през този сезон")),
	4 => array("question" => "Доволен ли сте от представянето на отбора си до момента?", "type" => "select", "options" => array("не знам", "не", "да", "до момента отборът ми играе задоволително", "отборът ми е слаб, но виждам бъдеще в него", "отборът ми е роден победител")),
	5 => array("question" => "Кой от модулите към стадиона развивате приоритетно?", "type" => "select", "options" => array("не знам", "Седалки", "Паркинги", "Барове", "Тоалетни", "Трева", "Светлини", "Рекламни табла", "ДЮШ", "Покрив", "Нагревател", "Пръскачки", "Фен магазин", "Болница")),
	6 => array("question" => "Имате ли любимец сред играчите и кой е той?", "type" => "text"),
	7 => array("question" => "Коя е „звездата” на вашия отбор?", "type" => "text"),
	8 => array("question" => "Смятате ли да дадете шанс на играчи от ДЮШ?", "type" => "select", "options" => array("не знам", "да", "не", "ще ги обигравам в приятелските мачове и купи")),
	9 => array("question" => "Колко играчи ще са ви необходими, за да участвате едновременно в мачовете от шампионата, Купата на МениджйрТиймс, приятелските купи и приятелските мачове?", "type" => "select", "options" => array("не знам", "11", "22", "33", "44", "55")),
	10 => array("question" => "Какъв е вашият коментар за отбора и какви са вашите цели на развитие?", "type" => "textbox"),
);

// Login/Signup settings
$userslimit = 52480;
$allownewusers = true;
$needconfirmation = true;
define("FREE_VIP", false);
$username_minlen = 4; // Username minimal length
$realname_minlen = 5; // Real name minimal length
$usernameallowedchars = ".abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ_0123456789"; // Username allowed characters
$namesallowedchars = ".abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ_ 0123456789"; // Username allowed characters
$password_minlen = 6; // Password maximal length
$password_maxlen = 32; // Password minimal length
$teamname_minlen = 4; // Team name minimal length
$stadiumname_minlen = 4; // Stadium name minimal length
define("INVITATION_BONUS", 250000);

// Encoding
define("ENCODING", "windows-1251"); // Charset //define("ENCODING", "utf-8");
define("SQLNAMES", "cp1251"); // MySQL Connection encoding define("SQLNAMES", "utf8");
define("TIMEZONE", "CET"); // The time zone for the whole system
define("ERROR_CACHE_FILE", "./cache/errors.cache");

// Debug information
define("DEBUG", false); // Show debug information
$SHOWSQLQUERIES = false; // $SHOWSQLQUERIES = DEBUG // Show sql queries (for debugging only!)

// Users classes
define("UC_UNCONFIRMED", 0);
define("UC_USER", 1);
define("UC_PRO_USER", 2);
define("UC_VIP_USER", 3);
define("UC_NEWBIEMODERATOR", 4);
define("UC_MODERATOR", 5);
define("UC_SUPERMODERATOR", 6);
define("UC_ADMIN", 7);
define("UC_CODER", 8);
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
/*
// managerteams@yahoo.com
// t0b30rn0tt0b3
Alternate Email: nrpg1990@gmail.com
Birthday: June 22, 1990
Security Question: What is your favorite sports team?
My Answer: uns1gn3dm1nus0n3
Postal Code: 1000
*/
?>
