<?php
function add_to_log($uid, $ip)
{
	include("browser_detection.php");
	$ip = sqlsafe($ip);
	$page = sqlsafe($_SERVER["REQUEST_URI"]);
	$referer = $_SERVER['HTTP_REFERER'];
	$referer = str_ireplace("http://managerteams.com", "[mt]", $referer);
	$referer = str_ireplace("http://www.managerteams.com", "[wmt]", $referer);
	$referer = sqlsafe($referer);
	$postdata = sqlsafe(serialize($_POST));
	$uid = sqlsafe($uid);
	$browser = sqlsafe(bd_get_browser($_SERVER['HTTP_USER_AGENT']));
	$os = sqlsafe(bd_get_os($_SERVER['HTTP_USER_AGENT']));
	sql_query("INSERT INTO `logs` (`ip`, `datetime`, `page`, `referer`, `postdata`, `uid`, `browser`, `os`) VALUES ({$ip}, NOW(), {$page}, {$referer}, {$postdata}, {$uid}, {$browser}, {$os})", __FILE__, __LINE__);
}
function loadconfig()
{
	global $config;
	$rez = sql_query("SELECT * FROM `config`", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($rez)) $config[$row['name']] = $row['value'];
}
function get_date_time($sql = true, $nowplus = 0, $timestamp = 0, $onlytime = false)
{
	// Date formats
	if ($onlytime) $format = "H:i:s";
	else $format = "Y-m-d H:i:s";
	// Time stamps
	if ($timestamp != 0) $ret = date($format, $timestamp);
	else if ($nowplus != 0) $ret = date($format, time() + $nowplus);
	else $ret = date($format);
	// SQL
	if ($sql) $ret = sqlsafe($ret);
	// Return
	return $ret;
}
function date_diff($str_start, $str_end)
{
	$str_start = strtotime($str_start); // The start date becomes a timestamp
	$str_end = strtotime($str_end); // The end date becomes a timestamp
	$nseconds = $str_end - $str_start; // Number of seconds between the two dates
	return $nseconds;
}
function gen_pass_hash($pass, $secret)
{
	return md5(HIDDEN_PASSWORD . $pass . $secret);
}
function gen_confirmation_id($username, $secret)
{
	return md5($secret . $username . HIDDEN_PASSWORD);
}
function get_user_class_name($class)
{
	switch ($class)
	{
		case UNCONFIRMED: return UNCONFIRMED_USER;
		case UC_USER: return USER;
		case UC_PRO_USER: return PRO_PLAYER;
		case UC_VIP_USER: return VIP_PLAYER;
		case UC_NEWBIEMODERATOR: return NEWBIE_MODERATOR;
		case UC_MODERATOR: return MODERATOR;
		case UC_SUPERMODERATOR: return SUPER_MODERATOR;
		case UC_ADMIN: return VIP_PLAYER;
		case UC_CODER: return CODER;
		case UC_OWNER: return OWNER;
	}
	return "";
}
function additionaldata($getstadium = false)
{
	global $TEAM;
	if ($getstadium) $GLOBALS['STADIUM'] = sql_data("SELECT * FROM stadiums WHERE id = {$TEAM['stadium']}", __FILE__, __LINE__);
}
function userlogin($dontupdate = false)
{
	$uid = 0 + $_COOKIE["uid"];
	$pass = $_COOKIE["pass"];
	if (!$uid || strlen($pass) != 32) return;
	$uid2 = sqlsafe($uid);
	$pass2 = sqlsafe($pass);
	$ip = sqlsafe(getip());
	$row = sql_data("SELECT * FROM `users` WHERE `id` = {$uid2} AND `passhash` = {$pass2}", __FILE__, __LINE__);
	if (!$row) return;
	//if (rand(1, 20) == 16)
	//sql_query("UPDATE `users` SET `ip` = {$ip}, `lastaction` = ".get_date_time()." WHERE `id` = {$row['id']}", __FILE__, __LINE__);
	$lastactionin = $_SERVER["REQUEST_URI"];
	if (!$dontupdate) sql_query("UPDATE `users` SET `ip` = {$ip}, `lastaction` = ".get_date_time().", `lastactionin` = '{$lastactionin}' WHERE `id` = {$row['id']}", __FILE__, __LINE__);
	$GLOBALS['USER'] = $row;
	$GLOBALS['TEAM'] = sql_data("SELECT * FROM `teams` WHERE `id` = '{$row['team']}'", __FILE__, __LINE__);
	if ($_COOKIE["rem"] != '1') logincookie($uid, $pass, 0, false);
}
function logincookie($id, $passhash, $rem, $updsql = true)
{
	if ($rem)
	{
		$expires = time() + 99999999;
		setcookie("rem", "1", $expires);
	}
	else
	{
		$expires = time() + 900;
		setcookie("rem", "0", $expires);
	}
	setcookie("uid", $id, $expires, "/");
	setcookie("pass", $passhash, $expires, "/");
	$id = sqlsafe($id);
	if ($updsql)
	{
		$ip = sqlsafe(getip());
		sql_query("UPDATE `users` SET `lastlogin` = ".get_date_time().", `ip` = {$ip}, `lastaction` = ".get_date_time()." WHERE id = {$id}", __FILE__, __LINE__);
	}
}
function mkglobal($vars, $postonly = false)
{
	if (!is_array($vars)) $vars = explode(":", $vars);
	$ret = true;
	foreach ($vars as $v)
	{
		$GLOBALS[$v] = "";
		if (!$postonly && isset($_GET[$v])) $GLOBALS[$v] = protect($_GET[$v]);
		elseif (isset($_POST[$v])) $GLOBALS[$v] = protect($_POST[$v]);
		else $ret = false;
	}
	return $ret;
}
function sqlsafe($value, $stripslashes = true, $mysqlescape = true)
{
	$value = stripcslashes($value);
	if (get_magic_quotes_gpc() && $stripslashes) $value = stripslashes($value);
	if ($mysqlescape) $value = mysql_real_escape_string($value);
	if (!is_numeric($value)) $value = "'{$value}'";
	return $value;
}
function sqlsafe_name($value, $stripslashes = true, $mysqlescape = true)
{
	if (get_magic_quotes_gpc() && $stripslashes) $value = stripslashes($value);
	if ($mysqlescape) $value = mysql_real_escape_string($value);
	return "`{$value}`";
}
function limit_cover($class = UC_USER)
{
	global $USER;
	if ($class == UC_VIP_USER && FREE_VIP && $USER['class'] >= UC_USER) return true;
	if ($USER['class'] < $class) return false;
	else return true;
}
function limit($class = UC_USER)
{
	if (!limit_cover($class))
	{
		if ($class == UC_USER) info(PERMISSION_DENIED." ".REGISTERED_ONLY, PERMISSION_DENIED, true);
		else if ($class == UC_VIP_USER) info(PERMISSION_DENIED." ".VIP_ONLY, PERMISSION_DENIED, true);
		else info(PERMISSION_DENIED." ".STAFF_ONLY, PERMISSION_DENIED, true);
	}
}
function print_var($varname)
{
	prnt("\$$varname = \"{$GLOBALS[$varname]}\";", true);
}
function my_is_numeric($value)
{
	$american = preg_match ("/^(-){0,1}([0-9]+)(,[0-9][0-9][0-9])*([.][0-9]){0,1}([0-9]*)$/" ,$value) == 1;
	$world = preg_match ("/^(-){0,1}([0-9]+)(.[0-9][0-9][0-9])*([,][0-9]){0,1}([0-9]*)$/" ,$value) == 1;
	return ($american or $world);
}
function protect($str)
{
	if (my_is_numeric($str)) $str = str_replace(" ", "", $str);
	return htmlspecialchars(@mysql_real_escape_string($str));
}
function shortnumber($number)
{
	if ($number < 1000) return $number;
	else if ($number < 1000000) return number_format($number / 1000, 2, ',', '')." K";
	else return number_format($number / 1000000, 2, ',', '')." M";
}
function is_valid_username($username)
{
	global $usernameallowedchars;
	if ($username == "") return false;
	if(is_int($username[0])) return false;
	for ($i = 0; $i < strlen($username); $i++) if (strpos($usernameallowedchars, $username[$i]) == false) return false;
	return true;
}
function is_valid_name($name)
{
	global $namesallowedchars;
	if ($name == "") return false;
	if(is_int($name[0])) return false;
	for ($i = 0; $i < strlen($name); $i++)
	if (strpos($namesallowedchars, $name[$i]) == false) return false;
	return true;
}
function is_valid_email($email)
{
	return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
}
function is_valid_id($id)
{
	return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}
