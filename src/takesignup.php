<?php
/*
File name: takesignup.php
Last change: Sat Feb 09 09:03:46 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
if (!$allownewusers) info(NO_NEW_USERS, ERROR);
$users = sql_get("SELECT COUNT(`id`) FROM `users`", __FILE__, __LINE__);
if ($users >= $userslimit) info(NO_NEW_USERS, ERROR);

mkglobal("username:password:passagain:realname:teamname:stadium:email:country", true);
if (empty($username) || empty($password) || empty($passagain) || empty($teamname) || empty($realname) ||
   empty($stadium) || empty($email) || empty($country)) info(MISSING_DATA, ERROR);

if ($_POST["rules1"] != 'yes') info(ONE_TEAM_ONLY, ERROR);
if ($_POST["rules2"] != 'yes') info(MUST_AGREE_RULES, ERROR);

// Username
if (strlen($username) < $username_minlen) info(SHORT_USERNAME, ERROR);
if (strlen($username) > 16) info(LONG_USERNAME, ERROR);
if (!is_valid_username($username)) info(WRONG_USERNAME, ERROR);
$username2 = sqlsafe($username);
// Check if username is already in use
$ms = sql_get("SELECT COUNT(`id`) FROM `users` WHERE `username` = {$username2}", __FILE__, __LINE__);
if ($ms != 0) info(USERNAME_TAKEN, ERROR);

// Password
if ($password != $passagain) info(PASS_NOT_MATCH, ERROR);
if (strlen($password) < $password_minlen) info(SHORT_PASS, ERROR);
if (strlen($password) > $password_maxlen) info(LONG_PASS, ERROR);
$secret = mksecret(16);
$passhash = sqlsafe(gen_pass_hash($password, $secret));
$secret2 = "'".$secret."'";

// Real Name
if (strlen($realname) < $realname_minlen) info(SHORT_REALNAME, ERROR);
if (strlen($realname) > 50) info(LONG_REALNAME, ERROR);
if (!is_valid_name($realname)) info(WRONG_REALNAME, ERROR);
$realname2 = sqlsafe($realname);

// Team Name
if (strlen($teamname) < $teamname_minlen) info(SHORT_TEAMNAME, ERROR);
if (strlen($teamname) > 32) info(LONG_TEAMNAME, ERROR);
if (!is_valid_name($teamname)) info(WRONG_TEAMNAME, ERROR);
$teamname2 = sqlsafe($teamname);
// Check if team name is already in use
$ms = sql_get("SELECT COUNT(`id`) FROM `teams` WHERE `name` = {$teamname2}", __FILE__, __LINE__);
if ($ms != 0) info(TEAMNAME_TAKEN, ERROR);

// Stadium Name
if (strlen($stadium) < $stadiumname_minlen) info(SHORT_STADIUMNAME, ERROR);
if (strlen($stadium) > 32) info(LONG_STADIUMNAME, ERROR);
if (!is_valid_name($stadium)) info(WRONG_STADIUMNAME, ERROR);
$stadium2 = sqlsafe($stadium);
// Check if stadium name is already in use
$ms = sql_get("SELECT COUNT(`id`) FROM `stadiums` WHERE `name` = {$stadium2}", __FILE__, __LINE__);
if ($ms != 0) info(STADIUMNAME_TAKEN, ERROR);

// E-Mail
if (strlen($email) > 50) info(LONG_EMAIL, ERROR);
if (!is_valid_email($email)) info(WRONG_EMAIL, ERROR);
$email2 = sqlsafe($email);
// Check if email is already in use
$ms = sql_get("SELECT COUNT(`id`) FROM `users` WHERE `email` = {$email2}", __FILE__, __LINE__);
if ($ms != 0) info(EMAIL_TAKEN, ERROR);

// Country
if (!is_valid_id($country)) info(WRONG_COUNTRY, ERROR);

// IP
$ip = getip();
$ip2 = sqlsafe($ip);

if ($needconfirmation)
{
   $confid = gen_confirmation_id($username, $secret);
   $link = ADDRESS."confirm.php?user={$username}&id={$confid}";
   $mess = CONFIRMATION_MAIL;
   $mess = str_replace("{realname}", $realname, $mess);
   $mess = str_replace("{username}", $username, $mess);
   $mess = str_replace("{address}", ADDRESS, $mess);
   $mess = str_replace("{gamename}", GAME_NAME, $mess);
   $mess = str_replace("{link}", $link, $mess);
   $mess = str_replace("{password}", $password, $mess);
   $mess = str_replace("{ip}", $ip, $mess);
   $headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
   $res = mail($email, GAME_NAME." "._ACCOUNT_CONFIRMATON, $mess, $headers);
   if (!$res) info(SENDING_MAIL_ERROR, ERROR);
}

if ($needconfirmation) $class = UC_UNCONFIRMED;
else if (FREE_VIP) $class = UC_VIP_USER;
else $class = UC_USER;
$leagueid = 0;
$team = generate_team_for_user($teamname2, $stadium2);
$team2 = sqlsafe($team);
if (FREE_VIP) $vipuntil = get_date_time(true, 30*24*3600);
else $vipuntil = "'0000-00-00 00:00:00'";
$stad = sql_query("UPDATE `stadiums` SET `eastseats` = '1', `westseats` = '1', `northseats` = '1', `southseats` = '1', `parking` = '0', `bars` = '0', `toilets` = '0', `grass` = '1', `lights` = '1', `boards` = '1', `youthcenter` = '0', `roof` = '0', `heater` = '0', `sprinkler` = '0' WHERE `id` = (SELECT `stadium` FROM `teams` WHERE `id` = {$team2})", __FILE__, __LINE__);
sql_query("INSERT INTO `users` (`username`, `passhash`, `secret`, `realname`, `email`, `ip`, `team`, `country`, `class`, `registred`, `vipuntil`) VALUES
   ({$username2}, {$passhash}, {$secret2}, {$realname2}, {$email2}, {$ip2}, {$team2}, {$country}, {$class}, ".get_date_time().", {$vipuntil})", __FILE__, __LINE__);
$id = sqlsafe(mysql_insert_id());
add_to_manager_history("<a href='viewprofile.php?id={$id}'>{$username}</a> {_STARTS_HIS_HER_CAREER_}.", $id);
add_to_manager_history("<a href='viewprofile.php?id={$id}'>{$username}</a> {_BECAME_THE_MANAGER_OF_THE_TEAM_} {$teamname}.", $id);
add_to_team_history("<a href='viewprofile.php?id={$id}'>{$username}</a> {_BECAME_THE_MANAGER_OF_THE_TEAM_}.", $team);
sql_query("DELETE FROM `money_history` WHERE `team` = {$team}", __FILE__, __LINE__);
$tacid = generate_players_for_team($team, $country, $leagueid);
sql_query("UPDATE `teams` SET `tactic1` = {$tacid} WHERE `id` = {$team2}", __FILE__, __LINE__);
sql_query("DELETE FROM `loans` WHERE `team` = {$team2}", __FILE__, __LINE__);
sql_query("DELETE FROM `trophies` WHERE `team` = {$team2}", __FILE__, __LINE__);
automatic_training($team);

cleanup("last_update15", 0, true);

if ($needconfirmation) info(SIGNUP_SUCCESS."<br>".EMAIL_CONFIRMATION, SUCCESS, false);
else info(SIGNUP_SUCCESS, SUCCESS, false);
?>
