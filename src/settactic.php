<?php
/*
File name: settactic.php
Last change: Mon Feb 04 09:32:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("tactic", false);
sqlsafe($tactic);
if (empty($tactic) || !is_numeric($tactic)) info(WRONG_ID, ERROR);
$tactic = sql_data("SELECT * FROM `tactics` WHERE `id` = {$tactic}", __FILE__, __LINE__);
if (!$tactic) info(WRONG_ID, ERROR);
$tacid = $tactic['id'];
$match = sql_get("SELECT `id` FROM `match` WHERE ((`hometeam` = {$TEAM['id']} AND `hometactic` = {$tacid}) OR (`awayteam` = {$TEAM['id']} AND `awaytactic` = {$tacid})) AND `played` = 'no'", __FILE__, __LINE__);
if ($TEAM['tactic1'] == $tacid || $TEAM['tactic2'] == $tacid || $TEAM['tactic3'] == $tacid || $TEAM['tactic4'] == $tacid || $TEAM['tactic5'] == $tacid || $match > 0)
{
   $query = "UPDATE `tactics` SET ";
   mkglobal("formation", true);
   if (!is_numeric($formation) || $formation < 0 || $formation >= count($formations)) $formation = DEFAULT_FORMATION;
   $query .= "`formation` = '{$formation}', ";
   mkglobal("tactictype", true);
   if (!is_numeric($tactictype) || $tactictype < 0 || $tactictype >= count($tactictypes)) $tactictype = DEFAULT_TACTIC_TYPE;
   $query .= "`tactics` = '{$tactictype}', ";
   mkglobal("style", true);
   if (!is_numeric($style) || $style < 0 || $style > 100) $style = DEFAULT_STYLE;
   $query .= "`style` = '{$style}', ";
   mkglobal("aggression", true);
   if (!is_numeric($aggression) || $aggression < 0 || $aggression > 100) $aggression = DEFAULT_AGGRESSION;
   $query .= "`aggression` = '{$aggression}', ";
   $used = array();
   $fields = array("GK", "LB", "CB1", "CB2", "CB3", "RB", "LM", "CM1", "CM2", "CM3", "RM", "CF1", "CF2", "CF3", "S1", "S2", "S3", "S4", "S5");
   foreach ($fields as $value)
   {
      if (mkglobal($value, true))
      {
         $val = $GLOBALS[$value];
         $sqlval = sqlsafe($val);
         $check = sql_get("SELECT `injured` FROM `players` WHERE `id` = {$sqlval}", __FILE__, __LINE__);
         if (!in_array($val, $used) && $check == 0)
         {
            if (is_numeric($val) && $val >= 0) $query .= "`{$value}` = {$sqlval}, ";
         }
         else $query .= "`{$value}` = '0', ";
         array_push($used, $val);
      }
      else $query .= "`{$value}` = '0', ";
   }
   mkglobal("captain", true);
   $captain = sqlsafe($captain);
   $check = sql_get("SELECT `id` FROM `players` WHERE `id` = {$captain} AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
   if ($captain != $check) $captain = 0;
   $query .= "`captain` = '{$captain}'";
   $query .= " WHERE `id` = '{$tacid}'";
   sql_query($query, __FILE__, __LINE__);
   //print_post_content();
   info(TACTIC_SUCCESSFULY_SET, SUCCESS, true);
}
else info(WRONG_ID, ERROR);
?>
