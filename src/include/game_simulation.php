<?php
function play_matches()
{
   $matches = sql_query("SELECT `id`, `round`, `hometeam`, `awayteam`, `hometactic`, `awaytactic`, `start`, `rules`, `type` FROM `match` WHERE `start` <= ".get_date_time(true)." AND `played` = 'no' AND `hometeam` < 60000 AND `awayteam` < 60000 ORDER BY `start`", __FILE__, __LINE__);
   if (mysql_affected_rows() == 0) return ;
   set_time_limit(0);
   ignore_user_abort(true);
   $cupid = sql_get("SELECT `id` FROM `match_type` WHERE `name` = 'CUP'", __FILE__, __LINE__);
   $l = 0;
   $c = 0;
   while ($match = mysql_fetch_assoc($matches))
   {
      $winner = simulate_game($match['id'], $match['hometeam'], $match['awayteam'], $match['hometactic'], $match['awaytactic'], $match['start'], $match['rules'], $match['type']);
      //
      if ($match['rules'] == "league") $l++;
      if ($match['rules'] == "cup")
      {
         if ($match['round'] == 17)
         {
            $looser = $match['hometeam'] == $winner ? $match['awayteam'] : $match['hometeam'];
            $teamname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$looser}", __FILE__, __LINE__);
            add_to_league_history("<a href='teamdetails.php?id={$looser}'>{$teamname}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} ".CUP_NAME, $cupid);
            add_to_team_history("<a href='teamdetails.php?id={$looser}'>{$teamname}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} ".CUP_NAME, $looser);
            add_to_money_history("<a href='teamdetails.php?id={$looser}'>{$teamname}</a> (_HAS_REACHED_SEMIFINALS_IN_THE_} ".CUP_NAME, CUP_SEMIFINAL_BONUS, $looser, true);
         }
         if ($match['round'] == 18)
         {
            $looser = $match['hometeam'] == $winner ? $match['awayteam'] : $match['hometeam'];
            $teamnamel = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$looser}", __FILE__, __LINE__);
            $teamnamew = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$winner}", __FILE__, __LINE__);
            add_to_league_history("<a href='teamdetails.php?id={$looser}'>{$teamnamel}</a> {_WAS_IN_SECOND_PLACE_IN_} ".CUP_NAME, $cupid);
            add_to_team_history("<a href='teamdetails.php?id={$looser}'>{$teamnamel}</a> {_WAS_IN_SECOND_PLACE_IN_} ".CUP_NAME, $looser);
            add_to_money_history("<a href='teamdetails.php?id={$looser}'>{$teamnamel}</a> (_WAS_IN_SECOND_PLACE_IN_} ".CUP_NAME, CUP_SECOND_BONUS, $looser, true);
            add_to_league_history("<a href='teamdetails.php?id={$winner}'>{$teamnamew}</a> {_HAS_WON_IN_} ".CUP_NAME, $cupid);
            add_to_team_history("<a href='teamdetails.php?id={$winner}'>{$teamnamew}</a> {_HAS_WON_IN_} ".CUP_NAME, $winner);
            add_to_money_history("<a href='teamdetails.php?id={$winner}'>{$teamnamew}</a> {_HAS_WON_IN_} ".CUP_NAME, CUP_WINNER_BONUS, $winner, true);
         }
         $matc = sql_get("SELECT `id` FROM `match` WHERE `type` = '{$cupid}' AND `hometeam` = '{$match['id']}'", __FILE__, __LINE__);
         if ($matc > 0) sql_query("UPDATE `match` SET `hometeam` = {$winner} WHERE `id` = {$matc}", __FILE__, __LINE__);
         $matc2 = sql_get("SELECT `id` FROM `match` WHERE `type` = '{$cupid}' AND `awayteam` = '{$match['id']}'", __FILE__, __LINE__);
         if ($matc2 > 0) sql_query("UPDATE `match` SET `awayteam` = {$winner} WHERE `id` = {$matc2}", __FILE__, __LINE__);
         $c++;
      }
      if ($match['rules'] == "frcup")
      {
         $teams = sql_get("SELECT `teams` FROM `match_type` WHERE `id` = {$match['type']}", __FILE__, __LINE__);
         $looser = $match['hometeam'] == $winner ? $match['awayteam'] : $match['hometeam'];
         sql_query("UPDATE `friendly_participants` SET `incup` = 'no' WHERE `team` = {$looser} AND `type` = {$match['type']}", __FILE__, __LINE__);
         $rounds = log($teams, 2);
         if ($match['round'] == $rounds)
         {
            sql_query("UPDATE `match_type` SET `finished` = 'yes' WHERE `id` = {$match['type']}", __FILE__, __LINE__);
            sql_query("UPDATE `friendly_participants` SET `incup` = 'no' WHERE `team` = {$winner} AND `type` = {$match['type']}", __FILE__, __LINE__);
            $fee = sql_get("SELECT `fee` FROM `match_type` WHERE `id` = {$match['type']}", __FILE__, __LINE__);
            $fee *= $teams;
            $m1 = 0.65 * $fee;
            $m2 = 0.20 * $fee;
            $name = sql_get("SELECT `name` FROM `match_type` WHERE `id` = {$match['type']}", __FILE__, __LINE__);
            add_to_money_history("1 - {$name}", $m1, $winner, true);
            add_to_team_history("1 - {$name}", $winner);
            add_to_money_history("2 - {$name}", $m2, $looser, true);
            add_to_team_history("2 - {$name}", $looser);
         }
         $matc = sql_get("SELECT `id` FROM `match` WHERE `type` = '{$match['type']}' AND `hometeam` = '{$match['id']}'", __FILE__, __LINE__);
         if ($matc > 0) sql_query("UPDATE `match` SET `hometeam` = {$winner} WHERE `id` = {$matc}", __FILE__, __LINE__);
         $matc2 = sql_get("SELECT `id` FROM `match` WHERE `type` = '{$match['type']}' AND `awayteam` = '{$match['id']}'", __FILE__, __LINE__);
         if ($matc2 > 0) sql_query("UPDATE `match` SET `awayteam` = {$winner} WHERE `id` = {$matc2}", __FILE__, __LINE__);
      }
   }
   if ($c > 0)
   {
      sql_query("UPDATE `match` SET `hometeam` = FLOOR(1 + RAND() * 40000) WHERE `hometeam` = 0 AND `type` = 3281", __FILE__, __LINE__);
      sql_query("UPDATE `match` SET `awayteam` = FLOOR(1 + RAND() * 40000) WHERE `awayteam` = 0 AND `type` = 3281", __FILE__, __LINE__);
      sql_query("UPDATE `config` SET `value` = `value` + 1 WHERE `name` = 'cupround'", __FILE__, __LINE__);
   }
   if ($l > 0) sql_query("UPDATE `config` SET `value` = `value` + 1 WHERE `name` = 'round'", __FILE__, __LINE__);
}
function simulate_game($id, $hometeamid, $awayteamid, $hometacticid, $awaytacticid, $start, $rules = 'league', $matchtype = 0)
{
   global $defaultnames, $formations, $tactictypes;
   $debug = "Match simulator v0.9.11\n";
   // Get teams:
   $home = sql_data("SELECT * FROM `teams` WHERE `id` = '{$hometeamid}'", __FILE__, __LINE__);
   $away = sql_data("SELECT * FROM `teams` WHERE `id` = '{$awayteamid}'", __FILE__, __LINE__);
   // Get tactics:
   if ($hometacticid == 0) $hometacticid = $home['tactic1'];
   $hometactic = sql_data("SELECT * FROM `tactics` WHERE `id` = '{$hometacticid}'", __FILE__, __LINE__);
   if ($awaytacticid == 0) $awaytacticid = $away['tactic1'];
   $awaytactic = sql_data("SELECT * FROM `tactics` WHERE `id` = '{$awaytacticid}'", __FILE__, __LINE__);
   // Stadium data:
   $stadium = sql_data("SELECT * FROM `stadiums` WHERE `id` = {$home['stadium']}", __FILE__, __LINE__);
   $stadiumcapacity = calculate_seats($stadium['eastseats']) + calculate_seats($stadium['westseats']) + calculate_seats($stadium['northseats']) + calculate_seats($stadium['southseats']);
   //
   // Home players:
   //
   $players = array();
   $hformation = $formations[$hometactic['formation']];
   $hkeeper = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['GK']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
   $hkeeper["ind"] = $hometactic["GK_ind"];
   array_push($players, &$hkeeper);
   $hdef = $hformation[0];
   $hdef1 = false; $hdef2 = false; $hdef3 = false; $hdef4 = false; $hdef5 = false;
   if ($hdef == 2)
   {
      $hdef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef1["ind"] = $hometactic["CB1_ind"];
      $hdef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef2["ind"] = $hometactic["CB2_ind"];
      array_push($players, &$hdef1);
      array_push($players, &$hdef2);
   }
   if ($hdef == 3)
   {
      $hdef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['LB']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef1["ind"] = $hometactic["LB_ind"];
      $hdef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef2["ind"] = $hometactic["CB1_ind"];
      $hdef3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['RB']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef3["ind"] = $hometactic["RB_ind"];
      array_push($players, &$hdef1);
      array_push($players, &$hdef2);
      array_push($players, &$hdef3);
   }
   else if ($hdef == 4)
   {
      $hdef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['LB']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef1["ind"] = $hometactic["LB_ind"];
      $hdef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef2["ind"] = $hometactic["CB1_ind"];
      $hdef3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef3["ind"] = $hometactic["CB2_ind"];
      $hdef4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['RB']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef4["ind"] = $hometactic["RB_ind"];
      array_push($players, &$hdef1);
      array_push($players, &$hdef2);
      array_push($players, &$hdef3);
      array_push($players, &$hdef4);
   }
   else if ($hdef == 5)
   {
      $hdef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['LB']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef1["ind"] = $hometactic["LB_ind"];
      $hdef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef2["ind"] = $hometactic["CB1_ind"];
      $hdef3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef3["ind"] = $hometactic["CB2_ind"];
      $hdef4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CB3']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef4["ind"] = $hometactic["CB3_ind"];
      $hdef5 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['RB']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hdef5["ind"] = $hometactic["RB_ind"];
      array_push($players, &$hdef1);
      array_push($players, &$hdef2);
      array_push($players, &$hdef3);
      array_push($players, &$hdef4);
      array_push($players, &$hdef5);
   }
   $hmid = $hformation[1];
   $hmid1 = false; $hmid2 = false; $hmid3 = false; $hmid4 = false; $hmid5 = false;
   if ($hmid == 2)
   {
      $hmid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid1["ind"] = $hometactic["CM1_ind"];
      $hmid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid2["ind"] = $hometactic["CM2_ind"];
      array_push($players, &$hmid1);
      array_push($players, &$hmid2);
   }
   if ($hmid == 3)
   {
      $hmid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['LM']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid1["ind"] = $hometactic["LM_ind"];
      $hmid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid2["ind"] = $hometactic["CM1_ind"];
      $hmid3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['RM']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid3["ind"] = $hometactic["RM_ind"];
      array_push($players, &$hmid1);
      array_push($players, &$hmid2);
      array_push($players, &$hmid3);
   }
   else if ($hmid == 4)
   {
      $hmid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['LM']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid1["ind"] = $hometactic["LM_ind"];
      $hmid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid2["ind"] = $hometactic["CM1_ind"];
      $hmid3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid3["ind"] = $hometactic["CM2_ind"];
      $hmid4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['RM']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid4["ind"] = $hometactic["RM_ind"];
      array_push($players, &$hmid1);
      array_push($players, &$hmid2);
      array_push($players, &$hmid3);
      array_push($players, &$hmid4);
   }
   else if ($hmid == 5)
   {
      $hmid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['LM']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid1["ind"] = $hometactic["LM_ind"];
      $hmid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid2["ind"] = $hometactic["CM1_ind"];
      $hmid3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid3["ind"] = $hometactic["CM2_ind"];
      $hmid4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CM3']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid4["ind"] = $hometactic["CM3_ind"];
      $hmid5 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['RM']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hmid5["ind"] = $hometactic["RM_ind"];
      array_push($players, &$hmid1);
      array_push($players, &$hmid2);
      array_push($players, &$hmid3);
      array_push($players, &$hmid4);
      array_push($players, &$hmid5);
   }
   $hatt = $hformation[2];
   $hatt1 = false; $hatt2 = false; $hatt3 = false;
   if ($hatt == 1)
   {
      $hatt1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CF1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hatt1["ind"] = $hometactic["CF1_ind"];
      array_push($players, &$hatt1);
   }
   if ($hatt == 2)
   {
      $hatt1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CF1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hatt1["ind"] = $hometactic["CF1_ind"];
      $hatt2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CF2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hatt2["ind"] = $hometactic["CF2_ind"];
      array_push($players, &$hatt1);
      array_push($players, &$hatt2);
   }
   if ($hatt == 3)
   {
      $hatt1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CF1']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hatt1["ind"] = $hometactic["CF1_ind"];
      $hatt2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CF2']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hatt2["ind"] = $hometactic["CF2_ind"];
      $hatt3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$hometactic['CF3']}' AND `team` = {$hometeamid}", __FILE__, __LINE__);
      $hatt3["ind"] = $hometactic["CF3_ind"];
      array_push($players, &$hatt1);
      array_push($players, &$hatt2);
      array_push($players, &$hatt3);
   }
   $hdefglobal = $hdef1["global"] + $hdef2["global"] + $hdef3["global"] + $hdef4["global"] + $hdef5["global"];
   $hmdglobal = $hmid1["global"] + $hmid2["global"] + $hmid3["global"] + $hmid4["global"] + $hmid5["global"];
   $hattglobal = $hatt1["global"] + $hatt2["global"] + $hatt3["global"];
   $hglobal = $hkeeper["global"] + $hdefglobal + $hmdglobal + $hattglobal;
   //
   // Away players:
   //
   $aformation = $formations[$awaytactic['formation']];
   $akeeper = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['GK']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
   $akeeper["ind"] = $awaytactic["GK_ind"];
   array_push($players, &$akeeper);
   $adef = $aformation[0];
   $adef1 = false; $adef2 = false; $adef3 = false; $adef4 = false; $adef5 = false;
   if ($adef == 2)
   {
      $adef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef1["ind"] = $awaytactic["CB1_ind"];
      $adef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef2["ind"] = $awaytactic["CB2_ind"];
      array_push($players, &$adef1);
      array_push($players, &$adef2);
   }
   if ($adef == 3)
   {
      $adef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['LB']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef1["ind"] = $awaytactic["LB_ind"];
      $adef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef2["ind"] = $awaytactic["CB1_ind"];
      $adef3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['RB']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef3["ind"] = $awaytactic["RB_ind"];
      array_push($players, &$adef1);
      array_push($players, &$adef2);
      array_push($players, &$adef3);
   }
   else if ($adef == 4)
   {
      $adef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['LB']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef1["ind"] = $awaytactic["LB_ind"];
      $adef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef2["ind"] = $awaytactic["CB1_ind"];
      $adef3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef3["ind"] = $awaytactic["CB2_ind"];
      $adef4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['RB']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef4["ind"] = $awaytactic["RB_ind"];
      array_push($players, &$adef1);
      array_push($players, &$adef2);
      array_push($players, &$adef3);
      array_push($players, &$adef4);
   }
   else if ($adef == 5)
   {
      $adef1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['LB']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef1["ind"] = $awaytactic["LB_ind"];
      $adef2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef2["ind"] = $awaytactic["CB1_ind"];
      $adef3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef3["ind"] = $awaytactic["CB2_ind"];
      $adef4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CB3']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef4["ind"] = $awaytactic["CB3_ind"];
      $adef5 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['RB']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $adef5["ind"] = $awaytactic["RB_ind"];
      array_push($players, &$adef1);
      array_push($players, &$adef2);
      array_push($players, &$adef3);
      array_push($players, &$adef4);
      array_push($players, &$adef5);
   }
   $amid = $aformation[1];
   $amid1 = false; $amid2 = false; $amid3 = false; $amid4 = false; $amid5 = false;
   if ($amid == 2)
   {
      $amid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid1["ind"] = $awaytactic["CM1_ind"];
      $amid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid2["ind"] = $awaytactic["CM2_ind"];
      array_push($players, &$amid1);
      array_push($players, &$amid2);
   }
   if ($amid == 3)
   {
      $amid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['LM']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid1["ind"] = $awaytactic["LM_ind"];
      $amid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid2["ind"] = $awaytactic["CM1_ind"];
      $amid3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['RM']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid3["ind"] = $awaytactic["RM_ind"];
      array_push($players, &$amid1);
      array_push($players, &$amid2);
      array_push($players, &$amid3);
   }
   else if ($amid == 4)
   {
      $amid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['LM']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid1["ind"] = $awaytactic["LM_ind"];
      $amid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid2["ind"] = $awaytactic["CM1_ind"];
      $amid3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid3["ind"] = $awaytactic["CM2_ind"];
      $amid4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['RM']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid4["ind"] = $awaytactic["RM_ind"];
      array_push($players, &$amid1);
      array_push($players, &$amid2);
      array_push($players, &$amid3);
      array_push($players, &$amid4);
   }
   else if ($amid == 5)
   {
      $amid1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['LM']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid1["ind"] = $awaytactic["LM_ind"];
      $amid2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid2["ind"] = $awaytactic["CM1_ind"];
      $amid3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid3["ind"] = $awaytactic["CM2_ind"];
      $amid4 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CM3']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid4["ind"] = $awaytactic["CM3_ind"];
      $amid5 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['RM']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $amid5["ind"] = $awaytactic["RM_ind"];
      array_push($players, &$amid1);
      array_push($players, &$amid2);
      array_push($players, &$amid3);
      array_push($players, &$amid4);
      array_push($players, &$amid5);
   }
   $aatt = $aformation[2];
   $aatt1 = false; $aatt2 = false; $aatt3 = false;
   if ($aatt == 1)
   {
      $aatt1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CF1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $aatt1["ind"] = $awaytactic["CF1_ind"];
      array_push($players, &$aatt1);
   }
   if ($aatt == 2)
   {
      $aatt1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CF1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $aatt1["ind"] = $awaytactic["CF1_ind"];
      $aatt2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CF2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $aatt2["ind"] = $awaytactic["CF2_ind"];
      array_push($players, &$aatt1);
      array_push($players, &$aatt2);
   }
   if ($aatt == 3)
   {
      $aatt1 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CF1']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $aatt1["ind"] = $awaytactic["CF1_ind"];
      $aatt2 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CF2']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $aatt2["ind"] = $awaytactic["CF2_ind"];
      $aatt3 = sql_data("SELECT * FROM `players` WHERE `id` = '{$awaytactic['CF3']}' AND `team` = {$awayteamid}", __FILE__, __LINE__);
      $aatt3["ind"] = $awaytactic["CF3_ind"];
      array_push($players, &$aatt1);
      array_push($players, &$aatt2);
      array_push($players, &$aatt3);
   }
   $adefglobal = $adef1["global"] + $adef2["global"] + $adef3["global"] + $adef4["global"] + $adef5["global"];
   $amdglobal = $amid1["global"] + $amid2["global"] + $amid3["global"] + $amid4["global"] + $amid5["global"];
   $aattglobal = $aatt1["global"] + $aatt2["global"] + $aatt3["global"];
   $aglobal = $akeeper["global"] + $adefglobal + $amdglobal + $aattglobal;
   //
   // Additional calculations:
   //
   sql_query("UPDATE `teams` SET `odds_points` = `odds_points` + {$hglobal}, `odds_matches` = `odds_matches` + 1 WHERE `id` = {$hometeamid}", __FILE__, __LINE__);
   sql_query("UPDATE `teams` SET `odds_points` = `odds_points` + {$aglobal}, `odds_matches` = `odds_matches` + 1 WHERE `id` = {$awayteamid}", __FILE__, __LINE__);
   if (!$hkeeper["global"])
   {
      $hkeeper["global"] = 0;
      if ($hglobal <= 200) $hglobal = 0;
      else $hglobal -= 200;
   }
   if (!$akeeper["global"])
   {
      $akeeper["global"] = 0;
      if ($aglobal <= 200) $aglobal = 0;
      else $aglobal -= 200;
   }
   $hglobal += rand(1, 5);
   $aglobal += rand(1, 5);
   if ($rules != "cup" && $rules != "frcup") $hglobal += 5;
   $homeglobalrating = $hglobal;
   $awayglobalrating = $aglobal;
   while($hglobal > 100 && $aglobal > 100) { $hglobal -= 85; $aglobal -= 85; }
   if ($hglobal <= 0) $hglobal = 1;
   if ($aglobal <= 0) $aglobal = 1;
   $refstr = rand(10, 90);
   $weather = rand(1, 7);
   $injurychance = ($hometactic['aggression'] + $awaytactic['aggression'] + $weather * 12) / 180;
   $injurychance1 = ($hometactic['aggression'] * 3 + $awaytactic['aggression'] + $weather * 12) / 640;
   $injurychance2 = ($hometactic['aggression'] + $awaytactic['aggression'] * 3 + $weather * 12) / 640;
   $strctness = ($refstr + $hometactic['aggression'] + $awaytactic['aggression']) / 30;
   $strctness2 = $strctness / 10;
   $goalchance = ($hometactic['style'] + $awaytactic['style']) / 20.0;
   for ($i = 0; $i <= 21; $i++)
   {
      if ($players[$i]["injured"] > 0) { $players[$i] = false; $players[$i]["id"] = false; }
      if ($rules == "league" && $players[$i]["banleague"] > 0) { $players[$i] = false; $players[$i]["id"]  = false; }
      if ($rules == "cup" && $players[$i]["bancup"] > 0) { $players[$i] = false; $players[$i]["id"] = false; }
      if (!$players[$i]) $players[$i]["name"] = "N.A.";
      $players[$i]["name"] = get_player_name($players[$i]["name"], $players[$i]["shortname"]);;
      $players[$i]["goals"] = 0;
      $players[$i]["injfor"] = 0;
      $players[$i]["yel"] = 0;
      $players[$i]["red"] = 0;
      $players[$i]["inj"] = 0;
      $players[$i]["injmin"] = "";
      $players[$i]["submin"] = "";
   }
   //
   // The real match simulation:
   //
   $report = "";
   $goals = "";
   $homeres = 0; $awayres = 0;
   $hplayers = 11; $aplayers = 11;
   if ($hdef > 0) $hdefence =  round(($hdef1["global"] + $hdef2["global"] + $hdef3["global"] + $hdef4["global"] + $hdef5["global"]) / $hdef);
   else $hdefence = 0;
   if ($adef > 0) $adefence =  round(($adef1["global"] + $adef2["global"] + $adef3["global"] + $adef4["global"] + $adef5["global"]) / $adef);
   else $adefence = 0;
   if ($hmid > 0) $hmidfield =  round(($hmid1["global"] + $hmid2["global"] + $hmid3["global"] + $hmid4["global"] + $hmid5["global"]) / $hmid);
   else $hmidfield = 0;
   if ($amid > 0) $amidfield =  round(($amid1["global"] + $amid2["global"] + $amid3["global"] + $amid4["global"] + $amid5["global"]) / $amid);
   else $amidfield = 0;
   if ($hatt > 0) $hattack =  round(($hatt1["global"] + $hatt2["global"] + $hatt3["global"]) / $hatt);
   else $hattack = 0;
   if ($aatt > 0) $aattack =  round(($aatt1["global"] + $aatt2["global"] + $aatt3["global"]) / $aatt);
   else $aattack = 0;
   $hpassing = 0; $apassing = 0;
   $hshoots = round($hglobal / 40); $ashoots = round($aglobal / 40);
   $hshootson = round($hshoots / (1100 / $hglobal)); $ashootson = round($ashoots / (1100 / $aglobal));
   $htackles = 0; $atackles = 0;
   $hfouls = 0; $afouls = 0;
   $hbookings = 0; $abookings = 0;
   $hcorners = 0; $acorners = 0;
   $hoffsides = 0; $aoffsides = 0;
   $hsubs = 0; $asubs = 0;
   $debug .= "global:{$hglobal}, global:{$aglobal}\n";
   $debug .= "injurychance:{$injurychance}\n";
   $debug .= "strctness:{$strctness}\n";
   $debug .= "strctness2:{$strctness2}\n";
   $debug .= "goalchance:{$goalchance}\n";
   //
   // First half
   //
   for ($i = 1; $i <= 45; $i++)
   {
      if ($i == 45) { $i += rand(0, 5); $min = $min = "45'"; }
      if ($i > 45) $min = $min = "45+".($i-45)."'";
      else $min = $i."'";
      // Goal
      $rand = rand(1, 100);
      if ($rand <= $goalchance)
      {
         $rand = rand(1, $hglobal + $aglobal);
         if ($rand <= $hglobal)
         {
            $rand = 11 - rand(1, $hatt);
            $hshoots++;
            $hshootson++;
            $check = true;
            if ($aglobal > $hglobal && $homeres >= $awayres) $check = false;
            if ($hatt == 0) $check = false;
            if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
            {
               $homeres++;
               $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
               $players[$rand]["goals"]++;
               $goals .= "<tr><td class=\"tb\">{$scorername}</td><td class=\"tb\">{$min}</td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td></tr>";
               $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
            }
         }
         else
         {
            $rand = 21 - rand(1, $aatt);
            $ashoots++;
            $ashootson++;
            $check = true;
            if ($hglobal > $aglobal && $awayres >= $homeres) $check = false;
            if ($aatt == 0) $check = false;
            if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
            {
               $awayres++;
               $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
               $players[$rand]["goals"]++;
               $goals .= "<tr><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\">{$min}</td><td class=\"tb\">{$scorername}</td></tr>";
               $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
            }
         }
      }
      // Yellow
      $rand = rand(1, 100);
      if ($rand <= $strctness)
      {
         $rand = rand(0, 21);
         if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
         {
            if ($players[$rand]["yel"] == 1)
            {
               $players[$rand]["red"] = 1;
               $players[$rand]["yel"] = 2;
               $players[$rand]["yel2"] = $min;
            }
            else
            {
               $players[$rand]["yel"] = 1;
               $players[$rand]["yel1"] = $min;
            }
            if ($rand <= 10) $hbookings++;
            else $abookings++;
            $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
            $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Yellow card for {$plr}</td></tr>";
         }
      }
      // Red
      $rand = rand(1, 100);
      if ($rand <= $strctness2)
      {
         $rand = rand(0, 21);
         if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
         {
            $players[$rand]["red"] = 1;
            $players[$rand]["red1"] = $min;
            if ($rand <= 10) $hbookings++;
            else $abookings++;
         }
      }
      // Injury
      $rand = rand(1, 100);
      if ($rand <= $injurychance)
      {
         $rand = rand(0, 21);
         if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
         {
            $players[$rand]["inj"] = 1;
            $players[$rand]["injmin"] = $min;
            $players[$rand]["injfor"] = rand(1, 9);
            $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
            $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} injured</td></tr>";
            if ($rand <= 10)
            {
               if ($hsubs < 3)
               {
                  $hsubs++;
                  $players[$rand]["submin"] = $min;
                  $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                  $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
               }
            }
            else
            {
               if ($asubs < 3)
               {
                  $asubs++;
                  $players[$rand]["submin"] = $min;
                  $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                  $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
               }
            }
         }
      }
   }
   //
   // Second half
   //
   for ($i = 46; $i <= 90; $i++)
   {
      if ($i == 90) { $i += rand(0, 5); $min = $min = "90'"; }
      if ($i > 90) $min = $min = "90+".($i-90)."'";
      else $min = $i."'";
      // Goal
      $rand = rand(1, 100);
      if ($rand <= $goalchance)
      {
         $rand = rand(1, $hglobal + $aglobal);
         if ($rand <= $hglobal)
         {
            $rand = 11 - rand(1, $hatt);
            $hshoots++;
            $hshootson++;
            $check = true;
            if ($aglobal > $hglobal && $homeres >= $awayres) $check = false;
            if ($hatt == 0) $check = false;
            if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
            {
               $homeres++;
               $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
               $players[$rand]["goals"]++;
               $goals .= "<tr><td class=\"tb\">{$scorername}</td><td class=\"tb\">{$min}</td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td></tr>";
               $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
            }
         }
         else
         {
            $rand = 21 - rand(1, $aatt);
            $ashoots++;
            $ashootson++;
            $check = true;
            if ($hglobal > $aglobal && $awayres >= $homeres) $check = false;
            if ($aatt == 0) $check = false;
            if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
            {
               $awayres++;
               $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
               $players[$rand]["goals"]++;
               $goals .= "<tr><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\">{$min}</td><td class=\"tb\">{$scorername}</td></tr>";
               $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
            }
         }
      }
      // Yellow
      $rand = rand(1, 100);
      if ($rand <= $strctness)
      {
         $rand = rand(0, 21);
         if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
         {
            if ($players[$rand]["yel"] == 1)
            {
               $players[$rand]["red"] = 1;
               $players[$rand]["yel"] = 2;
               $players[$rand]["yel2"] = $min;
            }
            else
            {
               $players[$rand]["yel"] = 1;
               $players[$rand]["yel1"] = $min;
            }
            if ($rand <= 10) $hbookings++;
            else $abookings++;
            $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
            $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Yellow card for {$plr}</td></tr>";
         }
      }
      // Red
      $rand = rand(1, 100);
      if ($rand <= $strctness2)
      {
         $rand = rand(0, 21);
         if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
         {
            $players[$rand]["red"] = 1;
            $players[$rand]["red1"] = $min;
            if ($rand <= 10) $hbookings++;
            else $abookings++;
         }
      }
      // Injury
      $rand = rand(1, 100);
      if ($rand <= $injurychance)
      {
         $rand = rand(0, 21);
         if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
         {
            $players[$rand]["inj"] = 1;
            $players[$rand]["injmin"] = $min;
            $players[$rand]["injfor"] = rand(1, 9);
            $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
            $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} injured</td></tr>";
            if ($rand <= 10)
            {
               if ($hsubs < 3)
               {
                  $hsubs++;
                  $players[$rand]["submin"] = $min;
                  $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                  $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
               }
            }
            else
            {
               if ($asubs < 3)
               {
                  $asubs++;
                  $players[$rand]["submin"] = $min;
                  $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                  $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
               }
            }
         }
      }
   }
   $bets_hr = $homeres;
   $bets_ar = $awayres;
   if ($rules == "cup" || $rules == "frcup")
   {
      if ($homeres == $awayres)
      {
         for ($i = 91; $i <= 105; $i++)
         {
            $min = $i."'";
            // Goal
            $rand = rand(1, 100);
            if ($rand <= $goalchance)
            {
               $rand = rand(1, $hglobal + $aglobal);
               if ($rand <= $hglobal)
               {
                  $rand = 11 - rand(1, $hatt);
                  $hshoots++;
                  $hshootson++;
                  $check = true;
                  if ($aglobal > $hglobal && $homeres >= $awayres) $check = false;
                  if ($hatt == 0) $check = false;
                  if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
                  {
                     $homeres++;
                     $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                     $players[$rand]["goals"]++;
                     $goals .= "<tr><td class=\"tb\">{$scorername}</td><td class=\"tb\">{$min}</td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td></tr>";
                     $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
                  }
               }
               else
               {
                  $rand = 21 - rand(1, $aatt);
                  $ashoots++;
                  $ashootson++;
                  $check = true;
                  if ($hglobal > $aglobal && $awayres >= $homeres) $check = false;
                  if ($aatt == 0) $check = false;
                  if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
                  {
                     $awayres++;
                     $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                     $players[$rand]["goals"]++;
                     $goals .= "<tr><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\">{$min}</td><td class=\"tb\">{$scorername}</td></tr>";
                     $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
                  }
               }
            }
            // Yellow
            $rand = rand(1, 100);
            if ($rand <= $strctness)
            {
               $rand = rand(0, 21);
               if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
               {
                  if ($players[$rand]["yel"] == 1)
                  {
                     $players[$rand]["red"] = 1;
                     $players[$rand]["yel"] = 2;
                     $players[$rand]["yel2"] = $min;
                  }
                  else
                  {
                     $players[$rand]["yel"] = 1;
                     $players[$rand]["yel1"] = $min;
                  }
                  if ($rand <= 10) $hbookings++;
                  else $abookings++;
                  $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                  $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Yellow card for {$plr}</td></tr>";
               }
            }
            // Red
            $rand = rand(1, 100);
            if ($rand <= $strctness2)
            {
               $rand = rand(0, 21);
               if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
               {
                  $players[$rand]["red"] = 1;
                  $players[$rand]["red1"] = $min;
                  if ($rand <= 10) $hbookings++;
                  else $abookings++;
               }
            }
            // Injury
            $rand = rand(1, 100);
            if ($rand <= $injurychance)
            {
               $rand = rand(0, 21);
               if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
               {
                  $players[$rand]["inj"] = 1;
                  $players[$rand]["injmin"] = $min;
                  $players[$rand]["injfor"] = rand(1, 9);
                  $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                  $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} injured</td></tr>";
                  if ($rand <= 10)
                  {
                     if ($hsubs < 3)
                     {
                        $hsubs++;
                        $players[$rand]["submin"] = $min;
                        $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                        $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
                     }
                  }
                  else
                  {
                     if ($asubs < 3)
                     {
                        $asubs++;
                        $players[$rand]["submin"] = $min;
                        $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                        $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
                     }
                  }
               }
            }
         }
         if ($homeres == $awayres)
         {
            for ($i = 106; $i <= 120; $i++)
            {
               $min = $i."'";
               // Goal
               $rand = rand(1, 100);
               if ($rand <= $goalchance)
               {
                  $rand = rand(1, $hglobal + $aglobal);
                  if ($rand <= $hglobal)
                  {
                     $rand = 11 - rand(1, $hatt);
                     $hshoots++;
                     $hshootson++;
                     $check = true;
                     if ($aglobal > $hglobal && $homeres >= $awayres) $check = false;
                     if ($hatt == 0) $check = false;
                     if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
                     {
                        $homeres++;
                        $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                        $players[$rand]["goals"]++;
                        $goals .= "<tr><td class=\"tb\">{$scorername}</td><td class=\"tb\">{$min}</td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td></tr>";
                        $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
                     }
                  }
                  else
                  {
                     $rand = 21 - rand(1, $aatt);
                     $ashoots++;
                     $ashootson++;
                     $check = true;
                     if ($hglobal > $aglobal && $awayres >= $homeres) $check = false;
                     if ($aatt == 0) $check = false;
                     if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"] && $check)
                     {
                        $awayres++;
                        $scorername = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                        $players[$rand]["goals"]++;
                        $goals .= "<tr><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\"></td><td class=\"tb\">{$min}</td><td class=\"tb\">{$scorername}</td></tr>";
                        $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Goal by {$scorername}</td></tr>";
                     }
                  }
               }
               // Yellow
               $rand = rand(1, 100);
               if ($rand <= $strctness)
               {
                  $rand = rand(0, 21);
                  if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
                  {
                     if ($players[$rand]["yel"] == 1)
                     {
                        $players[$rand]["red"] = 1;
                        $players[$rand]["yel"] = 2;
                        $players[$rand]["yel2"] = $min;
                     }
                     else
                     {
                        $players[$rand]["yel"] = 1;
                        $players[$rand]["yel1"] = $min;
                     }
                     if ($rand <= 10) $hbookings++;
                     else $abookings++;
                     $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                     $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Yellow card for {$plr}</td></tr>";
                  }
               }
               // Red
               $rand = rand(1, 100);
               if ($rand <= $strctness2)
               {
                  $rand = rand(0, 21);
                  if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
                  {
                     $players[$rand]["red"] = 1;
                     $players[$rand]["red1"] = $min;
                     if ($rand <= 10) $hbookings++;
                     else $abookings++;
                  }
               }
               // Injury
               $rand = rand(1, 100);
               if ($rand <= $injurychance)
               {
                  $rand = rand(0, 21);
                  if ($players[$rand]["id"] && !$players[$rand]["inj"] && !$players[$rand]["red"])
                  {
                     $players[$rand]["inj"] = 1;
                     $players[$rand]["injmin"] = $min;
                     $players[$rand]["injfor"] = rand(1, 9);
                     $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                     $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} injured</td></tr>";
                     if ($rand <= 10)
                     {
                        if ($hsubs < 3)
                        {
                           $hsubs++;
                           $players[$rand]["submin"] = $min;
                           $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                           $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
                        }
                     }
                     else
                     {
                        if ($asubs < 3)
                        {
                           $asubs++;
                           $players[$rand]["submin"] = $min;
                           $plr = get_player_name($players[$rand]["name"], $players[$rand]["shortname"]);
                           $report .= "<tr><td class=\"tb\">{$min}</td><td class=\"tb\" colspan=\"2\">Player {$plr} substituted</td></tr>";
                        }
                     }
                  }
               }
            }
            if ($homeres == $awayres)
            {
               $i = 0;
               $homeres += rand(2, 5);
               $awayres += rand(2, 5);
               while ($homeres == $awayres)
               {
                  $homeres += rand(0, 1);
                  $awayres += rand(0, 1);
                  // purviqt otbor bie duzpa
                  // vtoriqt otbor bie duzpa
               }
            }
         }
      }
   }
   //
   // Slujebna pobeda???
   //
   $sl = false;
   $hpl = 0;
   $apl = 0;
   foreach ($players as $value)
   {
      if ($value && $value['id'] > 0 && $value['team'] == $hometeamid) $hpl++;
      if ($value && $value['id'] > 0 && $value['team'] == $awayteamid) $apl++;
   }
   if ($hpl <= 8 && $apl <= 8)
   {
      $sl = true;
      $homeres = 0;
      $awayres = 0;
      $bets_hr = 0;
      $bets_ar = 0;
      $goals = "<tr><td class=\"tb\" colspan=\"5\"><center>{_TECHNICAL_WIN_} (Both teams have less than 8 players)</center></td></tr>";
   }
   else if ($hpl <= 8)
   {
      $sl = true;
      $homeres = 0;
      $awayres = 3;
      $bets_hr = 0;
      $bets_ar = 3;
      $goals = "<tr><td class=\"tb\" colspan=\"5\"><center>{_TECHNICAL_WIN_} (Home team have less than 8 players)</center></td></tr>";
   }
   else if ($apl <= 8)
   {
      $sl = true;
      $homeres = 3;
      $awayres = 0;
      $bets_hr = 3;
      $bets_ar = 0;
      $goals = "<tr><td class=\"tb\" colspan=\"5\"><center>{_TECHNICAL_WIN_} (Away team have less than 8 players)</center></td></tr>";
   }
   // dead with bets:
   if ($bets_hr > $bets_ar) $type = 0;
   if ($bets_hr == $bets_ar) $type = 1;
   if ($bets_hr < $bets_ar) $type = 2;
   $bets = sql_query("SELECT * FROM `bets` WHERE `matchid` = {$id} AND `result` = '{$type}'", __FILE__, __LINE__);
   $homename = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$hometeamid}", __FILE__, __LINE__);
   $awayname = sql_get("SELECT `name` FROM `teams` WHERE `id` = {$awayteamid}", __FILE__, __LINE__);
   while ($bet = mysql_fetch_assoc($bets))
   {
      $value = $bet['value'] * $bet['coefic'];
      add_to_money_history("{_MONEY_FROM_MATCH_BET_}: <a href=\"matchreport.php?id={$id}\">{$homename} - {$awayname}</a>", +$value, $bet['teamid'], true);
      $balance = sql_get("SELECT `value` FROM `config` WHERE `name` = 'bets_balance'", __FILE__, __LINE__);
      $balance -= $value;
      sql_query("UPDATE `config` SET `value` = {$balance} WHERE `name` = 'bets_balance'", __FILE__, __LINE__);
      sql_query("UPDATE `teams` SET `odds_balance` = `odds_balance` + {$value} WHERE `id` = '{$bet['teamid']}'", __FILE__, __LINE__);
   }
   sql_query("UPDATE `bets` SET `payed` = 'yes' WHERE `matchid` = {$id}", __FILE__, __LINE__);
   //
   // Preparing to write:
   //
   // Referees
   $firstnames = file("./include/names/{$defaultnames}_FirstNames.txt");
   $lastnames = file("./include/names/{$defaultnames}_LastNames.txt");
   shuffle($firstnames);
   shuffle($lastnames);
   $ref1 = $firstnames[0] . " " . $lastnames[0];
   $ref2 = $firstnames[1] . " " . $lastnames[1];
   $ref3 = $firstnames[2] . " " . $lastnames[2];
   $refdel = $firstnames[3] . " " . $lastnames[3];
   // Match type
   $prefix = "";
   if ($rules == "league")
   {
      $rlsstr = "{_LEAGUE_}";
      $prefix = "leag";
   }
   else if ($rules == "cup")
   {
      $rlsstr = "{_CUP_}";
      $prefix = "cup";
   }
   else if ($rules == "frmatch")
   {
      $rlsstr = "{_FRIENDLY_MATCH_}";
      $prefix = "fr";
   }
   else if ($rules == "frcup")
   {
      $rlsstr = "{_FRIENDLY_CUP_}";
      $prefix = "fr";
   }
   else if ($rules == "frleague")
   {
      $rlsstr = "{_FRIENDLY_LEAGUE_}";
      $prefix = "fr";
   }
   // Weather
   switch ($weather)
   {
      case 1: $weather = "<img src=\"images/weather/sun.gif\" width=\"21px\" alt=\"{_SUNNY_}\"> {_SUNNY_}"; break;
      case 2: $weather = "<img src=\"images/weather/cloud.gif\" width=\"21px\" alt=\"{_CLOUD_}\"> {_CLOUD_}"; break;
      case 3: $weather = "<img src=\"images/weather/darkcloud.gif\" width=\"21px\" alt=\"{_DARK_CLOUD_}\"> {_DARK_CLOUD_}"; break;
      case 4: $weather = "<img src=\"images/weather/rain.gif\" width=\"21px\" alt=\"{_RAIN_}\"> {_RAIN_}"; break;
      case 5: $weather = "<img src=\"images/weather/bigrain.gif\" width=\"21px\" alt=\"{_BIG_RAIN_}\"> {_BIG_RAIN_}"; break;
      case 6: $weather = "<img src=\"images/weather/storm.gif\" width=\"21px\" alt=\"{_STORM_}\"> {_STORM_}"; break;
      case 7: $weather = "<img src=\"images/weather/snow.gif\" width=\"21px\" alt=\"{_SNOW_}\"> {_SNOW_}"; break;
   }
   // Tickets
   $htickets = $home["fansatisfaction"] * $home["fanbase"];
   if ($htickets > 0.9 * $stadiumcapacity) $htickets = 0.9 * $stadiumcapacity;
   $atickets = $away["fansatisfaction"] * $away["fanbase"];
   if ($atickets > 0.1 * $stadiumcapacity) $atickets = 0.1 * $stadiumcapacity;
   $totaltickets = $htickets + $atickets;
   if ($totaltickets <= 0) $totaltickets = 1;
   $percent = $htickets / $totaltickets * 100;
   if ($rules == "frmatch") $percent = 60;
   $ticketsinfo = "{$totaltickets} ({$percent}% - ".(100 - $percent)."%)";
   //
   // Database updates:
   //
   $hwins = 0; $hdraws = 0; $hloses = 0;
   if ($homeres > $awayres) { $hpoints = 3; $apoints = 0; $hwins = 1; }
   else if ($homeres < $awayres) { $hpoints = 0; $apoints = 3; $hloses = 1; }
   else { $hpoints = 1; $apoints = 1; $hdraws = 1; }
   if ($rules == "cup")
   {
      if ($homeres > $awayres)
      {
         $hts = $home['teamspirit'] + 20;
         $ats = $away['teamspirit'] - 25;
         $hfs = $home['fansatisfaction'] + 8;
         $afs = $away['fansatisfaction'] - 5;
         $hfb = $home['fanbase'] + rand(20, 50);
         $afb = $away['fanbase'] - rand(5, 10);
         sql_query("UPDATE `teams` SET `cup` = 'no' WHERE `id` = {$away['id']}", __FILE__, __LINE__);
      }
      else if ($awayres > $homeres)
      {
         $hts = $home['teamspirit'] - 25;
         $ats = $away['teamspirit'] + 20;
         $hfs = $home['fansatisfaction'] - 5;
         $afs = $away['fansatisfaction'] + 8;
         $hfb = $home['fanbase'] - rand(5, 10);
         $afb = $away['fanbase'] + rand(20, 50);
         sql_query("UPDATE `teams` SET `cup` = 'no' WHERE `id` = {$home['id']}", __FILE__, __LINE__);
      }
   }
   else if ($rules == "league")
   {
      if ($homeres > $awayres)
      {
         $hts = $home['teamspirit'] + 8;
         $ats = $away['teamspirit'] - 8;
         $hfs = $home['fansatisfaction'] + 4;
         $afs = $away['fansatisfaction'] - 4;
         $hfb = $home['fanbase'] + rand(10, 20);
         $afb = $away['fanbase'] - rand(1, 5);
      }
      else if ($awayres > $homeres)
      {
         $hts = $home['teamspirit'] - 8;
         $ats = $away['teamspirit'] + 8;
         $hfs = $home['fansatisfaction'] - 4;
         $afs = $away['fansatisfaction'] + 4;
         $hfb = $home['fanbase'] - rand(1, 5);
         $afb = $away['fanbase'] + rand(10, 20);
      }
      else
      {
         $hts = $home['teamspirit'] + 3;
         $ats = $away['teamspirit'] + 3;
         $hfs = $home['fansatisfaction'];
         $afs = $away['fansatisfaction'];
         $hfb = $home['fanbase'] + rand(5, 10);
         $afb = $away['fanbase'] + rand(5, 10);
      }
      sql_query("UPDATE `teams` SET `total` = `total` + 1, `points` = `points` + '{$hpoints}', `wins` = `wins` + '{$hwins}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hloses}', `goalsscored` = `goalsscored` + {$homeres}, `goalsconceded` = `goalsconceded` + {$awayres} WHERE `id` = {$hometeamid}", __FILE__, __LINE__);
      sql_query("UPDATE `teams` SET `total` = `total` + 1, `points` = `points` + '{$apoints}', `wins` = `wins` + '{$hloses}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hwins}', `goalsscored` = `goalsscored` + {$awayres}, `goalsconceded` = `goalsconceded` + {$homeres} WHERE `id` = {$awayteamid}", __FILE__, __LINE__);
   }
   else if ($rules == "frmatch" || $rules == "frcup" || $rules == "frleague")
   {
      if ($homeres > $awayres)
      {
         $hts = $home['teamspirit'] + 2;
         $ats = $away['teamspirit'] - 2;
         $hfs = $home['fansatisfaction'] + 1;
         $afs = $away['fansatisfaction'] - 1;
         $hfb = $home['fanbase'] + rand(5, 10);
         $afb = $away['fanbase'];
      }
      else if ($awayres > $homeres)
      {
         $hts = $home['teamspirit'] - 2;
         $ats = $away['teamspirit'] + 2;
         $hfs = $home['fansatisfaction'] - 1;
         $afs = $away['fansatisfaction'] + 1;
         $hfb = $home['fanbase'];
         $afb = $away['fanbase'] + rand(5, 10);
      }
      else
      {
         $hts = $home['teamspirit'] + 1;
         $ats = $away['teamspirit'] + 1;
         $hfs = $home['fansatisfaction'];
         $afs = $away['fansatisfaction'];
         $hfb = $home['fanbase'] + rand(1, 5);
         $afb = $away['fanbase'] + rand(1, 5);
      }
   }
   if ($hts > 100) $hts = 100;
   if ($hts < 0) $hts = 0;
   if ($hfs > 100) $hfs = 100;
   if ($hfs < 0) $hfs = 0;
   if ($hfb < 100) $hfb = 100;
   if ($ats > 100) $ats = 100;
   if ($ats < 0) $ats = 0;
   if ($afs > 100) $afs = 100;
   if ($afs < 0) $afs = 0;
   if ($afb < 100) $afb = 100;
   if ($rules == "frmatch" || $rules == "frcup" || $rules == "frleague")
   {
      $hmoney = 10000 + $htickets * 25 + rand(1, 1000);
      $amoney = 3000 + $atickets * 25 + rand(1, 1000);
   }
   else
   {
      $hmoney = 20000 + $htickets * 25 + rand(1, 1000);
      $amoney = 6000 + $atickets * 25 + rand(1, 1000);
   }
   $hmoney += get_additional_incoms($stadium['parking']);
   $hmoney += get_additional_incoms($stadium['bars']);
   $hmoney += get_additional_incoms($stadium['toilets']);
   $hmoney += get_additional_incoms($stadium['grass']);
   $hmoney += get_additional_incoms($stadium['lights']);
   $hmoney += get_additional_incoms($stadium['roof']);
   $hmoney += get_additional_incoms($stadium['heater']);
   $hmoney += get_additional_incoms($stadium['sprinkler']);
   if ($rules == "frmatch" || $rules == "frcup" || $rules == "frleague")
   {
      $hmoney /= 2;
      $amoney /= 2;
   }
   if ($rules == "frmatch")
   {
      $summoney = $hmoney + $amoney;
      $hmoney = $summoney * 0.6;
      $amoney = $summoney * 0.4;
   }
   add_to_money_history("<a href=\"matchreport.php?id={$id}\">{$home['name']} - {$away['name']} {$homeres} : {$awayres}</a>", $hmoney, $hometeamid, true);
   add_to_money_history("<a href=\"matchreport.php?id={$id}\">{$home['name']} - {$away['name']} {$homeres} : {$awayres}</a>", $amoney, $awayteamid, true);
   sql_query("UPDATE `teams` SET `teamspirit` = '{$hts}', `fanbase` = '{$hfb}', `fansatisfaction` = '{$hfs}' WHERE `id` = {$hometeamid}", __FILE__, __LINE__);
   sql_query("UPDATE `teams` SET `teamspirit` = '{$ats}', `fanbase` = '{$afb}', `fansatisfaction` = '{$afs}' WHERE `id` = {$awayteamid}", __FILE__, __LINE__);
   sql_query("UPDATE `users` SET `points` = `points` + {$hpoints}, `weekpoints` = `weekpoints` + {$hpoints}, `wins` = `wins` + {$hwins}, `draws` = `draws` + {$hdraws}, `loses` = `loses` + {$hloses}, `goalsscored` = `goalsscored` + {$homeres}, `goalsconceded` = `goalsconceded` + {$awayres} WHERE `team` = {$home['id']}", __FILE__, __LINE__);
   sql_query("UPDATE `users` SET `points` = `points` + {$apoints}, `weekpoints` = `weekpoints` + {$apoints}, `wins` = `wins` + {$hloses}, `draws` = `draws` + {$hdraws}, `loses` = `loses` + {$hwins}, `goalsscored` = `goalsscored` + {$awayres}, `goalsconceded` = `goalsconceded` + {$homeres} WHERE `team` = {$away['id']}", __FILE__, __LINE__);
   //
   // Write the report:
   //
   $fh = fopen("./cache/match/m_{$id}.cache", "w");
   fwrite($fh, "<h2>{_HOMENAME_} - {_AWAYNAME_} {$homeres} : {$awayres}</h2>");
   fwrite($fh, "<table border=\"2\" width=\"100%\" style=\"width:100%;\">");

   fwrite($fh, "<tr><td class=\"tb\">");
   fwrite($fh, "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">");
   fwrite($fh, "<tr><th>{_TYPE_}</th><td class=\"tb\">{$rlsstr}</td></tr>");
   fwrite($fh, "<tr><th>{_DATE_}</th><td class=\"tb\">{$start}</td></tr>");
   fwrite($fh, "<tr><th>{_STADIUM_}</th><td class=\"tb\">{$stadium['name']}</td></tr>");
   fwrite($fh, "<tr><th>{_WEATHER_}</th><td class=\"tb\">{$weather}</td></tr>");
   fwrite($fh, "<tr><th>{_TICKETS_}</th><td class=\"tb\">{$ticketsinfo}</td></tr>");
   fwrite($fh, "<tr><th>{_REFEREE_}</th><td class=\"tb\">{$ref1} ({_STRICTNESS_}: {$refstr}%)</td></tr>");
   fwrite($fh, "<tr><th>{_LEFT_SIDE_REFEREE_}</th><td class=\"tb\">{$ref2}</td></tr>");
   fwrite($fh, "<tr><th>{_RIGHT_SIDE_REFEREE_}</th><td class=\"tb\">{$ref3}</td></tr>");
   fwrite($fh, "<tr><th>".GAME_NAME." {_DELEGATE_}</th><td class=\"tb\">{$refdel}</td></tr>");
   fwrite($fh, "</table>");
   fwrite($fh, "</td></tr>");

   fwrite($fh, "<tr><td class=\"tb\">");
   fwrite($fh, "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">");
   fwrite($fh, "<tr><th colspan=\"2\" style=\"width: 42%;\"><a href=\"teamdetails.php?id={$home['id']}\">{_HOMENAME_}</a></th><th style=\"width: 16%;\">{$homeres} - {$awayres}</th><th colspan=\"2\" style=\"width: 42%;\"><a href=\"teamdetails.php?id={$away['id']}\">{_AWAYNAME_}</a></th></tr>");
   fwrite($fh, $goals);
   fwrite($fh, "</table>");
   fwrite($fh, "</td></tr>");
   if (!$sl)
   {
      $hfouls = rand($hometactic['aggression'] / 3, $hometactic['aggression'] / 2);
      $afouls = rand($awaytactic['aggression'] / 3, $awaytactic['aggression'] / 2);

      $hcorners = rand($hglobal / 40, $hglobal / 50) + $homeres * 7;
      $acorners = rand($aglobal / 40, $aglobal / 50) + $awayres * 7;

      $hpassing = rand($hglobal / 3, $hglobal / 2) + $homeres * 20;
      $apassing = rand($aglobal / 3, $aglobal / 2) + $awayres * 20;

      fwrite($fh, "<tr><td class=\"tb\">");
      fwrite($fh, "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hdef}-{$hmid}-{$hatt}</td><th style=\"width: 16%;\">{_FORMATION_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$adef}-{$amid}-{$aatt}</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">".$tactictypes[$hometactic['tactics']]."</td><th style=\"width: 16%;\">{_TACTICS_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">".$tactictypes[$awaytactic['tactics']]."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($hometactic['style'], 200, "left")."</td><td class=\"tb\">{$hometactic["style"]}</td><th style=\"width: 16%;\">{_STYLE_}</th><td class=\"tb\">{$awaytactic['style']}</td><td class=\"tb\">".create_progress_bar($awaytactic['style'], 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($hometactic['aggression'], 200, "left")."</td><td class=\"tb\">{$hometactic['aggression']}</td><th style=\"width: 16%;\">{_AGGRESSION_}</th><td class=\"tb\">{$awaytactic['aggression']}</th><td class=\"tb\">".create_progress_bar($awaytactic['aggression'], 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($homeglobalrating / ($homeglobalrating + $awayglobalrating) * 100, 200, "left")."</td><td class=\"tb\">{$homeglobalrating}</td><th style=\"width: 16%;\">Team ratings</th><td class=\"tb\">{$awayglobalrating}</td><td class=\"tb\">".create_progress_bar($awayglobalrating / ($homeglobalrating + $awayglobalrating) * 100, 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($hkeeper["global"], 200, "left")."</td><td class=\"tb\">{$hkeeper["global"]}</td><th style=\"width: 16%;\">{_GOALKEEPING_}</th><td class=\"tb\">{$akeeper["global"]}</td><td class=\"tb\">".create_progress_bar($akeeper["global"], 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($hdefence, 200, "left")."</td><td class=\"tb\">{$hdefence}</td><th style=\"width: 16%;\">{_DEFENCE_}</th><td class=\"tb\">{$adefence}</td><td class=\"tb\">".create_progress_bar($adefence, 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($hmidfield, 200, "left")."</td><td class=\"tb\">{$hmidfield}</td><th style=\"width: 16%;\">{_MIDFIELD_}</th><td class=\"tb\">{$amidfield}</td><td class=\"tb\">".create_progress_bar($amidfield, 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\">".create_progress_bar($hattack, 200, "left")."</td><td class=\"tb\">{$hattack}</td><th style=\"width: 16%;\">{_ATTACK_}</th><td class=\"tb\">{$aattack}</td><td class=\"tb\">".create_progress_bar($aattack, 200, "left")."</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hshoots}</td><th style=\"width: 16%;\">{_SHOOTS_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$ashoots}</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hshootson}</td><th style=\"width: 16%;\">{_SHOOTS_ON_TARGET_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$ashootson}</td></tr>");
      //fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$htackles}</td><th style=\"width: 16%;\">{_TACKLES_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$atackles}</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hpassing}</td><th style=\"width: 16%;\">{_PASSING_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$apassing}</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hfouls}</td><th style=\"width: 16%;\">{_FOULS_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$afouls}</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hbookings}</td><th style=\"width: 16%;\">{_BOOKINGS_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$abookings}</td></tr>");
      fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hcorners}</td><th style=\"width: 16%;\">{_CORNERS_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$acorners}</td></tr>");
      //fwrite($fh, "<tr><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$hoffsides}</td><th style=\"width: 16%;\">{_OFFSIDES_}</th><td class=\"tb\" colspan=\"2\" style=\"width: 42%;\">{$aoffsides}</td></tr>");
      fwrite($fh, "</table>");
      fwrite($fh, "</td></tr>");

      fwrite($fh, "<tr><td class=\"tb\">");
      fwrite($fh, "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">");
      fwrite($fh, "<tr><th colspan=\"10\">{$home['name']}</th></tr>");
      fwrite($fh, "<tr><th>�</th><th></th><th>{_NAME_}</th><th>".div("statistics_goal")."</th><th>".div("statistics_yellow")."</th><th>".div("img_match_second_yellow")."</th><th>".div("statistics_red")."</th><th>".div("statistics_injuries")."</th><th>".div("img_match_change")."</th><th>".div("img_match_form")."</th></tr>");
      for ($i = 0; $i <= 10; $i++)
      {
         if ($players[$i]["id"])
         {
            $players[$i]["form"] = rand($players[$i]["global"] - 20, $players[$i]["global"] + 20);
            if ($players[$i]["yel1"]) $players[$i]["form"] -= 10;
            if ($players[$i]["yel2"]) $players[$i]["form"] -= 15;
            if ($players[$i]["red1"]) $players[$i]["form"] -= 25;
            $players[$i]["form"] += 15 * $players[$i]["goals"];
            if ($players[$i]["form"] < 10) $players[$i]["form"] = 10;
            if ($players[$i]["form"] > 95) $players[$i]["form"] = 95;
            if ($players[$i]["goals"]) $players[$i]["goals1"] = $players[$i]["goals"];
            fwrite($fh, "<tr><td class=\"tb\">{$players[$i]["number"]}.</td><td class=\"tb\">{$players[$i]["possition"]}</td><td class=\"tb\">{$players[$i]["name"]}</td><td class=\"tb\">{$players[$i]["goals1"]}</td><td class=\"tb\">{$players[$i]["yel1"]}</td><td class=\"tb\">{$players[$i]["yel2"]}</td><td class=\"tb\">{$players[$i]["red1"]}</td><td class=\"tb\">{$players[$i]["injmin"]}</td><td class=\"tb\">{$players[$i]["submin"]}</td><td class=\"tb\">{$players[$i]["form"]}</td></tr>");
         }
      }
      fwrite($fh, "</table>");
      fwrite($fh, "</td></tr>");

      fwrite($fh, "<tr><td class=\"tb\">");
      fwrite($fh, "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">");
      fwrite($fh, "<tr><th colspan=\"10\">{$away['name']}</th></tr>");
      fwrite($fh, "<tr><th>�</th><th></th><th>{_NAME_}</th><th>".div("statistics_goal")."</th><th>".div("statistics_yellow")."</th><th>".div("img_match_second_yellow")."</th><th>".div("statistics_red")."</th><th>".div("statistics_injuries")."</th><th>".div("img_match_change")."</th><th>".div("img_match_form")."</th></tr>");
      for ($i = 11; $i <= 21; $i++)
      {
         if ($players[$i]["id"])
         {
            $players[$i]["form"] = rand($players[$i]["global"] - 20, $players[$i]["global"] + 20);
            if ($players[$i]["yel1"]) $players[$i]["form"] -= 10;
            if ($players[$i]["yel2"]) $players[$i]["form"] -= 15;
            if ($players[$i]["red1"]) $players[$i]["form"] -= 25;
            $players[$i]["form"] += 15 * $players[$i]["goals"];
            if ($players[$i]["form"] < 1) $players[$i]["form"] = 1;
            if ($players[$i]["form"] > 99) $players[$i]["form"] = 99;
            if ($players[$i]["goals"]) $players[$i]["goals1"] = $players[$i]["goals"];
            fwrite($fh, "<tr><td class=\"tb\">{$players[$i]["number"]}.</td><td class=\"tb\">{$players[$i]["possition"]}</td><td class=\"tb\">{$players[$i]["name"]}</td><td class=\"tb\">{$players[$i]["goals1"]}</td><td class=\"tb\">{$players[$i]["yel1"]}</td><td class=\"tb\">{$players[$i]["yel2"]}</td><td class=\"tb\">{$players[$i]["red1"]}</td><td class=\"tb\">{$players[$i]["injmin"]}</td><td class=\"tb\">{$players[$i]["submin"]}</td><td class=\"tb\">{$players[$i]["form"]}</td></tr>");
         }
      }
      fwrite($fh, "</table>");
      fwrite($fh, "</td></tr>");

      fwrite($fh, "<tr><td class=\"tb\">");
      fwrite($fh, "<table border=\"0\" class=\"specialtable_login\" width=\"100%\" style=\"width:100%;\">");
      fwrite($fh, "<tr><th colspan=\"8\">{_REPORT_}</th></tr>");
      fwrite($fh, $report);
      fwrite($fh, "</table>");
      fwrite($fh, "</td></tr>");
   }

   fwrite($fh, "</table>");
   fwrite($fh, "\n<!--\n{$debug}generated:".get_date_time(false)."\n--->\n");
   fclose($fh);
   $hcl = sql_get("SELECT `class` FROM `users` WHERE `team` = {$hometeamid} AND `mailreports` = 'yes'", __FILE__, __LINE__);
   if ($hcl >= UC_VIP_USER)
   {
      $email = sql_get("SELECT `email` FROM `users` WHERE `team` = {$hometeamid}", __FILE__, __LINE__);
      $file = fopen("./cache/match/m_{$id}.cache", "r");
      $mess = fread($file, filesize("./cache/match/m_{$id}.cache"));
      $find = array("{_HOMENAME_}", "{_AWAYNAME_}", "{_TYPE_}", "{_DATE_}", "{_STADIUM_}", "{_WEATHER_}", "{_TICKETS_}", "{_REFEREE_}", "{_LEFT_SIDE_REFEREE_}", "{_RIGHT_SIDE_REFEREE_}", "{_DELEGATE_}", "{_LEAGUE_}", "{_CUP_}", "{_FRIENDLY_MATCH_}", "{_FRIENDLY_CUP_}", "{_FRIENDLY_LEAGUE_}", "{_SUNNY_}", "{_CLOUD_}", "{_DARK_CLOUD_}", "{_RAIN_}", "{_BIG_RAIN_}", "{_STORM_}", "{_SNOW_}", "{_STRICTNESS_}", "{_FORMATION_}", "{_TACTICS_}", "{_STYLE_}", "{_AGGRESSION_}", "{_GOALKEEPING_}", "{_DEFENCE_}", "{_MIDFIELD_}", "{_ATTACK_}", "{_SHOOTS_}", "{_SHOOTS_ON_TARGET_}", "{_TACKLES_}", "{_PASSING_}", "{_FOULS_}", "{_BOOKINGS_}", "{_CORNERS_}", "{_OFFSIDES_}", "{_NAME_}", "{_REPORT_}", "{_TECHNICAL_WIN_}");
      $repl = array($home['name'], $away['name'], TYPE, DATE, STADIUM, WEATHER, TICKETS, REFEREE, LEFT_SIDE_REFEREE, RIGHT_SIDE_REFEREE, DELEGATE, LEAGUE, CUP, FRIENDLY_MATCH, FRIENDLY_CUP, FRIENDLY_LEAGUE, SUNNY, CLOUD, DARK_CLOUD, RAIN, BIG_RAIN, STORM, SNOW, STRICTNESS, FORMATION, TACTICS, STYLE, AGGRESSION, GOALKEEPING, DEFENCE, MIDFIELD, ATTACK, SHOOTS, SHOOTS_ON_TARGET, TACKLES, PASSING, FOULS, BOOKINGS, CORNERS, OFFSIDES, NAME, REPORT, TECHNICAL_WIN);
      $mess = "<html><head><link href=\"".ADDRESS."styles/new/style2.css\" type=\"text/css\" rel=\"stylesheet\"></head><body>".str_replace($find, $repl, $mess)."</body></html>";
      $headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
      mail($email, "{$home['name']} - {$away['name']} {$homeres} : {$awayres}", $mess, $headers);
   }
   $acl = sql_get("SELECT `class` FROM `users` WHERE `team` = {$awayteamid} AND `mailreports` = 'yes'", __FILE__, __LINE__);
   if ($acl >= UC_VIP_USER)
   {
      $email = sql_get("SELECT `email` FROM `users` WHERE `team` = {$awayteamid}", __FILE__, __LINE__);
      $file = fopen("./cache/match/m_{$id}.cache", "r");
      $mess = fread($file, filesize("./cache/match/m_{$id}.cache"));
      $find = array("{_HOMENAME_}", "{_AWAYNAME_}", "{_TYPE_}", "{_DATE_}", "{_STADIUM_}", "{_WEATHER_}", "{_TICKETS_}", "{_REFEREE_}", "{_LEFT_SIDE_REFEREE_}", "{_RIGHT_SIDE_REFEREE_}", "{_DELEGATE_}", "{_LEAGUE_}", "{_CUP_}", "{_FRIENDLY_MATCH_}", "{_FRIENDLY_CUP_}", "{_FRIENDLY_LEAGUE_}", "{_SUNNY_}", "{_CLOUD_}", "{_DARK_CLOUD_}", "{_RAIN_}", "{_BIG_RAIN_}", "{_STORM_}", "{_SNOW_}", "{_STRICTNESS_}", "{_FORMATION_}", "{_TACTICS_}", "{_STYLE_}", "{_AGGRESSION_}", "{_GOALKEEPING_}", "{_DEFENCE_}", "{_MIDFIELD_}", "{_ATTACK_}", "{_SHOOTS_}", "{_SHOOTS_ON_TARGET_}", "{_TACKLES_}", "{_PASSING_}", "{_FOULS_}", "{_BOOKINGS_}", "{_CORNERS_}", "{_OFFSIDES_}", "{_NAME_}", "{_REPORT_}", "{_TECHNICAL_WIN_}");
      $repl = array($home['name'], $away['name'], TYPE, DATE, STADIUM, WEATHER, TICKETS, REFEREE, LEFT_SIDE_REFEREE, RIGHT_SIDE_REFEREE, DELEGATE, LEAGUE, CUP, FRIENDLY_MATCH, FRIENDLY_CUP, FRIENDLY_LEAGUE, SUNNY, CLOUD, DARK_CLOUD, RAIN, BIG_RAIN, STORM, SNOW, STRICTNESS, FORMATION, TACTICS, STYLE, AGGRESSION, GOALKEEPING, DEFENCE, MIDFIELD, ATTACK, SHOOTS, SHOOTS_ON_TARGET, TACKLES, PASSING, FOULS, BOOKINGS, CORNERS, OFFSIDES, NAME, REPORT, TECHNICAL_WIN);
      $mess = "<html><head><link href=\"".ADDRESS."styles/new/style2.css\" type=\"text/css\" rel=\"stylesheet\"></head><body>".str_replace($find, $repl, $mess)."</body></html>";
      $headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
      mail($email, "{$home['name']} - {$away['name']} {$homeres} : {$awayres}", $mess, $headers);
   }
   if ($rules == "league") sql_query("UPDATE `players` SET `banleague` = `banleague` - 1 WHERE `banleague` > 0 AND (`team` = {$home["id"]} OR `team` = {$away["id"]})", __FILE__, __LINE__);
   if ($rules == "cup") sql_query("UPDATE `players` SET `bancup` = `bancup` - 1 WHERE `bancup` > 0 AND (`team` = {$home["id"]} OR `team` = {$away["id"]})", __FILE__, __LINE__);
   if ($rules == "frcup")
   {
      sql_query("UPDATE `friendly_participants` SET `total` = `total` + 1, `points` = `points` + '{$hpoints}', `wins` = `wins` + '{$hwins}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hloses}', `goalsscored` = `goalsscored` + {$homeres}, `goalsconceded` = `goalsconceded` + {$awayres} WHERE `type` = {$matchtype} AND `team` = {$hometeamid}", __FILE__, __LINE__);
      sql_query("UPDATE `friendly_participants` SET `total` = `total` + 1, `points` = `points` + '{$apoints}', `wins` = `wins` + '{$hloses}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hwins}', `goalsscored` = `goalsscored` + {$awayres}, `goalsconceded` = `goalsconceded` + {$homeres} WHERE `type` = {$matchtype} AND `team` = {$awayteamid}", __FILE__, __LINE__);
   }
   if (!$sl) for ($i = 0; $i <= 21; $i++)
   {
      if ($players[$i]["id"])
      {
         sql_query("UPDATE `players_stats` SET
         `cur_{$prefix}_goals` = `cur_{$prefix}_goals` + {$players[$i]["goals"]}, `all_{$prefix}_goals` = `all_{$prefix}_goals` + {$players[$i]["goals"]},
         `cur_{$prefix}_red` = `cur_{$prefix}_red` + {$players[$i]["red"]}, `all_{$prefix}_red` = `all_{$prefix}_red` + {$players[$i]["red"]},
         `cur_{$prefix}_yellow` = `cur_{$prefix}_yellow` + {$players[$i]["yel"]}, `all_{$prefix}_yellow` = `all_{$prefix}_yellow` + {$players[$i]["yel"]},
         `cur_{$prefix}_played` = `cur_{$prefix}_played` + 1, `all_{$prefix}_played` = `all_{$prefix}_played` + 1,
         `cur_{$prefix}_inj` = `cur_{$prefix}_inj` + {$players[$i]["inj"]}, `all_{$prefix}_inj` = `all_{$prefix}_inj` + {$players[$i]["inj"]}
         WHERE `id` = {$players[$i]["id"]}", __FILE__, __LINE__);
         sql_query("UPDATE `players` SET `currentform` = {$players[$i]["form"]} WHERE `id` = {$players[$i]["id"]}", __FILE__, __LINE__);
         if ($players[$i]["bestform"] < $players[$i]["form"]) sql_query("UPDATE `players` SET `bestform` = {$players[$i]["form"]} WHERE `id` = {$players[$i]["id"]}", __FILE__, __LINE__);
         if (rand(1, 10) == 6) sql_query("UPDATE `players` SET `experience` = `experience` + 1 WHERE `id` = {$players[$i]["id"]} AND `experience` < 99", __FILE__, __LINE__);
         if ($players[$i]["injfor"] > 0) sql_query("UPDATE `players` SET `training` = 0, `injured` = {$players[$i]["injfor"]} WHERE `id` = {$players[$i]["id"]}", __FILE__, __LINE__);
         if ($rules == "league" && $players[$i]["red"]) sql_query("UPDATE `players` SET `banleague` = `banleague` + 1 WHERE `id` = {$players[$i]["id"]}", __FILE__, __LINE__);
         else if ($rules == "cup" && $players[$i]["red"]) sql_query("UPDATE `players` SET `bancup` = `bancup` + 1 WHERE `id` = {$players[$i]["id"]}", __FILE__, __LINE__);
      }
   }
   sql_query("UPDATE `match` SET `played` = 'yes', `homescore` = {$homeres}, `awayscore` = {$awayres} WHERE `id` = {$id}", __FILE__, __LINE__);
   if ($homeres > $awayres) return $hometeamid;
   else if ($awayres > $homeres) return $awayteamid;
   else return 0;
}
// TODO: Substitutions
// TODO: Calculate global ratings for players (who plays in game) only
// TODO: More things in comments
// TODO: More info in debug information (+hide it from the page, as html comment)
// TODO: Fix: "Tactics" not shown
/*
version 0.9.11 (28-07-2008)
- Home and away team shares the incomes from the friendly matches (60%-40%)
version 0.9.10 (09-06-2008)
- Fixed serious bug with sold players plaing in seller and buyer
version 0.9.9 (03-06-2008)
- Added global rating information
- Rediced home team advantage
- Reduced random advantages for both teams
version 0.9.8 (01-06-2008)
- Fixed bug with penalties after cup matches
version 0.9.7 (31-05-2008)
- Updating users week points
- Fixed bug with fans staisfaction
version 0.9.6 (28-05-2008)
- Incomes from friendly games reduced twice
- Fixed bug with bets when technical win
version 0.9.5 (26-05-2008)
- Some changes for the bets module
version 0.9.4 (24-05-2008)
- Result depends on some more random things
version 0.9.3 (20-05-2008)
- Some changes for the bets module
version 0.9.1 (26-04-2008)
- Some changes for friendly cups
version 0.9.0 (25-04-2008)
- Technical results implemented
- Some balace fixes
- Injury chance improved little bit
version 0.8.8 (22-04-2008)
- Some changes for friendly cups
- Injuries reduced with 1 day (now they are between 1 and 9 days)
- Form is limited from 1 to 99
version 0.8.7 (05-04-2008)
- Some changes for friendly cups
version 0.8.6 (04-04-2008)
- Goal chance increased
- Injury chance reduced
version 0.8.5 (02-04-2008)
- Some balance fixes in the simulstor core
- Fixed bug with away teams tactic same as home teams tactic
version 0.84 (30-03-2008)
- Fixed bug with player nicknames in the reports
version 0.83 (17-03-2008)
- Fixed some division by zero problems
version 0.82 (13-03-2008)
- Attack, midfield and defence stats fixed
version 0.81 (12-03-2008)
- Attack, midfield and defence stats implemented
version 0.80 (08-03-2008)
- Injury chance reduced
- Fixed bug with player nicknames
- Fixed some mysql errors
version 0.75 (01-03-2008)
- Fully translated
- Implemented form, best form and experience
- Implemented passes, corners and fouls
- Some design improvements (added pictures in some of the headers)
- Bugs fixed
version 0.72 (29-02-2008)
- Injury chance reduced
version 0.71 (27-02-2008)
- Some fixes and improvements in the match report
version 0.70 (25-02-2008)
- Injuries and substitusions implemented
- Statistics implemented
- Some fixes and improvements
- Division by zero fixed
version 0.60 (24-02-2008)
- Some fixes and improvements
version 0.50 (23-02-2008)
- Match simulations rewritten
version 0.13 (18-01-2008)
- Fixed bug with cup stats (cards, topscorers)
version 0.12 (11-01-2008)
- Fixed division by zero error
version 0.11 (23-12-2007)
- Progress bars fixes
version 0.10 (13-12-2007)
- Added more stats to match report
version 0.09 (02-12-2007)
- Increased wins from matches
version 0.08 (21-11-2007)
- Added manager wins/draws/loses
version 0.07 (17-11-2007)
- Fixed bug with non-league matches
version 0.06 (11-11-2007)
- Fixed some small bugs
- Added links to team details
- Added league history for cup
version 0.05 (07-11-2007):
- Added attack, defence, away stats for match for both teams
- Added referee statss
- Added weather stats
- Added tickets stats
- Update cup winners
- Fixed match type text
version 0.01 (05-11-2007):
- First release version
*/
?>
