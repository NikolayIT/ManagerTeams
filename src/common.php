<?php
//die("<center><b><big>System maintenance</big><br /><br />We will be back soon!</b></center>");
if (!defined("IN_GAME")) exit;
// Report all errors, except notices
error_reporting(E_ALL ^ E_NOTICE); // error_reporting(E_ALL);

$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
$debug = "";

//if (!file_exists("config.php")) die("<p>The config.php file could not be found!</p>");
include ("./config.php");
include ("./include/cleanup.php");
include ("./include/functions.php");
include ("./include/sql.php");
include ("./include/adv.php");
include ("./include/interface.php");
include ("./include/players.php");
include ("./include/stadium.php");

date_default_timezone_set(TIMEZONE);

// Invitations
mkglobal("inviter", false);
if ($inviter > 0) setcookie("inviter", $inviter, time() + 999999999);

// MySQL connect
if (defined("DONT_UPDATE")) mysqlconnect(true);
else mysqlconnect();
unset($db_pass);

$ip = getip();
$debug .= "\r\n   IP: {$ip}";
$sqlip = sqlsafe($ip);
$ipinfo = sql_data("SELECT `id`, `lastinfo`, `banned`, `vote` FROM `ips` WHERE `ip` = {$sqlip}", __FILE__, __LINE__);
if($ipinfo['banned'] == 'yes') die ("<html><head><title>Error 403 :: Forbidden</title></head><body><b>Error 403 :: Forbidden</b><br><br>You do not have permission to access the requested file on this server. Please notify the webmaster if you believe there is a problem.<br><br><a href=\"".FORUM_ADDRESS."\">For more information visit our forum.</a><br></body></html>");
if(!$ipinfo)
{
   //exec("traceroute -m 50 {$ip} 8", &$res);
   //foreach($res as $line) $trace .= $line . "<br>";
   //$trace = sqlsafe($trace);
   $host = sqlsafe(gethostbyaddr($ip));
   $remote = sqlsafe("{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']}");
   $httpvia = sqlsafe($HTTP_VIA);
   $httpxfor = sqlsafe($HTTP_X_FORWARDED);
   $httpxforfor = sqlsafe($HTTP_X_FORWARDED_FOR);
   $httpfor = sqlsafe($HTTP_FORWARDED);
   $httpforfor = sqlsafe($HTTP_FORWARDED_FOR);
   $httpcom = sqlsafe($HTTP_COMING_FROM);
   $httpxfor = sqlsafe($HTTP_X_COMING_FROM);
   sql_query("INSERT INTO `ips` (`ip`, `iplong`, `lastinfo`, `host`, `REMOTE_ADDR`, `HTTP_VIA`, `HTTP_X_FORWARDED`, `HTTP_X_FORWARDED_FOR`, `HTTP_FORWARDED`, `HTTP_FORWARDED_FOR`, `HTTP_COMING_FROM`, `HTTP_X_COMING_FROM`) VALUES ({$sqlip}, ".ip2long($ip).", NOW(), {$host}, {$remote}, {$httpvia}, {$httpxfor}, {$httpxforfor}, {$httpfor}, {$httpforfor}, {$httpcom}, {$httpxfor})", __FILE__, __LINE__);
   $ipinfo = sql_data("SELECT `id`, `lastinfo`, `banned`, `vote` FROM `ips` WHERE `ip` = {$sqlip}", __FILE__, __LINE__);
}
else if ($ipinfo['lastinfo'] < get_date_time(false, -60*60*24*7))
{
   //exec("traceroute -m 50 {$ip} 8", &$res);
   //foreach($res as $line) $trace .= $line . "<br>";
   //$trace = sqlsafe($trace);
   $host = sqlsafe(gethostbyaddr($ip));
   $remote = sqlsafe("{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']}");
   $httpvia = sqlsafe($HTTP_VIA);
   $httpxfor = sqlsafe($HTTP_X_FORWARDED);
   $httpxforfor = sqlsafe($HTTP_X_FORWARDED_FOR);
   $httpfor = sqlsafe($HTTP_FORWARDED);
   $httpforfor = sqlsafe($HTTP_FORWARDED_FOR);
   $httpcom = sqlsafe($HTTP_COMING_FROM);
   $httpxfor = sqlsafe($HTTP_X_COMING_FROM);
   $query = "UPDATE `ips` SET `lastinfo` = NOW(), `host` = {$host}, `REMOTE_ADDR` = {$remote},
    `HTTP_VIA` = {$httpvia}, `HTTP_X_FORWARDED` = {$httpxfor}, `HTTP_X_FORWARDED_FOR` = {$httpxforfor}, `HTTP_FORWARDED` = {$httpfor}, `HTTP_FORWARDED_FOR` = {$httpforfor},
    `HTTP_COMING_FROM` = {$httpcom}, `HTTP_X_COMING_FROM` = {$httpxfor} WHERE `id` = {$ipinfo['id']}";
   sql_query($query, __FILE__, __LINE__);
}


include("languages/common.php");
$lang = sql_get("SELECT `file` FROM `languages` WHERE `id` = ".sqlsafe(0 + $_COOKIE["lang"]), __FILE__, __LINE__);
if ($lang != null) include("languages/{$lang}");
else if ($USER['language']) setcookie("lang", $USER['language'], time()+99999999);
else if (!$onlyconn) setcookie("lang", "1", time()+99999999);
if (!defined("IN_LANG")) include("languages/".DEFAULT_LANGUAGE_FILE);

userlogin(defined("DONT_UPDATE"));
if (!defined("DONT_UPDATE")) add_to_log($USER['id'], $ipinfo['id']);

if ($config['online'] == 0 && $USER['class'] != UC_CODER) we_are_offline($config['offlinemessage'], $config['offlinestatus']);

$tactictypes = array(NONE, DEFENSIVE_WALL, PASSING, LONG_BALL, KICK_AND_RUSH);

if ($USER['holiday'] == 'yes')
{
   mkglobal("holidaymodeoff");
   if ($holidaymodeoff == 1)
   {
      sql_query("UPDATE `users` SET `holiday` = 'no' WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
      redirect("index.php");
   }
   pagestart(HOLYDAY_MODE);
   head(HOLYDAY_MODE);
   prnt(YOU_ARE_IN_HOLYDAY_MODE, true);
   br();
   create_button("holiday.php?holidaymodeoff=1", DEACTIVATE_HOLYDAY_MODE, false, true);
   pageend();
}
?>
