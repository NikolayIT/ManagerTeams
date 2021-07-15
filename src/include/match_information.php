<?php
define("MATCHINFORMATION_VERSION", "1.06");
class MatchInformation
{
   private $ID = 0;
   public $DATA = 0;
   public $matchdata = "";
   public function __construct($id)
   {
      $this->ID = $id;
      $this->matchdata = sql_data("SELECT *,
      (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`,
      (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname`
      FROM `match` WHERE `id` = {$this->ID}", __FILE__, __LINE__);
      $id = "000".$this->ID;
      $l = strlen($id);
      $path = "./cache/matches/{$id[$l-4]}/{$id[$l-3]}/{$id[$l-2]}/{$id[$l-1]}/{$this->ID}.txt.gz";
      $FileOpen = fopen($path, "rb");
      fseek($FileOpen, -4, SEEK_END);
      $buf = fread($FileOpen, 4);
      $GZFileSize = end(unpack("V", $buf));
      fclose($FileOpen);
      $HandleRead = gzopen($path, "rb");
      $ContentRead = gzread($HandleRead, $GZFileSize);
      gzclose($HandleRead);
      $this->DATA = unserialize($ContentRead);
      $this->prepare();
   }
   public function __destruct() { }
   public function prepare()
   {
      $lang = sql_get("SELECT `file_match` FROM `languages` WHERE `id` = ".sqlsafe(0 + $_COOKIE["lang"]), __FILE__, __LINE__);
      @include("languages/{$lang}");
      if (!defined("IN_MATCH_LANG")) include("languages/".DEFAULT_MATCH_LANGUAGE_FILE);
      $this->DATA['hometeam']['image'] = sql_get("SELECT `avatar` FROM `users` WHERE `team` = '{$this->DATA['hometeam']['id']}'", __FILE__, __LINE__);
	  if ($this->DATA['hometeam']['image']) $this->DATA['hometeam']['imagehtml'] = "<img src=\"{$this->DATA['hometeam']['image']}\" style=\"max-width:150px; width: expression(this.width > 150 ? 150: true);\" />";
      $this->DATA['awayteam']['image'] = sql_get("SELECT `avatar` FROM `users` WHERE `team` = '{$this->DATA['awayteam']['id']}'", __FILE__, __LINE__);
	  if ($this->DATA['awayteam']['image']) $this->DATA['awayteam']['imagehtml'] = "<img src=\"{$this->DATA['awayteam']['image']}\" style=\"max-width:150px; width: expression(this.width > 150 ? 150: true);\" />";
      $this->DATA['stadium']['parking_info'] = calculate_parkings($this->DATA['stadium']['parking']);
      $this->DATA['stadium']['bars_info'] = calculate_bars($this->DATA['stadium']['bars']);
      $this->DATA['stadium']['toilets_info'] = calculate_toilets($this->DATA['stadium']['toilets']);
      $this->DATA['stadium']['grass_info'] = calculate_grass($this->DATA['stadium']['grass']);
      $this->DATA['stadium']['lights_info'] = calculate_lights($this->DATA['stadium']['lights']);
      $this->DATA['stadium']['boards_info'] = calculate_boards($this->DATA['stadium']['boards']);
      $this->DATA['stadium']['youthcenter_info'] = calculate_youthcenter($this->DATA['stadium']['youthcenter']);
      $this->DATA['stadium']['roof_info'] = calculate_roof($this->DATA['stadium']['roof']);
      $this->DATA['stadium']['heater_info'] = calculate_heater($this->DATA['stadium']['heater']);
      $this->DATA['stadium']['sprinkler_info'] = calculate_sprinkler($this->DATA['stadium']['sprinkler']);
      $rep = array("{_MIN_}", "{_TEAM1_}", "{_TEAM2_}", "{_PLAYER1_}", "{_PLAYER2_}", "{_RESULT_}");
      for ($i = 0; true; $i++)
      {
         if ($this->DATA['match'][$i]['minute'])
         {
            $with1 = array($this->DATA['match'][$i]['min'], $this->DATA['match'][$i]['event1_team1'], $this->DATA['match'][$i]['event1_team2'], $this->DATA['match'][$i]['event1_player1'], $this->DATA['match'][$i]['event1_player2'], $this->DATA['match'][$i]['event_result']);
            $this->DATA['match'][$i]['event1'] = @constant($this->DATA['match'][$i]['event1_comm']);
            $this->DATA['match'][$i]['event1'] = str_replace($rep, $with1, $this->DATA['match'][$i]['event1']);
            $with2 = array($this->DATA['match'][$i]['min'], $this->DATA['match'][$i]['event2_team1'], $this->DATA['match'][$i]['event2_team2'], $this->DATA['match'][$i]['event2_player1'], $this->DATA['match'][$i]['event2_player2'], $this->DATA['match'][$i]['event_result']);
            $this->DATA['match'][$i]['event2'] = @constant($this->DATA['match'][$i]['event2_comm']);
            $this->DATA['match'][$i]['event2'] = str_replace($rep, $with2, $this->DATA['match'][$i]['event2']);
            $with3 = array($this->DATA['match'][$i]['min'], $this->DATA['match'][$i]['event3_team1'], $this->DATA['match'][$i]['event3_team2'], $this->DATA['match'][$i]['event3_player1'], $this->DATA['match'][$i]['event3_player2'], $this->DATA['match'][$i]['event_result']);
            $this->DATA['match'][$i]['event3'] = @constant($this->DATA['match'][$i]['event3_comm']);
            $this->DATA['match'][$i]['event3'] = str_replace($rep, $with3, $this->DATA['match'][$i]['event3']);
         }
         else break;
      }
   }
   public function get_image_for_event($event)
   {
      if (stripos($event, "M_GOAL_") !== false) return "<div class=\"statistics_goal\">";
      else if (stripos($event, "M_YELLOW_") !== false) return "<div class=\"statistics_yellow\">";
      else if (stripos($event, "M_RED_") !== false) return "<div class=\"statistics_red\">";
      else if (stripos($event, "M_PENALTY_") !== false) return "<div class=\"statistics_goal\">";
      else if (stripos($event, "M_INJURY_") !== false) return "<div class=\"img_player_injured\">";
      else if (stripos($event, "M_SUBTITUTION_") !== false) return "<div class=\"img_match_change\">";
      else return "";
   }
   public function create_text()
   {
      $ret = "<h2>{$this->DATA['hometeam']['name']} - {$this->DATA['awayteam']['name']} {$this->DATA['hometeam']['result']} : {$this->DATA['awayteam']['result']}</h2>";
      $ret .= "<table border=\"2\" width=\"100%\" style=\"width:100%;\">";
      $ret .= "<tr><td class=\"tb\">";
      $ret .= "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">";
      $ret .= "<tr><th>".TYPE."</th><td class=\"tb\">{$this->DATA['matchtype']['name']}</td></tr>";
      $ret .= "<tr><th>".DATE."</th><td class=\"tb\">{$this->DATA['matchdata']['start']}</td></tr>";
      $ret .= "<tr><th>".STADIUM."</th><td class=\"tb\"><a href=\"stadium.php?id={$this->DATA['stadium']['id']}\">{$this->DATA['stadium']['name']}</a></td></tr>";
      $ret .= "<tr><th>".WEATHER."</th><td class=\"tb\">{$this->DATA['matchdata']['weather']}</td></tr>";
      $ret .= "<tr><th>".TICKETS."</th><td class=\"tb\">{$this->DATA['matchdata']['tickets']} ({$this->DATA['matchdata']['htickets']}% - {$this->DATA['matchdata']['atickets']}%)</td></tr>";
      $ret .= "<tr><th>".REFEREE."</th><td class=\"tb\">{$this->DATA['matchdata']['main_referee']} (".STRICTNESS.": {$this->DATA['matchdata']['referee_strictness']}%)</td></tr>";
      $ret .= "<tr><th>".LEFT_SIDE_REFEREE."</th><td class=\"tb\">{$this->DATA['matchdata']['left_referee']}</td></tr>";
      $ret .= "<tr><th>".RIGHT_SIDE_REFEREE."</th><td class=\"tb\">{$this->DATA['matchdata']['right_referee']}</td></tr>";
      $ret .= "<tr><th>4 ".REFEREE."</th><td class=\"tb\">{$this->DATA['matchdata']['fourth_referee']}</td></tr>";
      $ret .= "<tr><th>".GAME_NAME." ".DELEGATE."</th><td class=\"tb\">{$this->DATA['matchdata']['delegate']}</td></tr>";
      $ret .= "<tr><th>Играч на мача</th><td class=\"tb\">{$this->DATA['player_of_the_match']['thename']} ({$this->DATA['player_of_the_match']['goals']} гола)</td></tr>";
      $ret .= "</table>";
      $ret .= "</td></tr>";

      $ret .= "<tr><td class=\"tb\">";
      $ret .= "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">";
      $ret .= "<tr>	<th valign='top' style=\"width: 50%;\"><b><a href=\"teamdetails.php?id={$this->DATA['hometeam']['id']}\">{$this->DATA['hometeam']['name']}</a></b><br />{$this->DATA['hometeam']['imagehtml']}</th>
					<th><b>{$this->DATA['hometeam']['result']} - {$this->DATA['awayteam']['result']}</b></th>
					<th valign='top' style=\"width: 50%;\"><b><a href=\"teamdetails.php?id={$this->DATA['awayteam']['id']}\">{$this->DATA['awayteam']['name']}</a></b><br />{$this->DATA['awayteam']['imagehtml']}</th></tr>";
      for ($i = 0; true; $i++)
      {
         if ($this->DATA['match'][$i]['minute'])
         {
            if (stripos($this->DATA['match'][$i]['event1_comm'], "M_GOAL_") !== false)
            {
               if ($this->DATA['match'][$i]['event1_team1'] == $this->DATA['hometeam']['name'])
               {
                  $ret .= "<tr><th style=\"width: 50%;\">{$this->DATA['match'][$i]['event1_player1']}</th><th>{$this->DATA['match'][$i]['min']}</th><th style=\"width: 50%;\"></th></tr>";
               }
               else
               {
                  $ret .= "<tr><th style=\"width: 50%;\"></th><th>{$this->DATA['match'][$i]['min']}</th><th style=\"width: 50%;\">{$this->DATA['match'][$i]['event1_player1']}</th></tr>";
               }
            }
            if (stripos($this->DATA['match'][$i]['event1_comm'], "M_PENALTY_") !== false)
            {
               if ($this->DATA['match'][$i]['event1_team1'] == $this->DATA['hometeam']['name'])
               {
                  $ret .= "<tr><th style=\"width: 50%;\">{$this->DATA['match'][$i]['event1_player1']} (P)</th><th>{$this->DATA['match'][$i]['min']}</th><th style=\"width: 50%;\"></th></tr>";
               }
               else
               {
                  $ret .= "<tr><th style=\"width: 50%;\"></th><th>{$this->DATA['match'][$i]['min']}</th><th style=\"width: 50%;\">{$this->DATA['match'][$i]['event1_player1']} (P)</th></tr>";
               }
            }
         }
         else break;
      }
      $ret .= "</table>";
      $ret .= "</td></tr>";
      $ret .= "<tr><td class=\"tb\">";
      $ret .= "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['hometeam']['init_rating'] * 100) / ($this->DATA['hometeam']['init_rating'] + $this->DATA['awayteam']['init_rating'] + 1))." ({$this->DATA['hometeam']['init_rating']})</center></th><th>Начален рейтинг</th><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['awayteam']['init_rating'] * 100) / ($this->DATA['hometeam']['init_rating'] + $this->DATA['awayteam']['init_rating'] + 1))." ({$this->DATA['awayteam']['init_rating']})<center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['hometeam']['rating'] * 100) / ($this->DATA['hometeam']['rating'] + $this->DATA['awayteam']['rating']))." ({$this->DATA['hometeam']['rating']})</center></th><th>".RATING."</th><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['awayteam']['rating'] * 100) / ($this->DATA['hometeam']['rating'] + $this->DATA['awayteam']['rating']))." ({$this->DATA['awayteam']['rating']})<center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['hometeam']['defence'] * 100) / ($this->DATA['hometeam']['defence'] + $this->DATA['awayteam']['defence']))." ({$this->DATA['hometeam']['defence']})</center></th><th>".DEFENCE."</th><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['awayteam']['defence'] * 100) / ($this->DATA['hometeam']['defence'] + $this->DATA['awayteam']['defence']))." ({$this->DATA['awayteam']['defence']})<center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['hometeam']['middle'] * 100) / ($this->DATA['hometeam']['middle'] + $this->DATA['awayteam']['middle']))." ({$this->DATA['hometeam']['middle']})</center></th><th>".MIDFIELD."</th><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['awayteam']['middle'] * 100) / ($this->DATA['hometeam']['middle'] + $this->DATA['awayteam']['middle']))." ({$this->DATA['awayteam']['middle']})<center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['hometeam']['attack'] * 100) / ($this->DATA['hometeam']['attack'] + $this->DATA['awayteam']['attack']))." ({$this->DATA['hometeam']['attack']})</center></th><th>".ATTACK."</th><th style=\"width: 50%;\"><center>".create_progress_bar(($this->DATA['awayteam']['attack'] * 100) / ($this->DATA['hometeam']['attack'] + $this->DATA['awayteam']['attack']))." ({$this->DATA['awayteam']['attack']})<center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar($this->DATA['hometeam']['tactics']['aggression'])." ({$this->DATA['hometeam']['tactics']['aggression']})</center></th><th>".AGGRESSION."</th><th style=\"width: 50%;\"><center>".create_progress_bar($this->DATA['awayteam']['tactics']['aggression'])." ({$this->DATA['awayteam']['tactics']['aggression']})</center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>".create_progress_bar($this->DATA['hometeam']['tactics']['style'])." ({$this->DATA['hometeam']['tactics']['style']})</center></th><th>".STYLE."</th><th style=\"width: 50%;\"><center>".create_progress_bar($this->DATA['awayteam']['tactics']['style'])." ({$this->DATA['awayteam']['tactics']['style']})</center></th></tr>";
      $ret .= "<tr><th style=\"width: 50%;\"><center>{$this->DATA['hometeam']['win_bonus']}</center></th><th>".WIN_BONUS."</th><th style=\"width: 50%;\"><center>{$this->DATA['awayteam']['win_bonus']}</center></th></tr>";
      $ret .= "</table>";
      $ret .= "<tr><td class=\"tb\">";
      $ret .= "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">";
      for ($i = 0; true; $i++)
      {
         if ($this->DATA['match'][$i]['minute'])
         {
            if ($this->DATA['match'][$i]['event1']) $ret .= "<tr><td class=\"tb\">{$this->DATA['match'][$i]['min']}</td><td class=\"tb\">{$this->get_image_for_event($this->DATA['match'][$i]['event1_comm'])}</td><td class=\"tbwrap\">{$this->DATA['match'][$i]['event1']}</td></tr>";
            if ($this->DATA['match'][$i]['event2']) $ret .= "<tr><td class=\"tb\">{$this->DATA['match'][$i]['min']}</td><td class=\"tb\">{$this->get_image_for_event($this->DATA['match'][$i]['event2_comm'])}</td><td class=\"tbwrap\">{$this->DATA['match'][$i]['event2']}</td></tr>";
            if ($this->DATA['match'][$i]['event3']) $ret .= "<tr><td class=\"tb\">{$this->DATA['match'][$i]['min']}</td><td class=\"tb\">{$this->get_image_for_event($this->DATA['match'][$i]['event3_comm'])}</td><td class=\"tbwrap\">{$this->DATA['match'][$i]['event3']}</td></tr>";
         }
         else break;
      }
      $ret .= "</table>";
      $ret .= "</td></tr>";

      $ret .= "<tr><td class=\"tb\">";
      $ret .= "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">";
      for ($j = 1; $j <= 2; $j++)
      {
         if ($j == 1)
         {
            $team = "hometeam";
            $ret .= "<tr><th colspan=\"10\">{$this->DATA['hometeam']['name']}</th></tr>";
         }
         else
         {
            $team = "awayteam";
            $ret .= "<tr><th colspan=\"10\">{$this->DATA['awayteam']['name']}</th></tr>";
         }
         $ret .= "<tr><th>Match pos</th><th>".NAME."</th><th>Win bonus</th><th>".RATING."</th><th>".FITNESS."</th><th>".POSSITION."<th>Performance</th><th>".GOALS."</th><th>".FORM."</th></tr>";
         if (!$this->DATA['matchdata']['technical']) for ($i = 0; $i <= 10; $i++)
         {
            $player = $this->DATA[$team]['players'][$i];
            if ($player['id'])
            {
               $performance = $player['performance'] / 2;
               $ret .= "<tr>";
               $ret .= "<td class=\"tb\">{$player['possition_macth']}</td>";
               $ret .= table_player_name($player['id'], $player['name'], $player['shortname'], "", false);
               $ret .= "<td class=\"tb\">{$player['winbonus']}".MONEY_SIGN."</td>";
               $ret .= "<td class=\"tb\">{$player['global']}%</td>";
               $ret .= "<td class=\"tb\">{$player['fitness']}%</td>";
               $ret .= "<td class=\"tb\">{$player['possition']} ({$player['possition_handicap']}%)</td>";
               $ret .= "<td class=\"tb\">{$performance}%</td>";
               $ret .= "<td class=\"tb\">{$player['goals']}</td>";
               $ret .= "<td class=\"tb\">".ceil($player['form'])."</td>";
               $ret .= "</tr>";
               $ret .= table_player_row($player['id'], 11, false);
            }
         }
      }
      $ret .= "</td></tr>";
      $ret .= "</table>";
      $ret .= "</td></tr>";
      $ret .= "</table>";
      $ret .= "Generated by: Match Information v".MATCHINFORMATION_VERSION;
      return $ret;
   }
   public function create_xml()
   {
      $result = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
      $result .= "<!-- XML Match Data -->\n";
      $result .= "<data>\n";
      $result .= $this->array_to_xml($this->DATA, 1);
      $result .= "</data>\n";
      return iconv("windows-1251", "utf-8", $result);
   }
   private function get_tabs($count)
   {
      $ret = "";
      for ($i = 1; $i <= $count; $i++) $ret .= "\t";
      return $ret;
   }
   private function array_to_xml($array, $level)
   {
      $ret = "";
      foreach ($array as $key => $value)
      {
         if (is_numeric($key)) $ret .= $this->get_tabs($level) . "<i{$key}>";
         else $ret .= $this->get_tabs($level) . "<{$key}>";
         if (is_array($value)) $ret .= "\n".$this->array_to_xml($value, $level+1).$this->get_tabs($level);
         else $ret .= $value;
         if (is_numeric($key)) $ret .=  "</i{$key}>\n";
         else $ret .= "</{$key}>\n";
      }
      return $ret;
   }
   private function clean_array_to_xml($array, $level)
   {
      $ret = "";
      foreach ($array as $key => $value)
      {
         if (is_numeric($key)) $ret .= "<i{$key}>";
         else $ret .= "<{$key}>";
         if (is_array($value)) $ret .= "\n".$this->array_to_xml($value, $level+1);
         else $ret .= $value;
         if (is_numeric($key)) $ret .=  "</i{$key}>\n";
         else $ret .= "</{$key}>\n";
      }
      return $ret;
   }
}
?>
