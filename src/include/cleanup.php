<?php
/*
File name: cleanup.php
Last change: Fri Feb 15 20:25:56 EET 2008
Copyright: NRPG (c) 2008
*/
// find /opt/lampp/htdocs/managerteams/cache/match -type f -mtime +7 | xargs rm
function full_cleanup()
{
	global $config;
	if ($config['cleaning'] == 1) return;
	set_time_limit(0);
	ignore_user_abort(true);
	sql_query("UPDATE `config` SET `value` = '1' WHERE `name` = 'cleaning'", __FILE__, __LINE__);
	cleanup("last_update1", 60);
	cleanup("last_update5", 300);
	cleanup("last_update15", 900);
	cleanup("last_update30", TIME_HOUR / 2);
	cleanup("last_update60", TIME_HOUR);
	cleanup("last_update120", TIME_HOUR * 2);
	cleanup("last_update6", TIME_HOUR * 6);
	cleanup("last_update24", TIME_DAY);
	cleanup("last_update7", TIME_WEEK);
	cleanup("last_update3w", TIME_WEEK * 3);
	cleanup("last_update4w", TIME_WEEK * 4);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'cleaning'", __FILE__, __LINE__);
	ignore_user_abort(false);
}
function reset_cleanup()
{
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update1'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update5'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update15'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update30'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update60'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update120'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update6'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update24'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update7'", __FILE__, __LINE__);
	//sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update3w'", __FILE__, __LINE__);
	//sql_query("UPDATE `config` SET `value` = '0' WHERE `name` = 'last_update4w'", __FILE__, __LINE__);
}
function cleanup($name, $seconds, $now = false)
{
	global $config;
	if ($config[$name] <= get_date_time(false, -$seconds, 0, false) || $now)
	{
		if ($name == "last_update1")
		{
			include_once("./include/match_simulation.php");
			playmatches();
			//include_once("./include/game_simulation.php");
			//play_matches();
			cache_stats();
			deal_with_transfers();
			sql_query("UPDATE `teams` SET `daysminus` = 0 WHERE `money` >= ".MONEY_WARN, __FILE__, __LINE__);
		}
		else if ($name == "last_update5")
		{
			deal_with_bank();
			deal_with_updates();
			deal_with_friendly_cups();
			deal_with_staff_courses();
			// News
			deal_with_news();
		}
		else if ($name == "last_update15")
		{
			cache_top_managers();
			// Odds
			deal_with_odds();
		}
		else if ($name == "last_update30")
		{
			// Deal with VIP Users
			sql_query("UPDATE `users` SET `class` = 2 WHERE `class` = 3 AND `vipuntil` < ".get_date_time(true), __FILE__, __LINE__);
			// Remove bots fans
			sql_query("UPDATE `teams` SET `fanbase` = 1000 WHERE `free` = 'yes'", __FILE__, __LINE__);
			mail_info_to_coder();
		}
		else if ($name == "last_update60")
		{
			cache_current_poll();
			cache_polls_options();
			cache_polls_resuls();
			deal_with_match_of_the_week();
		}
		else if ($name == "last_update120")
		{
			sql_query("DELETE FROM `money_history` WHERE `eventtime` <= ".get_date_time(true, -TIME_WEEK*4), __FILE__, __LINE__);
		}
		else if ($name == "last_update6")
		{
		}
		else if ($name == "last_update24")
		{
			if ($config['match'] == $config['matchcount']) start_season($config['season'] + 1);
			else
			{
				deal_with_lottery();
				sql_query("DELETE FROM `friendly_invitations` WHERE `date` < '".substr(get_date_time(false), 0, 10)."'", __FILE__, __LINE__);
				deal_with_staff_contracts(); // Time 2-3 sec.
				deal_with_staff_salaries(); // Time: 25-26 sec.
				deal_with_players_contracts(); // Time: ~80sec.
				deal_with_players_salaries(); // Time: ~66sec.
				add_new_players_to_teams_with_notenought_players();
				//deal_with_inactive_users(); // Time: 1-2 sec.
				deal_with_loans(); // Time: 1 sec.
				deal_with_advboards(); // Time 1 sec.
				fanshop_incomes();
				recalculate_player_ratings();
				recalculate_team_ratings();
				redeal_with_odds();
				sql_query("DELETE FROM `messages` WHERE `readstatus` = 'yes' AND `timesent` <= ".get_date_time(true, -TIME_DAY * 5), __FILE__, __LINE__);
				sql_query("DELETE FROM `messages` WHERE `readstatus` = 'no' AND `timesent` <= ".get_date_time(true, -TIME_WEEK * 2), __FILE__, __LINE__);
				sql_query("UPDATE `players_stats` SET `league` = 0", __FILE__, __LINE__);
				sql_query("UPDATE `players_stats` SET `league` = (SELECT `id` FROM `match_type` WHERE `name` = (SELECT `league` FROM `teams` WHERE `id` = (SELECT `team` FROM `players` WHERE `id` = `players_stats`.`id`)))", __FILE__, __LINE__);
				sql_query("UPDATE `teams` SET `daysminus` = `daysminus` + 1 WHERE `money` < ".MONEY_WARN, __FILE__, __LINE__);
				sql_query("UPDATE `players` SET `injured` = `injured` - 1 WHERE `injured` > 0", __FILE__, __LINE__);
				sql_query("UPDATE `players` SET `fitness` = 99 WHERE `fitness` >= 64 ", __FILE__, __LINE__);
				sql_query("UPDATE `players` SET `fitness` = `fitness` + 35 WHERE `fitness` < 64", __FILE__, __LINE__);
				sql_query("UPDATE `config` SET `value` = `value` + 1 WHERE `name` = 'match'", __FILE__, __LINE__);
				deal_with_players_training();
			}
		}
		else if ($name == "last_update7")
		{
			sql_query("UPDATE `users` SET `weekpoints` = 0", __FILE__, __LINE__);
		}
		else if ($name == "last_update3w")
		{
			if ($config['match'] >= 21 && $config['match'] <= 22) generate_cl_final_matches($config['season']);
			//send_inactive_mail_bg();
		}
		else if ($name == "last_update4w")
		{
		}
		// Update time
		if ($name != "last_update24" || $config['match'] != $config['matchcount'])
		{
			if ($name == "last_update24")
			{
				sql_query("UPDATE `config` SET `value` = '".date("Y-m-d", time())." 01:30:00' WHERE `name` = '{$name}' LIMIT 1", __FILE__, __LINE__);
			}
			else sql_query("UPDATE `config` SET `value` = ".get_date_time()." WHERE `name` = '{$name}' LIMIT 1", __FILE__, __LINE__);
		}
	}
}
function deal_with_lottery()
{
	$count = sql_get("SELECT COUNT(*) FROM `lottery`", __FILE__, __LINE__);
	if (!$count)
	{
		$winnerinfo = sqlsafe("Няма продадени билети за лотарията и няма победител.");
		sql_query("UPDATE `config` SET `value` = {$winnerinfo} WHERE `name` = 'lottery_winner' LIMIT 1", __FILE__, __LINE__);
		sql_query("TRUNCATE TABLE `lottery`", __FILE__, __LINE__);
		return;
	}
	$winner = sql_data("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `lottery`.`user`) AS `winnername`, (SELECT `name` FROM `teams` WHERE `id` = `lottery`.`team`) AS `winnerteamname` FROM `lottery` ORDER BY RAND() LIMIT 1", __FILE__, __LINE__);
	add_to_money_history("Печалба от лотарията", +$count*LOTTERY_TICKET*LOTTERY_WIN, $winner['team'], true);
	$winnerinfo = sqlsafe("Победител в лотарията е потребител {$winner['winnername']} с отбор {$winner['winnerteamname']} и билет номер #{$winner['id']}!");
	sql_query("UPDATE `config` SET `value` = {$winnerinfo} WHERE `name` = 'lottery_winner' LIMIT 1", __FILE__, __LINE__);
	sql_query("TRUNCATE TABLE `lottery`", __FILE__, __LINE__);
}
function cache_current_poll()
{
	$poll = sql_get("SELECT `id` FROM `polls` WHERE `active` = 'yes' ORDER BY `id` DESC LIMIT 1", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '{$poll}' WHERE `name` = 'current_poll'", __FILE__, __LINE__);
}
function cache_polls_options()
{
	$polls = sql_query("SELECT * FROM `polls`", __FILE__, __LINE__);
	while ($poll = mysql_fetch_assoc($polls))
	{
		$res = "<b>{$poll['question']}</b><br><br>\n";
		$res .= "<form action=\"polls_vote.php\" method=\"POST\">\n";
		$res .= "<input type=\"hidden\" name=\"id\" value=\"{$poll['id']}\">\n";
		for($i = 1; $i <= 20; $i++) if ($poll["option_{$i}"]) $res .= "<label for=\"option_{$i}\"><input type=\"radio\" name=\"option\" value=\"{$i}\" id=\"option_{$i}\" />" . $poll["option_{$i}"] . "</label><br>\n";
		$res .= "<label for=\"option_0\"><input type=\"radio\" name=\"option\" value=\"0\" id=\"option_0\" />Празен глас</label><br>\n";
		$res .= "<br><input type=\"submit\" value=\"Гласувай!\" />\n";
		$res .= "</form>\n";
		$file = fopen("./cache/polls/{$poll['id']}_options.cache", "w");
		fwrite($file, $res);
		fclose($file);
	}
}
function cache_polls_resuls()
{
	$polls = sql_query("SELECT * FROM `polls`", __FILE__, __LINE__);
	while ($poll = mysql_fetch_assoc($polls))
	{
		$res = "<b>{$poll['question']}</b><br><br>\n";
		$all_votes = sql_get("SELECT COUNT(`id`) FROM `poll_votes` WHERE `poll` = '{$poll['id']}'", __FILE__, __LINE__);
		for($i = 1; $i <= 20; $i++) if ($poll["option_{$i}"])
		{
			$votes = sql_get("SELECT COUNT(`id`) FROM `poll_votes` WHERE `poll` = '{$poll['id']}' AND `option` = '{$i}'", __FILE__, __LINE__);
			$vote_text = $votes == 1 ? "глас" : "гласа";
			$perc = $all_votes > 0 ? $votes / $all_votes * 100 : 0;
			$res .= create_progress_bar($perc, 175) . $poll["option_{$i}"] . "&nbsp;({$votes})<br><br>\n";
		}
		$votes = sql_get("SELECT COUNT(`id`) FROM `poll_votes` WHERE `poll` = '{$poll['id']}' AND `option` = '0'", __FILE__, __LINE__);
      $vote_text = $votes == 1 ? "глас" : "гласа";
		$perc = $all_votes > 0 ? $votes / $all_votes * 100 : 0;
		$res .= create_progress_bar($perc, 175) . "Празен глас ({$votes})<br>\n";
		$res .= "<br>Общ брой гласове: ({$all_votes})<br>\n";
		$file = fopen("./cache/polls/{$poll['id']}_results.cache", "w");
		fwrite($file, $res);
		fclose($file);
	}
}
function deal_with_bank()
{
	$teams = sql_query("SELECT `id`, `bank_in`, `bank_out`, `bank_until` FROM `teams` WHERE `bank_in` > 0 AND `bank_until` < ".get_date_time(), __FILE__, __LINE__);
	while($team = mysql_fetch_assoc($teams))
	{
		add_to_money_history("{_DEPOSIT_}", +$team['bank_out'], $team['id'], true);
		sql_query("UPDATE `teams` SET `bank_in` = 0, `bank_out` = 0 WHERE `id` = {$team['id']}", __FILE__, __LINE__);
	}
}
function deal_with_match_of_the_week()
{
	$match = sql_data("SELECT `id`, `homescore`, `awayscore`, ABS(`homescore` - `awayscore`) AS `diff`, (`homescore` + `awayscore`) AS `sum`,
   (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`,
   (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname`
   FROM `match` WHERE `start` >= ".get_date_time(true, 0, mktime(0, 0, 0, date('m'), date('d')-date('N')+1, date('Y')))." ORDER BY `sum` DESC, `diff` ASC LIMIT 1", __FILE__, __LINE__);
	$match_id = $match['id'];
	$match_cache = "<center><a href=\"matchreport.php?id={$match['id']}\">{$match['homename']} - {$match['awayname']} {$match['homescore']}:{$match['awayscore']}</a></center>";
	sql_query("UPDATE `config` SET `value` = '{$match_id}' WHERE `name` = 'match_of_week'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '{$match_cache}' WHERE `name` = 'match_of_week2'", __FILE__, __LINE__);
}
function fanshop_incomes()
{
	$stads = sql_query("SELECT `id`, `fanshop`, (SELECT `id` FROM `teams` WHERE `stadium` = `stadiums`.`id`) AS `teamid` FROM `stadiums` WHERE `fanshop` > 0", __FILE__, __LINE__);
	while ($stad = mysql_fetch_assoc($stads)) add_to_money_history("{_FANSHOP_}", +get_fanshop_income($stad['fanshop']), $stad['teamid'], true);
}
function mail_info_to_coder()
{
	global $config;
	$stylefile = "styles/new/style2.css";
	$stylehandle = fopen($stylefile, "r");
	$style = fread($stylehandle, filesize($stylefile));
	fclose($stylehandle);
	$mess = "<html><head><style type=\"text/css\">{$style}</style></head><body>";
	// `config` table
	$data = sql_query("SELECT * FROM `config` ORDER BY `id` ASC", __FILE__, __LINE__);
	$mess .= "<b><font color='white'>Config:</font></b><br>";
	$mess .= "<table class='specialtable_login' border=\"1\" width=100%>";
	$mess .= "<tr><th>id</th><th>name</th><th>value</th></tr>";
	while ($row = mysql_fetch_assoc($data))
	{
		$mess .= "<tr>";
		$mess .= "<td>{$row['id']}</td>";
		$mess .= "<td>{$row['name']}</td>";
		$mess .= "<td>{$row['value']}</td>";
		$mess .= "</tr>";
	}
	$mess .= "</table><br>";
	// `paytries` table
	$data = sql_query("SELECT * FROM `paytries` ORDER BY `id` DESC LIMIT 15", __FILE__, __LINE__);
	$mess .= "<b><font color='white'>Paytries:</font></b><br>";
	$mess .= "<table class='specialtable_login' border=\"1\" width=100%>";
	$mess .= "<tr><th>id</th><th>time</th><th>ip</th><th>user</th><th>text</th><th>success</th><th>type</th></tr>";
	while ($row = mysql_fetch_assoc($data))
	{
		$mess .= "<tr>";
		$mess .= "<td>{$row['id']}</td>";
		$mess .= "<td>{$row['time']}</td>";
		$mess .= "<td>{$row['ip']}</td>";
		$mess .= "<td>{$row['user']}</td>";
		$mess .= "<td>{$row['text']}</td>";
		$mess .= "<td>{$row['success']}</td>";
		$mess .= "<td>{$row['type']}</td>";
		$mess .= "</tr>";
	}
	$mess .= "</table>";
	$mess .= "<font color='white'><b>Server information:</b><br>";
	$uptime = @exec('uptime');
	preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$uptime,$avgs);
	$uptime = explode(' up ', $uptime);
	$uptime = explode(',', $uptime[1]);
	$uptime = $uptime[0].', '.$uptime[1];
	$start = mktime(0, 0, 0, 1, 1, date("Y"), 0);
	/* Make date 1/1/(current year) */
	$end = mktime(0, 0, 0, date("m"), date("j"), date("y"), 0);
	/* Make todays date */
	$diff = $end-$start;
	$days = $diff/86400.0;
	$percentage = $days > 0 ? ($uptime/$days) * 100 : 0;
	$load = $avgs[1].", ".$avgs[2].", ".$avgs[3];
	$mess .= "Average Load: ".$load."<br>";
	$mess .= "Uptime: ".$uptime."<br>";
	$mess .= "Persentage: ".$percentage."%";
	$mess .= "</font></body></html>";
	// Send mail
	$headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
	mail("nikolaybackup@yahoo.com", "[".get_date_time(false)."] ".GAME_NAME." coders info", $mess, $headers);
}
function recalculate_team_ratings()
{
	$data = sql_query("SELECT `team`, COUNT(`id`) AS `count`, SUM(`global`) AS `sum` FROM `players` GROUP BY `team`", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data))
	{
		$rating = (round($row['sum'] / $row['count']));
		//prnt("$rating = {$row['sum']} / {$row['count']};<br>");
		sql_query("UPDATE `teams` SET `global` = {$rating} WHERE `id` = {$row['team']}", __FILE__, __LINE__);
	}
}
function redeal_with_odds()
{
	$data = sql_query("SELECT *, (SELECT `odds_points` / `odds_matches` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homerate`, (SELECT `odds_points` / `odds_matches` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayrate` FROM `match` WHERE `played` = 'no' AND `hometeam` <= ".MAX_TEAMS." AND `awayteam` <= ".MAX_TEAMS, __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data))
	{
		$odds = get_odds_ind(abs($row['homerate'] - $row['awayrate']));
		$better = $row['homerate'] > $row['awayrate'] ? 1 : 2;
		sql_query("UPDATE `match` SET `odds` = {$odds}, `better` = '{$better}' WHERE `id` = {$row['id']}", __FILE__, __LINE__);
	}
}
function deal_with_odds()
{
	$data = sql_query("SELECT *, (SELECT `odds_points` / `odds_matches` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homerate`, (SELECT `odds_points` / `odds_matches` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayrate` FROM `match` WHERE `odds` = 0 AND `played` = 'no' AND `hometeam` <= ".MAX_TEAMS." AND `awayteam` <= ".MAX_TEAMS, __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data))
	{
		$odds = get_odds_ind(abs($row['homerate'] - $row['awayrate']));
		$better = $row['homerate'] > $row['awayrate'] ? 1 : 2;
		sql_query("UPDATE `match` SET `odds` = {$odds}, `better` = '{$better}' WHERE `id` = {$row['id']}", __FILE__, __LINE__);
	}
}
function cache_stats()
{
	$registred = sql_get("SELECT COUNT(`id`) FROM `users`", __FILE__, __LINE__);
	$onlinemanagers = sql_get("SELECT COUNT(`id`) FROM `users` WHERE `lastaction` > " . get_date_time(true, -900), __FILE__, __LINE__);
	$fans = sql_get("SELECT SUM(`fanbase`) FROM `teams`", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '{$registred}' WHERE `name` = 'registered'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '{$onlinemanagers}' WHERE `name` = 'onlinemanagers'", __FILE__, __LINE__);
	sql_query("UPDATE `config` SET `value` = '{$fans}' WHERE `name` = 'fans'", __FILE__, __LINE__);
}
function recalculate_player_ratings()
{
	$data = sql_query("SELECT * FROM `players`", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data))
	{
		$abb = get_trainig_abb($row['possition']);
		$total = 0;
		foreach ($abb as $value) $total += $row[get_abb_id($value)];
		$total /= count($abb);
		sql_query("UPDATE `players` SET `global` = {$total} WHERE `id` = {$row['id']}", __FILE__, __LINE__);
	}
}
function create_new_players()
{
	$data = sql_query("SELECT `id`, `cup`, `free`,
   (SELECT `id` FROM `match_type` WHERE `name` = `teams`.`league`) AS `leagueid`,
   (SELECT `youthcenter` FROM `stadiums` WHERE `id` = `teams`.`stadium`) AS `youthcenter`,
   (SELECT `country` FROM `users` WHERE `team` = `teams`.`id`) AS `country` FROM `teams`", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data))
	{
		$players_num = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = {$row['id']}", __FILE__, __LINE__);
		if ($row['youthcenter'] > 0 && $players_num <= MAXIMUM_PLAYERS_IN_TEAM + 2 && $row['free'] == 'no')
		{
			$nums = array();
			$numbers = sql_array("SELECT `number` FROM `players` WHERE `team` = '{$row['id']}'", __FILE__, __LINE__);
			foreach ($numbers as $num) $nums[$num] = true;
			$number = 0;
			for($i = 1; $i <= 99; $i++) if (!$nums[$i]) { $number = $i; break; }
			generate_player($row['country'], get_random_name($row['country']), 16, get_random_possition(), $row['id'], get_youthcenter($row['youthcenter']), $number, 0, $row['leagueid'], $row['cup'], $row['youthcenter']);
		}
	}
}
function deal_with_friendly_cups()
{
	global $config;
	$data = sql_query("SELECT * FROM `match_type` WHERE `type` = 'Friendly cup' AND `started` = 'no' AND `participants` = POW(2, CAST(`teams` AS SIGNED)+1)", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data))
	{
		$startat = $row['startat'] < 10 ? "0".$row['startat'].":30:00" : $row['startat'].":30:00";
		$participants = sql_query("SELECT * FROM `friendly_participants` WHERE `type` = {$row['id']} ORDER BY RAND()", __FILE__, __LINE__);
		while ($participant = mysql_fetch_assoc($participants))
		{
			$participant2 = mysql_fetch_assoc($participants);
			$start = sqlsafe(substr(get_date_time(false, TIME_DAY*FRCUP_START_AFTER), 0, 10)." ".$startat);
			sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$row['id']}, {$config['season']}, '1', {$start}, {$participant['team']}, {$participant2['team']}, 'frcup')", __FILE__, __LINE__);
		}
		$i = 0;
		while (true)
		{
			$i++;
			$participants = sql_query("SELECT * FROM `match` WHERE `type` = {$row['id']} AND `round` = {$i} ORDER BY RAND()", __FILE__, __LINE__);
			if (mysql_numrows($participants) <= 1) break;
			while ($participant = mysql_fetch_assoc($participants))
			{
				$participant2 = mysql_fetch_assoc($participants);
				$start = sqlsafe(substr(get_date_time(false, TIME_DAY*(FRCUP_START_AFTER + $i)), 0, 10)." ".$startat);
				$round = $i + 1;
				sql_query("INSERT INTO `match` (`type`, `season`, `round`, `start`, `hometeam`, `awayteam`, `rules`) VALUES ({$row['id']}, {$config['season']}, '{$round}', {$start}, '{$participant['id']}', '{$participant2['id']}', 'frcup')", __FILE__, __LINE__);
			}
		}
		sql_query("UPDATE `match_type` SET `started` = 'yes' WHERE `id` = {$row['id']}", __FILE__, __LINE__);
	}
}
function cache_top_managers()
{
	$fh = fopen("cache/topmanagers.cache", "w");
	$topmanagerstext = "";
	$topmanagers = sql_query("SELECT * FROM `users` ORDER BY `weekpoints` DESC, `id` ASC LIMIT ".MAX_BEST_MANAGERS, __FILE__, __LINE__);
	$i = 1;
	while ($row = mysql_fetch_assoc($topmanagers))
	{
		if ($row['class'] >= UC_VIP_USER) $star = "<img src=\"images/star.gif\" alt=\"VIP\">";
		else $star = "";
		$topmanagerstext .= ($i++).". <a href=\"viewprofile.php?id={$row['id']}\">{$row['username']}{$star}</a> - {$row['weekpoints']} pts.<br>\n";
	}
	fwrite($fh, $topmanagerstext."<br>");
	fclose($fh);
}

