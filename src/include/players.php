<?php
function get_random_name($thecountry)
{
   global $defaultnames;
   if (empty($thecountry) || $thecountry == 0) $thecountry = 100;
   $countryname = sql_get("SELECT `name` FROM `countries` WHERE `id` = {$thecountry} AND `hasnames` = 'yes'", __FILE__, __LINE__);
   if ($countryname)
   {
      $firstnames = file("./include/names/{$countryname}_FirstNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $lastnames = file("./include/names/{$countryname}_LastNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   }
   else
   {
      $firstnames = file("./include/names/{$defaultnames}_FirstNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $lastnames = file("./include/names/{$defaultnames}_LastNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   }
   $name = sqlsafe(trim($firstnames[rand(0, count($firstnames) - 1)]) . " " . trim($lastnames[rand(0, count($lastnames) - 1)]));
   return $name;
}
function get_random_possition()
{
   $i = rand(1, 27);
   $poss = "'GK'";
   if ($i == 1 || $i == 2) $poss = "'GK'";
   else if ($i == 3 || $i == 4) $poss = "'LB'";
   else if ($i == 5 || $i == 6) $poss = "'CB'";
   else if ($i == 7 || $i == 8) $poss = "'RB'";
   else if ($i == 9) $poss = "'LBM'";
   else if ($i == 10) $poss = "'CBM'";
   else if ($i == 11) $poss = "'RBM'";
   else if ($i == 12 || $i == 13) $poss = "'LM'";
   else if ($i == 14 || $i == 15) $poss = "'CM'";
   else if ($i == 16 || $i == 17) $poss = "'RM'";
   else if ($i == 18) $poss = "'LFM'";
   else if ($i == 19) $poss = "'CFM'";
   else if ($i == 20) $poss = "'RFM'";
   else if ($i == 21 || $i == 22) $poss = "'LF'";
   else if ($i == 23 || $i == 24) $poss = "'CF'";
   else if ($i == 25 || $i == 26) $poss = "'RF'";
   return $poss;
}
function get_possitions_form($script, $varname, $selected = "GK", $buttontext = "Show", $method = "GET")
{
   global $possitions;
   $ret = "<form method='{$method}' action='{$script}'><select name='{$varname}'>";
   foreach ($possitions as $value)
   {
      if ($selected == $value) $ret .= "<option value='{$value}' selected>{$value}</option>";
      else $ret .= "<option value='{$value}'>{$value}</option>";
   }
   $ret .= "</select> <input type='submit' value='{$buttontext}!'></form>";
   return $ret;
}
function get_color_for_possition($possition)
{
   switch ($possition)
   {
      case "GK":
         return "#FF8C00";
      case "LB":case "CB":case "CB1":case "CB2":case "CB3":case "RB":
         return "#B86000";
      case "LBM":case "CBM":case "RBM":
         return "#A52A2A";
      case "LM":case "CM":case "CM1":case "CM2":case "CM3":case "RM":
         return "#B03060";
      case "LFM":case "CFM":case "RFM":
         return "#FF0000";
      case "LF":case "CF":case "CF1":case "CF2":case "CF3":case "RF":
         return "#0000FF";
      case "S1":case "S2":case "S3":case "S4":case "S5":
         return "#333333";
   }
   return "";
}
function get_player_name($realname, $shortname, $short = false)
{
   $ret = "";
   if ($shortname == "") $ret = $realname;
   else $ret = $shortname;
   if ($short && str_word_count($ret, 0) > 1)
   {
      $num = str_word_count($ret, 0);
      $arr = str_word_count($ret, 1);
      $ret = $arr[0][0].". ".$arr[$num-1];
   }
   return $ret;
}
function generate_player($country, $name = "'unknown'", $age = "17", $possition = "'GK'", $team = 0, $potential = 50, $number = 0, $experience = 0, $league = 0, $incup = "'yes'", $shkola = 0)
{
   global $defaultattribute;
   //prnt("generate_player($country, $name, $age, $possition, $team, $potential, $number, $experience, $league, $incup<br>");
   // ALL
	if ($shkola == 9) $plus = 0;
	else $plus = 5;
   if ($potential > 99) $potential = 99;
   if ($potential < 10) $potential = 10;
   if (!$country) $country = rand(1, 100);
   $weight = rand(60, 99);
   $height = rand(160, 210);
   $aggression = rand(10, 90);
   $stamina = rand($potential - 2, $potential +$plus);
   if ($stamina > 99) $stamina = 99;
   $passing = rand($potential - 2, $potential +$plus);
   $speed = rand($potential - 2, $potential +$plus);
   $ballcontrol = rand($potential - 2, $potential +$plus);
   $global = $stamina + $passing + $speed + $ballcontrol;
   // GK
   $goalkeeping = $defaultattribute;
   $takeball = $defaultattribute;
   $playalong = $defaultattribute;
   $jumping = $defaultattribute;
   $flexibility = $defaultattribute;
   $courage = $defaultattribute;
   // ALL ^ GK
   $positioning = $defaultattribute;
   $tackling = $defaultattribute;
   // DEF
   $heading = $defaultattribute;
   $playitout = $defaultattribute;
   // MID
   $dribble = $defaultattribute;
   $shooting = $defaultattribute;
   $technique = $defaultattribute;
   // ATT
   $goalsense = $defaultattribute;
   //print $possition."<br>";
   switch (str_replace("'", "", $possition))
   {
      case "GK":
         $goalkeeping = rand($potential - 2, $potential +$plus);
         $takeball = rand($potential - 2, $potential +$plus);
         $playalong = rand($potential - 2, $potential +$plus);
         $jumping = rand($potential - 2, $potential +$plus);
         $flexibility = rand($potential - 2, $potential +$plus);
         $courage = rand($potential - 2, $potential +$plus);
         $global = ($global + $goalkeeping + $takeball + $playalong + $jumping + $flexibility + $courage) / 10;
         break;
      case "LB":
      case "CB":
      case "RB":
         $takeball = rand($potential - 2, $potential +$plus);
         $heading = rand($potential - 2, $potential +$plus);
         $playitout = rand($potential - 2, $potential +$plus);
         $playalong = rand($potential - 2, $potential +$plus);
         $positioning = rand($potential - 2, $potential +$plus);
         $tackling = rand($potential - 2, $potential +$plus);
         $global = ($global + $takeball + $heading + $playitout + $playalong + $positioning + $tackling) / 10;
         break;
      case "LBM":
      case "CBM":
      case "RBM":
         $takeball = rand($potential - 2, $potential +$plus);
         $heading = rand($potential - 2, $potential +$plus);
         $playitout = rand($potential - 2, $potential +$plus);
         $playalong = rand($potential - 2, $potential +$plus);
         $positioning = rand($potential - 2, $potential +$plus);
         $tackling = rand($potential - 2, $potential +$plus);
         $dribble = rand($potential - 2, $potential +$plus);
         $shooting = rand($potential - 2, $potential +$plus);
         $technique = rand($potential - 2, $potential +$plus);
         $global = ($global + $takeball + $heading + $playitout + $playalong + $positioning + $tackling + $dribble + $shooting + $technique) / 13;
         break;
      case "LM":
      case "CM":
      case "RM":
         $dribble = rand($potential - 2, $potential +$plus);
         $shooting = rand($potential - 2, $potential +$plus);
         $playitout = rand($potential - 2, $potential +$plus);
         $technique = rand($potential - 2, $potential +$plus);
         $positioning = rand($potential - 2, $potential +$plus);
         $tackling = rand($potential - 2, $potential +$plus);
         $global = ($global + $dribble + $shooting + $playitout + $technique + $positioning + $tackling) / 10;
         break;
      case "LFM":
      case "CFM":
      case "RFM":
         $dribble = rand($potential - 2, $potential +$plus);
         $shooting = rand($potential - 2, $potential +$plus);
         $playitout = rand($potential - 2, $potential +$plus);
         $technique = rand($potential - 2, $potential +$plus);
         $positioning = rand($potential - 2, $potential +$plus);
         $tackling = rand($potential - 2, $potential +$plus);
         $heading = rand($potential - 2, $potential +$plus);
         $goalsense = rand($potential - 2, $potential +$plus);
         $global = ($global + $dribble + $shooting + $playitout + $technique + $positioning + $tackling + $heading + $goalsense) / 12;
         break;
      case "LF":
      case "CF":
      case "RF":
         $heading = rand($potential - 2, $potential +$plus);
         $goalsense = rand($potential - 2, $potential +$plus);
         $shooting = rand($potential - 2, $potential +$plus);
         $technique = rand($potential - 2, $potential +$plus);
         $positioning = rand($potential - 2, $potential +$plus);
         $tackling = rand($potential - 2, $potential +$plus);
         $global = ($global + $heading + $goalsense + $shooting + $technique + $positioning + $tackling) / 10;
         break;
   }
   $global = round($global);
   //  id	name	shortname	team	number	contrtime	wage	winbonus	car	house	country	age	weight	height	possition	training	injured	banleague	bancup	global	currentform	bestform	maximum	aggression	experience	moral	fitness	stamina	speed	ballcontrol	passing	goalkeeping	takeball	playalong	jumping	flexibility	courage	positioning	tackling	heading	playitout	dribble	shooting	technique	goalsense
   sql_query("INSERT INTO `players_stats` (`id`, `league`) VALUES (NULL, {$league})", __FILE__, __LINE__);
   sql_query("INSERT INTO `players` VALUES (NULL,{$name},'',{$team},{$number},50,600,0,'no','no',{$country},{$age},{$weight},{$height},{$possition},8,0,'no',0,0,{$global},0,0,{$aggression},{$experience},99,{$stamina},{$speed},{$ballcontrol},{$passing},{$goalkeeping},{$takeball},{$playalong},{$jumping},{$flexibility},{$courage},{$positioning},{$tackling},{$heading},{$playitout},{$dribble},{$shooting},{$technique},{$goalsense},'')", __FILE__, __LINE__);
}
function generate_players_for_team($team = 0, $thecountry = 0, $league = 0, $minpot = 20, $maxpot = 50)
{
   global $defaultnames;
   $incup = sql_get("SELECT `cup` FROM `teams` WHERE `id` = {$team}", __FILE__, __LINE__);
   //sql_query("DELETE FROM `players_stats` WHERE `team` = {$team}", __FILE__, __LINE__);
   $countryname = sql_get("SELECT `name` FROM `countries` WHERE `id` = {$thecountry} AND `hasnames` = 'yes'", __FILE__, __LINE__);
   if ($countryname)
   {
      $firstnames = file("./include/names/{$countryname}_FirstNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $lastnames = file("./include/names/{$countryname}_LastNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   }
   else
   {
      $firstnames = file("./include/names/{$defaultnames}_FirstNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      $lastnames = file("./include/names/{$defaultnames}_LastNames.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   }
   $GK = 0; $LB = 0; $CB = 0; $CB1 = 0; $CB2 = 0; $CB3 = 0; $RB = 0; $LM = 0; $CM = 0; $CM1 = 0; $CM2 = 0; $CM3 = 0; $RM = 0; $CF1 = 0; $CF2 = 0; $CF3 = 0; $S1 = 0; $S2 = 0; $S3 = 0; $S4 = 0; $S5 = 0;
   if ($thecountry == 0) $countries = sql_get("SELECT COUNT(`id`) FROM `countries`", __FILE__, __LINE__);
   else $country = $thecountry;
   for ($i = 1; $i <= 26; $i++)
   {
      if ($thecountry == 0) $country = rand(1, $countries);
      $name = sqlsafe(trim($firstnames[rand(0, count($firstnames) - 1)]) . " " . trim($lastnames[rand(0, count($lastnames) - 1)]));
      $time = rand(17, 25);
      $potential = rand($minpot, $maxpot);
      // 'GK', 'LB', 'CB', 'RB', 'LBM', 'CBM', 'RBM', 'LM', 'CM', 'RM', 'LFM', 'CFM', 'RFM', 'LF', 'CF', 'RF'
      if ($i == 1 || $i == 2) $poss = "'GK'";
      else if ($i == 3 || $i == 4) $poss = "'LB'";
      else if ($i == 5 || $i == 6) $poss = "'CB'";
      else if ($i == 7 || $i == 8) $poss = "'RB'";
      else if ($i == 9) $poss = "'LBM'";
      else if ($i == 10) $poss = "'CBM'";
      else if ($i == 11) $poss = "'RBM'";
      else if ($i == 12 || $i == 13) $poss = "'LM'";
      else if ($i == 14 || $i == 15) $poss = "'CM'";
      else if ($i == 16 || $i == 17) $poss = "'RM'";
      else if ($i == 18) $poss = "'LFM'";
      else if ($i == 19) $poss = "'CFM'";
      else if ($i == 20) $poss = "'RFM'";
      else if ($i == 21 || $i == 22) $poss = "'LF'";
      else if ($i == 23 || $i == 24) $poss = "'CF'";
      else if ($i == 25 || $i == 26) $poss = "'RF'";
      generate_player($country, $name, $time, $poss, $team, $potential, $i, 0, $league, $incup);
      $id = mysql_insert_id();
      switch ($poss)
      {
         case "'GK'": if ($GK == 0) $GK = $id; else $S1 = $id; break;
         case "'LB'": $LB = $id; break;
         case "'CB'":
            {
               if ($CB == 0) { $CB1 = $id; $CB++; }
               else if ($CB == 1) { $CB2 = $id; $CB++; }
               break;
            }
         case "'RB'": $RB = $id; break;
         case "'LBM'": $S2 = $id; break;
         case "'CBM'": $CB3 = $id; break;
         case "'RBM'": $S3 = $id; break;
         case "'LM'": $LM = $id; break;
         case "'CM'":
            {
               if ($CM == 0) { $CM1 = $id; $CM++; }
               else if ($CM == 1) { $CM2 = $id; $CM++; }
               break;
            }
         case "'RM'": $RM = $id; break;
         case "'LFM'": $S4 = $id; break;
         case "'CFM'": $CM3 = $id; break;
         case "'RFM'": $S5 = $id; break;
         case "'LF'": $CF1 = $id; break;
         case "'CF'": $CF2 = $id; break;
         case "'RF'": $CF3 = $id; break;
      }
   }
   sql_query("INSERT INTO `tactics` (`captain`,`GK`,`LB`,`CB1`,`CB2`,`CB3`,`RB`,`LM`,`CM1`,`CM2`,`CM3`,`RM`,`CF1`,`CF2`,`CF3`,`S1`,`S2`,`S3`,`S4`,`S5`)
VALUES ('{$GK}','{$GK}','{$LB}','{$CB1}','{$CB2}','{$CB3}','{$RB}','{$LM}','{$CM1}','{$CM2}','{$CM3}','{$RM}','{$CF1}','{$CF2}','{$CF3}','{$S1}','{$S2}','{$S3}','{$S4}','{$S5}');", __FILE__, __LINE__);
   return mysql_insert_id();
}
function get_special_abb($poss)
{
   switch ($poss)
   {
      case "GK": return array(10, 11, 12, 13, 14, 15);
      //
      case "LB": return array(11, 12, 16, 17, 18, 19);
      case "RB": return array(11, 12, 16, 17, 18, 19);
      case "CB": return array(11, 12, 16, 17, 18, 19);
      //
      case "LBM": return array(11, 12, 16, 17, 18, 19, 20, 21, 22);
      case "CBM": return array(11, 12, 16, 17, 18, 19, 20, 21, 22);
      case "RBM": return array(11, 12, 16, 17, 18, 19, 20, 21, 22);
      //
      case "LM": return array(16, 17, 19, 20, 21, 22);
      case "CM": return array(16, 17, 19, 20, 21, 22);
      case "RM": return array(16, 17, 19, 20, 21, 22);
      //
      case "LFM": return array(16, 17, 18, 19, 20, 21, 22, 23);
      case "CFM": return array(16, 17, 18, 19, 20, 21, 22, 23);
      case "RFM": return array(16, 17, 18, 19, 20, 21, 22, 23);
      //
      case "LF": return array(16, 17, 18, 21, 22, 23);
      case "CF": return array(16, 17, 18, 21, 22, 23);
      case "RF": return array(16, 17, 18, 21, 22, 23);
   }
}
function get_trainig_abb($poss)
{
   switch ($poss)
   {
      case "GK": return array(6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
      //
      case "LB": return array(6, 7, 8, 9, 11, 12, 16, 17, 18, 19);
      case "RB": return array(6, 7, 8, 9, 11, 12, 16, 17, 18, 19);
      case "CB": return array(6, 7, 8, 9, 11, 12, 16, 17, 18, 19);
      //
      case "LBM": return array(6, 7, 8, 9, 11, 12, 16, 17, 18, 19, 20, 21, 22);
      case "CBM": return array(6, 7, 8, 9, 11, 12, 16, 17, 18, 19, 20, 21, 22);
      case "RBM": return array(6, 7, 8, 9, 11, 12, 16, 17, 18, 19, 20, 21, 22);
      //
      case "LM": return array(6, 7, 8, 9, 16, 17, 19, 20, 21, 22);
      case "CM": return array(6, 7, 8, 9, 16, 17, 19, 20, 21, 22);
      case "RM": return array(6, 7, 8, 9, 16, 17, 19, 20, 21, 22);
      //
      case "LFM": return array(6, 7, 8, 9, 16, 17, 18, 19, 20, 21, 22, 23);
      case "CFM": return array(6, 7, 8, 9, 16, 17, 18, 19, 20, 21, 22, 23);
      case "RFM": return array(6, 7, 8, 9, 16, 17, 18, 19, 20, 21, 22, 23);
      //
      case "LF": return array(6, 7, 8, 9, 16, 17, 18, 21, 22, 23);
      case "CF": return array(6, 7, 8, 9, 16, 17, 18, 21, 22, 23);
      case "RF": return array(6, 7, 8, 9, 16, 17, 18, 21, 22, 23);
   }
}
function get_abb_name($id)
{
   switch ($id)
   {
      // Common
      case 1: return GLOBAL_RATING;
      case 2: return AGGRESSION;
      case 3: return EXPERIENCE;
      //case 4: return MORAL;
      case 5: return FITNESS;
      case 6: return STAMINA;
      case 7: return SPEED;
      case 8: return BALL_CONTROL;
      case 9: return PASSING;
      // Goalkeepers
      case 10: return GOALKEEPING;
      case 11: return TAKEBALL;
      case 12: return PLAY_ALONG;
      case 13: return JUMPING;
      case 14: return FLEXIBILITY;
      case 15: return COURAGE;
      // All ^ GK
      case 16: return POSITIONING;
      case 17: return TACKLING;
      // Defenders
      case 18: return HEADING;
      case 19: return PLAY_IT_OUT;
      // Midfielders
      case 20: return DRIBBLE;
      case 21: return SHOOTING;
      case 22: return TECHNIQUE;
      // Attackers
      case 23: return GOAL_SENSE;
      default: return "";
   }
}
function get_abb_id($id)
{
   switch ($id)
   {
      // Common
      case 1: return "global";
      case 2: return "aggression";
      case 3: return "experience";
      //case 4: return "moral";
      case 5: return "fitness";
      case 6: return "stamina";
      case 7: return "speed";
      case 8: return "ballcontrol";
      case 9: return "passing";
      // Goalkeepers
      case 10: return "goalkeeping";
      case 11: return "takeball";
      case 12: return "playalong";
      case 13: return "jumping";
      case 14: return "flexibility";
      case 15: return "courage";
      // All ^ GK
      case 16: return "positioning";
      case 17: return "tackling";
      // Defenders
      case 18: return "heading";
      case 19: return "playitout";
      // Midfielders
      case 20: return "dribble";
      case 21: return "shooting";
      case 22: return "technique";
      // Attackers
      case 23: return "goalsense";
      default: return "";
   }
}
function automatic_training($id)
{
   $players = sql_query("SELECT * FROM `players` WHERE `team` = {$id}", __FILE__, __LINE__);
   while ($row = mysql_fetch_assoc($players))
   {
      $min = 1001;
      $minatt = "ballcontrol";
      $att = get_trainig_abb($row['possition']);
      foreach ($att as $value)
      {
         if ($row[get_abb_id($value)] < $min)
         {
            $min = $row[get_abb_id($value)];
            $minatt =  $value;
         }
      }
      sql_query("UPDATE `players` SET `training` = {$minatt} WHERE `id` = {$row['id']}", __FILE__, __LINE__);
   }
}
/*
UPDATE `players` SET `stamina` = `stamina` + 5 WHERE `age` = 16 AND `stamina` > 5;
UPDATE `players` SET `speed` = `speed` + 5 WHERE `age` = 16 AND `speed` > 5;
UPDATE `players` SET `ballcontrol` = `ballcontrol` + 5 WHERE `age` = 16 AND `ballcontrol` > 5;
UPDATE `players` SET `passing` = `passing` + 5 WHERE `age` = 16 AND `passing` > 5;

UPDATE `players` SET `goalkeeping` = `goalkeeping` + 5 WHERE `age` = 16 AND `goalkeeping` > 5;
UPDATE `players` SET `takeball` = `takeball` + 5 WHERE `age` = 16 AND `takeball` > 5;
UPDATE `players` SET `playalong` = `playalong` + 5 WHERE `age` = 16 AND `playalong` > 5;
UPDATE `players` SET `jumping` = `jumping` + 5 WHERE `age` = 16 AND `jumping` > 5;
UPDATE `players` SET `flexibility` = `flexibility` + 5 WHERE `age` = 16 AND `flexibility` > 5;
UPDATE `players` SET `courage` = `courage` + 5 WHERE `age` = 16 AND `courage` > 5;

UPDATE `players` SET `positioning` = `positioning` + 5 WHERE `age` = 16 AND `positioning` > 5;
UPDATE `players` SET `tackling` = `tackling` + 5 WHERE `age` = 16 AND `tackling` > 5;

UPDATE `players` SET `heading` = `heading` + 5 WHERE `age` = 16 AND `heading` > 5;
UPDATE `players` SET `playitout` = `playitout` + 5 WHERE `age` = 16 AND `playitout` > 5;

UPDATE `players` SET `dribble` = `dribble` + 5 WHERE `age` = 16 AND `dribble` > 5;
UPDATE `players` SET `shooting` = `shooting` + 5 WHERE `age` = 16 AND `shooting` > 5;
UPDATE `players` SET `technique` = `technique` + 5 WHERE `age` = 16 AND `technique` > 5;

UPDATE `players` SET `goalsense` = `goalsense` + 5 WHERE `age` = 16 AND `goalsense` > 5;
*/
?>