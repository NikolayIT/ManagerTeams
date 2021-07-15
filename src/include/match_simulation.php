<?php
include("include/stdfuncs.inc.php");
// Constants
define("MATCH_SIMULATOR_NAME", "MT Match Simulator");
define("MATCH_SIMULATOR_VERSION", "1.03");
define("MATCH_SIMULATOR_DEBUG", false);
define("HALF_1", 45);
define("HALF_2", 45);
define("HALF_3", 15);
define("HALF_4", 15);
function playmatches()
{
	global $config;
	$teams = MAX_TEAMS;
	$matches = sql_query("SELECT `id` FROM `match` WHERE  `hometeam` <= {$teams} AND `awayteam` <= {$teams} AND `start` <= ".get_date_time(true)." AND `played` = 'no' ORDER BY `start` ASC", __FILE__, __LINE__);
	if (mysql_affected_rows() == 0) return;
	set_time_limit(0);
	ignore_user_abort(true);
	$l = 0; $c = 0;
	while ($matchid = mysql_fetch_assoc($matches))
	{
		$match = new Match($matchid['id']);
		if ($match->SUCCESS !== true) continue;
		if ($match->matchdata['rules'] == "cl")
		{
			if ($match->matchdata['round'] >= 7)
			{
				$match->get_winner_loser();
				sql_query("UPDATE `match` SET `hometeam` = '{$match->WINNER['id']}' WHERE `type` = '{$match->matchdata['type']}' AND `hometeam` = '{$match->ID}'", __FILE__, __LINE__);
				sql_query("UPDATE `match` SET `awayteam` = '{$match->WINNER['id']}' WHERE `type` = '{$match->matchdata['type']}' AND `awayteam` = '{$match->ID}'", __FILE__, __LINE__);
			}
			if ($match->matchdata['round'] == 7) // 1/8 finals
			{
				
			}
			else if ($match->matchdata['round'] == 8) // 1/4 finals
			{
				
			}
			else if ($match->matchdata['round'] == 9) // 1/2 finals
			{
				add_to_league_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} Champions League", $config['clid']);
				add_to_team_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} Champions League", $match->LOSER['id']);
				add_to_money_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} Champions League", CL_SEMIFINAL_BONUS, $match->LOSER['id'], true);
			}
			else if ($match->matchdata['round'] == 10) // final
			{
				add_to_league_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> {_WAS_IN_SECOND_PLACE_IN_} Champions League", $config['clid']);
				add_to_team_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> {_WAS_IN_SECOND_PLACE_IN_} Champions League", $match->LOSER['id']);
				add_to_money_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_WAS_IN_SECOND_PLACE_IN_} Champions League", CL_SECOND_BONUS, $match->LOSER['id'], true);
				add_to_league_history("<a href='teamdetails.php?id={$match->WINNER['id']}'>{$match->WINNER['name']}</a> {_HAS_WON_IN_} Champions League", $config['clid']);
				add_to_team_history("<a href='teamdetails.php?id={$match->WINNER['id']}'>{$match->WINNER['name']}</a> {_HAS_WON_IN_} Champions League", $match->WINNER['id']);
				add_to_money_history("<a href='teamdetails.php?id={$match->WINNER['id']}'>{$match->WINNER['name']}</a> {_HAS_WON_IN_} Champions League", CL_WINNER_BONUS, $match->WINNER['id'], true);
				add_to_trophy_history("Champions League", "cl", $match->WINNER['id']);
			}
		}
		if ($match->matchdata['rules'] == "league") $l++;
		if ($match->matchdata['rules'] == "cup")
		{
			$c++;
			$match->get_winner_loser();
			if ($match->matchdata['round'] == 17)
			{
				add_to_league_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} ".CUP_NAME, $config['cupid']);
				add_to_team_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} ".CUP_NAME, $match->LOSER['id']);
				add_to_money_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} ".CUP_NAME, CUP_SEMIFINAL_BONUS, $match->LOSER['id'], true);
			}
			if ($match->matchdata['round'] == 18)
			{
				add_to_league_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> {_WAS_IN_SECOND_PLACE_IN_} ".CUP_NAME, $config['cupid']);
				add_to_team_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> {_WAS_IN_SECOND_PLACE_IN_} ".CUP_NAME, $match->LOSER['id']);
				add_to_money_history("<a href='teamdetails.php?id={$match->LOSER['id']}'>{$match->LOSER['name']}</a> (_WAS_IN_SECOND_PLACE_IN_} ".CUP_NAME, CUP_SECOND_BONUS, $match->LOSER['id'], true);
				add_to_league_history("<a href='teamdetails.php?id={$match->WINNER['id']}'>{$match->WINNER['name']}</a> {_HAS_WON_IN_} ".CUP_NAME, $config['cupid']);
				add_to_team_history("<a href='teamdetails.php?id={$match->WINNER['id']}'>{$match->WINNER['name']}</a> {_HAS_WON_IN_} ".CUP_NAME, $match->WINNER['id']);
				add_to_money_history("<a href='teamdetails.php?id={$match->WINNER['id']}'>{$match->WINNER['name']}</a> {_HAS_WON_IN_} ".CUP_NAME, CUP_WINNER_BONUS, $match->WINNER['id'], true);
				add_to_trophy_history(CUP_NAME, "cup", $match->WINNER['id']);
			}
		}
		if ($match->matchdata['rules'] == "frcup")
		{
			$match->get_winner_loser();
			sql_query("UPDATE `friendly_participants` SET `incup` = 'no' WHERE `team` = '{$match->LOSER['id']}' AND `type` = '{$match->matchdata['type']}'", __FILE__, __LINE__);
			$rounds = log($match->matchtype['teams'], 2);
			if ($match->matchdata['round'] == $rounds)
			{
				sql_query("UPDATE `match_type` SET `finished` = 'yes' WHERE `id` = '{$match->matchdata['type']}'", __FILE__, __LINE__);
				sql_query("UPDATE `friendly_participants` SET `incup` = 'no' WHERE `team` = '{$match->WINNER['id']}' AND `type` = '{$match->matchdata['type']}'", __FILE__, __LINE__);
				$fee = $match->matchtype['fee'] * $match->matchtype['teams'];
				add_to_money_history("2 - <a href='friendlycupview.php?id={$match->matchtype['id']}'>{$match->matchtype['name']}</a>", 0.20 * $fee, $match->LOSER['id'], true);
				add_to_team_history("2 - <a href='friendlycupview.php?id={$match->matchtype['id']}'>{$match->matchtype['name']}</a>", $match->LOSER['id']);
				add_to_money_history("1 - <a href='friendlycupview.php?id={$match->matchtype['id']}'>{$match->matchtype['name']}</a>", 0.65 * $fee, $match->WINNER['id'], true);
				add_to_team_history("1 - <a href='friendlycupview.php?id={$match->matchtype['id']}'>{$match->matchtype['name']}</a>", $match->WINNER['id']);
				add_to_trophy_history($match->matchtype['name'], "friendly", $match->WINNER['id']);
			}
		}
		sql_query("UPDATE `match` SET `hometeam` = '{$match->WINNER['id']}' WHERE `type` = '{$match->matchdata['type']}' AND `hometeam` = '{$match->ID}'", __FILE__, __LINE__);
		sql_query("UPDATE `match` SET `awayteam` = '{$match->WINNER['id']}' WHERE `type` = '{$match->matchdata['type']}' AND `awayteam` = '{$match->ID}'", __FILE__, __LINE__);
	}
	if ($c > 0)
	{
		$teams -= 10;
		sql_query("UPDATE `match` SET `hometeam` = FLOOR(1 + RAND() * {$teams}) WHERE `hometeam` = 0 AND `type` = '{$config['cupid']}'", __FILE__, __LINE__);
		sql_query("UPDATE `match` SET `awayteam` = FLOOR(1 + RAND() * {$teams}) WHERE `awayteam` = 0 AND `type` = '{$config['cupid']}'", __FILE__, __LINE__);
		sql_query("UPDATE `config` SET `value` = `value` + 1 WHERE `name` = 'cupround'", __FILE__, __LINE__);
	}
	if ($l > 0) sql_query("UPDATE `config` SET `value` = `value` + 1 WHERE `name` = 'round'", __FILE__, __LINE__);
}
class Match
{
	// Main variables
	public $ID = 0;
	public $matchdata = array();
	public $SUCCESS = true;
	public $matchtype = array();
	private $hometeam = array();
	private $awayteam = array();
	private $stadium = array();
	private $hometactic = array();
	private $awaytactic = array();
	private $MATCH = array();
	private $ball_possession = 50;
	private $h_win_bonus = 0;
	private $a_win_bonus = 0;
	// Home players
	private $hcoach = 0;
	private $hcaptain = array();
	private $hgoalkeeper = array();
	private $hdefender1 = array();
	private $hdefender2 = array();
	private $hdefender3 = array();
	private $hdefender4 = array();
	private $hdefender5 = array();
	private $hmidfielder1 = array();
	private $hmidfielder2 = array();
	private $hmidfielder3 = array();
	private $hmidfielder4 = array();
	private $hmidfielder5 = array();
	private $hattacker1 = array();
	private $hattacker2 = array();
	private $hattacker3 = array();
	private $hsubstitute1 = array();
	private $hsubstitute2 = array();
	private $hsubstitute3 = array();
	private $hsubstitute4 = array();
	private $hsubstitute5 = array();
	// Away players
	private $acoach = 0;
	private $acaptain = array();
	private $agoalkeeper = array();
	private $adefender1 = array();
	private $adefender2 = array();
	private $adefender3 = array();
	private $adefender4 = array();
	private $adefender5 = array();
	private $amidfielder1 = array();
	private $amidfielder2 = array();
	private $amidfielder3 = array();
	private $amidfielder4 = array();
	private $amidfielder5 = array();
	private $aattacker1 = array();
	private $aattacker2 = array();
	private $aattacker3 = array();
	private $asubstitute1 = array();
	private $asubstitute2 = array();
	private $asubstitute3 = array();
	private $asubstitute4 = array();
	private $asubstitute5 = array();
	// Calculated variables
	private $CUP_RULES = false;
	private $CHANCES = array();
	private $MINUTES = 0;
	private $TECHNICAL = 0;
	// Calculated home variables
	private $HOMEPLAYERS = array();
	private $HOMESUBSTITUTES = array();
	private $HOMESTATS = array();
	private $HOMERESULT = 0;
	private $HOMERESULT2 = 0;
	// Calculated away variables
	private $AWAYPLAYERS = array();
	private $AWAYSUBSTITUTES = array();
	private $AWAYSTATS = array();
	private $AWAYRESULT = 0;
	private $AWAYRESULT2 = 0;
	public $WINNER = array();
	public $LOSER = array();
	// System functions
	public function __construct($id)
	{
		$this->debug("start");
		$this->ID = $id;
		// Match preparation
		$this->debug("start initialization");
		$this->init();
		if ($this->matchdata['id'] != $this->ID)
		{
			$this->SUCCESS = false;
			return;
		}
		$this->debug("end initialization");
		// Play the mach
		$this->debug("start playing");
		$this->play();
		$this->debug("end playing");
		// Finilize
		$this->debug("start finalization");
		$this->finalize();
		$this->debug("end finalization");
		$this->debug("end");
	}
	public function __destruct() { }
	public function get_winner_loser()
	{
		if ($this->HOMERESULT > $this->AWAYRESULT)
		{
			$this->WINNER = $this->hometeam;
			$this->LOSER = $this->awayteam;
		}
		else if ($this->AWAYRESULT > $this->HOMERESULT)
		{
			$this->WINNER = $this->awayteam;
			$this->LOSER = $this->hometeam;
		}
	}
	public function init()
	{
		$this->init_common();
		if ($this->matchdata['id'] != $this->ID) return;
		$this->init_stadium();
		$this->init_referee();
		$this->init_weather();
		$this->init_other();
		$this->init_home_team();
		$this->init_away_team();
	}
	private function play()
	{
		$minutes = 0;
		$minutes += HALF_1;
		for ($i = 1; $i <= $minutes; $i++)
		{
			if ($i == $minutes)
			{
				$add = mt_rand(0, 5);
				for ($j = 0; $j <= $add; $j++) $this->play_minute($i, $j);
			}
			else $this->play_minute($i, 0);
		}
		$minutes += HALF_2;
		for ($i = HALF_1 + 1; $i <= $minutes; $i++)
		{
			if ($i == $minutes)
			{
				$add = mt_rand(0, 5);
				for ($j = 0; $j <= $add; $j++) $this->play_minute($i, $j);
			}
			else $this->play_minute($i, 0);
		}
		$this->HOMERESULT2 = $this->HOMERESULT;
		$this->AWAYRESULT2 = $this->AWAYRESULT;
		if ($this->CUP_RULES && $this->HOMERESULT == $this->AWAYRESULT)
		{
			$minutes += HALF_3;
			for ($i = HALF_1 + HALF_2 + 1; $i <= $minutes; $i++) $this->play_minute($i, 0);
			$minutes += HALF_4;
			for ($i = HALF_1 + HALF_2 + HALF_3 + 1; $i <= $minutes; $i++) $this->play_minute($i, 0);
			if ($this->HOMERESULT == $this->AWAYRESULT) $this->penalties($minutes);
		}
	}
	private function finalize()
	{
		$this->update_match();
		$this->save_data();
	}
	private function debug($message)
	{
		if (MATCH_SIMULATOR_DEBUG == true) print (microtime(true) . ": " . $message."<br>");
	}
	// Initialization
	private function init_common()
	{
		// Match data
		$this->matchdata = sql_data("SELECT * FROM `match` WHERE `id` = '{$this->ID}'", __FILE__, __LINE__);
		if ($this->matchdata['id'] != $this->ID) return;
		if ($this->matchdata['type'] == 0) $this->matchtype['name'] = "Friendly match";
		else $this->matchtype = sql_data("SELECT * FROM `match_type` WHERE `id` = {$this->matchdata['type']}", __FILE__, __LINE__);
		//aprint($this->matchdata);
		// Team data
		$this->hometeam = sql_data("SELECT * FROM `teams` WHERE `id` = '{$this->matchdata['hometeam']}'", __FILE__, __LINE__);
		//print_r($this->matchdata);
		$this->awayteam = sql_data("SELECT * FROM `teams` WHERE `id` = '{$this->matchdata['awayteam']}'", __FILE__, __LINE__);
		// Tactics data
		if ($this->matchdata['hometactic'] <= 0) $this->hometactic = sql_data("SELECT * FROM `tactics` WHERE `id` = '{$this->hometeam['tactic1']}'", __FILE__, __LINE__);
		else $this->hometactic = sql_data("SELECT * FROM `tactics` WHERE `id` = '{$this->matchdata['hometactic']}'", __FILE__, __LINE__);
		if ($this->matchdata['awaytactic'] <= 0) $this->awaytactic = sql_data("SELECT * FROM `tactics` WHERE `id` = '{$this->awayteam['tactic1']}'", __FILE__, __LINE__);
		else $this->awaytactic = sql_data("SELECT * FROM `tactics` WHERE `id` = '{$this->matchdata['awaytactic']}'", __FILE__, __LINE__);
		// Cup rules?
		if ($this->matchdata['rules'] == "cup" || $this->matchdata['rules'] == "frcup" || ($this->matchdata['rules'] == "cl" && $this->matchdata['round'] >= 7))
			$this->CUP_RULES = true;
		else
			$this->CUP_RULES = false;
		return true;
	}
	private function init_stadium()
	{
		$this->stadium = sql_data("SELECT * FROM `stadiums` WHERE `id` = '{$this->hometeam['stadium']}'", __FILE__, __LINE__);
		$this->stadium['eastseats_info'] = calculate_seats($this->stadium['eastseats']);
		$this->stadium['westseats_info'] = calculate_seats($this->stadium['westseats']);
		$this->stadium['northseats_info'] = calculate_seats($this->stadium['northseats']);
		$this->stadium['southseats_info'] = calculate_seats($this->stadium['southseats']);
		$this->stadium['vipseats_info'] = calculate_vipseats($this->stadium['vipseats']);
		$this->stadium['capacity'] = $this->stadium['eastseats_info'] + $this->stadium['westseats_info'] + $this->stadium['northseats_info'] + $this->stadium['southseats_info'] + $this->stadium['vipseats_info'];
	}
	private function init_referee()
	{
		global $defaultnames;
		$firstnames = file("./include/names/{$defaultnames}_FirstNames.txt");
		$lastnames = file("./include/names/{$defaultnames}_LastNames.txt");
		shuffle($firstnames);
		shuffle($lastnames);
		$this->matchdata['main_referee'] = trim($firstnames[0])." ".trim($lastnames[0]);
		$this->matchdata['left_referee'] = trim($firstnames[1])." ".trim($lastnames[1]);
		$this->matchdata['right_referee'] = trim($firstnames[2])." ".trim($lastnames[2]);
		$this->matchdata['fourth_referee'] = trim($firstnames[3])." ".trim($lastnames[3]);
		$this->matchdata['delegate'] = trim($firstnames[4])." ".trim($lastnames[4]);
		$this->matchdata['referee_strictness'] = mt_rand(7, 93);
	}
	private function init_weather()
	{
		$weather = mt_rand(1, 11);
		$weather_comm = mt_rand(1, 3);
		switch ($weather)
		{
			case 1: case 2:
				{
					$this->matchdata['weather'] = "Sunny";
					$this->matchdata['weather_comment'] = "M_WEATHER_1_{$weather_comm}";
					break;
				}
			case 3: case 4:
				{
					$this->matchdata['weather'] = "Cloud";
					$this->matchdata['weather_comment'] = "M_WEATHER_2_{$weather_comm}";
					break;
				}
			case 5: case 6:
				{
					$this->matchdata['weather'] = "Dark cloud";
					$this->matchdata['weather_comment'] = "M_WEATHER_3_{$weather_comm}";
					break;
				}
			case 7:
				{
					$this->matchdata['weather'] = "Rain";
					$this->matchdata['weather_comment'] = "M_WEATHER_4_{$weather_comm}";
					break;
				}
			case 8:
				{
					$this->matchdata['weather'] = "Big rain";
					$this->matchdata['weather_comment'] = "M_WEATHER_4_{$weather_comm}";
					break;
				}
			case 9:
				{
					$this->matchdata['weather'] = "Storm";
					$this->matchdata['weather_comment'] = "M_WEATHER_4_{$weather_comm}";
					break;
				}
			case 10:
				{
					$this->matchdata['weather'] = "Snow";
					$this->matchdata['weather_comment'] = "M_WEATHER_5_{$weather_comm}";
					break;
				}
			case 11:
				{
					$this->matchdata['weather'] = "Snow storm";
					$this->matchdata['weather_comment'] = "M_WEATHER_5_{$weather_comm}";
					break;
				}
		}
	}
	private function init_other()
	{
		$odds = format_odds($this->matchdata['odds'], $this->matchdata['better'], "no", $this->matchdata['id'], false);
		$this->matchdata['coefic1'] = $odds[0];
		$this->matchdata['coeficX'] = $odds[1];
		$this->matchdata['coefic2'] = $odds[2];
		$this->matchdata['tickets'] = $this->stadium['capacity'];
		switch ($this->matchdata['rules'])
		{
			case "cl":
				$this->matchdata['htickets'] = 50;
				$this->matchdata['atickets'] = 50;
				break;
			case "cup":
				$this->matchdata['htickets'] = 50;
				$this->matchdata['atickets'] = 50;
				break;
			case "league":
				$this->matchdata['htickets'] = 90;
				$this->matchdata['atickets'] = 10;
				break;
			default:
				$this->matchdata['htickets'] = 60;
				$this->matchdata['atickets'] = 40;
				break;
		}
	}
	private function init_home_team()
	{
		global $formations;
		$this->hometactic['theformation'] = $formations[$this->hometactic['formation']];
		$this->hometactic['defenders'] = $this->hometactic['theformation'][0];
		$this->hometactic['midfielders'] = $this->hometactic['theformation'][1];
		$this->hometactic['attackers'] = $this->hometactic['theformation'][2];
		// Substitutions
		$this->hsubstitute1 = $this->init_player($this->hometactic['S1'], $this->hometactic['S1_ind']);
		array_push($this->HOMESUBSTITUTES, &$this->hsubstitute1);
		$this->hsubstitute2 = $this->init_player($this->hometactic['S2'], $this->hometactic['S2_ind']);
		array_push($this->HOMESUBSTITUTES, &$this->hsubstitute2);
		$this->hsubstitute3 = $this->init_player($this->hometactic['S3'], $this->hometactic['S3_ind']);
		array_push($this->HOMESUBSTITUTES, &$this->hsubstitute3);
		$this->hsubstitute4 = $this->init_player($this->hometactic['S4'], $this->hometactic['S4_ind']);
		array_push($this->HOMESUBSTITUTES, &$this->hsubstitute4);
		$this->hsubstitute5 = $this->init_player($this->hometactic['S5'], $this->hometactic['S5_ind']);
		array_push($this->HOMESUBSTITUTES, &$this->hsubstitute5);
		// Goalkeeper
		$this->hgoalkeeper = $this->init_h_player($this->hometactic['GK'], "GK", $this->hometactic['GK_ind']);
		array_push($this->HOMEPLAYERS, &$this->hgoalkeeper);
		// Defenders
		if ($this->hometactic['defenders'] == 2)
		{
			$this->hdefender1 = $this->init_h_player($this->hometactic['CB1'], "CB", $this->hometactic['CB1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender1);
			$this->hdefender2 = $this->init_h_player($this->hometactic['CB2'], "CB", $this->hometactic['CB2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender2);
		}
		if ($this->hometactic['defenders'] == 3)
		{
			$this->hdefender1 = $this->init_h_player($this->hometactic['LB'], "LB", $this->hometactic['LB_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender1);
			$this->hdefender2 = $this->init_h_player($this->hometactic['CB1'], "CB", $this->hometactic['CB1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender2);
			$this->hdefender3 = $this->init_h_player($this->hometactic['RB'], "RB", $this->hometactic['RB_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender3);
		}
		if ($this->hometactic['defenders'] == 4)
		{
			$this->hdefender1 = $this->init_h_player($this->hometactic['LB'], "LB", $this->hometactic['LB_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender1);
			$this->hdefender2 = $this->init_h_player($this->hometactic['CB1'], "CB", $this->hometactic['CB1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender2);
			$this->hdefender3 = $this->init_h_player($this->hometactic['CB2'], "CB", $this->hometactic['CB2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender3);
			$this->hdefender4 = $this->init_h_player($this->hometactic['RB'], "RB", $this->hometactic['RB_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender4);
		}
		if ($this->hometactic['defenders'] == 5)
		{
			$this->hdefender1 = $this->init_h_player($this->hometactic['LB'], "LB", $this->hometactic['LB_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender1);
			$this->hdefender2 = $this->init_h_player($this->hometactic['CB1'], "CB", $this->hometactic['CB1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender2);
			$this->hdefender3 = $this->init_h_player($this->hometactic['CB2'], "CB", $this->hometactic['CB2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender3);
			$this->hdefender4 = $this->init_h_player($this->hometactic['CB3'], "CB", $this->hometactic['CB3_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender4);
			$this->hdefender5 = $this->init_h_player($this->hometactic['RB'], "RB", $this->hometactic['RB_ind']);
			array_push($this->HOMEPLAYERS, &$this->hdefender5);
		}
		// Midfielders
		if ($this->hometactic['midfielders'] == 2)
		{
			$this->hmidfielder1 = $this->init_h_player($this->hometactic['CM1'], "CM", $this->hometactic['CM1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder1);
			$this->hmidfielder2 = $this->init_h_player($this->hometactic['CM2'], "CM", $this->hometactic['CM2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder2);
		}
		if ($this->hometactic['midfielders'] == 3)
		{
			$this->hmidfielder1 = $this->init_h_player($this->hometactic['LM'], "LM", $this->hometactic['LM_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder1);
			$this->hmidfielder2 = $this->init_h_player($this->hometactic['CM1'], "CM", $this->hometactic['CM1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder2);
			$this->hmidfielder3 = $this->init_h_player($this->hometactic['RM'], "RM", $this->hometactic['RM_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder3);
		}
		if ($this->hometactic['midfielders'] == 4)
		{
			$this->hmidfielder1 = $this->init_h_player($this->hometactic['LM'], "LM", $this->hometactic['LM_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder1);
			$this->hmidfielder2 = $this->init_h_player($this->hometactic['CM1'], "CM", $this->hometactic['CM1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder2);
			$this->hmidfielder3 = $this->init_h_player($this->hometactic['CM2'], "CM", $this->hometactic['CM2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder3);
			$this->hmidfielder4 = $this->init_h_player($this->hometactic['RM'], "RM", $this->hometactic['RM_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder4);
		}
		if ($this->hometactic['midfielders'] == 5)
		{
			$this->hmidfielder1 = $this->init_h_player($this->hometactic['LM'], "LM", $this->hometactic['LM_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder1);
			$this->hmidfielder2 = $this->init_h_player($this->hometactic['CM1'], "CM", $this->hometactic['CM1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder2);
			$this->hmidfielder3 = $this->init_h_player($this->hometactic['CM2'], "CM", $this->hometactic['CM2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder3);
			$this->hmidfielder4 = $this->init_h_player($this->hometactic['CM3'], "CM", $this->hometactic['CM3_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder4);
			$this->hmidfielder5 = $this->init_h_player($this->hometactic['RM'], "RM", $this->hometactic['RM_ind']);
			array_push($this->HOMEPLAYERS, &$this->hmidfielder5);
		}
		// Attackers
		if ($this->hometactic['attackers'] == 1)
		{
			$this->hattacker1 = $this->init_h_player($this->hometactic['CF1'], "CF", $this->hometactic['CF1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hattacker1);
		}
		if ($this->hometactic['attackers'] == 2)
		{
			$this->hattacker1 = $this->init_h_player($this->hometactic['CF1'], "LF", $this->hometactic['CF1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hattacker1);
			$this->hattacker2 = $this->init_h_player($this->hometactic['CF2'], "RF", $this->hometactic['CF2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hattacker2);
		}
		if ($this->hometactic['attackers'] == 3)
		{
			$this->hattacker1 = $this->init_h_player($this->hometactic['CF1'], "LF", $this->hometactic['CF1_ind']);
			array_push($this->HOMEPLAYERS, &$this->hattacker1);
			$this->hattacker2 = $this->init_h_player($this->hometactic['CF2'], "CF", $this->hometactic['CF2_ind']);
			array_push($this->HOMEPLAYERS, &$this->hattacker2);
			$this->hattacker3 = $this->init_h_player($this->hometactic['CF3'], "RF", $this->hometactic['CF3_ind']);
			array_push($this->HOMEPLAYERS, &$this->hattacker3);
		}
	}
	private function init_away_team()
	{
		global $formations;
		$this->awaytactic['theformation'] = $formations[$this->awaytactic['formation']];
		$this->awaytactic['defenders'] = $this->awaytactic['theformation'][0];
		$this->awaytactic['midfielders'] = $this->awaytactic['theformation'][1];
		$this->awaytactic['attackers'] = $this->awaytactic['theformation'][2];
		// Substitutions
		$this->asubstitute1 = $this->init_player($this->awaytactic['S1'], $this->awaytactic['S1_ind']);
		array_push($this->AWAYSUBSTITUTES, &$this->asubstitute1);
		$this->asubstitute2 = $this->init_player($this->awaytactic['S2'], $this->awaytactic['S2_ind']);
		array_push($this->AWAYSUBSTITUTES, &$this->asubstitute2);
		$this->asubstitute3 = $this->init_player($this->awaytactic['S3'], $this->awaytactic['S3_ind']);
		array_push($this->AWAYSUBSTITUTES, &$this->asubstitute3);
		$this->asubstitute4 = $this->init_player($this->awaytactic['S4'], $this->awaytactic['S4_ind']);
		array_push($this->AWAYSUBSTITUTES, &$this->asubstitute4);
		$this->asubstitute5 = $this->init_player($this->awaytactic['S5'], $this->awaytactic['S5_ind']);
		array_push($this->AWAYSUBSTITUTES, &$this->asubstitute5);
		// Goalkeeper
		$this->agoalkeeper = $this->init_a_player($this->awaytactic['GK'], "GK", $this->awaytactic['GK_ind']);
		array_push($this->AWAYPLAYERS, &$this->agoalkeeper);
		// Defenders
		if ($this->awaytactic['defenders'] == 2)
		{
			$this->adefender1 = $this->init_a_player($this->awaytactic['CB1'], "CB", $this->awaytactic['CB1_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender1);
			$this->adefender2 = $this->init_a_player($this->awaytactic['CB2'], "CB", $this->awaytactic['CB2_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender2);
		}
		if ($this->awaytactic['defenders'] == 3)
		{
			$this->adefender1 = $this->init_a_player($this->awaytactic['LB'], "LB", $this->awaytactic['LB_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender1);
			$this->adefender2 = $this->init_a_player($this->awaytactic['CB1'], "CB", $this->awaytactic['CB1_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender2);
			$this->adefender3 = $this->init_a_player($this->awaytactic['RB'], "RB", $this->awaytactic['RB_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender3);
		}
		if ($this->awaytactic['defenders'] == 4)
		{
			$this->adefender1 = $this->init_a_player($this->awaytactic['LB'], "LB", $this->awaytactic['LB_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender1);
			$this->adefender2 = $this->init_a_player($this->awaytactic['CB1'], "CB", $this->awaytactic['CB1_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender2);
			$this->adefender3 = $this->init_a_player($this->awaytactic['CB2'], "CB", $this->awaytactic['CB2_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender3);
			$this->adefender4 = $this->init_a_player($this->awaytactic['RB'], "RB", $this->awaytactic['RB_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender4);
		}
		if ($this->awaytactic['defenders'] == 5)
		{
			$this->adefender1 = $this->init_a_player($this->awaytactic['LB'], "LB", $this->awaytactic['LB_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender1);
			$this->adefender2 = $this->init_a_player($this->awaytactic['CB1'], "CB", $this->awaytactic['CB1_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender2);
			$this->adefender3 = $this->init_a_player($this->awaytactic['CB2'], "CB", $this->awaytactic['CB2_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender3);
			$this->adefender4 = $this->init_a_player($this->awaytactic['CB3'], "CB", $this->awaytactic['CB3_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender4);
			$this->adefender5 = $this->init_a_player($this->awaytactic['RB'], "RB", $this->awaytactic['RB_ind']);
			array_push($this->AWAYPLAYERS, &$this->adefender5);
		}
		// Midfielders
		if ($this->awaytactic['midfielders'] == 2)
		{
			$this->amidfielder1 = $this->init_a_player($this->awaytactic['CM1'], "CM", $this->awaytactic['CM1_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder1);
			$this->amidfielder2 = $this->init_a_player($this->awaytactic['CM2'], "CM", $this->awaytactic['CM2_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder2);
		}
		if ($this->awaytactic['midfielders'] == 3)
		{
			$this->amidfielder1 = $this->init_a_player($this->awaytactic['LM'], "LM", $this->awaytactic['LM_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder1);
			$this->amidfielder2 = $this->init_a_player($this->awaytactic['CM1'], "CM", $this->awaytactic['CM1_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder2);
			$this->amidfielder3 = $this->init_a_player($this->awaytactic['RM'], "RM", $this->awaytactic['RM_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder3);
		}
		if ($this->awaytactic['midfielders'] == 4)
		{
			$this->amidfielder1 = $this->init_a_player($this->awaytactic['LM'], "LM", $this->awaytactic['LM_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder1);
			$this->amidfielder2 = $this->init_a_player($this->awaytactic['CM1'], "CM", $this->awaytactic['CM1_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder2);
			$this->amidfielder3 = $this->init_a_player($this->awaytactic['CM2'], "CM", $this->awaytactic['CM2_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder3);
			$this->amidfielder4 = $this->init_a_player($this->awaytactic['RM'], "RM", $this->awaytactic['RM_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder4);
		}
		if ($this->awaytactic['midfielders'] == 5)
		{
			$this->amidfielder1 = $this->init_a_player($this->awaytactic['LM'], "LM", $this->awaytactic['LM_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder1);
			$this->amidfielder2 = $this->init_a_player($this->awaytactic['CM1'], "CM", $this->awaytactic['CM1_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder2);
			$this->amidfielder3 = $this->init_a_player($this->awaytactic['CM2'], "CM", $this->awaytactic['CM2_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder3);
			$this->amidfielder4 = $this->init_a_player($this->awaytactic['CM3'], "CM", $this->awaytactic['CM3_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder4);
			$this->amidfielder5 = $this->init_a_player($this->awaytactic['RM'], "RM", $this->awaytactic['RM_ind']);
			array_push($this->AWAYPLAYERS, &$this->amidfielder5);
		}
		// Attackers
		if ($this->awaytactic['attackers'] == 1)
		{
			$this->aattacker1 = $this->init_a_player($this->awaytactic['CF1'], "CF", $this->awaytactic['CF1_ind']);
			array_push($this->AWAYPLAYERS, &$this->aattacker1);
		}
		if ($this->awaytactic['attackers'] == 2)
		{
			$this->aattacker1 = $this->init_a_player($this->awaytactic['CF1'], "LF", $this->awaytactic['CF1_ind']);
			array_push($this->AWAYPLAYERS, &$this->aattacker1);
			$this->aattacker2 = $this->init_a_player($this->awaytactic['CF2'], "RF", $this->awaytactic['CF2_ind']);
			array_push($this->AWAYPLAYERS, &$this->aattacker2);
		}
		if ($this->awaytactic['attackers'] == 3)
		{
			$this->aattacker1 = $this->init_a_player($this->awaytactic['CF1'], "LF", $this->awaytactic['CF1_ind']);
			array_push($this->AWAYPLAYERS, &$this->aattacker1);
			$this->aattacker2 = $this->init_a_player($this->awaytactic['CF2'], "CF", $this->awaytactic['CF2_ind']);
			array_push($this->AWAYPLAYERS, &$this->aattacker2);
			$this->aattacker3 = $this->init_a_player($this->awaytactic['CF3'], "RF", $this->awaytactic['CF3_ind']);
			array_push($this->AWAYPLAYERS, &$this->aattacker3);
		}
	}
	private function init_h_player($id, $match_poss, $individual)
	{
		$player = $this->init_player($id, $individual);
		if (!$player['id'])
		{
			$s1 = $this->get_player_handicap($match_poss, $this->hsubstitute1['possition_info']);
			$s2 = $this->get_player_handicap($match_poss, $this->hsubstitute2['possition_info']);
			$s3 = $this->get_player_handicap($match_poss, $this->hsubstitute3['possition_info']);
			$s4 = $this->get_player_handicap($match_poss, $this->hsubstitute4['possition_info']);
			$s5 = $this->get_player_handicap($match_poss, $this->hsubstitute5['possition_info']);
			if ($this->hsubstitute1['id'] > 0 && $s1 >= $s2 && $s1 >= $s3 && $s1 >= $s4 && $s1 >= $s5)
			{
				$player = $this->hsubstitute1;
				$this->hsubstitute1 = array();
			}
			else if ($this->hsubstitute2['id'] > 0 && $s2 >= $s1 && $s2 >= $s3 && $s2 >= $s4 && $s2 >= $s5)
			{
				$player = $this->hsubstitute2;
				$this->hsubstitute2 = array();
			}
			else if ($this->hsubstitute3['id'] > 0 && $s3 >= $s1 && $s3 >= $s2 && $s3 >= $s4 && $s3 >= $s5)
			{
				$player = $this->hsubstitute3;
				$this->hsubstitute3 = array();
			}
			else if ($this->hsubstitute4['id'] > 0 && $s4 >= $s1 && $s4 >= $s2 && $s4 >= $s3 && $s4 >= $s5)
			{
				$player = $this->hsubstitute4;
				$this->hsubstitute4 = array();
			}
			else if ($this->hsubstitute5['id'] > 0 && $s5 >= $s1 && $s5 >= $s2 && $s5 >= $s3 && $s5 >= $s4)
			{
				$player = $this->hsubstitute5;
				$this->hsubstitute5 = array();
			}
			else return array();
		}
		$player['possition_macth'] = $match_poss;
		$player['possition_macth_info'] = $this->get_post_and_side_by_possition($player['possition_macth']);
		$player['possition_handicap'] = $this->get_player_handicap($player['possition_macth_info'], $player['possition_info']);
		$player['performance'] = $player['global'] * $player['global'] * 4;
		$player['performance'] += $player['winbonus'] / 60.0;
		$player['performance'] *= $player['fitness'] / 100.0;
		$player['performance'] *= $player['possition_handicap'] / 100.0;
		$player['performance'] /= 200.0;
		$player['ingame'] = true;
		return $player;
	}
	private function init_a_player($id, $match_poss, $individual)
	{
		$player = $this->init_player($id, $individual);
		if (!$player['id'])
		{
			$s1 = $this->get_player_handicap($match_poss, $this->asubstitute1['possition_info']);
			$s2 = $this->get_player_handicap($match_poss, $this->asubstitute2['possition_info']);
			$s3 = $this->get_player_handicap($match_poss, $this->asubstitute3['possition_info']);
			$s4 = $this->get_player_handicap($match_poss, $this->asubstitute4['possition_info']);
			$s5 = $this->get_player_handicap($match_poss, $this->asubstitute5['possition_info']);
			if ($this->asubstitute1['id'] > 0 && $s1 >= $s2 && $s1 >= $s3 && $s1 >= $s4 && $s1 >= $s5)
			{
				$player = $this->asubstitute1;
				$this->asubstitute1 = array();
			}
			else if ($this->asubstitute2['id'] > 0 && $s2 >= $s1 && $s2 >= $s3 && $s2 >= $s4 && $s2 >= $s5)
			{
				$player = $this->asubstitute2;
				$this->asubstitute2 = array();
			}
			else if ($this->asubstitute3['id'] > 0 && $s3 >= $s1 && $s3 >= $s2 && $s3 >= $s4 && $s3 >= $s5)
			{
				$player = $this->asubstitute3;
				$this->asubstitute3 = array();
			}
			else if ($this->asubstitute4['id'] > 0 && $s4 >= $s1 && $s4 >= $s2 && $s4 >= $s3 && $s4 >= $s5)
			{
				$player = $this->asubstitute4;
				$this->asubstitute4 = array();
			}
			else if ($this->asubstitute5['id'] > 0 && $s5 >= $s1 && $s5 >= $s2 && $s5 >= $s3 && $s5 >= $s4)
			{
				$player = $this->asubstitute5;
				$this->asubstitute5 = array();
			}
			else return array();
		}
		$player['possition_macth'] = $match_poss;
		$player['possition_macth_info'] = $this->get_post_and_side_by_possition($player['possition_macth']);
		$player['possition_handicap'] = $this->get_player_handicap($player['possition_macth_info'], $player['possition_info']);
		$player['performance'] = $player['global'] * $player['global'] * 4;
		$player['performance'] += $player['winbonus'] / 60.0;
		$player['performance'] *= $player['fitness'] / 100.0;
		$player['performance'] *= $player['possition_handicap'] / 100.0;
		$player['performance'] /= 200.0;
		$player['ingame'] = true;
		return $player;
	}
	private function init_player($id, $individual)
	{
		$id = sqlsafe($id);
		$player = sql_data("SELECT * FROM `players` WHERE `id` = {$id}", __FILE__, __LINE__, false, true);
		$player['possition_info'] = $this->get_post_and_side_by_possition($player['possition']);
		if ($player['injured'] > 0) $player = array();
		if ($this->matchdata['rules'] == "league" && $player['banleague'] > 0) $player = array();
		if ($this->matchdata['rules'] == "cup" && $player['bancup'] > 0) $player = array();
		$player['thename'] = get_player_name($player['name'], $player['shortname']);
		$player['individual'] = $individual;
		$player['staminaminus'] = (100 - $player['stamina']) / 250.0;
		$player['goals'] = 0;
		$player['red'] = 0;
		$player['yel'] = 0;
		$player['inj'] = 0;
		$player['ingame'] = false;
		return $player;
	}
	private function get_post_and_side_by_possition($possition)
	{
		$poss = array();
		$poss['possition'] = $possition;
		if ($possition == "GK")
		{
			$poss['post1'] = 'G';
			$poss['post2'] = '';
			$poss['side'] = '';
		}
		else
		{
			$poss['post1'] = $possition[1];
			$poss['post2'] = $possition[2];
			$poss['side'] = $possition[0];
		}
		return $poss;
	}
	private function get_player_handicap($poss_info_1, $poss_info_2)
	{
		if (empty($poss_info_1['possition'])) return 0;
		if (empty($poss_info_2['possition'])) return 0;
		// Goalkeepers
		if ($poss_info_1['possition'] == "GK")
		{
			if ($poss_info_2['possition'] == "GK") return 100;
			else if ($poss_info_2['post1'] == 'B' || $poss_info_2['post2'] == 'B') return 45;
			else if ($poss_info_2['post1'] == 'M' || $poss_info_2['post2'] == 'M') return 20;
			else if ($poss_info_2['post1'] == 'F' || $poss_info_2['post2'] == 'F') return 5;
		}
		if ($poss_info_2['possition'] == "GK")
		{
			if ($poss_info_1['possition'] == "GK") return 100;
			else if ($poss_info_1['post1'] == 'B' || $poss_info_1['post2'] == 'B') return 45;
			else if ($poss_info_1['post1'] == 'M' || $poss_info_1['post2'] == 'M') return 20;
			else if ($poss_info_1['post1'] == 'F' || $poss_info_1['post2'] == 'F') return 5;
		}
		$handicap = 100;
		// Sides
		if ($poss_info_1['side'] == 'L')
		{
			if ($poss_info_2['side'] == 'L') $handicap -= 0;
			else if ($poss_info_2['side'] == 'C') $handicap -= 10;
			else if ($poss_info_2['side'] == 'R') $handicap -= 30;
		}
		if ($poss_info_1['side'] == 'C')
		{
			if ($poss_info_2['side'] == 'C') $handicap -= 0;
			else if ($poss_info_2['side'] == 'L') $handicap -= 10;
			else if ($poss_info_2['side'] == 'R') $handicap -= 10;
		}
		if ($poss_info_1['side'] == 'R')
		{
			if ($poss_info_2['side'] == 'R') $handicap -= 0;
			else if ($poss_info_2['side'] == 'C') $handicap -= 10;
			else if ($poss_info_2['side'] == 'L') $handicap -= 30;
		}
		// Posts
		if ($poss_info_1['post1'] == 'B' || $poss_info_1['post2'] == 'B')
		{
			if ($poss_info_2['post1'] == 'B' || $poss_info_2['post2'] == 'B') $handicap -= 0;
			else if ($poss_info_2['post1'] == 'M' || $poss_info_2['post2'] == 'M') $handicap -= 30;
			else if ($poss_info_2['post1'] == 'F' || $poss_info_2['post2'] == 'F') $handicap -= 55;
		}
		else if ($poss_info_1['post1'] == 'M' || $poss_info_1['post2'] == 'M')
		{
			if ($poss_info_2['post1'] == 'M' || $poss_info_2['post2'] == 'M') $handicap -= 0;
			else if ($poss_info_2['post1'] == 'B' || $poss_info_2['post2'] == 'B') $handicap -= 30;
			else if ($poss_info_2['post1'] == 'F' || $poss_info_2['post2'] == 'F') $handicap -= 30;
		}
		else if ($poss_info_1['post1'] == 'F' || $poss_info_1['post2'] == 'F')
		{
			if ($poss_info_2['post1'] == 'F' || $poss_info_2['post2'] == 'F') $handicap -= 0;
			else if ($poss_info_2['post1'] == 'M' || $poss_info_2['post2'] == 'M') $handicap -= 30;
			else if ($poss_info_2['post1'] == 'B' || $poss_info_2['post2'] == 'B') $handicap -= 55;
		}
		return $handicap;
	}
	// Play the match
	private function rand_attacker($team)
	{
		$t = mt_rand(1, 5);
		if ($t == 1 || $t == 2)
		{
			if ($team == "h") $mid = mt_rand(1, $this->hometactic['midfielders']);
			else $mid = mt_rand(1, $this->awaytactic['midfielders']);
			return "{$team}midfielder{$mid}";
		}
		else
		{
			if ($team == "h") $att = mt_rand(1, $this->hometactic['attackers']);
			else $att = mt_rand(1, $this->awaytactic['attackers']);
			return "{$team}attacker{$att}";
		}
	}
	private function rand_player($team)
	{
		$i = 0;
		while(true)
		{
			$i++;
			$t = mt_rand(0, count($this->{$team}));
			$player = $this->{$team}[$t];
			if ($player['id'] != 0 || $i > 100) return $t;
		}
	}
	private function play_minute($minute, $add = 0)
	{
		if ($this->TECHNICAL > 0) return ;
		if ($add == 0) $min = "{$minute}'";
		else $min = "{$minute}'+{$add}'";
		$data['minute'] = $minute;
		$data['add'] = $add;
		$data['min'] = $min;
		$this->debug("start minute {$min}");
		$hpl = $this->update_home_team();
		$apl = $this->update_away_team();
		if ($hpl <= 9 && $apl <= 9)
		{
			$this->TECHNICAL = 3;
			$this->HOMERESULT = 0;
			$this->AWAYRESULT = 0;
			$data['event3_comm'] = "TECHNICAL_WIN";
			$data['event_result'] = "{$this->HOMERESULT} - {$this->AWAYRESULT}";
			array_push($this->MATCH, $data);
			$this->MINUTES++;
			return;
		}
		else if ($hpl <= 9)
		{
			$this->TECHNICAL = 1;
			$this->HOMERESULT = 0;
			$this->AWAYRESULT = 3;
			$data['event3_comm'] = "TECHNICAL_WIN";
			$data['event_result'] = "{$this->HOMERESULT} - {$this->AWAYRESULT}";
			array_push($this->MATCH, $data);
			$this->MINUTES++;
			$this->MINUTES++;
			return;
		}
		else if ($apl <= 9)
		{
			$this->TECHNICAL = 2;
			$this->HOMERESULT = 3;
			$this->AWAYRESULT = 0;
			$data['event3_comm'] = "TECHNICAL_WIN";
			$data['event_result'] = "{$this->HOMERESULT} - {$this->AWAYRESULT}";
			array_push($this->MATCH, $data);
			$this->MINUTES++;
			return;
		}
		if ($minute == 1)
		{
			$this->HOMESTATS['init_rating'] = $this->HOMESTATS['rating'];
			$this->AWAYSTATS['init_rating'] = $this->AWAYSTATS['rating'];
		}
		$this->debug("home: {$this->HOMESTATS['rating']}; away: {$this->AWAYSTATS['rating']}");
		$this->calculate_chances();
		$card = false;
		$injury = false;
		if ($this->rand_will($this->CHANCES['penalty']))
		{
			$add = true;
			$miss = false;
			if ($this->goal_who($this->CHANCES['goalchance_home'], $this->CHANCES['goalchance_away']) == 1)
			{
				$player = $this->rand_attacker("h");
				if ($this->{$player}['id'] > 0 && $this->{$player}['ingame'] == true)
				{
					$data['event1_player1'] = $this->{$player}['thename'];
					$data['event1_team1'] = $this->hometeam['name'];
					$data['event1_team2'] = $this->awayteam['name'];
					if ($this->rand_will($this->CHANCES['penalty_home']))
					{
						$this->HOMERESULT++;
						$this->{$player}['goals']++;
						$this->ball_possession += 15;
					}
					else
					{
						$miss = true;
						$this->ball_possession += 8;
					}
				}
				else $add = false;
			}
			else
			{
				$player = $this->rand_attacker("a");
				if ($this->{$player}['id'] > 0 && $this->{$player}['ingame'] == true)
				{
					$data['event1_player1'] = $this->{$player}['thename'];
					$data['event1_team1'] = $this->awayteam['name'];
					$data['event1_team2'] = $this->hometeam['name'];
					if ($this->rand_will($this->CHANCES['penalty_away']))
					{
						$this->AWAYRESULT++;
						$this->{$player}['goals']++;
						$this->ball_possession += 15;
					}
					else
					{
						$miss = true;
						$this->ball_possession += 8;
					}
				}
				else $add = false;
			}
			if ($miss && $add)
			{
				$comm = mt_rand(1, 3);
				$data['event1_comm'] = "M_MISS_PENALTY_{$comm}";
				$data['event1'] = "";
			}
			else if ($add)
			{
				$comm = mt_rand(1, 3);
				$data['event1_comm'] = "M_PENALTY_{$comm}";
				$data['event1'] = "";
			}
		}
		else if ($this->rand_will($this->CHANCES['goalchance']))
		{
			$add = true;
			if ($this->goal_who($this->CHANCES['goalchance_home'], $this->CHANCES['goalchance_away']) == 1)
			{
				$player = $this->rand_attacker("h");
				if ($this->{$player}['id'] > 0 && $this->{$player}['ingame'] == true)
				{
					$data['event1_player1'] = $this->{$player}['thename'];
					$this->HOMERESULT++;
					$this->{$player}['goals']++;
					$this->ball_possession += 15;
					$data['event1_team1'] = $this->hometeam['name'];
					$data['event1_team2'] = $this->awayteam['name'];
				}
				else $add = false;
			}
			else
			{
				$player = $this->rand_attacker("a");
				if ($this->{$player}['id'] > 0 && $this->{$player}['ingame'] == true)
				{
					$data['event1_player1'] = $this->{$player}['thename'];
					$this->AWAYRESULT++;
					$this->{$player}['goals']++;
					$this->ball_possession -= 15;
					$data['event1_team1'] = $this->awayteam['name'];
					$data['event1_team2'] = $this->hometeam['name'];
				}
				else $add = false;
			}
			if ($add)
			{
				$comm = mt_rand(1, 28);
				$data['event1_comm'] = "M_GOAL_{$comm}";
				$data['event1'] = "";
			}
		}
		else if ($this->rand_will($this->CHANCES['misschance']))
		{
			$add = true;
			if ($this->rand_who($this->CHANCES['goalchance_home'], $this->CHANCES['goalchance_away']) == 1)
			{
				$player = $this->rand_attacker("h");
				if ($this->{$player}['id'] > 0 && $this->{$player}['ingame'] == true)
				{
					$data['event1_player1'] = $this->{$player}['thename'];
					$this->ball_possession += 5;
					$data['event1_team1'] = $this->hometeam['name'];
					$data['event1_team2'] = $this->awayteam['name'];
				}
				else $add = false;
			}
			else
			{
				$player = $this->rand_attacker("a");
				if ($this->{$player}['id'] > 0 && $this->{$player}['ingame'] == true)
				{
					$data['event1_player1'] = $this->{$player}['thename'];
					$this->ball_possession -= 5;
					$data['event1_team1'] = $this->awayteam['name'];
					$data['event1_team2'] = $this->hometeam['name'];
				}
				else $add = false;
			}
			if ($add)
			{
				$comm = mt_rand(1, 18);
				$data['event1_comm'] = "M_MISS_{$comm}";
				$data['event1'] = "";
			}
		}
		// Injury
		if (!$injury && $this->rand_will($this->CHANCES['injurychance']))
		{
			$add = true;
			if ($this->rand_who($this->awaytactic['aggression'], $this->hometactic['aggression']) == 1)
			{
				$player = $this->rand_player("HOMEPLAYERS");
				if ($this->HOMEPLAYERS[$player]['id'] > 0 && $this->HOMEPLAYERS[$player]['ingame'] == true)
				{
					$data['event2_player1'] = $this->HOMEPLAYERS[$player]['thename'];
					$this->HOMEPLAYERS[$player]['ingame'] = false;
					$this->HOMEPLAYERS[$player]['inj'] = 1;
					$data['event2_team1'] = $this->hometeam['name'];
					$data['event2_team2'] = $this->awayteam['name'];
				}
				else $add = false;
			}
			else
			{
				$player = $this->rand_player("AWAYPLAYERS");
				if ($this->AWAYPLAYERS[$player]['id'] > 0 && $this->AWAYPLAYERS[$player]['ingame'] == true)
				{
					$data['event2_player1'] = $this->AWAYPLAYERS[$player]['thename'];
					$this->AWAYPLAYERS[$player]['ingame'] = false;
					$this->AWAYPLAYERS[$player]['inj'] = 1;
					$data['event2_team1'] = $this->awayteam['name'];
					$data['event2_team2'] = $this->hometeam['name'];
				}
				else $add = false;
			}
			if ($add)
			{
				$comm = mt_rand(1, 1);
				$data['event2_comm'] = "M_INJURY_{$comm}";
				$data['event2'] = "";
			}
		}
		// Cards
		if (!$card && $this->rand_will($this->CHANCES['cardchance']))
		{
			$add = true;
			$thecomm = "M_YELLOW_CARD_";
			if ($this->rand_who($this->hometactic['aggression'], $this->awaytactic['aggression']) == 1)
			{
				$player = $this->rand_player("HOMEPLAYERS");
				if ($this->HOMEPLAYERS[$player]['id'] > 0 && $this->HOMEPLAYERS[$player]['ingame'] == true)
				{
					if ($this->HOMEPLAYERS[$player]['yel'])
					{
						$this->HOMEPLAYERS[$player]['ingame'] = false;
						$this->HOMEPLAYERS[$player]['red'] = 1;
						$thecomm = "M_RED_CARD_";
					}
					else $this->HOMEPLAYERS[$player]['yel'] = 1;
					$data['event3_player1'] = $this->HOMEPLAYERS[$player]['thename'];
					$data['event3_team1'] = $this->hometeam['name'];
					$data['event3_team2'] = $this->awayteam['name'];
				}
				else $add = false;
			}
			else
			{
				$player = $this->rand_player("AWAYPLAYERS");
				if ($this->AWAYPLAYERS[$player]['id'] > 0 && $this->AWAYPLAYERS[$player]['ingame'] == true)
				{
					if ($this->AWAYPLAYERS[$player]['yel'])
					{
						$this->AWAYPLAYERS[$player]['ingame'] = false;
						$this->AWAYPLAYERS[$player]['red'] = 1;
						$thecomm = "M_RED_CARD_";
					}
					else $this->AWAYPLAYERS[$player]['yel'] = 1;
					$data['event3_player1'] = $this->AWAYPLAYERS[$player]['thename'];
					$data['event3_team1'] = $this->awayteam['name'];
					$data['event3_team2'] = $this->hometeam['name'];
				}
				else $add = false;
			}
			if ($add)
			{
				$comm = mt_rand(1, 1);
				$data['event3_comm'] = "{$thecomm}{$comm}";
				$data['event3'] = "";
			}
		}
		//else
		{
			if ($this->hometactic['sub1_min'] == $minute) $this->planned_substitute_h($this->hometactic['sub1_in'], $this->hometactic['sub1_out'], &$data);
			if ($this->hometactic['sub2_min'] == $minute) $this->planned_substitute_h($this->hometactic['sub2_in'], $this->hometactic['sub2_out'], &$data);
			if ($this->hometactic['sub3_min'] == $minute) $this->planned_substitute_h($this->hometactic['sub3_in'], $this->hometactic['sub3_out'], &$data);
			if ($this->awaytactic['sub1_min'] == $minute) $this->planned_substitute_a($this->awaytactic['sub1_in'], $this->awaytactic['sub1_out'], &$data);
			if ($this->awaytactic['sub2_min'] == $minute) $this->planned_substitute_a($this->awaytactic['sub2_in'], $this->awaytactic['sub2_out'], &$data);
			if ($this->awaytactic['sub3_min'] == $minute) $this->planned_substitute_a($this->awaytactic['sub3_in'], $this->awaytactic['sub3_out'], &$data);
		}
		$data['event_result'] = "{$this->HOMERESULT} - {$this->AWAYRESULT}";
		// ball possession
		$test = mt_rand(0, 1);
		$test2 = mt_rand(0, 3);
		if ($test) $this->ball_possession += $test2;
		else $this->ball_possession -= $test2;
		if ($this->ball_possession < 10) $this->ball_possession = 10;
		if ($this->ball_possession > 90) $this->ball_possession = 90;
		$data['hbp'] = $this->ball_possession;
		$data['abp'] = 100 - $this->ball_possession;

		array_push($this->MATCH, $data);
		$this->MINUTES++;
	}
	private $hsubs = 0;
	private $asubs = 0;
	private function planned_substitute_h($in, $out, $data)
	{
		$this->hsubs++;
		foreach ($this->HOMEPLAYERS as &$value)
		{
			if ($value['ingame'] == true && $value['id'] == $out)
			{
				if ($this->hsubs > 3)
				{
					$value = array();
					return;
				}
				else foreach ($this->HOMESUBSTITUTES as &$value2)
				{
					if ($value2['ingame'] == false && $value2['id'] == $in)
					{
						$this->h_win_bonus += $value['winbonus'];
						//print("planned_substitute_h($in, $out)<br>");
						$value['ingame'] = false;
						$value2['ingame'] = true;
						$data['event2_comm'] = "M_SUBTITUTION_1";
						$data['event2_player1'] = $value['thename'];
						$data['event2_player2'] = $value2['thename'];
						$data['event2_team1'] = $this->hometeam['name'];
						$value = $this->init_h_player($value2['id'], $value['possition_macth'], $value['individual']);
						$value['ingame'] = true;
						$value['insub'] = true;
						array_push($this->HOMEPLAYERS, &$value);
					}
				}
			}
		}
	}
	private function planned_substitute_a($in, $out, $data)
	{
		$this->asubs++;
		foreach ($this->AWAYPLAYERS as &$value)
		{
			if ($value['ingame'] == true && $value['id'] == $out)
			{
				if ($this->asubs > 3)
				{
					$value = array();
					return ;
				}
				else foreach ($this->AWAYSUBSTITUTES as &$value2)
				{
					if ($value2['ingame'] == false && $value2['id'] == $in)
					{
						$this->a_win_bonus += $value['winbonus'];
						//print("planned_substitute_a($in, $out)<br>");
						$value['ingame'] = false;
						$value2['ingame'] = true;
						$data['event3_comm'] = "M_SUBTITUTION_1";
						$data['event3_player1'] = $value['thename'];
						$data['event3_player2'] = $value2['thename'];
						$data['event3_team1'] = $this->awayteam['name'];
						$value = $this->init_a_player($value2['id'], $value['possition_macth'], $value['individual']);
						array_push($this->AWAYPLAYERS, &$value);
					}
				}
			}
		}
	}
	private function penalties($minute)
	{
		if ($this->TECHNICAL) return;
		for ($i = 1; $i <= 5; $i++)
		{
			// HOME PENALTY
			$data = array();
			$miss = !$this->rand_will($this->CHANCES['penalty_home']);
			$data['minute'] = ++$minute;
			$data['add'] = 0;
			$data['min'] = "{$minute}'";
			$hplayer = $this->rand_player("HOMEPLAYERS");
			$data['event1_player1'] = $this->HOMEPLAYERS[$hplayer]['thename'];
			$data['event1_team1'] = $this->hometeam['name'];
			$data['event1_team2'] = $this->awayteam['name'];
			if ($miss)
			{
				$comm = mt_rand(1, 3);
				$data['event1_comm'] = "M_MISS_PENALTY_{$comm}";
			}
			else
			{
				$this->HOMERESULT++;
				$comm = mt_rand(1, 3);
				$data['event1_comm'] = "M_PENALTY_{$comm}";
			}
			$data['event1'] = "";
			$data['event_result'] = "{$this->HOMERESULT} - {$this->AWAYRESULT}";
			array_push($this->MATCH, $data);
			$this->MINUTES++;
			// AWAY PENALTY
			$data = array();
			$miss = !$this->rand_will($this->CHANCES['penalty_away']);
			$data['minute'] = ++$minute;
			$data['add'] = 0;
			$data['min'] = "{$minute}'";
			$aplayer = $this->rand_player("AWAYPLAYERS");
			$data['event1_player1'] = $this->AWAYPLAYERS[$aplayer]['thename'];
			$data['event1_team1'] = $this->awayteam['name'];
			$data['event1_team2'] = $this->hometeam['name'];
			if ($miss)
			{
				$comm = mt_rand(1, 3);
				$data['event1_comm'] = "M_MISS_PENALTY_{$comm}";
			}
			else
			{
				$this->AWAYRESULT++;
				$comm = mt_rand(1, 3);
				$data['event1_comm'] = "M_PENALTY_{$comm}";
			}
			$data['event1'] = "";
			$data['event_result'] = "{$this->HOMERESULT} - {$this->AWAYRESULT}";
			array_push($this->MATCH, $data);
			$this->MINUTES++;
			if ($i == 5 && $this->HOMERESULT == $this->AWAYRESULT) $i--;
		}
	}
	private function update_home_team()
	{
		$this->HOMESTATS['rating'] = 0;
		$this->HOMESTATS['players'] = 0;
		for ($i = 0; $i <= count($this->HOMEPLAYERS); $i++)
		{
			if ($this->HOMEPLAYERS[$i]['id'] > 0)
			{
				if ($this->HOMEPLAYERS[$i]['id'] == $this->hometactic['captain']) $this->HOMEPLAYERS[$i]['captain'] = true;
				else $this->HOMEPLAYERS[$i]['captain'] = false;
				$this->HOMEPLAYERS[$i]['performance'] -= $this->HOMEPLAYERS[$i]['staminaminus'];
				if ($this->HOMEPLAYERS[$i]['performance'] < 2) $this->HOMEPLAYERS[$i]['performance'] = 2;
				$this->HOMESTATS['rating'] += $this->HOMEPLAYERS[$i]['performance'];
				if (!$this->HOMEPLAYERS[$i]['red'] && !$this->HOMEPLAYERS[$i]['inj']) $this->HOMESTATS['players']++;
			}
		}
		if (!$this->hgoalkeeper['id']) $this->HOMESTATS['rating'] -= 100;
		$this->HOMESTATS['defence'] = $this->hdefender1['performance'] + $this->hdefender2['performance'] + $this->hdefender3['performance'] + $this->hdefender4['performance'] + $this->hdefender5['performance'];
		$this->HOMESTATS['defence'] += 100 - $this->hometactic['style'];
		$this->HOMESTATS['middle'] = $this->hmidfielder1['performance'] + $this->hmidfielder2['performance'] + $this->hmidfielder3['performance'] + $this->hmidfielder4['performance'] + $this->hmidfielder5['performance'];
		$this->HOMESTATS['attack'] = $this->hattacker1['performance'] + $this->hattacker2['performance'] + $this->hattacker3['performance'];
		$this->HOMESTATS['attack'] += $this->hometactic['style'];
		$this->HOMESTATS['rating'] += $this->hcoach / 2;
		if ($this->HOMESTATS['rating'] < 20) $this->HOMESTATS['rating'] = 20;
		return $this->HOMESTATS['players'];
	}
	private function update_away_team()
	{
		$this->AWAYSTATS['rating'] = 0;
		$this->AWAYSTATS['players'] = 0;
		for ($i = 0; $i <= count($this->AWAYPLAYERS); $i++)
		{
			if ($this->AWAYPLAYERS[$i]['id'] > 0)
			{
				if ($this->AWAYPLAYERS[$i]['id'] == $this->awaytactic['captain']) $this->AWAYPLAYERS[$i]['captain'] = true;
				else $this->AWAYPLAYERS[$i]['captain'] = false;
				$this->AWAYPLAYERS[$i]['performance'] -= $this->AWAYPLAYERS[$i]['staminaminus'];
				if ($this->AWAYPLAYERS[$i]['performance'] < 2) $this->AWAYPLAYERS[$i]['performance'] = 2;
				$this->AWAYSTATS['rating'] += $this->AWAYPLAYERS[$i]['performance'];
				if (!$this->AWAYPLAYERS[$i]['red'] && !$this->AWAYPLAYERS[$i]['inj']) $this->AWAYSTATS['players']++;
			}
		}
		if (!$this->agoalkeeper['id']) $this->AWAYSTATS['rating'] -= 100;
		$this->AWAYSTATS['defence'] = $this->adefender1['performance'] + $this->adefender2['performance'] + $this->adefender3['performance'] + $this->adefender4['performance'] + $this->adefender5['performance'];
		$this->AWAYSTATS['defence'] += 100 - $this->awaytactic['style'];
		$this->AWAYSTATS['middle'] = $this->amidfielder1['performance'] + $this->amidfielder2['performance'] + $this->amidfielder3['performance'] + $this->amidfielder4['performance'] + $this->amidfielder5['performance'];
		$this->AWAYSTATS['attack'] = $this->aattacker1['performance'] + $this->aattacker2['performance'] + $this->aattacker3['performance'];
		$this->AWAYSTATS['attack'] += $this->awaytactic['style'];
		$this->AWAYSTATS['rating'] += $this->acoach / 2;
		if ($this->AWAYSTATS['rating'] < 20) $this->AWAYSTATS['rating'] = 20;
		return $this->AWAYSTATS['players'];
	}
	private function calculate_chances()
	{
		// Goal chance
		$this->CHANCES['goalchance'] = 0.5 + ($this->hometactic['style'] / 100.0) + ($this->awaytactic['style'] / 100.0);
		$this->CHANCES['goalchance'] /= 2;
		$diff = abs($this->HOMESTATS['rating'] - $this->AWAYSTATS['rating']);
		if ($diff < 5 || $diff >= 1000) $this->CHANCES['goalchance'] += 8;
		else if ($diff < 10 || $diff >= 800) $this->CHANCES['goalchance'] += 7;
		else if ($diff < 20 || $diff >= 600) $this->CHANCES['goalchance'] += 6;
		else if ($diff < 30 || $diff >= 450) $this->CHANCES['goalchance'] += 5;
		else if ($diff < 50 || $diff >= 300) $this->CHANCES['goalchance'] += 4;
		else if ($diff < 75 || $diff >= 200) $this->CHANCES['goalchance'] += 3;
		else if ($diff < 90 || $diff >= 150) $this->CHANCES['goalchance'] += 2;
		else $this->CHANCES['goalchance'] += 1;
		// Penalty
		$this->CHANCES['penalty'] = $this->CHANCES['goalchance'] / 4;
		// Who will score?
		$this->CHANCES['goalchance_home'] = $this->HOMESTATS['rating'] - $this->HOMERESULT * ($diff / 4.0);
		$this->CHANCES['goalchance_away'] = $this->AWAYSTATS['rating'] - $this->AWAYRESULT * ($diff / 4.0);
		// Miss the target
		$this->CHANCES['misschance'] = $this->CHANCES['goalchance'] * 4;
		// Injury chance
		$this->CHANCES['injurychance'] = 0.1;
		// Card chance
		$this->CHANCES['cardchance'] = 2;
		// Score a penalty
		$this->CHANCES['penalty_home'] = ($this->HOMESTATS['rating'] * 100) / ($this->HOMESTATS['rating'] + $this->AWAYSTATS['rating']);
		$this->CHANCES['penalty_away'] = ($this->AWAYSTATS['rating'] * 100) / ($this->HOMESTATS['rating'] + $this->AWAYSTATS['rating']);
	}
	private function goal_who($chance1, $chance2)
	{
		if ($chance1 < $chance2 && $this->HOMERESULT >= $this->AWAYRESULT) return 2;
		if ($chance1 > $chance2 && $this->HOMERESULT <= $this->AWAYRESULT) return 1;
		else return $this->rand_who($chance1, $chance2);
	}
	private function rand_who($chance1, $chance2)
	{
		if (mt_rand(0, $chance1 + $chance2) <= $chance1) return 1;
		else return 2;
	}
	private function rand_will($chance)
	{
		if (mt_rand(0, 10000) <= $chance * 100) return true;
		else return false;
	}
	// Finalization functions
	private function update_match()
	{
		// odds
		if (!$this->HOMESTATS['init_rating']) $this->HOMESTATS['init_rating'] = 0;
		if (!$this->AWAYSTATS['init_rating']) $this->AWAYSTATS['init_rating'] = 0;
		sql_query("UPDATE `teams` SET `odds_points` = 0, `odds_matches` = 0 WHERE `id` = '{$this->hometeam['id']}' AND `odds_matches` >= 10", __FILE__, __LINE__);
		sql_query("UPDATE `teams` SET `odds_points` = 0, `odds_matches` = 0 WHERE `id` = '{$this->awayteam['id']}' AND `odds_matches` >= 10", __FILE__, __LINE__);
		sql_query("UPDATE `teams` SET `odds_points` = `odds_points` + {$this->HOMESTATS['init_rating']}, `odds_matches` = `odds_matches` + 1 WHERE `id` = '{$this->hometeam['id']}'", __FILE__, __LINE__);
		sql_query("UPDATE `teams` SET `odds_points` = `odds_points` + {$this->AWAYSTATS['init_rating']}, `odds_matches` = `odds_matches` + 1 WHERE `id` = '{$this->awayteam['id']}'", __FILE__, __LINE__);
		// precalculations
		$prefix = "";
		$hwins = 0; $hdraws = 0; $hloses = 0;
		if ($this->HOMERESULT > $this->AWAYRESULT) { $hpoints = 3; $apoints = 0; $hwins = 1; }
		else if ($this->HOMERESULT < $this->AWAYRESULT) { $hpoints = 0; $apoints = 3; $hloses = 1; }
		else { $hpoints = 1; $apoints = 1; $hdraws = 1; }
		$hts = $this->hometeam['teamspirit']; $ats = $this->awayteam['teamspirit'];
		$hfs = $this->hometeam['fansatisfaction']; $afs = $this->awayteam['fansatisfaction'];
		$hfb = $this->hometeam['fanbase']; $afb = $this->awayteam['fanbase'];
		// money
		$money = $this->stadium['capacity'] * 20 + $this->stadium['vipseats_info'] * 60;
		$money += get_additional_incoms($this->stadium['parking']);
		$money += get_additional_incoms($this->stadium['bars']);
		$money += get_additional_incoms($this->stadium['toilets']);
		$money += get_additional_incoms($this->stadium['grass']);
		$money += get_additional_incoms($this->stadium['lights']);
		$money += get_additional_incoms($this->stadium['roof']);
		$money += get_additional_incoms($this->stadium['heater']);
		$money += get_additional_incoms($this->stadium['sprinkler']);
		$money += mt_rand(0, 999);
		$hmoney = 0; $amoney = 0;
		switch ($this->matchdata['rules'])
		{
			case "cl":
				$prefix = "cl";
				if ($this->HOMERESULT > $this->AWAYRESULT)
				{
					$hts += 15;
					$ats -= 15;
					$hfs += 10;
					$afs -= 10;
					$hfb += rand(16, 24);
					$afb -= rand(4, 10);
				}
				else if ($this->HOMERESULT < $this->AWAYRESULT)
				{
					$hts -= 15;
					$ats += 15;
					$hfs -= 10;
					$afs += 10;
					$hfb -= rand(4, 10);
					$afb += rand(16, 24);
				}
				else
				{
					$hts += 7;
					$ats += 7;
					$hfs += 4;
					$afs += 4;
					$hfb += rand(5, 10);
					$afb += rand(5, 10);
				}
				$money += 50000;
				if ($this->TECHNICAL)
				{
					$hmoney = 2000;
					$amoney = -5000;
				}
				else
				{
					$hmoney = $money * 0.9;
					$amoney = -($money * 0.1);
				}
				if ($this->matchdata['round'] <= 6)
				{
					sql_query("UPDATE `champions_league` SET `total` = `total` + 1, `points` = `points` + '{$hpoints}', `wins` = `wins` + '{$hwins}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hloses}', `goalsscored` = `goalsscored` + {$this->HOMERESULT}, `goalsconceded` = `goalsconceded` + {$this->AWAYRESULT} WHERE `team` = {$this->hometeam['id']}", __FILE__, __LINE__);
					sql_query("UPDATE `champions_league` SET `total` = `total` + 1, `points` = `points` + '{$apoints}', `wins` = `wins` + '{$hloses}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hwins}', `goalsscored` = `goalsscored` + {$this->AWAYRESULT}, `goalsconceded` = `goalsconceded` + {$this->HOMERESULT} WHERE `team` = {$this->awayteam['id']}", __FILE__, __LINE__);
				}
				//sql_query("UPDATE `players` SET `banleague` = `banleague` - 1 WHERE `banleague` > 0 AND (`team` = {$this->hometeam['id']} OR `team` = {$this->awayteam['id']})", __FILE__, __LINE__);
				break;
			case "cup":
				$prefix = "cup";
				if ($this->HOMERESULT > $this->AWAYRESULT)
				{
					$hts += 10;
					$ats -= 20;
					$hfs += 5;
					$afs -= 10;
					$hfb += rand(10, 20);
					$afb -= rand(5, 10);
					sql_query("UPDATE `teams` SET `cup` = 'no' WHERE `id` = {$this->awayteam['id']}", __FILE__, __LINE__);
				}
				else
				{
					$hts -= 20;
					$ats += 10;
					$hfs -= 10;
					$afs += 5;
					$hfb -= rand(5, 10);
					$afb += rand(10, 20);
					sql_query("UPDATE `teams` SET `cup` = 'no' WHERE `id` = {$this->hometeam['id']}", __FILE__, __LINE__);
				}
				$money += 35000;
				if ($this->TECHNICAL)
				{
					$hmoney = 2000;
					$amoney = -5000;
				}
				else
				{
					$hmoney = $money * 0.9;
					$amoney = -($money * 0.1);
				}
				sql_query("UPDATE `players` SET `bancup` = `bancup` - 1 WHERE `bancup` > 0 AND (`team` = {$this->hometeam['id']} OR `team` = {$this->awayteam['id']})", __FILE__, __LINE__);
				break;
			case "league":
				$prefix = "leag";
				if ($this->HOMERESULT > $this->AWAYRESULT)
				{
					$hts += 10;
					$ats -= 10;
					$hfs += 5;
					$afs -= 5;
					$hfb += rand(8, 12);
					$afb -= rand(2, 5);
				}
				else if ($this->HOMERESULT < $this->AWAYRESULT)
				{
					$hts -= 10;
					$ats += 10;
					$hfs -= 5;
					$afs += 5;
					$hfb -= rand(2, 5);
					$afb += rand(8, 12);
				}
				else
				{
					$hts += 5;
					$ats += 5;
					$hfs += 2;
					$afs += 2;
					$hfb += rand(1, 5);
					$afb += rand(1, 5);
				}
				$money += 30000;
				if ($this->TECHNICAL)
				{
					$hmoney = 2000;
					$amoney = -5000;
				}
				else
				{
					$hmoney = $money * 0.9;
					$amoney = -($money * 0.1);
				}
				sql_query("UPDATE `teams` SET `total` = `total` + 1, `points` = `points` + '{$hpoints}', `wins` = `wins` + '{$hwins}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hloses}', `goalsscored` = `goalsscored` + {$this->HOMERESULT}, `goalsconceded` = `goalsconceded` + {$this->AWAYRESULT} WHERE `id` = {$this->hometeam['id']}", __FILE__, __LINE__);
				sql_query("UPDATE `teams` SET `total` = `total` + 1, `points` = `points` + '{$apoints}', `wins` = `wins` + '{$hloses}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hwins}', `goalsscored` = `goalsscored` + {$this->AWAYRESULT}, `goalsconceded` = `goalsconceded` + {$this->HOMERESULT} WHERE `id` = {$this->awayteam['id']}", __FILE__, __LINE__);
				sql_query("UPDATE `players` SET `banleague` = `banleague` - 1 WHERE `banleague` > 0 AND (`team` = {$this->hometeam['id']} OR `team` = {$this->awayteam['id']})", __FILE__, __LINE__);
				break;
			default:
				$prefix = "fr";
				if ($this->HOMERESULT > $this->AWAYRESULT)
				{
					$hts += 2;
					$ats -= 2;
					$hfs += 1;
					$afs -= 1;
					$hfb += rand(5, 10);
					$afb -= rand(2, 5);
				}
				else if ($this->HOMERESULT < $this->AWAYRESULT)
				{
					$hts -= 2;
					$ats += 2;
					$hfs -= 1;
					$afs += 1;
					$hfb -= rand(2, 5);
					$afb += rand(5, 10);
				}
				else
				{
					$hts += 1;
					$ats += 1;
					$hfs += 1;
					$afs += 1;
					$hfb += rand(1, 5);
					$afb += rand(1, 5);
				}
				$money += 15000;
				$money /= 2;
				if ($this->TECHNICAL)
				{
					$hmoney = 1000;
					$amoney = 1000;
				}
				else
				{
					$hmoney = $money * 0.6;
					$amoney = $money * 0.4;
				}
				break;
		}
		if ($this->matchdata['rules'] == "frcup")
		{
			sql_query("UPDATE `friendly_participants` SET `total` = `total` + 1, `points` = `points` + '{$hpoints}', `wins` = `wins` + '{$hwins}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hloses}', `goalsscored` = `goalsscored` + {$this->HOMERESULT}, `goalsconceded` = `goalsconceded` + {$this->AWAYRESULT} WHERE `type` = {$this->matchdata['type']} AND `team` = '{$this->hometeam['id']}'", __FILE__, __LINE__);
			sql_query("UPDATE `friendly_participants` SET `total` = `total` + 1, `points` = `points` + '{$apoints}', `wins` = `wins` + '{$hloses}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hwins}', `goalsscored` = `goalsscored` + {$this->AWAYRESULT}, `goalsconceded` = `goalsconceded` + {$this->HOMERESULT} WHERE `type` = {$this->matchdata['type']} AND `team` = '{$this->awayteam['id']}'", __FILE__, __LINE__);
		}
		if ($hts > 100) $hts = 100;
		if ($hts < 10) $hts = 10;
		if ($hfs > 100) $hfs = 100;
		if ($hfs < 10) $hfs = 10;
		if ($hfb < 100) $hfb = 100;
		if ($ats > 100) $ats = 100;
		if ($ats < 10) $ats = 10;
		if ($afs > 100) $afs = 100;
		if ($afs < 10) $afs = 10;
		if ($afb < 100) $afb = 100;
		$matchlink = "<a href=\"matchreport.php?id={$this->ID}\">{$this->hometeam['name']} - {$this->awayteam['name']} {$this->HOMERESULT} : {$this->AWAYRESULT}</a>";
		add_to_money_history($matchlink, $hmoney, $this->hometeam['id'], true);
		add_to_money_history($matchlink, $amoney, $this->awayteam['id'], true);
		add_to_money_history($matchlink." - {_TV_RIGHTS_}", 10000, $this->hometeam['id'], true);
		add_to_money_history($matchlink." - {_TV_RIGHTS_}", 5000, $this->awayteam['id'], true);
		sql_query("UPDATE `teams` SET `teamspirit` = '{$hts}', `fanbase` = '{$hfb}', `fansatisfaction` = '{$hfs}' WHERE `id` = '{$this->hometeam['id']}'", __FILE__, __LINE__);
		sql_query("UPDATE `teams` SET `teamspirit` = '{$ats}', `fanbase` = '{$afb}', `fansatisfaction` = '{$afs}' WHERE `id` = '{$this->awayteam['id']}'", __FILE__, __LINE__);
		sql_query("UPDATE `users` SET `points` = `points` + {$hpoints}, `weekpoints` = `weekpoints` + {$hpoints}, `wins` = `wins` + {$hwins}, `draws` = `draws` + {$hdraws}, `loses` = `loses` + {$hloses}, `goalsscored` = `goalsscored` + {$this->HOMERESULT}, `goalsconceded` = `goalsconceded` + {$this->AWAYRESULT} WHERE `team` = '{$this->hometeam['id']}'", __FILE__, __LINE__);
		sql_query("UPDATE `users` SET `points` = `points` + {$apoints}, `weekpoints` = `weekpoints` + {$apoints}, `wins` = `wins` + {$hloses}, `draws` = `draws` + {$hdraws}, `loses` = `loses` + {$hwins}, `goalsscored` = `goalsscored` + {$this->AWAYRESULT}, `goalsconceded` = `goalsconceded` + {$this->HOMERESULT} WHERE `team` = '{$this->awayteam['id']}'", __FILE__, __LINE__);
		// Players:
		for($j = 1; $j <= 2; $j++)
		{
			$team = $j == 1 ? "h" : "a";
			$this->update_player("{$team}goalkeeper", $prefix, false, "{$team}_win_bonus");
			for($i = 1; $i <= 3; $i++) $this->update_player("{$team}attacker{$i}", $prefix, false, "{$team}_win_bonus");
			for($i = 1; $i <= 5; $i++)
			{
				$this->update_player("{$team}defender{$i}", $prefix, false, "{$team}_win_bonus");
				$this->update_player("{$team}midfielder{$i}", $prefix, false, "{$team}_win_bonus");
				$this->update_player("{$team}substitut{$i}", $prefix, true, "{$team}_win_bonus");
			}
		}
		if ($this->HOMERESULT > $this->AWAYRESULT) add_to_money_history("Win bonus: ".$matchlink, -$this->h_win_bonus, $this->hometeam['id'], true);
		if ($this->AWAYRESULT > $this->HOMERESULT) add_to_money_history("Win bonus: ".$matchlink, -$this->a_win_bonus, $this->awayteam['id'], true);
		/*
		TODO: injuries
		TODO: cards
		TODO: text report
		TODO: Mails
		TODO: flash report
		TODO: download report
		*/
		// dead with bets:
		if ($this->HOMERESULT2 > $this->AWAYRESULT2) $type = 0;
		else if ($this->HOMERESULT2 == $this->AWAYRESULT2) $type = 1;
		else if ($this->HOMERESULT2 < $this->AWAYRESULT2) $type = 2;
		$bets = sql_query("SELECT * FROM `bets` WHERE `matchid` = {$this->ID} AND `result` = '{$type}'", __FILE__, __LINE__);
		$balance = sql_get("SELECT `value` FROM `config` WHERE `name` = 'bets_balance'", __FILE__, __LINE__);
		while ($bet = mysql_fetch_assoc($bets))
		{
			$value = $bet['value'] * $bet['coefic'];
			add_to_money_history("{_MONEY_FROM_MATCH_BET_}: {$matchlink}", +$value, $bet['teamid'], true);
			$balance -= $value;
			sql_query("UPDATE `teams` SET `odds_balance` = `odds_balance` + {$value} WHERE `id` = '{$bet['teamid']}'", __FILE__, __LINE__);
		}
		sql_query("UPDATE `config` SET `value` = {$balance} WHERE `name` = 'bets_balance'", __FILE__, __LINE__);
		sql_query("UPDATE `bets` SET `payed` = 'yes' WHERE `matchid` = {$this->ID}", __FILE__, __LINE__);
		// update match
		sql_query("UPDATE `match` SET `played` = 'yes', `homescore` = {$this->HOMERESULT}, `awayscore` = {$this->AWAYRESULT} WHERE `id` = {$this->ID}", __FILE__, __LINE__);
	}
	private $player_of_the_match = array();
	private function update_player2($player, $prefix, $money_var)
	{
		
	}
	private function update_player($var_name, $prefix, $sub, $money_var)
	{
		$player = &$this->{$var_name};
		if ($player['id'] > 0)
		{
			if (!$sub) $this->{$money_var} += $player['winbonus'];
			$player["form"] = $player['performance'] + 5 * $player['goals'];
			if ($player["form"] > 99) $player["form"] = 99;
			if ($player["form"] < 1) $player["form"] = 1;
			// Player of the match
			if ($this->player_of_the_match["form"] < $player["form"]) $this->player_of_the_match = $this->{$var_name};
			else if ($this->player_of_the_match["form"] == $player["form"]) if ($this->player_of_the_match["goals"] < $player["goals"]) $this->player_of_the_match = $this->{$var_name};
			if ($player["inj"] > 0) $player["injfor"] = rand(2, 9);
			sql_query("UPDATE `players_stats` SET
         `cur_{$prefix}_goals` = `cur_{$prefix}_goals` + {$player["goals"]},
         `all_{$prefix}_goals` = `all_{$prefix}_goals` + {$player["goals"]},
         `cur_{$prefix}_red` = `cur_{$prefix}_red` + {$player["red"]},
         `all_{$prefix}_red` = `all_{$prefix}_red` + {$player["red"]},
         `cur_{$prefix}_yellow` = `cur_{$prefix}_yellow` + {$player["yel"]},
         `all_{$prefix}_yellow` = `all_{$prefix}_yellow` + {$player["yel"]},
         `cur_{$prefix}_played` = `cur_{$prefix}_played` + 1,
         `all_{$prefix}_played` = `all_{$prefix}_played` + 1,
         `cur_{$prefix}_inj` = `cur_{$prefix}_inj` + {$player["inj"]},
         `all_{$prefix}_inj` = `all_{$prefix}_inj` + {$player["inj"]}
         WHERE `id` = {$player["id"]}", __FILE__, __LINE__);
			$fitness = $player["fitness"];
			if (!$sub && $this->TECHNICAL == 0)
			{
				$fitness = $player["fitness"] - 10;
				if ($fitness < 20) $fitness = 20;
			}
			$exp = $player['experience'];
			if (mt_rand(1, 20) == 10)
			{
				$exp++;
				if ($exp > 99) $exp = 99;
			}
			sql_query("UPDATE `players` SET `currentform` = {$player["form"]}, `fitness` = {$fitness}, `experience` = {$exp} WHERE `id` = {$player["id"]}", __FILE__, __LINE__);
			if ($player["bestform"] < $player["form"]) sql_query("UPDATE `players` SET `bestform` = {$player["form"]} WHERE `id` = {$player["id"]}", __FILE__, __LINE__);
			if ($player["injfor"] > 0) sql_query("UPDATE `players` SET `injured` = {$player["injfor"]} WHERE `id` = {$player["id"]}", __FILE__, __LINE__);
			$ban = mt_rand(1, 2);
			if ($this->matchdata['rules'] == "league" && $player["red"]) sql_query("UPDATE `players` SET `banleague` = `banleague` + {$ban} WHERE `id` = {$player["id"]}", __FILE__, __LINE__);
			else if ($this->matchdata['rules'] == "cup" && $player["red"]) sql_query("UPDATE `players` SET `bancup` = `bancup` + {$ban} WHERE `id` = {$player["id"]}", __FILE__, __LINE__);
		}
	}
	private function save_data()
	{
		$data = $this->serialize_data();
		$id = "000".$this->ID;
		$l = strlen($id);
		$path = "./cache/matches/{$id[$l-4]}/{$id[$l-3]}/{$id[$l-2]}/{$id[$l-1]}/{$this->ID}.txt.gz";
		$file = gzopen($path, "w9");
		gzwrite($file, $data);
		gzclose($file);
	}
	private function serialize_data()
	{
		$DATA['result'] = "OK";
		$DATA['generator'] = MATCH_SIMULATOR_NAME;
		$DATA['version'] = MATCH_SIMULATOR_VERSION;
		$DATA['time'] = get_date_time(false);
		$DATA['id'] = $this->ID;

		$DATA['matchdata'] = $this->matchdata;
		$DATA['matchdata']['minutes'] = $this->MINUTES;
		$DATA['matchdata']['technical'] = $this->TECHNICAL;
		$DATA['matchtype'] = $this->matchtype;

		$DATA['stadium'] = $this->stadium;

		$DATA['hometeam'] = array_merge($this->hometeam, $this->HOMESTATS);
		$DATA['hometeam']['result'] = $this->HOMERESULT;
		$DATA['hometeam']['result2'] = $this->HOMERESULT2;
		$DATA['hometeam']['tactics'] = $this->hometactic;
		$DATA['hometeam']['players'] = $this->HOMEPLAYERS;
		$DATA['hometeam']['substitutes'] = $this->HOMESUBSTITUTES;
		$DATA['hometeam']['win_bonus'] = $this->h_win_bonus;

		$DATA['awayteam'] = array_merge($this->awayteam, $this->AWAYSTATS);
		$DATA['awayteam']['result'] = $this->AWAYRESULT;
		$DATA['awayteam']['result2'] = $this->AWAYRESULT2;
		$DATA['awayteam']['tactics'] = $this->awaytactic;
		$DATA['awayteam']['players'] = $this->AWAYPLAYERS;
		$DATA['awayteam']['substitutes'] = $this->AWAYSUBSTITUTES;
		$DATA['awayteam']['win_bonus'] = $this->a_win_bonus;

		$DATA['player_of_the_match'] = $this->player_of_the_match;

		$DATA['match'] = $this->MATCH;
		//debug::show($DATA, "DATA");
		//print("<b>{$this->HOMERESULT} - {$this->AWAYRESULT}</b><br>");
		return serialize($DATA);
	}
}
?>
