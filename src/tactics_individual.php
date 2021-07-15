<?php
/*
File name: tactics_individual.php
Last change: Fri Feb 08 15:35:51 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function player_tactic($poss, $curr, $plid, $arr, $poss2)
{
   if ($plid != 0) $player = sql_data("SELECT `name`, `shortname` FROM `players` WHERE `id` = {$plid}", __FILE__, __LINE__);
   table_startrow();
   table_cell($poss2);
   if ($plid != 0) table_player_name($plid, $player['name'], $player['shortname']);
   else table_cell("");
   $sel .= "<select name=\"{$poss}_ind\" style=\"width: 200px\">";
   $i = 0;
   foreach ($arr as $value) $sel .= option($i++, $value, $i-1 == $curr);
   $sel .= end_select();
   table_cell($sel);
   table_endrow();
   if ($plid != 0) table_player_row($plid, 3);
}
mkglobal("id");
mkglobal("back", false);
$id = sqlsafe($id);
$tactic = sql_data("SELECT * FROM `tactics` WHERE `id` = {$id}", __FILE__, __LINE__);
if (!$tactic) info(WRONG_ID, ERROR);
$tacts1 = array(NORMAL, INDIVIDUAL_TACTICS_GK_1, INDIVIDUAL_TACTICS_GK_2, INDIVIDUAL_TACTICS_GK_3, INDIVIDUAL_TACTICS_GK_4);
$tacts2 = array(NORMAL, INDIVIDUAL_TACTICS_B_1, INDIVIDUAL_TACTICS_B_2, INDIVIDUAL_TACTICS_B_3, INDIVIDUAL_TACTICS_B_4);
$tacts3 = array(NORMAL, INDIVIDUAL_TACTICS_M_1, INDIVIDUAL_TACTICS_M_2, INDIVIDUAL_TACTICS_M_3, INDIVIDUAL_TACTICS_M_4);
$tacts4 = array(NORMAL, INDIVIDUAL_TACTICS_F_1, INDIVIDUAL_TACTICS_F_2, INDIVIDUAL_TACTICS_F_3, INDIVIDUAL_TACTICS_F_4);
//$tacts5 = array(NORMAL, INDIVIDUAL_TACTICS_F_1, INDIVIDUAL_TACTICS_F_2, INDIVIDUAL_TACTICS_F_3, INDIVIDUAL_TACTICS_F_4);
$formation = $formations[$tactic['formation']];
$def = $formation[0];
$mid = $formation[1];
$att = $formation[2];
pagestart(INDIVIDUAL_TACTICS);
head(INDIVIDUAL_TACTICS);
form_start("settactics_individual.php", "POST");
input("hidden", "id", $id, "", true);
table_start();
table_header("", "Name", TACTIC);
player_tactic("GK", $tactic["GK_ind"], $tactic["GK"], $tacts1, "GK");
if ($def == 2)
{
   player_tactic("CB1", $tactic["CB2_ind"], $tactic["CB1"], $tacts2, "CB1");
   player_tactic("CB2", $tactic["CB2_ind"], $tactic["CB2"], $tacts2, "CB2");
}
if ($def == 3)
{
   player_tactic("LB", $tactic["LB_ind"], $tactic["LB"], $tacts2, "LB");
   player_tactic("CB1", $tactic["CB1_ind"], $tactic["CB1"], $tacts2, "CB");
   player_tactic("RB", $tactic["RB_ind"], $tactic["RB"], $tacts2, "RB");
}
else if ($def == 4)
{
   player_tactic("LB", $tactic["LB_ind"], $tactic["LB"], $tacts2, "LB");
   player_tactic("CB1", $tactic["CB1_ind"], $tactic["CB1"], $tacts2, "CB1");
   player_tactic("CB2", $tactic["CB2_ind"], $tactic["CB2"], $tacts2, "CB2");
   player_tactic("RB", $tactic["RB_ind"], $tactic["RB"], $tacts2, "RB");
}
else if ($def == 5)
{
   player_tactic("LB", $tactic["LB_ind"], $tactic["LB"], $tacts2, "LB");
   player_tactic("CB1", $tactic["CB1_ind"], $tactic["CB1"], $tacts2, "CB1");
   player_tactic("CB2", $tactic["CB2_ind"], $tactic["CB2"], $tacts2, "CB2");
   player_tactic("CB3", $tactic["CB3_ind"], $tactic["CB3"], $tacts2, "CB3");
   player_tactic("RB", $tactic["RB_ind"], $tactic["RB"], $tacts2, "RB");
}
if ($mid == 2)
{
   player_tactic("CM1", $tactic["CM1_ind"], $tactic["CM1"], $tacts3, "CM1");
   player_tactic("CM2", $tactic["CM2_ind"], $tactic["CM2"], $tacts3, "CM2");
}
else if ($mid == 3)
{
   player_tactic("LM", $tactic["LM_ind"], $tactic["LM"], $tacts3, "LM");
   player_tactic("CM1", $tactic["CM1_ind"], $tactic["CM1"], $tacts3, "CM");
   player_tactic("RM", $tactic["RM_ind"], $tactic["RM"], $tacts3, "RM");
}
else if ($mid == 4)
{
   player_tactic("LM", $tactic["LM_ind"], $tactic["LM"], $tacts3, "LM");
   player_tactic("CM1", $tactic["CM1_ind"], $tactic["CM1"], $tacts3, "CM1");
   player_tactic("CM2", $tactic["CM2_ind"], $tactic["CM2"], $tacts3, "CM2");
   player_tactic("RM", $tactic["RM_ind"], $tactic["RM"], $tacts3, "RM");
}
else if ($mid == 5)
{
   player_tactic("LM", $tactic["LM_ind"], $tactic["LM"], $tacts3, "LM");
   player_tactic("CM1", $tactic["CM1_ind"], $tactic["CM1"], $tacts3, "CM1");
   player_tactic("CM2", $tactic["CM2_ind"], $tactic["CM2"], $tacts3, "CM2");
   player_tactic("CM3", $tactic["CM3_ind"], $tactic["CM3"], $tacts3, "CM3");
   player_tactic("RM", $tactic["RM_ind"], $tactic["RM"], $tacts3, "RM");
}
if ($att == 1) player_tactic("CF1", $tactic["CF1_ind"], $tactic["CF1"], $tacts4, "CF");
else if ($att == 2)
{
   player_tactic("CF1", $tactic["CF1_ind"], $tactic["CF1"], $tacts4, "LF");
   player_tactic("CF2", $tactic["CF2_ind"], $tactic["CF2"], $tacts4, "RF");
}
else if ($att == 3)
{
   player_tactic("CF1", $tactic["CF1_ind"], $tactic["CF1"], $tacts4, "LF");
   player_tactic("CF2", $tactic["CF2_ind"], $tactic["CF2"], $tacts4, "CF");
   player_tactic("CF3", $tactic["CF3_ind"], $tactic["CF3"], $tacts4, "RF");
}
/*
player_tactic("S1", $tactic["S1_ind"], $tactic["S1"], $tacts5);
player_tactic("S2", $tactic["S2_ind"], $tactic["S2"], $tacts5);
player_tactic("S3", $tactic["S3_ind"], $tactic["S3"], $tacts5);
player_tactic("S4", $tactic["S4_ind"], $tactic["S4"], $tacts5);
player_tactic("S5", $tactic["S5_ind"], $tactic["S5"], $tacts5);
*/
table_end();
br();
input("submit", "", SET_THE_TACTIC, "", true);
form_end();
$back = htmlspecialchars($back);
create_button($back, GO_BACK);
pageend();
?>