// News:
function deal_with_news()
{
   read_news_links("http://topsport.ibox.bg/rss_2", true);
   read_news_links("http://topsport.ibox.bg/rss_3", true);
   read_news();
   cache_news();
}
function cache_news()
{
	$fh = fopen("cache/tdmiddleleft.cache", "w");
	$newstext = "";
	$newss = sql_query("SELECT * FROM `news` ORDER BY `time` DESC LIMIT ".MAX_NEWS, __FILE__, __LINE__);
	while ($news = mysql_fetch_assoc($newss))
	{
      $newstext .= "» <a href=\"viewnews.php?id={$news['id']}\">{$news['name']}</a> <small>({$news['time']})</small><br />\n";
	}
	$newstext .= "<br />» <a href=\"viewnews.php\">Списък с всички новини, добавени в сайта...</a><br />\n";
	fwrite($fh, $newstext);
	fclose($fh);
}
function read_news()
{
   $links = sql_query("SELECT * FROM `news_links` WHERE `visited` = 'no'", __FILE__, __LINE__);
   while ($link = mysql_fetch_assoc($links))
   {
      if (strstr($link['link'], "ibox.bg") !== false) read_news_ibox($link);
      sql_query("UPDATE `news_links` SET `visited` = 'yes' WHERE `id` = '{$link['id']}'", __FILE__, __LINE__);
   }
}
function read_news_ibox($link)
{
   $handle = fopen($link['link'], "rb");
   $contents = stream_get_contents($handle);
   fclose($handle);
   //$contents = iconv('CP1251', 'UTF-8', $contents);
   //$contents = mb_convert_encoding($contents, "UTF-8", "WINDOWS-1251");
   $time = get_date_time(true, 0, strtotime(str_replace(".", "-", trim(get_string_between($contents, "<p class=\"reated-datetime\">Публикуване:", "</p>"))).":00"));
   $name = sqlsafe(trim(get_string_between($contents, "<h2 class=\"title\">", "</h2>")));
   $image = trim(stripcslashes(get_string_between($contents, "<div class=\"material-picture\">", "</div>")));
   $image = trim(get_string_between($image, "src=\"", "\""));
   if ($image) $content = "<img src=\"".$image."\" align='right' />";
   else $content = "";
   $content .= trim(stripcslashes(get_string_between($contents, "<div class=\"EasyadsIntext\">", "</div>")));
   $content = sqlsafe($content);
   $link2 = sqlsafe($link['link']);
   sql_query("INSERT INTO `news` (`name`, `content`, `from`, `time`) VALUES ({$name}, {$content}, {$link2}, {$time})", __FILE__, __LINE__);
}
function read_news_links($link, $not_utf8 = false)
{
   $handle = fopen($link, "rb");
   $contents = stream_get_contents($handle);
   fclose($handle);
   if ($not_utf8)
   {
      $contents = iconv('CP1251', 'UTF-8', $contents);
      //$contents = mb_convert_encoding($contents, "UTF-8", "WINDOWS-1251");
      $contents = str_replace("windows-1251", "utf-8", $contents);
   }
   // Create an xml parser
   $xmlParser = xml_parser_create("utf-8");
   xml_set_element_handler($xmlParser, "read_news_start_element", "read_news_end_element");
   xml_set_character_data_handler($xmlParser, "read_news_data");
   xml_parse($xmlParser, $contents, true);
   // Free xml parser form memory
   xml_parser_free($xmlParser);
}
$inside = true;
function read_news_start_element($parser, $tagName, $attrs)
{
   global $inside;
   if ($tagName == "LINK") $inside = true;
   else $inside = false;
}
function read_news_data($parser, $text)
{
   global $inside;
   if ($inside && strstr($text, "/news/") !== false)
   {
      $text = sqlsafe($text);
      $check = sql_get("SELECT `id` FROM `news_links` WHERE `link` = {$text}", __FILE__, __LINE__);
      if (!$check) sql_query("INSERT INTO `news_links` (`link`) VALUES ({$text})", __FILE__, __LINE__);
   }
}
function read_news_end_element($parser, $tagName)
{
}



