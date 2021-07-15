<?php
/*
File name: crosstable.php
Last change: Fri Jan 18 10:19:53 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);

mkglobal("id", false);
if (empty($id) || !is_numeric($id)) $league = $TEAM['league'];
else $league = sql_get("SELECT `name` FROM `match_type` WHERE `id` = ".sqlsafe($id), __FILE__, __LINE__);
$ln = substr($league, 1);
if ($ln == "A.1") $ln = "A";

pagestart(CROSS_TABLE_FOR." {$ln}");
head(CROSS_TABLE_FOR." {$ln}");
form_start("crosstable.php", "GET");
select("id", "", true);
$dat = sql_query("SELECT `id`, `name` FROM `match_type` WHERE `type` = 'League'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($dat))
{
   $leagnam = substr($row['name'], 1);
   if ($leagnam == "A.1") $leagnam = "A";
   option($row['id'], $leagnam, $row['name'] == $league, true);
}
end_select(true);
input("submit", "", SHOW, "", true);
form_end();
br(2);

table_start();
table_startrow();
table_th("");
table_th(NAME);
for ($i = 1; $i <= 16; $i++) table_th($i);
table_endrow();
$ind = sql_array("SELECT `id` FROM `teams` WHERE `league` = '{$league}' ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC", __FILE__, __LINE__);
$data = sql_query("SELECT `id`, `name` FROM `teams` WHERE `league` = '{$league}' ORDER BY `points` DESC, (`goalsscored` - `goalsconceded`) DESC, `goalsscored` DESC, `wins` DESC, `id` DESC", __FILE__, __LINE__);
$pos = 0;
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   table_startrow();
   table_cell("<b>{$pos}</b>");
   table_cell(create_link("teamdetails.php?id={$row['id']}", $row['name']));
   foreach ($ind as $value)
   {
      $res = sql_data("SELECT `id`, `played`, `hometeam`, `awayteam`, `homescore`, `awayscore` FROM `match` WHERE `hometeam` = {$row['id']} AND `awayteam` = '{$value}' AND `rules` = 'league' AND `season` = {$config['season']}", __FILE__, __LINE__);
      if (!$res) table_cell("", 1, "specialtb", "#EFC5C2");
      else if ($res['played'] == 'no') table_cell("&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;");
      else if ($res['hometeam'] == $TEAM['id'] || $res['awayteam'] == $TEAM['id']) table_cell(create_link("matchreport.php?id={$res['id']}", "<b>{$res['homescore']} - {$res['awayscore']}</b>"));
      else table_cell(create_link("matchreport.php?id={$res['id']}", "{$res['homescore']} - {$res['awayscore']}"));
   }
   table_endrow();
}
table_end();
pageend();
?>
