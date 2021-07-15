<?php
/*
File name: playerratings.php
Last change: Mon Jan 28 21:57:44 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(PLAYER_RATINGS);
head(PLAYER_RATINGS);
table_start();
// Goalkeepers
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'GK') ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_num_rows($players) > 0)
{
   table_header("", NAME, "Rat", "Agg", "Fit", "Sta", "Spe", "BaC", "Pas", "GK", "TaB", "Pla", "Jum", "Fle", "Cou", "", "", "");
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['global']);
      table_cell($row['aggression']);
      table_cell($row['fitness']);
      table_cell($row['stamina']);
      table_cell($row['speed']);
      table_cell($row['ballcontrol']);
      table_cell($row['passing']);
      table_cell($row['goalkeeping']);
      table_cell($row['takeball']);
      table_cell($row['playalong']);
      table_cell($row['jumping']);
      table_cell($row['flexibility']);
      table_cell($row['courage']);
      table_cell("");
      table_cell("");
      table_cell("");
      table_endrow();
      table_player_row($row['id'], 20);
   }
}
// Defenders
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LB' || `possition` = 'CB' || `possition` = 'RB') ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_num_rows($players) > 0)
{
   table_header("", NAME, "Rat", "Agg", "Fit", "Sta", "Spe", "BaC", "Pas", "Hea", "Tak", "PlA", "PlO", "Pos", "Tac", "", "", "");
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['global']);
      table_cell($row['aggression']);
      table_cell($row['fitness']);
      table_cell($row['stamina']);
      table_cell($row['speed']);
      table_cell($row['ballcontrol']);
      table_cell($row['passing']);
      table_cell($row['heading']);
      table_cell($row['takeball']);
      table_cell($row['playalong']);
      table_cell($row['playitout']);
      table_cell($row['positioning']);
      table_cell($row['tackling']);
      table_cell("");
      table_cell("");
      table_cell("");
      table_endrow();
      table_player_row($row['id'], 20);
   }
}
// Defensive Midfielders
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LBM' || `possition` = 'CBM' || `possition` = 'RBM') ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_num_rows($players) > 0)
{
   table_header("", NAME, "Rat", "Agg", "Fit", "Sta", "Spe", "BaC", "Pas", "Hea", "Tak", "PlA", "PlO", "Pos", "Tac", "Dri", "Sho", "Tec");
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['global']);
      table_cell($row['aggression']);
      table_cell($row['fitness']);
      table_cell($row['stamina']);
      table_cell($row['speed']);
      table_cell($row['ballcontrol']);
      table_cell($row['passing']);
      table_cell($row['heading']);
      table_cell($row['takeball']);
      table_cell($row['playalong']);
      table_cell($row['playitout']);
      table_cell($row['positioning']);
      table_cell($row['tackling']);
      table_cell($row['dribble']);
      table_cell($row['shooting']);
      table_cell($row['technique']);
      table_endrow();
      table_player_row($row['id'], 20);
   }
}
// Midfielders
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LM' || `possition` = 'CM' || `possition` = 'RM') ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_num_rows($players) > 0)
{
   table_header("", NAME, "Rat", "Agg", "Fit", "Sta", "Spe", "BaC", "Pas", "Dri", "Sho", "Tec", "PlO", "Pos", "Tac", "", "", "");
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['global']);
      table_cell($row['aggression']);
      table_cell($row['fitness']);
      table_cell($row['stamina']);
      table_cell($row['speed']);
      table_cell($row['ballcontrol']);
      table_cell($row['passing']);
      table_cell($row['dribble']);
      table_cell($row['shooting']);
      table_cell($row['technique']);
      table_cell($row['playitout']);
      table_cell($row['positioning']);
      table_cell($row['tackling']);
      table_cell("");
      table_cell("");
      table_cell("");
      table_endrow();
      table_player_row($row['id'], 20);
   }
}
// Attacking Midfielders
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LFM' || `possition` = 'CFM' || `possition` = 'RFM') ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_num_rows($players) > 0)
{
   table_header("", NAME, "Rat", "Agg", "Fit", "Sta", "Spe", "BaC", "Pas", "Dri", "Sho", "Tec", "PlO", "Pos", "Tac", "Hea", "GoS", "");
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['global']);
      table_cell($row['aggression']);
      table_cell($row['fitness']);
      table_cell($row['stamina']);
      table_cell($row['speed']);
      table_cell($row['ballcontrol']);
      table_cell($row['passing']);
      table_cell($row['dribble']);
      table_cell($row['shooting']);
      table_cell($row['technique']);
      table_cell($row['playitout']);
      table_cell($row['positioning']);
      table_cell($row['tackling']);
      table_cell($row['heading']);
      table_cell($row['goalsense']);
      table_cell("");
      table_endrow();
      table_player_row($row['id'], 20);
   }
}
// Attackers
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LF' || `possition` = 'CF' || `possition` = 'RF') ORDER BY `possition` ASC", __FILE__, __LINE__);
if (mysql_num_rows($players) > 0)
{
   table_header("", NAME, "Rat", "Agg", "Fit", "Sta", "Spe", "BaC", "Pas", "Hea", "GoS", "Sho", "Tec", "Pos", "Tac", "", "", "");
   while ($row = mysql_fetch_assoc($players))
   {
      table_startrow();
      table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
      table_player_name($row['id'], $row['name'], $row['shortname']);
      table_cell($row['global']);
      table_cell($row['aggression']);
      table_cell($row['fitness']);
      table_cell($row['stamina']);
      table_cell($row['speed']);
      table_cell($row['ballcontrol']);
      table_cell($row['passing']);
      table_cell($row['heading']);
      table_cell($row['goalsense']);
      table_cell($row['shooting']);
      table_cell($row['technique']);
      table_cell($row['positioning']);
      table_cell($row['tackling']);
      table_cell("");
      table_cell("");
      table_cell("");
      table_endrow();
      table_player_row($row['id'], 20);
   }
}
table_end();
pageend();
?>