function mksecret($len = 16)
{
	return uniqid(rand(101, 998));
}
function getip()
{
	return $_SERVER['REMOTE_ADDR'];
}
/*function redirect($location)
{
header("Location: {$location}");
die();
}*/
function print_post_content()
{
	foreach ($_POST as $key => $value) prnt("<b>{$key}</b> = {$value}<br>");
}
function go_offline($message)
{
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'online'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '{$message}' WHERE `name` = 'offlinemessage'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'offlinestatus'", __FILE__, __LINE__);
}
function go_online()
{
	sql_query("UPDATE `config` SET `value` = '1' WHERE `name` = 'online'", __FILE__, __LINE__);
}
function set_offline_status($val)
{
	$val = round($val, 2);
	sql_query("UPDATE `config` SET `value` = '{$val}' WHERE `name` = 'offlinestatus'", __FILE__, __LINE__);
}
function send_game_message($to, $caption, $text, $name = "")
{
	if ($to == 0) return;
	$to = sqlsafe($to);
	$caption = sqlsafe($caption);
	$text = sqlsafe($text);
	if ($name == "") $name = sql_get("SELECT `username` FROM `users` WHERE `id` = {$to}", __FILE__, __LINE__);
	$name = sqlsafe($name);
	sql_query("INSERT INTO `messages` (`timesent`, `fromid`, `fromname`, `toid`, `toname`, `caption`, `message`) VALUES (".get_date_time().", 0, '', {$to}, {$name}, {$caption}, $text)", __FILE__, __LINE__);
}
function we_are_offline($mess, $status)
{
   ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <title>Offline</title>
      <meta name="description" content="<?=GAME_DESCRIPTION?>">
      <meta name="keywords" content="<?=GAME_KEYWORDS?>">
      <meta http-equiv="Content-Type" content="text/html; charset=<?=ENCODING?>">
      <link rel="shortcut icon" href="favicon.ico">
      <link href="styles/new/style.css" type="text/css" rel="stylesheet">
   </head>
   <body>
<?php
prnt("      <a href=\"".FORUM_ADDRESS."\"><font color=\"#00FF00\" size=\"3\"><b>{$mess}</b></font></a><br>\n");
prnt("      ".create_progress_bar($status, 550));
prnt("\n   </body>\n</html>");
die();
}
// History
function add_to_manager_history($event, $userid = 0)
{
	global $config;
	if ($userid == 0)
	{
		global $USER;
		$userid = $USER['id'];
	}
	$event = sqlsafe($event);
	sql_query("INSERT INTO `manager_history` (`eventtime`, `manager`, `season`, `event`) VALUES (".get_date_time().", {$userid}, {$config['season']}, {$event})", __FILE__, __LINE__);
}
function add_to_team_history($event, $teamid = 0)
{
	global $config;
	if ($teamid == 0)
	{
		global $TEAM;
		$teamid = $TEAM['id'];
	}
	$event = sqlsafe($event);
	sql_query("INSERT INTO `team_history` (`eventtime`, `team`, `season`, `event`) VALUES (".get_date_time().", {$teamid}, {$config['season']}, {$event})", __FILE__, __LINE__);
}
function add_to_trophy_history($name, $type, $teamid = 0, $season = 0)
{
	if ($teamid == 0)
	{
		global $TEAM;
		$teamid = $TEAM['id'];
	}
	if ($season == 0)
	{
		global $config;
		$season = $config['season'];
	}
	$name = sqlsafe($name);
	sql_query("INSERT INTO `trophies` (`team`, `type`, `name`, `season`) VALUES ('{$teamid}', '{$type}', {$name}, '{$season}')", __FILE__, __LINE__);
}
function add_to_league_history($event, $league)
{
	global $config;
	$event = sqlsafe($event);
	sql_query("INSERT INTO `league_history` (`eventtime`, `league`, `season`, `event`) VALUES (".get_date_time().", {$league}, {$config['season']}, {$event})", __FILE__, __LINE__);
}
function add_to_money_history($event, $money, $teamid = 0, $update = false)
{
	global $config;
	if ($teamid == 0)
	{
		global $TEAM;
		$teamid = $TEAM['id'];
	}
	$free = sql_get("SELECT `free` FROM `teams` WHERE `id` = '{$teamid}'", __FILE__, __LINE__);
	if ($free != 'no') return;
	$event = sqlsafe($event);
	$money = sqlsafe($money);
	$mon2 = sqlsafe(abs($money));
	if ($update && $money > 0) sql_query("UPDATE `teams` SET `money` = `money` + {$mon2} WHERE `id` = {$teamid}", __FILE__, __LINE__);
	if ($update && $money < 0) sql_query("UPDATE `teams` SET `money` = `money` - {$mon2} WHERE `id` = {$teamid}", __FILE__, __LINE__);
	//$uid = sql_get("SELECT `id` FROM `users` WHERE `team` = {$teamid}", __FILE__, __LINE__);
	if ($money != 0) sql_query("INSERT INTO `money_history` (`eventtime`, `team`, `season`, `event`, `money`) VALUES (".get_date_time().", '{$teamid}', '{$config['season']}', {$event}, {$money})", __FILE__, __LINE__);
}
// Teams
function generate_teams($league, $count = 16, $lid = 0)
{
	global $startmoney, $defaultnames;
	$firstnames = file("./include/names/Team_First.txt");
	$lastnames = file("./include/names/Team_Last.txt");
	$league = sqlsafe($league);
	for ($b = 1; $b <= $count; $b++)
	{
		$name = trim($firstnames[rand(0, count($firstnames) - 1)]) . " " . trim($lastnames[rand(0, count($lastnames) - 1)]);
		substr_replace($string ,"",-1);
		$stad = generate_stadium($name);
		$name = sqlsafe($name);
		sql_query("INSERT INTO `teams` (`name`, `stadium`, `league`, `money`) VALUES ({$name}, {$stad}, {$league}, {$startmoney});", __FILE__, __LINE__);
		$team = mysql_insert_id();
		$tact = generate_players_for_team($team, 0, $lid);
		sql_query("UPDATE `teams` SET `tactic1` = '{$tact}' WHERE `id` = '{$team}'", __FILE__, __LINE__);
	}
}
// Game
function generate_team_for_user($name, $stadname)
{
	global $startmoney, $leagueid;
	$team = sql_data("SELECT * FROM `teams` WHERE `free` = 'yes' ORDER BY `league` DESC LIMIT 1", __FILE__, __LINE__);
	sql_query("UPDATE `teams` SET `name` = {$name}, `free` = 'no', `money` = {$startmoney}, `teamspirit` = 50, `fanbase` = 1000, `fansatisfaction` = 50, `tactic2` = 0, `tactic3` = 0, `tactic4` = 0, `tactic5` = 0 WHERE `id` = {$team['id']}", __FILE__, __LINE__);
	sql_query("UPDATE `stadiums` SET `name` = {$stadname} WHERE `id` = {$team['stadium']}", __FILE__, __LINE__);
	sql_query("DELETE FROM `tactics` WHERE `id` = {$team['tactic1']}", __FILE__, __LINE__);
	sql_query("DELETE FROM `players` WHERE `team` = {$team['id']}", __FILE__, __LINE__);
	add_to_money_history("The board has given you this money to start your project!", $startmoney, $team['id']);
	$leagueid = sql_get("SELECT `id` FROM `match_type` WHERE `name` = '{$team['league']}'", __FILE__, __LINE__);
	return $team['id'];
}
function generate_cl_final_matches($season)
{
	// finals:     8   8   8   8   8   8   8   8   4   4   4   4   2   2   1
	$days = array( 0,  1,  2,  3,  4,  5,  6,  6,  8,  9, 12, 13, 15, 16, 20);
	$hour = array(21, 21, 21, 21, 21, 21, 12, 21, 21, 21, 21, 21, 21, 21, 21);
	$mins = array(30, 30, 30, 30, 30, 30,  0, 30, 30, 30, 30, 30, 30, 30, 30);
	// First 2 teams from each group continue in final phase
	sql_query("UPDATE `champions_league` SET `in` = 'no'", __FILE__, __LINE__);
	for($group = 'A'; $group <= 'H'; $group++)
	{
		sql_query("UPDATE `champions_league` SET `in` = 'yes' WHERE `group` = '{$group}' ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC LIMIT 2", __FILE__, __LINE__);
	}
	// 1/8 finals
	$teams = sql_array("SELECT `team` FROM `champions_league` WHERE `in` = 'yes' ORDER BY RAND()", __FILE__, __LINE__);
	$round = 7;
	for($i = 0; $i < count($teams); $i += 2)
	{
		$start = get_date_time(true, 0, mktime($hour[$i / 2], $mins[$i / 2], 0) + 60*60*24*($days[$i / 2]));
		$home = $teams[$i];
		$away = $teams[$i + 1];
		sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES (30000, {$season}, '{$round}', {$start}, {$home}, {$away}, 'cl')", __FILE__, __LINE__);
	}
	for ($round = 8; $round <= 10; $round++)
	{
		$arr = sql_array("SELECT `id` FROM `match` WHERE `season` = {$season} AND `type` = 30000 AND `round` = ".($round-1)." ORDER BY RAND()", __FILE__, __LINE__);
		for ($i = 0; $i < count($arr); $i += 2)
		{
			$startid = 16 - pow(2, 10 - $round + 1) + $i / 2;
			$start = get_date_time(true, 0, mktime($hour[$startid], $mins[$startid], 0) + 60*60*24*($days[$startid]));
			$home = $arr[$i];
			$away = $arr[$i + 1];
			sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES (30000, {$season}, '{$round}', {$start}, {$home}, {$away}, 'cl')", __FILE__, __LINE__);
		}
	}
}
function generate_cup_matches($season)
{
	sql_query("UPDATE `teams` SET `cup` = 'yes'", __FILE__, __LINE__);
	$cupid = sql_get("SELECT `id` FROM `match_type` WHERE `name` = 'CUP'", __FILE__, __LINE__);
	$days = array(0, 5,  5,  6,  12, 12, 13, 19, 19, 20, 26, 26, 27, 33, 33, 34, 40, 40, 41);
	//$days = array(0, 5,  5,  6,  12, 12, 13, 19, 19, 20, 26, 26, 27, 33, 33, 34, 40, 40, 41);
	$hour = array(0, 12, 18, 18, 12, 18, 18, 12, 18, 18, 12, 18, 18, 12, 18, 18, 12, 18, 18);
	$arr = sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LH%' OR `league` LIKE 'LG%'", __FILE__, __LINE__);
	$round = 1;
	shuffle($arr);
	for ($i = 0; $i <= count($arr) - 1; $i += 2)
	{
		$start = get_date_time(true, 0, mktime($hour[$round], 0, 0) + 60*60*24*($days[$round]));
		$home = $arr[$i];
		$away = $arr[$i + 1];
		sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$cupid}, {$season}, '{$round}', {$start}, {$home}, {$away}, 'cup')", __FILE__, __LINE__);
	}
	for ($round = 2; $round <= 18; $round++)
	{
		$arr = sql_array("SELECT `id` FROM `match` WHERE `season` = {$season} AND `type` = {$cupid} AND `round` = ".($round-1), __FILE__, __LINE__);
		switch ($round)
		{
			case 3: $arr = array_merge($arr, sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LF%'", __FILE__, __LINE__)); break;
			case 5: $arr = array_merge($arr, sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LE%'", __FILE__, __LINE__)); break;
			case 7: $arr = array_merge($arr, sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LD%'", __FILE__, __LINE__)); break;
			case 9: $arr = array_merge($arr, sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LC%'", __FILE__, __LINE__)); break;
			case 11: $arr = array_merge($arr, sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LB%'", __FILE__, __LINE__)); break;
			case 13: $arr = array_merge($arr, sql_array("SELECT `id` FROM `teams` WHERE `league` LIKE 'LA%'", __FILE__, __LINE__)); break;
		}
		shuffle($arr);
		for ($i = 0; $i <= count($arr) - 1; $i += 2)
		{
			$start = get_date_time(true, 0, mktime($hour[$round], 0, 0) + 60*60*24*($days[$round]));
			$home = $arr[$i];
			$away = $arr[$i + 1];
			sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$cupid}, {$season}, '{$round}', {$start}, {$home}, {$away}, 'cup')", __FILE__, __LINE__);
		}
	}
}
function generate_cl_matches($season)
{
	$names = array("A", "B", "C", "D", "E", "F", "G", "H");
	for ($i = 0; $i <= 7; $i++)
	{
		generate_matches_for_cl_league(30000, $season, $names[$i]);
	}
}
function generate_matches_for_cl_league($lid, $season, $lname)
{
	$rounds_data = array();
	$rounds_data["A"] = $rounds_data["B"] = array(
		1 => array("days" => 0, "hours" => 21, "minutes" => 30),
		2 => array("days" => 4, "hours" => 21, "minutes" => 30),
		3 => array("days" => 7, "hours" => 21, "minutes" => 30),
		4 => array("days" => 11, "hours" => 21, "minutes" => 30),
		5 => array("days" => 14, "hours" => 21, "minutes" => 30),
		6 => array("days" => 18, "hours" => 21, "minutes" => 30),
	);
	$rounds_data["C"] = $rounds_data["D"] = array(
		1 => array("days" => 1, "hours" => 21, "minutes" => 30),
		2 => array("days" => 5, "hours" => 21, "minutes" => 30),
		3 => array("days" => 8, "hours" => 21, "minutes" => 30),
		4 => array("days" => 12, "hours" => 21, "minutes" => 30),
		5 => array("days" => 15, "hours" => 21, "minutes" => 30),
		6 => array("days" => 19, "hours" => 21, "minutes" => 30),
	);
	$rounds_data["E"] = $rounds_data["F"] = array(
		1 => array("days" => 2, "hours" => 21, "minutes" => 30),
		2 => array("days" => 6, "hours" => 12, "minutes" => 30),
		3 => array("days" => 9, "hours" => 21, "minutes" => 30),
		4 => array("days" => 13, "hours" => 12, "minutes" => 30),
		5 => array("days" => 16, "hours" => 21, "minutes" => 30),
		6 => array("days" => 20, "hours" => 12, "minutes" => 30),
	);
	$rounds_data["G"] = $rounds_data["H"] = array(
		1 => array("days" => 3, "hours" => 21, "minutes" => 30),
		2 => array("days" => 6, "hours" => 21, "minutes" => 30),
		3 => array("days" => 10, "hours" => 21, "minutes" => 30),
		4 => array("days" => 13, "hours" => 21, "minutes" => 30),
		5 => array("days" => 17, "hours" => 21, "minutes" => 30),
		6 => array("days" => 20, "hours" => 21, "minutes" => 30),
	);
	$rounds = $rounds_data[$lname];
	$data = array();
	$data[0] = array(0, 1, 2, 3);
	$data[1] = array(1, 0, 3, 2);
	$data[2] = array(2, 3, 0, 1);
	$data[3] = array(3, 2, 1, 0);
	$teams = sql_array("SELECT `team` FROM `champions_league` WHERE `group` = '{$lname}' ORDER BY RAND()", __FILE__, __LINE__);
	for ($i = 0; $i <= 3; $i++)
	{
		for ($j = $i + 1; $j <= 3; $j++)
		{
			if ($data[$i][$j] != 0)
			{
				$home = $teams[$i];
				$away = $teams[$j];
				//
				$round = $data[$i][$j];
				$start = get_date_time(true, 0, mktime($rounds[$round]['hours'], $rounds[$round]['minutes'], 0) + 60*60*24*($rounds[$round]['days']));
				if ($round % 2 == 0) sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$lid}, {$season}, {$round}, {$start}, '{$home}', '{$away}', 'cl')", __FILE__, __LINE__);
				else sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$lid}, {$season},  {$round}, {$start}, '{$away}', '{$home}', 'cl')", __FILE__, __LINE__);
				//
				$round += 3;
				$start = get_date_time(true, 0, mktime($rounds[$round]['hours'], $rounds[$round]['minutes'], 0) + 60*60*24*($rounds[$round]['days']));
				if ($round % 2 == 1) sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$lid}, {$season}, {$round}, {$start}, '{$away}', '{$home}', 'cl')", __FILE__, __LINE__);
				else sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$lid}, {$season}, {$round}, {$start}, '{$home}', '{$away}', 'cl')", __FILE__, __LINE__);
			}
		}
	}
}
function generate_league_matches($season)
{
	$cur = 1;
	$all = 3280 * 20;
	$names = array("A", "B", "C", "D", "E", "F", "G", "H");
	for ($a = 0; $a <= 7; $a++)
	{
		$max = pow(3, $a);
		$name = $names[$a];
		for ($i = 1; $i <= $max; $i++)
		{
			$leag = "L{$name}.{$i}";
			$lid = sql_get("SELECT `id` FROM `match_type` WHERE `name` = '{$leag}'", __FILE__, __LINE__);
			generate_matches($cur, $lid, $season, $leag);
			$cur += 16;
			if ($i % 30 == 0) set_offline_status((($cur + 3280) / $all) * 100);
		}
	}
}
function generate_matches($from, $lid, $season, $lname)
{
	$days = array(0, 0, 1, 2, 3, 4, 7, 8, 9, 10, 11, 14, 15, 16, 17, 18, 21, 22, 23, 24, 25, 28, 29, 30, 31, 32, 35, 36, 37, 38, 39);
	$data[0] = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
	$data[1] = array(1, 0, 3, 2, 5, 4, 7, 6, 9, 8, 11, 10, 13, 12, 15, 14);
	$data[2] = array(2, 3, 0, 1, 6, 7, 4, 5, 10, 11, 8, 9, 14, 15, 12, 13);
	$data[3] = array(3, 2, 1, 0, 7, 6, 5, 4, 11, 10, 9, 8, 15, 14, 13, 12);
	$data[4] = array(4, 5, 6, 7, 0, 1, 2, 3, 12, 13, 14, 15, 8, 9, 10, 11);
	$data[5] = array(5, 4, 7, 6, 1, 0, 3, 2, 13, 12, 15, 14, 9, 8, 11, 10);
	$data[6] = array(6, 7, 4, 5, 2, 3, 0, 1, 14, 15, 12, 13, 10, 11, 8, 9);
	$data[7] = array(7, 6, 5, 4, 3, 2, 1, 0, 15, 14, 13, 12, 11, 10, 9, 8);
	$data[8] = array(8, 9, 10, 11, 12, 13, 14, 15, 0, 1, 2, 3, 4, 5, 6, 7);
	$data[9] = array(9, 8, 11, 10, 13, 12, 15, 14, 1, 0, 3, 2, 5, 4, 7, 6);
	$data[10] = array(10, 11, 8, 9, 14, 15, 12, 13, 2, 3, 0, 1, 6, 7, 4, 5);
	$data[11] = array(11, 10, 9, 8, 15, 14, 13, 12, 3, 2, 1, 0, 7, 6, 5, 4);
	$data[12] = array(12, 13, 14, 15, 8, 9, 10, 11, 4, 5, 6, 7, 0, 1, 2, 3);
	$data[13] = array(13, 12, 15, 14, 9, 8, 11, 10, 5, 4, 7, 6, 1, 0, 3, 2);
	$data[14] = array(14, 15, 12, 13, 10, 11, 8, 9, 6, 7, 4, 5, 2, 3, 0, 1);
	$data[15] = array(15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0);
	$teams = sql_array("SELECT `id` FROM `teams` WHERE `league` = '{$lname}' ORDER BY RAND()", __FILE__, __LINE__);
	for ($i = 0; $i <= 15; $i++)
	{
		for ($j = $i + 1; $j <= 15; $j++)
		{
			if ($data[$i][$j] != 0)
			{
				$home = $teams[$i];
				$away = $teams[$j];
				$round = $data[$i][$j];
				$start = get_date_time(true, 0, mktime(18, 0, 0) + 60*60*24*($days[$round]));
				if ($round % 2 == 0) sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`) VALUES ({$lid}, {$season}, {$round}, {$start}, '{$home}', '{$away}')", __FILE__, __LINE__);
				else sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`) VALUES ({$lid}, {$season},  {$round}, {$start}, '{$away}', '{$home}')", __FILE__, __LINE__);
				//
				$round += 15;
				$start = get_date_time(true, 0, mktime(18, 0, 0) + 60*60*24*($days[$round]));
				if ($round % 2 == 1) sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`) VALUES ({$lid}, {$season}, {$round}, {$start}, '{$away}', '{$home}')", __FILE__, __LINE__);
				else sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`) VALUES ({$lid}, {$season}, {$round}, {$start}, '{$home}', '{$away}')", __FILE__, __LINE__);
			}
		}
	}
}
function start_season($season)
{
	set_time_limit(0);
	ignore_user_abort(true);
	go_offline("Starting the new season! The game will be online soon!");
	sql_query("UPDATE `players` SET `age` = `age` + 1", __FILE__, __LINE__);
	sql_query("DELETE FROM `players` WHERE `age` >= ".PLAYERS_OLD, __FILE__, __LINE__);
	sql_query("UPDATE `staff` SET `age` = `age` + 1", __FILE__, __LINE__);
	sql_query("DELETE FROM `staff` WHERE `age` >= ".STAFF_OLD, __FILE__, __LINE__);
	if ($season > 1)
	{
		$letts = array("A", "B", "C", "D", "E", "F", "G", "H");
		// Money for topscorers
		$leagues = sql_get("SELECT MAX(`league`) FROM `players_stats`", __FILE__, __LINE__);
		for($i = 1; $i <= $leagues; $i++)
		{
			$data = sql_data("SELECT * FROM `players`, `players_stats` WHERE `players_stats`.`league` = '{$i}' AND `players`.`id` = `players_stats`.`id` ORDER BY `players_stats`.`cur_leag_goals` DESC LIMIT 1", __FILE__, __LINE__);
			add_to_money_history("{_PRIZE_FOR_THE_TOPSCORER_IN_THE_LEAGUE_}", TOPSCORER_PRIZE, $data['team'], true);
		}
		for($i = 1; $i <= $leagues; $i++)
		{
			$data = sql_data("SELECT *, (`players_stats`.`cur_leag_red` * 2 + `players_stats`.`cur_leag_yellow`) AS `points` FROM `players`, `players_stats` WHERE `players_stats`.`league` = '{$i}' AND `players`.`id` = `players_stats`.`id` ORDER BY `points` DESC LIMIT 1", __FILE__, __LINE__);
			add_to_money_history("{_FINE_FOR_THE_ROUGH_PLAYER_IN_THE_LEAGUE_}", -ROUGH_PLAYER_FINE, $data['team'], true);
		}
		// Select Champions League partisipants
		sql_query("TRUNCATE TABLE `champions_league`", __FILE__, __LINE__);
		sql_query("INSERT INTO `champions_league` (`team`) SELECT `id` FROM `teams`
			WHERE `league` = 'LA.1'
			ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC
			LIMIT 8", __FILE__, __LINE__);
		for($i = 1; $i <= 3; $i++)
		{
			sql_query("INSERT INTO `champions_league` (`team`) SELECT `id` FROM `teams`
				WHERE `league` = 'LB.{$i}'
				ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC
				LIMIT 5", __FILE__, __LINE__);
		}
		for($i = 1; $i <= 9; $i++)
		{
			sql_query("INSERT INTO `champions_league` (`team`) SELECT `id` FROM `teams`
				WHERE `league` = 'LC.{$i}'
				ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC
				LIMIT 1", __FILE__, __LINE__);
		}
		$clteams = sql_array("SELECT `id` FROM `champions_league` ORDER BY RAND()", __FILE__, __LINE__);
		for($i = 0; $i < 8; $i++)
		{
			$t1 = $clteams[$i * 4];
			$t2 = $clteams[$i * 4 + 1];
			$t3 = $clteams[$i * 4 + 2];
			$t4 = $clteams[$i * 4 + 3];
			sql_query("UPDATE `champions_league` SET `group` = '{$letts[$i]}' WHERE `id` IN ({$t1}, {$t2}, {$t3}, {$t4})", __FILE__, __LINE__);
		}
		// Promote and demote teams
		$teama = sql_data("SELECT `id`, `name` FROM `teams` WHERE `league` = 'LA.1' ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC LIMIT 1", __FILE__, __LINE__);
		add_to_league_history("<a href=\"teamdetails.php?id={$teama['id']}\">{$teama['name']}</a> {_HAS_WON_IN_} A", 1); // add to history
		add_to_team_history("<a href=\"teamdetails.php?id={$teama['id']}\">{$teama['name']}</a> {_HAS_WON_IN_} A", $teama['id']);
		add_to_trophy_history(substr($leaguename, 1), "league_A", $teama['id']);
		add_to_money_history("<a href=\"teamdetails.php?id={$teama['id']}\">{$teama['name']}</a> {_HAS_WON_IN_} A", LEAGUE_FIRST_BONUS, $teama['id'], true);
		// PROMOTE:
		for ($i = 1; $i < count($letts); $i++)
		{
			$lett = $letts[$i];
			print_flush("Promoting teams from league '{$lett}'");
			$leagues = sql_array("SELECT `id` FROM `match_type` WHERE `type` = 'League' AND `name` LIKE 'L{$lett}.%'", __FILE__, __LINE__);
			foreach ($leagues as $league)
			{
				$leaguename = sql_get("SELECT `name` FROM `match_type` WHERE `id` = {$league}", __FILE__, __LINE__);
				$num = substr($leaguename, strpos($leaguename, ".") + 1);
				$new = "L".$letts[$i - 1].".".ceil($num / 3.0);
				$teams = sql_array("SELECT `id` FROM `teams` WHERE `league` = '{$leaguename}' ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC LIMIT 2", __FILE__, __LINE__);
				$tn = 0;
				foreach ($teams as $team)
				{
					$tn++;
					if ($tn == 1)
					{
						$teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$team}", __FILE__, __LINE__);
						add_to_league_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_HAS_WON_IN_} ".substr($leaguename, 1), $league); // add to history
						add_to_team_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_HAS_WON_IN_} ".substr($leaguename, 1), $team);
						add_to_trophy_history(substr($leaguename, 1), "league_{$lett}", $team);
						if ($lett == "A")	add_to_money_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_HAS_WON_IN_} ".substr($leaguename, 1), 5000000, $team, true);
						else add_to_money_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_HAS_WON_IN_} ".substr($leaguename, 1), LEAGUE_FIRST_BONUS, $team, true);
					}
					else if ($tn == 2)
					{
						$teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$team}", __FILE__, __LINE__);
						add_to_league_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_WAS_IN_SECOND_PLACE_IN_} ".substr($leaguename, 1), $league); // add to history
						add_to_team_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_WAS_IN_SECOND_PLACE_IN_} ".substr($leaguename, 1), $team);
						add_to_money_history("<a href=\"teamdetails.php?id={$team}\">{$teamname}</a> {_WAS_IN_SECOND_PLACE_IN_} ".substr($leaguename, 1), LEAGUE_SECOND_BONUS, $team, true);
					}
					sql_query("UPDATE `teams` SET `league` = '{$new}' WHERE `id` = {$team}", __FILE__, __LINE__);
				}
				//print("2 * {$leaguename} ^ {$new}<br>\n");
				//flush();
				//ob_flush();
			}
		}
		br(3);
		//flush();
		//ob_flush();
		// DEMOTE:
		for ($i = count($letts) - 2; $i >= 0; $i--)
		{
			$lett = $letts[$i];
			print_flush("Demoting teams from league '{$lett}'");
			$leagues = sql_array("SELECT `id` FROM `match_type` WHERE `type` = 'League' AND `name` LIKE 'L{$lett}.%'", __FILE__, __LINE__);
			foreach ($leagues as $league)
			{
				$leaguename = sql_get("SELECT `name` FROM `match_type` WHERE `id` = {$league}", __FILE__, __LINE__);
				$num = substr($leaguename, strpos($leaguename, ".") + 1);
				$teams = sql_array("SELECT `id` FROM `teams` WHERE `league` = '{$leaguename}' ORDER BY `points` ASC, (`goalsscored` - `goalsconceded`) ASC, `goalsscored` ASC, `wins` ASC, `id` ASC LIMIT 6", __FILE__, __LINE__);
				$tn = 0;
				foreach ($teams as $team)
				{
					$tn++;
					$new = "L".$letts[$i + 1].".".(($num - 1) * 3 + ceil($tn / 2.0));
					sql_query("UPDATE `teams` SET `league` = '{$new}' WHERE `id` = {$team}", __FILE__, __LINE__);
					//if ($tn % 2 == 0) print("2 * {$leaguename} v {$new}<br>\n");
					//flush();
					//ob_flush();
				}
			}
		}
		set_offline_status((1 / 20) * 100);
	}
	//sql_query("FLUSH TABLES", __FILE__, __LINE__);
	// Updates leagues in players stats:
	sql_query("UPDATE `players_stats` SET `league` = 0", __FILE__, __LINE__);
	sql_query("UPDATE `players_stats` SET `league` = (SELECT `id` FROM `match_type` WHERE `name` = (SELECT `league` FROM `teams` WHERE `id` = (SELECT `team` FROM `players` WHERE `id` = `players_stats`.`id`)))", __FILE__, __LINE__);
	//sql_query("DELETE FROM `players_stats` WHERE `league` = 0", __FILE__, __LINE__);
	// Generate matches
	print_flush("Start generating champions league matches");
	generate_cl_matches($season);
	print_flush("Start generating league matches");
	generate_league_matches($season);
	print_flush("League matches generated successfully");
	set_offline_status((18 / 20) * 100);
	print_flush("Start generating cup matches");
	generate_cup_matches($season);
	print_flush("Cup matches generated successfully");
	set_offline_status((19 / 20) * 100);

	create_new_players();
	recalculate_player_ratings();
	recalculate_team_ratings();

	sql_query("UPDATE `teams` SET `cup` = 'yes', `total` = 0, `points` = 0, `wins` = 0, `draws` = 0, `loses` = 0, `goalsscored` = 0, `goalsconceded` = 0", __FILE__, __LINE__);
	sql_query("UPDATE `players_stats` SET `cur_leag_goals` = 0, `cur_leag_red` = 0, `cur_leag_yellow` = 0, `cur_leag_played` = 0, `cur_leag_inj` = 0, `cur_cup_goals` = 0, `cur_cup_red` = 0, `cur_cup_yellow` = 0, `cur_cup_played` = 0, `cur_cup_inj` = 0, `cur_fr_goals` = 0, `cur_fr_red` = 0, `cur_fr_yellow` = 0, `cur_fr_played` = 0, `cur_fr_inj` = 0", __FILE__, __LINE__);

	sql_query("UPDATE `config` SET `value` = 0 WHERE `name` = 'match'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = 0 WHERE `name` = 'round'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = 0 WHERE `name` = 'cupround'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = {$season} WHERE `name` = 'season'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = ".get_date_time()." WHERE `name` = 'started'", __FILE__, __LINE__);

	//sql_query("FLUSH TABLES", __FILE__, __LINE__);

	reset_cleanup();
	//full_cleanup();
	set_offline_status(100);
	go_online();
}
function generate_staff_members($type, $count)
{
	global $defaultnames;
	$type = sqlsafe($type);
	$n = 1;
	$firstnames[$n] = file("./include/names/{$defaultnames}_FirstNames.txt");
	$lastnames[$n] = file("./include/names/{$defaultnames}_LastNames.txt");
	$n++;
	$names = sql_query("SELECT `name` FROM `countries` WHERE `hasnames` = 'yes'", __FILE__, __LINE__);
	while ($name = mysql_fetch_assoc($names))
	{
		$firstnames[$n] = file("./include/names/{$name['name']}_FirstNames.txt");
		$lastnames[$n] = file("./include/names/{$name['name']}_LastNames.txt");
		$n++;
	}
	for ($i = 1; $i <= $count; $i++)
	{
		$nametype = rand(1, $n - 1);
		$name = sqlsafe(trim($firstnames[$nametype][rand(0, count($firstnames[$nametype]) - 1)]) . " " . trim($lastnames[$nametype][rand(0, count($lastnames[$nametype]) - 1)]));
		$rating = rand(15, 40);
		$age = rand(30, 60);
		sql_query("INSERT INTO `staff` (`name`, `type`, `rating`, `age`, `team`, `contrtime`, `atcourse`, `courseuntil`, `wage`) VALUES ({$name}, {$type}, {$rating}, {$age}, 0, 0, 'no', 0, 0)", __FILE__, __LINE__);
	}
}
function add_advertising_boards($teams)
{
	for ($i = 1; $i <= $teams; $i++)
	for ($j = 1; $j <= 10; $j++)
	sql_query("INSERT INTO `advboards` (`team`, `adv`, `board`, `left`) VALUES ('{$i}', '0', '{$j}', '0')", __FILE__, __LINE__);
}
function print_flush($text, $bold = false)
{
	if ($bold) print("<b>{$text}</b><br>\n");
	else print("{$text}<br>\n");
	flush();
	ob_flush();
}
function start_game()
{
	set_time_limit(0);
	flush();
	ob_flush();
	// prevent cleaning from user while starting game
	sql_query("UPDATE `config` SET `value` = '1' WHERE `name` = 'cleaning'", __FILE__, __LINE__);
	// match_type
	$names = array("A", "B", "C", "D", "E", "F", "G", "H");
	for ($a = 0; $a <= 7; $a++)
	{
		print_flush("Generating teams for league '{$names[$a]}'!", true);
		$max = pow(3, $a);
		$name = $names[$a];
		for ($i = 1; $i <= $max; $i++)
		{
			$leag = "L{$name}.{$i}";
			if ($i % 10 == 0)	print_flush("Current league generation status: {$leag}");
			sql_query("INSERT INTO `match_type` (`name`, `createdby`, `created`, `fee`, `participants`, `teams`, `type`) VALUES ('{$leag}', '0', ".get_date_time(true).", '0', '16', '16', 'League')", __FILE__, __LINE__);
			generate_teams($leag, 16, mysql_insert_id());
		}
	}
	sql_query("INSERT INTO `match_type` (`name`, `createdby`, `created`, `fee`, `participants`, `teams`, `type`) VALUES ('CUP', '0', ".get_date_time(true).", '0', '52480', 'all', 'Cup')", __FILE__, __LINE__);
	add_advertising_boards(52480);
	print_flush("Advertising boards added", true);
	generate_staff_members('coach', 52480);
	print_flush("Coaches generated", true);
	generate_staff_members('scout', 52480);
	print_flush("Scouts generated", true);
	generate_staff_members('doctor', 52480);
	print_flush("Doctors generated", true);
	print_flush("Starting new season...", true);
	start_season(1);
	print_flush("New season started", true);
	// prevent cleaning from user while starting game
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'cleaning'", __FILE__, __LINE__);
	print_flush("The game is installed successfully!!!", true);
}
function mobio_checkcode($code, $servID = 1156, $debug = 1)
{
	$res_lines = file("http://www.mobio.bg/code/checkcode.php?servID={$servID}&code={$code}");
	$ret = 0;
	if($res_lines)
	{
		if(strstr("PAYBG=OK", $res_lines[0])) $ret = 1;
		else if($debug) echo $line."\n";
	}
	else
	{
		if($debug) echo "Unable to connect to mobio.bg server.\n";
		$ret = 0;
	}
	return $ret;
}
function get_odds_by_ind($ind)
{
	if ($ind == 0)  return array(1.00, 1.00, 1.00);
	if ($ind == 1)  return array(1.00, 1.00, 1.00);
	if ($ind == 2)  return array(2.30, 2.80, 2.30);
	if ($ind == 3)  return array(2.25, 2.95, 2.25);
	if ($ind == 4)  return array(2.20, 3.05, 2.25);
	if ($ind == 5)  return array(2.15, 2.95, 2.35);
	if ($ind == 6)  return array(2.10, 3.00, 2.40);
	if ($ind == 7)  return array(2.05, 3.00, 2.45);
	if ($ind == 8)  return array(2.00, 2.95, 2.55);
	if ($ind == 9)  return array(1.95, 3.00, 2.60);
	if ($ind == 10) return array(1.90, 2.90, 2.80);
	if ($ind == 11) return array(1.85, 2.90, 2.90);
	if ($ind == 12) return array(1.80, 2.95, 3.00);
	if ($ind == 13) return array(1.75, 3.00, 3.10);
	if ($ind == 14) return array(1.70, 3.00, 3.25);
	if ($ind == 15) return array(1.65, 3.15, 3.30);
	if ($ind == 16) return array(1.60, 3.15, 3.50);
	if ($ind == 17) return array(1.55, 3.15, 3.75);
	if ($ind == 18) return array(1.50, 3.20, 4.00);
	if ($ind == 19) return array(1.45, 3.45, 4.00);
	if ($ind == 20) return array(1.40, 3.50, 4.35);
	if ($ind == 21) return array(1.35, 3.50, 5.00);
	if ($ind == 22) return array(1.30, 3.60, 5.50);
	if ($ind == 23) return array(1.25, 3.85, 6.00);
	if ($ind == 24) return array(1.20, 4.00, 7.00);
	if ($ind == 25) return array(1.15, 4.30, 8.00);
	if ($ind == 26) return array(1.10, 4.55, 10.00);
	if ($ind == 27) return array(1.05, 4.80, 15.00);
	if ($ind == 28) return array(1.02, 6.00, 30.00);
	if ($ind == 29) return array(1.01, 8.00, 40.00);
	if ($ind == 30) return array(1.00, 1.00, 1.00);
}
function get_odds_ind($diff)
{
	if ($diff <= 2)  return 1;
	if ($diff <= 4)  return 2;
	if ($diff <= 6)  return 3;
	if ($diff <= 8)  return 4;
	if ($diff <= 10) return 5;
	if ($diff <= 12) return 6;
	if ($diff <= 14) return 7;
	if ($diff <= 16) return 8;
	if ($diff <= 18) return 9;
	if ($diff <= 20) return 10;
	if ($diff <= 22) return 11;
	if ($diff <= 24) return 12;
	if ($diff <= 26) return 13;
	if ($diff <= 28) return 14;
	if ($diff <= 30) return 15;
	if ($diff <= 32) return 16;
	if ($diff <= 34) return 17;
	if ($diff <= 36) return 18;
	if ($diff <= 38) return 19;
	if ($diff <= 40) return 20;
	if ($diff <= 42) return 21;
	if ($diff <= 46) return 22;
	if ($diff <= 50) return 23;
	if ($diff <= 55) return 24;
	if ($diff <= 60) return 25;
	if ($diff <= 65) return 26;
	if ($diff <= 70) return 27;
	if ($diff <= 80) return 28;
	if ($diff <= 90) return 29;
	else return 30;
}
function format_odds($odds_ind, $better, $played, $matchid, $links = true)
{
	$odds = get_odds_by_ind($odds_ind);
	if ($better == 0)
	{
		$odds[0] = "-";
		$odds[1] = "-";
		$odds[2] = "-";
		return $odds;
	}
	if ($better == 2)
	{
		$swap = $odds[0];
		$odds[0] = $odds[2];
		$odds[2] = $swap;
	}
	$odds[0] = number_format($odds[0], 2, ".", "");
	$odds[1] = number_format($odds[1], 2, ".", "");
	$odds[2] = number_format($odds[2], 2, ".", "");
	if ($links && $played == 'no')
	{
		if ($odds[0] != 1.00) $odds[0] = create_link("makebet.php?match={$matchid}&type=0", $odds[0]);
		else $odds[0] = "N/A";
		if ($odds[1] != 1.00) $odds[1] = create_link("makebet.php?match={$matchid}&type=1", $odds[1]);
		else $odds[1] = "N/A";
		if ($odds[2] != 1.00) $odds[2] = create_link("makebet.php?match={$matchid}&type=2", $odds[2]);
		else $odds[2] = "N/A";
	}
	else
	{
		if ($odds[0] == 1.00) $odds[0] = "N/A";
		if ($odds[1] == 1.00) $odds[1] = "N/A";
		if ($odds[2] == 1.00) $odds[2] = "N/A";
	}
	return $odds;
}
function aprint($arr, $prefix = '')
{
	if (ltrim($prefix) != '') $prefix = '<span style="color:#336699">' . $prefix . '</span> =';
	echo "\n\n<table style=\"width:100%; margin:1px; background:#F0F2F4; border:1px solid #D8DDE6;\"><tbody><tr><td style=\"font-size: 12px;\"><pre style=\"color:#000000;\">$prefix" . _aprint($arr) . "</pre></td></tr></tbody></table>\n\n";
	// overflow:auto;
}
function _aprint($arr, $tab = 1) // similar to print_r()
{
	if (!is_array($arr)) return " <span style=\"color:#336699\">" . ucfirst(gettype($arr)) . "</span> " . _slashes($arr);
	$space = str_repeat("\t", $tab);
	$out = " <span style=\"color:#336699\">array(</span>\n";
	end($arr);
	$end = key($arr);
	if (count($arr) == 0) return "<span style=\"color:#336699\">array()</span>";
	foreach ($arr as $key => $val)
	{
		if (!is_numeric($key))
		{
			if ($key == $end) $colon = '';
			else $colon = ',';
			if (!is_numeric($key)) $key = "<span style=\"color:#993366\">'" . str_replace(array("\\", "'"), array("\\\\", "\'"), htmlspecialchars($key)) . "'</span>";
			if (is_array($val)) $val = _aprint($val, ($tab + 1));
			else if (!is_numeric($val)) $val = "<span style=\"color:#993366\">'" . str_replace(array("\\", "'"), array("\\\\", "\'"), htmlspecialchars($val)) . "'</span>";
			$out .= "$space$key => $val$colon\n";
		}
	}
	if ($tab == 1) return "$out$space<span style=\"color:#336699\">)</span>;";
	else return "$out$space<span style=\"color:#336699\">)</span>";
}
function get_string_between($string, $start, $end)
{
   $string = " " . $string;
   $ini = strpos($string, $start);
   if ($ini == 0) return "";
   $ini += strlen($start);
   $len = strpos($string, $end, $ini) - $ini;
   return substr($string, $ini, $len);
}
?>
