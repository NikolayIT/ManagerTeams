<?php
//die();
define("IN_GAME", true);
include("common.php");
// total 	points 	wins 	draws 	loses 	goalsscored 	goalsconceded
sql_query("UPDATE `teams` SET `total` = 0, `points` = 0, `wins` = 0, `draws` = 0, `loses` = 0, `goalsscored` = 0, `goalsconceded` = 0", __FILE__, __LINE__);
$matches = sql_query("SELECT * FROM `match` WHERE `season` = {$config['season']} AND `played` = 'yes' AND `rules` = 'league'", __FILE__, __LINE__);
while($match = mysql_fetch_assoc($matches))
{
   $homeres = $match['homescore'];
   $awayres = $match['awayscore'];
   $hometeamid = $match['hometeam'];
   $awayteamid = $match['awayteam'];
   if ($homeres > $awayres)
   {
      $hpoints = 3;
      $apoints = 0;
      $hwins = 1;
      $hdraws = 0;
      $hloses = 0;
   }
   else if ($awayres > $homeres)
   {
      $hpoints = 0;
      $apoints = 3;
      $hwins = 0;
      $hdraws = 0;
      $hloses = 1;
   }
   else
   {
      $hpoints = 1;
      $apoints = 1;
      $hwins = 0;
      $hdraws = 1;
      $hloses = 0;
   }
   sql_query("UPDATE `teams` SET `total` = `total` + 1, `points` = `points` + '{$hpoints}', `wins` = `wins` + '{$hwins}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hloses}', `goalsscored` = `goalsscored` + {$homeres}, `goalsconceded` = `goalsconceded` + {$awayres} WHERE `id` = {$hometeamid}", __FILE__, __LINE__);
   sql_query("UPDATE `teams` SET `total` = `total` + 1, `points` = `points` + '{$apoints}', `wins` = `wins` + '{$hloses}', `draws` = `draws` + '{$hdraws}', `loses` = `loses` + '{$hwins}', `goalsscored` = `goalsscored` + {$awayres}, `goalsconceded` = `goalsconceded` + {$homeres} WHERE `id` = {$awayteamid}", __FILE__, __LINE__);
}
?>