function deal_with_updates()
{
	$updates = sql_query("SELECT * FROM `updates` WHERE `until` <= ".get_date_time(), __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($updates))
	{
		if ($row['type'] == '+') $q = "UPDATE `{$row['table']}` SET `{$row['field']}` = `{$row['field']}` + '{$row['value']}' WHERE `id` = '{$row['whereid']}'";
		if ($row['type'] == '-') $q = "UPDATE `{$row['table']}` SET `{$row['field']}` = `{$row['field']}` - '{$row['value']}' WHERE `id` = '{$row['whereid']}'";
		if ($row['type'] == '=') $q = "UPDATE `{$row['table']}` SET `{$row['field']}` = '{$row['value']}' WHERE `id` = '{$row['whereid']}'";
		sql_query($q, __FILE__, __LINE__);
		sql_query("DELETE FROM `updates` WHERE `id` = {$row['id']} LIMIT 1", __FILE__, __LINE__);
	}
}
function add_new_players_to_teams_with_notenought_players()
{
	$teams = sql_query("SELECT `id`,
   (SELECT COUNT(`id`) FROM `players` WHERE `team` = `teams`.`id`) AS `count`,
   (SELECT `id` FROM `match_type` WHERE `name` = `teams`.`league`) AS `leagueid`,
   (SELECT `country` FROM `users` WHERE `team` = `teams`.`id`) AS `country`
   FROM `teams`", __FILE__, __LINE__);
	while ($team = mysql_fetch_assoc($teams))
	{
		if ($team['count'] < MINIMUM_PLAYERS_IN_TEAM)
		{
			if (!$team['country']) $team['country'] = rand(1, 100);
			sql_query("DELETE FROM `players` WHERE `team` = {$team['id']}", __FILE__, __LINE__);
			$tactic = generate_players_for_team($team['id'], $team['country'], $team['leagueid'], 20, 30);
			sql_query("UPDATE `teams` SET `tactic1` = {$tactic} WHERE `id` = {$team['id']}", __FILE__, __LINE__);
		}
	}
}
function add_bots_players()
{
	$teams = sql_query("SELECT `id`, `free`,
   (SELECT COUNT(`id`) FROM `players` WHERE `team` = `teams`.`id`) AS `count`,
   (SELECT `id` FROM `match_type` WHERE `name` = `teams`.`league`) AS `leagueid`,
   (SELECT `country` FROM `users` WHERE `team` = `teams`.`id`) AS `country`
   FROM `teams`", __FILE__, __LINE__);
	while ($team = mysql_fetch_assoc($teams))
	{
		if ($team['free'] == 'yes')
		{
			if (!$team['country']) $team['country'] = rand(1, 100);
			sql_query("DELETE FROM `players` WHERE `team` = {$team['id']}", __FILE__, __LINE__);
			$tactic = generate_players_for_team($team['id'], $team['country'], $team['leagueid']);
			sql_query("UPDATE `teams` SET `tactic1` = {$tactic} WHERE `id` = {$team['id']}", __FILE__, __LINE__);
		}
	}
}
function send_inactive_mail_bg()
{
	$mess = "Здравейте, {realname}!<br><br>
Отдавна не сте влизали в <a href=\"http://managerteams.com\">http://ManagerTeams.com</a><br>
От последното Ви влизане има доста промени по цялата игра, освен това отборът ви е изиграл много мачове без Вас, направил е много тренировки и е събрал много фенове. Все още можете да се върнете и отново да поемете контрол над отбора си, играчите си, стадиона си и всичко останало.<br>
Ако имате някакви въпроси, свързани с играта, можете да ги зададете тук: <a href=\"http://forum.managerteams.com\">http://Forum.ManagerTeams.com</a><br><br>
Целият екип на играта Ви пожелава успех! ";
	$headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
	$inact = sql_query("SELECT `id`, `language`, `username`, `realname`, `email` FROM `users` WHERE (`id` = 1) OR (`holiday` = 'no' AND `lastaction` <= ".get_date_time(true, -TIME_DAY*21).")", __FILE__, __LINE__);
	while ($usr = mysql_fetch_assoc($inact))
	{
		$mess2 = str_replace("{realname}", $usr['realname'], $mess);
		$emailaddress = $usr['email'];
		$res = mail($emailaddress, GAME_NAME." информация за отбора Ви", $mess2, $headers);
		//if ($res) print("<b>{$usr['username']} - {$usr['email']}</b><br>");
		//else print("{$usr['username']} - {$usr['email']}<br>");
	}
}
function deal_with_inactive_users()
{
	//sql_query("UPDATE `teams` SET `free` = 'yes' WHERE `id` = ANY (SELECT `team` FROM `users` WHERE `class` > 0 AND `holiday` = 'no' AND `lastaction` <= ".get_date_time(true, -MEDIUM_CLEANUP).")", __FILE__, __LINE__);
	//sql_query("DELETE FROM `users` WHERE `class` > 0 AND `holiday` = 'no' AND `lastaction` <= ".get_date_time(true, -MEDIUM_CLEANUP), __FILE__, __LINE__);
	//sql_query("UPDATE `teams` SET `free` = 'yes' WHERE `id` = ANY (SELECT `team` FROM `users` WHERE  `holiday` = 'no' AND `registred` <=  ".get_date_time(true, -MEDIUM_CLEANUP).")", __FILE__, __LINE__);
	//sql_query("DELETE FROM `users` WHERE `class` = 0 AND `holiday` = 'no' AND `registred` <=  ".get_date_time(true, -MEDIUM_CLEANUP), __FILE__, __LINE__);// Send mails to inactive users
	$inact = sql_query("SELECT `id`, `language`, `username`, `realname`, `email` FROM `users` WHERE `holiday` = 'no' AND ((`class` > 0 AND `lastaction` <= ".get_date_time(true, -60*60*24*7*3).") OR (`class` = '0' AND `registred` <= ".get_date_time(true, -60*60*24*7*3)."))", __FILE__, __LINE__);
	while ($usr = mysql_fetch_assoc($inact))
	{
		$mess = constant("INACTIVE_MAIL_{$usr['language']}");
		$mess = str_replace("{realname}", $usr['realname'], $mess);
		$mess = str_replace("{address}", ADDRESS, $mess);
		$mess = str_replace("{gamename}", GAME_NAME, $mess);
		$mess = str_replace("{forumaddress}", FORUM_ADDRESS, $mess);
		$emailaddress = $usr['email'];
		$headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
		mail($emailaddress, GAME_NAME." account information", $mess, $headers);
	}
}
function deal_with_transfers()
{
	$data = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `transfers`.`fromteam`) AS `fromteamname`, (SELECT `name` FROM `teams` WHERE `id` = `transfers`.`offerteam`) AS `offerteamname`, (SELECT `name` FROM `players` WHERE `id` = `transfers`.`player`) AS `name`, (SELECT `shortname` FROM `players` WHERE `id` = `transfers`.`player`) AS `shortname`  FROM `transfers` WHERE `available` = 'yes' AND `until` <= ".get_date_time(), __FILE__, __LINE__);
	sql_query("UPDATE `transfers` SET `available` = 'no' WHERE `until` <= ".get_date_time(), __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($data)) if ($row['offerteam'] != 0)
	{
		// Remove player from tactics
		sql_query("UPDATE `tactics` SET `captain` = 0 WHERE `captain` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `GK` = 0 WHERE `GK` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `LB` = 0 WHERE `LB` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CB1` = 0 WHERE `CB1` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CB2` = 0 WHERE `CB2` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CB3` = 0 WHERE `CB3` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `RB` = 0 WHERE `RB` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `LM` = 0 WHERE `LM` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CM1` = 0 WHERE `CM1` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CM2` = 0 WHERE `CM2` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CM3` = 0 WHERE `CM3` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `RM` = 0 WHERE `RM` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CF1` = 0 WHERE `CF1` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CF2` = 0 WHERE `CF2` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `CF3` = 0 WHERE `CF3` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `S1` = 0 WHERE `S1` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `S2` = 0 WHERE `S2` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `S3` = 0 WHERE `S3` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `S4` = 0 WHERE `S4` = '{$row['player']}'", __FILE__, __LINE__);
		sql_query("UPDATE `tactics` SET `S5` = 0 WHERE `S5` = '{$row['player']}'", __FILE__, __LINE__);
		// Sell the player
		$name = get_player_name($row['name'], $row['shortname']);
		sql_query("UPDATE `players` SET `team` = {$row['offerteam']}, `contrtime` = {$row['contrtime']}, `wage` = {$row['wage']}, `winbonus` = {$row['winbonus']}, `car` = '{$row['car']}', `house` = '{$row['house']}' WHERE `id` = {$row['player']}", __FILE__, __LINE__);
		$price = $row['bestoffer'];
		add_to_money_history("{_TRANSFER_PRICE_FOR_BUYING_} {$name}", -$price, $row['offerteam'], true);
		$userid = sql_get("SELECT `id` FROM `users` WHERE `team` = {$row['offerteam']}", __FILE__, __LINE__);
		send_game_message($userid, "{_YOU_HAVE_BOUGHT_} {$name}", "{_YOU_HAVE_BOUGHT_} {$name} {_FROM_} [url=teamdetails.php?id={$row['fromteam']}]{$row['fromteamname']}[/url]");
		add_to_money_history("{_TRANSFER_PRICE_FOR_SELLING_} {$name}", +0.9*$price, $row['fromteam'], true);
		$userid = sql_get("SELECT `id` FROM `users` WHERE `team` = {$row['fromteam']}", __FILE__, __LINE__);
		send_game_message($userid, "{_YOU_HAVE_SOLD_} {$name}", "{_YOU_HAVE_SOLD_} {$name} {_TO_} [url=teamdetails.php?id={$row['offerteam']}]{$row['offerteamname']}[/url]");
		$text = "{$name} {_FROM_} <a href=\"teamdetails.php?id={$row['fromteam']}\">{$row['fromteamname']}</a> {_TO_} <a href=\"teamdetails.php?id={$row['offerteam']}\">{$row['offerteamname']}</a>";
		add_to_team_history($text, $row['offerteam']);
		add_to_team_history($text, $row['fromteam']);
		if ($row['signbonus'] > 0) add_to_money_history("{_SIGN_BONUS_FOR_} {$name}", -$row['signbonus'], $row['offerteam'], true);
	}
	sql_query("DELETE FROM `transfers` WHERE `available` = 'no' AND `until` <= ".get_date_time(true, -TIME_DAY*5), __FILE__, __LINE__);
}
function deal_with_players_contracts()
{
	sql_query("UPDATE `players` SET `contrtime` = `contrtime` - 1 WHERE `contrtime` > 0", __FILE__, __LINE__);
	sql_query("UPDATE `players` SET `contrtime` = 99 WHERE `contrtime` = 0 AND (SELECT `free` FROM `teams` WHERE `id` = `players`.`team`) = 'yes'", __FILE__, __LINE__);
	sql_query("DELETE FROM `players` WHERE `contrtime` = 0 AND IFNULL((SELECT `id` FROM `transfers` WHERE `player` = `players`.`id`), 'yes') = 'yes'", __FILE__, __LINE__);
	/*
	UPDATE `players` SET `contrtime` = `contrtime` - 1 WHERE `contrtime` > 0 (37.5003008842)
	UPDATE `players` SET `contrtime` = 99 WHERE `contrtime` = 0 AND (SELECT `free` FROM `teams` WHERE `id` = `players`.`team`) = 'yes' (8.68846392632)
	DELETE FROM `players` WHERE `contrtime` = 0 (2.33571100235)
	*/
}
function deal_with_players_salaries()
{
	sql_query("UPDATE `teams` SET `money` = `money` - (SELECT IFNULL(SUM(`wage`), 0) FROM `players` WHERE `team`=`teams`.`id`) WHERE `id` = `teams`.`id`", __FILE__, __LINE__);
	sql_query("UPDATE `teams` SET `money` = `money` - (SELECT COUNT(`id`) FROM `players` WHERE `team`=`teams`.`id` AND `house` = 'yes') * 30 WHERE `id` = `teams`.`id`", __FILE__, __LINE__);
	sql_query("UPDATE `teams` SET `money` = `money` - (SELECT COUNT(`id`) FROM `players` WHERE `team`=`teams`.`id` AND `car` = 'yes') * 30 WHERE `id` = `teams`.`id`", __FILE__, __LINE__);
	$sal = sql_query("SELECT `id`, (SELECT IFNULL(SUM(`wage`), 0) FROM `players` WHERE `team`=`teams`.`id`) AS `wages`, (SELECT COUNT(`id`) FROM `players` WHERE `team`=`teams`.`id` AND `house` = 'yes') * 30 AS `houses`, (SELECT COUNT(`id`) FROM `players` WHERE `team`=`teams`.`id` AND `car` = 'yes') * 30 AS `cars` FROM `teams`", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($sal)) add_to_money_history("{_DAILY_PLAYERS_SALARY_}", -$row['wages']-$row['houses']-$row['cars'], $row['id']);
}
function deal_with_staff_courses()
{
	sql_query("UPDATE `staff` SET `rating` = `rating` + '".STAFF_COURSE_RATING."', `atcourse` = 'no', `courseuntil` = '0000-00-00 00:00:00' WHERE `atcourse` = 'yes' AND `rating` < ".(100-STAFF_COURSE_RATING)." AND `courseuntil` <= ".get_date_time(true), __FILE__, __LINE__);
	sql_query("UPDATE `staff` SET `rating` = 100, `atcourse` = 'no', `courseuntil` = '0000-00-00 00:00:00' WHERE `atcourse` = 'yes' AND `rating` >= ".(100-STAFF_COURSE_RATING)." AND `courseuntil` <= ".get_date_time(true), __FILE__, __LINE__);
	/*
	UPDATE `staff` SET `rating` = `rating` + '5', `atcourse` = 'no', `courseuntil` = '0000-00-00 00:00:00' WHERE `atcourse` = 'yes' AND `rating` < 95 AND `courseuntil` <= '2008-01-02 10:23:28' (0.768588066101)
	UPDATE `staff` SET `rating` = 100, `atcourse` = 'no', `courseuntil` = '0000-00-00 00:00:00' WHERE `atcourse` = 'yes' AND `rating` >= 95 AND `courseuntil` <= '2008-01-02 10:23:30' (0.783457040787)
	*/
}
function deal_with_staff_contracts()
{
	sql_query("UPDATE `staff` SET `contrtime` = `contrtime` - 1 WHERE `contrtime` > 0", __FILE__, __LINE__);
	sql_query("UPDATE `staff` SET `team` = 0 WHERE `contrtime` = 0", __FILE__, __LINE__);
	/*
	UPDATE `staff` SET `contrtime` = `contrtime` - 1 WHERE `contrtime` > 0 (0.664991140366)
	UPDATE `staff` SET `team` = 0 WHERE `contrtime` = 0 (0.662262916565)
	*/
}
function deal_with_staff_salaries()
{
	sql_query("UPDATE `teams` SET `money` = `money` - (SELECT IFNULL(SUM(`wage`), 0) FROM `staff` WHERE `team`=`teams`.`id`) WHERE `id` = `teams`.`id`", __FILE__, __LINE__);
	$sal = sql_query("SELECT `id`, (SELECT IFNULL(SUM(`wage`), 0) FROM `staff` WHERE `team`=`teams`.`id`) AS `wages` FROM `teams` WHERE `id` = `teams`.`id`", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($sal)) if ($row['wages'] > 0) add_to_money_history("{_DAILY_STAFF_SALARY_}", -$row['wages'], $row['id']);
}
function deal_with_players_training()
{
	//$trainingplayers = sql_query("SELECT `id`, `training`, `fitness`, `team`, `name`, `shortname`, (SELECT `free` FROM `teams` WHERE `id` = `players`.`team`) AS `free`, (SELECT `id` FROM `users` WHERE `team` = `players`.`team`) AS `user` FROM `players` WHERE `training` != 0 AND `injured` = 0 ORDER BY RAND() LIMIT ".TO_TRAIN, __FILE__, __LINE__);
	$trainingplayers = sql_query("SELECT `id`, `training`, `fitness`, `team`, `name`, `shortname`, (SELECT `free` FROM `teams` WHERE `id` = `players`.`team`) AS `free`, (SELECT `id` FROM `users` WHERE `team` = `players`.`team`) AS `user` FROM `players` WHERE `training` != 0 AND `injured` = 0", __FILE__, __LINE__);
	while ($player = mysql_fetch_assoc($trainingplayers))
	{
		$name = get_player_name($player['name'], $player['shortname']);
		$field = get_abb_id($player['training']);
		$abbname = get_abb_id($player['training']);
		$fitness = $player['fitness'] + 20;
		if ($fitness > 99) $fitness = 99;
		if ($player['free'] == 'no')
		{
			if ($field == "stamina") sql_query("UPDATE `players` SET `fitness` = {$fitness}, `{$field}` = `{$field}` + 1 WHERE `id` = {$player['id']} AND `{$field}` < 99", __FILE__, __LINE__);
			else sql_query("UPDATE `players` SET `fitness` = {$fitness}, `{$field}` = `{$field}` + 1 WHERE `id` = {$player['id']} AND `{$field}` < 255", __FILE__, __LINE__);
			if (mysql_affected_rows() > 0) send_game_message($player['user'], "{_TRAINING_INFORMATION_FOR_} {$name}", "{_YOUR_PLAYER_} {$name} {_HAS_INCREASED_WITH_1_POINT_HIS_} {$abbname}");
		}
	}
}
function deal_with_loans()
{
	global $interest;
	$loans = sql_query("SELECT * FROM `loans` WHERE `payed` = 'no'", __FILE__, __LINE__);
	while ($loan = mysql_fetch_assoc($loans))
	{
		$topay = $loan['money'] / $loan['parts'] + $loan['money'] * $interest;
		sql_query("UPDATE `teams` SET `money` = `money` - '{$topay}' WHERE `id` = {$loan['team']}", __FILE__, __LINE__);
		$loan['part']++;
		add_to_money_history("{_PAYED_PART_} {$loan['part']} / {$loan['parts']} {_FOR_LOAN_} {$loan['money']}", -$topay, $loan['team']);
	}
	sql_query("UPDATE `loans` SET `part` = `part` + 1 WHERE `payed` = 'no'", __FILE__, __LINE__);
	sql_query("UPDATE `loans` SET `payed` = 'yes' WHERE `part` = `parts`", __FILE__, __LINE__);
}
function deal_with_advboards()
{
	sql_query("UPDATE `advboards` SET `left` = `left` - 1 WHERE `left` > 0", __FILE__, __LINE__);
	sql_query("UPDATE `advboards` SET `adv` = 0 WHERE `left` = 0", __FILE__, __LINE__);
}
?>
