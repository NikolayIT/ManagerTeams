<?php
/*
File name: tactics.php
Last change: Thu Feb 07 19:37:32 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
function get_formation_id($formation, $default)
{
   global $formations;
   $formid = 0;
   if (is_numeric($formation) && $formation >= 0 && $formation < count($formations)) $formid = $formation;
   else if (is_numeric($default) && $default >= 0 && $default < count($formations)) $formid = $default;
   else $formid = DEFAULT_FORMATION;
   return $formid;
}
function get_formation_select($defformation)
{
   global $formations;
   $defformation = $formations[$defformation];
   $formform = select("formation");
   $i = 0;
   foreach ($formations as $formation)
   {
      $formform .= option($i, $formation[0]."-".$formation[1]."-".$formation[2], $formation == $defformation);
      $i++;

   }
   $formform .= end_select();
   return $formform;
}
function get_copyfrom_select()
{
   $tactics = array(1, 2, 3, 4, 5);
   $formform = select("from");
   $i = 1;
   foreach ($tactics as $tactic)
   {
      $formform .= option($i, SELECTION." ".$tactic);
      $i++;
   }
   $formform .= end_select();
   return $formform;
}
function get_players_select($fieldname, $default, $additional = "", $short = false)
{
   global $players;
   $ret = "<select name='{$fieldname}' id='{$fieldname}' {$additional}>".option(0, "&nbsp;");
   $last = "";
   while ($player = mysql_fetch_assoc($players))
   {
      if ($player['possition'] != $last)
      {
         if ($last != "") $ret .= "</optgroup>";
         $last = $player['possition'];
         $ret .= "<optgroup label='{$player['possition']}'>";
      }
      if ($short) $name = "{$player['number']}. ".get_player_name($player['name'], $player['shortname'],$short);
      else $name = "{$player['number']}. ({$player['possition']}) ".get_player_name($player['name'], $player['shortname'],$short);
      $ret .= option($player['id'], $name, $player['id'] == $default);
   }
   mysql_data_seek($players, 0);
   $ret .= "</optgroup>".end_select();
   return $ret;
}
function create_player_position($id, $type, $player, $left, $top)
{
   $player = sql_data("SELECT `id`, `name`, `shortname`, `number` FROM `players` WHERE `id` = '{$player}'", __FILE__, __LINE__);
   if ($player) $name = get_player_name($player['name'], $player['shortname'], true);
   else $name = "&nbsp;";
   prnt("<div id='_{$id}_' class='player position_{$type} selected_{$type}' style=\"left:{$left}px; top:{$top}px\">".get_players_select($id, $player['id'], "onchange=\"updatePlayer(this);\" style='display: none;'", true));
   prnt("   <div id='__{$id}__' style=\"cursor:pointer;\" onclick=\"showHideSelectionField(this);\">");
   prnt("      <div class='playerfigure'>");
   prnt("         <div id='___{$id}_number___'>{$player['number']}</div>");
   prnt("      </div>");
   prnt("      <div class='playername'>");
   prnt("         <div id='___{$id}_name___'>{$name}</div>");
   prnt("      </div>");
   prnt("   </div>");
   prnt("</div>");
}
$bool_match = false;
mkglobal("formation", false);
mkglobal("from", false);
mkglobal("mytactic", false);
mkglobal("match", false);
if (isset($mytactic) && $mytactic >= 1 && $mytactic <= 5)
{
   if ($mytactic > 1 && !limit_cover(UC_VIP_USER)) info(FOR_VIP_USER_ONLY, SORRY);
   $tacid = $TEAM["tactic{$mytactic}"];
   switch ($mytactic)
   {
      case 1: $text = EDIT_TACTIC_FOR_MAIN_SELECTION; if ($tacid == 0) { sql_query("INSERT INTO `tactics` (`id`) VALUES (NULL)", __FILE__, __LINE__); $tacid = mysql_insert_id(); sql_query("UPDATE `teams` SET `tactic1` = {$tacid} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__); } break;
      case 2: $text = EDIT_TACTIC_FOR_SELECTION." 2"; if ($tacid == 0) { sql_query("INSERT INTO `tactics` (`id`) VALUES (NULL)", __FILE__, __LINE__); $tacid = mysql_insert_id(); sql_query("UPDATE `teams` SET `tactic2` = {$tacid} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__); } break;
      case 3: $text = EDIT_TACTIC_FOR_SELECTION." 3"; if ($tacid == 0) { sql_query("INSERT INTO `tactics` (`id`) VALUES (NULL)", __FILE__, __LINE__); $tacid = mysql_insert_id(); sql_query("UPDATE `teams` SET `tactic3` = {$tacid} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__); } break;
      case 4: $text = EDIT_TACTIC_FOR_SELECTION." 4"; if ($tacid == 0) { sql_query("INSERT INTO `tactics` (`id`) VALUES (NULL)", __FILE__, __LINE__); $tacid = mysql_insert_id(); sql_query("UPDATE `teams` SET `tactic4` = {$tacid} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__); } break;
      case 5: $text = EDIT_TACTIC_FOR_SELECTION." 5"; if ($tacid == 0) { sql_query("INSERT INTO `tactics` (`id`) VALUES (NULL)", __FILE__, __LINE__); $tacid = mysql_insert_id(); sql_query("UPDATE `teams` SET `tactic5` = {$tacid} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__); } break;
   }
   $text = "Промяна на тактика \"".$USER["tacticname{$mytactic}"]."\"";
}
else if (isset($match) && is_numeric($match))
{
   $dat = sql_data("SELECT `id`, `hometeam`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`, `awayteam`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname`, `hometactic`, `awaytactic` FROM `match` WHERE `id` = '{$match}' AND `played` = 'no'", __FILE__, __LINE__);
   if ($dat['hometeam'] == $TEAM['id']) $tacid = $dat['hometactic'];
   else if ($dat['awayteam'] == $TEAM['id']) $tacid = $dat['awaytactic'];
   else info(MATCH_NOT_FOUND, ERROR);
   if ($tacid == 0)
   {
      sql_query("INSERT INTO `tactics` SELECT NULL, `formation`, `aggression`, `style`, `tactics`, `captain`, `GK`, `LB`, `CB1`, `CB2`, `CB3`, `RB`, `LM`, `CM1`, `CM2`, `CM3`, `RM`, `CF1`, `CF2`, `CF3`, `S1`, `S2`, `S3`, `S4`, `S5`, `GK_ind`, `LB_ind`, `CB1_ind`, `CB2_ind`, `CB3_ind`, `RB_ind`, `LM_ind`, `CM1_ind`, `CM2_ind`, `CM3_ind`, `RM_ind`, `CF1_ind`, `CF2_ind`, `CF3_ind`, `S1_ind`, `S2_ind`, `S3_ind`, `S4_ind`, `S5_ind`, `sub1_min`, `sub1_out`, `sub1_in`, `sub2_min`, `sub2_out`, `sub2_in`, `sub3_min`, `sub3_out`, `sub3_in` FROM `tactics` WHERE `id` = {$TEAM['tactic1']}", __FILE__, __LINE__);
      $tacid = mysql_insert_id();
      if ($dat['hometeam'] == $TEAM['id']) sql_query("UPDATE `match` SET `hometactic` = {$tacid} WHERE `id` = {$dat['id']}", __FILE__, __LINE__);
      else if ($dat['awayteam'] == $TEAM['id']) sql_query("UPDATE `match` SET `awaytactic` = {$tacid} WHERE `id` = {$dat['id']}", __FILE__, __LINE__);
   }
   $bool_match = true;
   $matchname = "{$dat['homename']} - {$dat['awayname']}";
   $text = EDIT_TACTIC_FOR_MATCH." ".$matchname;
}
else
{
   pagestart(TACTICS);
   head(TACTICS);
   create_special_link("tactics.php?mytactic=1", $USER['tacticname1'], MAIN_SELECTION_TEXT);
   create_special_link("tactics.php?mytactic=2", $USER['tacticname2'], SELECTION_2_TEXT);
   create_special_link("tactics.php?mytactic=3", $USER['tacticname3'], SELECTION_3_TEXT);
   create_special_link("tactics.php?mytactic=4", $USER['tacticname4'], SELECTION_4_TEXT);
   create_special_link("tactics.php?mytactic=5", $USER['tacticname5'], SELECTION_5_TEXT);
   create_special_link("tacticsnames.php", "Промяна на имената", "Промяна на имената на тактиките");
   pageend();
}

pagestart($text);
head($text);

if ($from >= 1 && $from <= 5)
{
   limit(UC_VIP_USER);
   $from_id = $TEAM["tactic{$from}"];
   sql_query("UPDATE `tactics` AS `t1`, `tactics` AS `t2` SET
   `t1`.`formation` = `t2`.`formation`,
   `t1`.`aggression` = `t2`.`aggression`,
   `t1`.`style` = `t2`.`style`,
   `t1`.`tactics` = `t2`.`tactics`,
   `t1`.`captain` = `t2`.`captain`,
   `t1`.`GK` = `t2`.`GK`,
   `t1`.`LB` = `t2`.`LB`,
   `t1`.`CB1` = `t2`.`CB1`,
   `t1`.`CB2` = `t2`.`CB2`,
   `t1`.`CB3` = `t2`.`CB3`,
   `t1`.`RB` = `t2`.`RB`,
   `t1`.`LM` = `t2`.`LM`,
   `t1`.`CM1` = `t2`.`CM1`,
   `t1`.`CM2` = `t2`.`CM2`,
   `t1`.`CM3` = `t2`.`CM3`,
   `t1`.`RM` = `t2`.`RM`,
   `t1`.`CF1` = `t2`.`CF1`,
   `t1`.`CF2` = `t2`.`CF2`,
   `t1`.`CF3` = `t2`.`CF3`,
   `t1`.`S1` = `t2`.`S1`,
   `t1`.`S2` = `t2`.`S2`,
   `t1`.`S3` = `t2`.`S3`,
   `t1`.`S4` = `t2`.`S4`,
   `t1`.`S5` = `t2`.`S5`,
   `t1`.`GK_ind` = `t2`.`GK_ind`,
   `t1`.`LB_ind` = `t2`.`LB_ind`,
   `t1`.`CB1_ind` = `t2`.`CB1_ind`,
   `t1`.`CB2_ind` = `t2`.`CB2_ind`,
   `t1`.`CB3_ind` = `t2`.`CB3_ind`,
   `t1`.`RB_ind` = `t2`.`RB_ind`,
   `t1`.`LM_ind` = `t2`.`LM_ind`,
   `t1`.`CM1_ind` = `t2`.`CM1_ind`,
   `t1`.`CM2_ind` = `t2`.`CM2_ind`,
   `t1`.`CM3_ind` = `t2`.`CM3_ind`,
   `t1`.`RM_ind` = `t2`.`RM_ind`,
   `t1`.`CF1_ind` = `t2`.`CF1_ind`,
   `t1`.`CF2_ind` = `t2`.`CF2_ind`,
   `t1`.`CF3_ind` = `t2`.`CF3_ind`,
   `t1`.`S1_ind` = `t2`.`S1_ind`,
   `t1`.`S2_ind` = `t2`.`S2_ind`,
   `t1`.`S3_ind` = `t2`.`S3_ind`,
   `t1`.`S4_ind` = `t2`.`S4_ind`,
   `t1`.`S5_ind` = `t2`.`S5_ind`,
   `t1`.`sub1_min` = `t2`.`sub1_min`,
   `t1`.`sub1_out` = `t2`.`sub1_out`,
   `t1`.`sub1_in` = `t2`.`sub1_in`,
   `t1`.`sub2_min` = `t2`.`sub2_min`,
   `t1`.`sub2_out` = `t2`.`sub2_out`,
   `t1`.`sub2_in` = `t2`.`sub2_in`,
   `t1`.`sub3_min` = `t2`.`sub3_min`,
   `t1`.`sub3_out` = `t2`.`sub3_out`,
   `t1`.`sub3_in` = `t2`.`sub3_in`
   WHERE `t1`.`id` = {$tacid} AND `t2`.`id` = {$from_id};", __FILE__, __LINE__);
   prnt("<b>".COPY_DONE."</b><br><br>");
}
$tactic = sql_data("SELECT * FROM `tactics` WHERE `id` = {$tacid}", __FILE__, __LINE__);
if (!$tactic) info(INVALID_SCTIPT_CALL, ERROR);
$players = sql_query("SELECT * FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
$formation = get_formation_id($formation, $tactic['formation']);

form_start("tactics.php", "GET");
print(CHANGE_TACTIC.": ");
if ($match) input("hidden", "match", $match, "match", true);
else input("hidden", "mytactic", $mytactic, "mytactic", true);
prnt(get_formation_select($formation)."&nbsp;");
input("submit", "", SELECT, "", true);
form_end();

if (limit_cover(UC_VIP_USER))
{
   form_start("tactics.php", "GET");
   print(COPY_SELECTION.": ");
   if ($match) input("hidden", "match", $match, "match", true);
   else input("hidden", "mytactic", $mytactic, "mytactic", true);
   prnt(get_copyfrom_select()."&nbsp;");
   input("submit", "", COPY_HERE, "", true);
   form_end();
}

create_button("tactics_individual.php?id={$tacid}&back={$_SERVER['REQUEST_URI']}", INDIVIDUAL_TACTICS);
br(2);
create_button("tactics_substitutions.php?id={$tacid}&back={$_SERVER['REQUEST_URI']}", SUBSTITUTIONS);
br(3);
form_start("settactic.php", "POST", "selform");
input("hidden", "tactic", $tacid, "tactic", true);
input("hidden", "formation", $formation, "formation", true);

prnt(TACTIC_TYPE.": ");
select("tactictype", "", true);
$i = 0;
foreach ($tactictypes as $tact)
{
   option($i, $tact, $i == $tactic['tactics'], true);
   $i++;
}
end_select(true);
prnt(" (".YOUR_MAIN_TACTIC_PLAN.")<br>", true);

prnt(ATTACKING_STYLE.": ");
select("style", "", true);
for ($i = 0; $i <= 100; $i++) option($i, $i, $i == $tactic['style'], true);
end_select(true);
prnt(" (0 - "._ULTRA_DEFENSIVE.", 100 - "._ULTRA_ATTACKING.")<br>", true);

prnt(AGGRESSION.": ");
select("aggression", "", true);
for ($i = 0; $i <= 100; $i++) option($i, $i, $i == $tactic['aggression'], true);
end_select(true);
prnt(" (0 - "._ULTRA_SOFT_PLAY.", 100 - "._ULTRA_HARD_PLAY.")<br>", true);

prnt(CAPTAIN.": ".get_players_select("captain", $tactic['captain'], "")." (".YOUR_TEAM_LEADER.")<br>", true);

prnt("<script type='text/javascript'>");
prnt("var players = new Array(");
$i = 0;
while ($row = mysql_fetch_assoc($players))
{
   $i++;
   switch ($row['possition'])
   {
      case "GK": $pltype = "keeper"; break;
      case "LB":case "CB":case "RB": $pltype = "defender"; break;
      case "LBM":case "CBM":case "RBM": $pltype = "defmid"; break;
      case "LM":case "CM":case "RM": $pltype = "midfielder"; break;
      case "LFM":case "CFM":case "RFM": $pltype = "strmid"; break;
      case "LF":case "CF":case "RF": $pltype = "striker"; break;
   }
   if ($i > 1) prnt(", ");
   $name = get_player_name($player['name'], $player['shortname'],$short);
   prnt("new Array({$row['id']}, '{$name}', '{$pltype}')");
}
mysql_data_seek($players, 0);
prnt(");</script>");
$formname = $formations[$formation];
$def = $formname[0];
$mid = $formname[1];
$att = $formname[2];
prnt("<div style=\"width: 600px; height: 900px\"><div id='formation_field'>");
create_player_position("GK", "keeper", $tactic["GK"], 249, 635);
if ($def == 2)
{
   create_player_position("CB1", "defender", $tactic['CB1'], 130, 490);
   create_player_position("CB2", "defender", $tactic['CB2'], 360, 490);
}
else if ($def == 3)
{
   create_player_position("LB", "defender", $tactic["LB"], 20, 470);
   create_player_position("CB1", "defender", $tactic["CB1"], 250, 510);
   create_player_position("RB", "defender", $tactic["RB"], 470, 470);
}
else if ($def == 4)
{
   create_player_position("LB", "defender", $tactic["LB"], 20, 470);
   create_player_position("CB1", "defender", $tactic["CB1"], 170, 490);
   create_player_position("CB2", "defender", $tactic["CB2"], 320, 490);
   create_player_position("RB", "defender", $tactic["RB"], 470, 470);
}
else if ($def == 5)
{
   create_player_position("LB", "defender", $tactic["LB"], 20, 470);
   create_player_position("CB1", "defender", $tactic["CB1"], 130, 490);
   create_player_position("CB2", "defender", $tactic["CB2"], 250, 510);
   create_player_position("CB3", "defender", $tactic["CB3"], 360, 490);
   create_player_position("RB", "defender", $tactic["RB"], 470, 470);
}
if ($mid == 2)
{
   create_player_position("CM1", "midfielder", $tactic["CM1"], 130, 335);
   create_player_position("CM2", "midfielder", $tactic["CM2"], 360, 335);
}
else if ($mid == 3)
{
   create_player_position("LM", "midfielder", $tactic["LM"], 20, 320);
   create_player_position("CM1", "midfielder", $tactic["CM1"], 250, 350);
   create_player_position("RM", "midfielder", $tactic["RM"], 470, 320);
}
else if ($mid == 4)
{
   create_player_position("LM", "midfielder", $tactic["LM"], 20, 320);
   create_player_position("CM1", "midfielder", $tactic["CM1"], 170, 335);
   create_player_position("CM2", "midfielder", $tactic["CM2"], 320, 335);
   create_player_position("RM", "midfielder", $tactic["RM"], 470, 320);
}
else if ($mid == 5)
{
   create_player_position("LM", "midfielder", $tactic["LM"], 20, 320);
   create_player_position("CM1", "midfielder", $tactic["CM1"], 130, 335);
   create_player_position("CM2", "midfielder", $tactic["CM2"], 250, 350);
   create_player_position("CM3", "midfielder", $tactic["CM3"], 360, 335);
   create_player_position("RM", "midfielder", $tactic["RM"], 470, 320);
}
if ($att == 1)
{
   create_player_position("CF1", "striker", $tactic["CF1"], 250, 145);
}
else if ($att == 2)
{
   create_player_position("CF1", "striker", $tactic["CF1"], 150, 165);
   create_player_position("CF2", "striker", $tactic["CF2"], 350, 165);
}
else if ($att == 3)
{
   create_player_position("CF1", "striker", $tactic["CF1"], 100, 165);
   create_player_position("CF2", "striker", $tactic["CF2"], 250, 155);
   create_player_position("CF3", "striker", $tactic["CF3"], 400, 165);
}
create_player_position("S1", "bench", $tactic["S1"], 10, 770);
create_player_position("S2", "bench", $tactic["S2"], 130, 770);
create_player_position("S3", "bench", $tactic["S3"], 250, 770);
create_player_position("S4", "bench", $tactic["S4"], 370, 770);
create_player_position("S5", "bench", $tactic["S5"], 490, 770);
prnt("</div></div>", true);
input("submit", "", SAVE_TACTIC, "", true);
form_end();
br(2);
head(PLAYERS);
$players = sql_query("SELECT `id`, `name`, `shortname`, `possition`, `number`, `currentform`, `injured`, `banleague`, `bancup`, `global`, `age`, `fitness` FROM `players` WHERE `team` = {$TEAM['id']} ORDER BY `possition` ASC", __FILE__, __LINE__);
table_start();
table_header("", NUMBER, NAME, AGE, FITNESS, div("img_player_injured"), div("img_player_locked_league"), div("img_player_locked_cup"), FORM, RATING);
function get_color($player, $tactic)
{
   foreach ($tactic as $key => $value)
   {
      if ($value == $player && (strlen($key) == 2 || strlen($key) == 3) && !is_numeric($key)) return get_color_for_possition($key);
   }
   return "";
}
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   $color = get_color($row['id'], $tactic);
   table_cell($row['possition'], 1, "specialtb", get_color_for_possition($row['possition']));
   table_cell($row['number'], 1, "tbnc", $color);
   table_player_name($row['id'], $row['name'], $row['shortname'], $color);
   table_cell($row['age'], 1, "tbnc", $color);
   table_cell($row['fitness'], 1, "tbnc", $color);
   if ($row['injured'] > 0)
   {
      $staff = sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'doctor'", __FILE__, __LINE__);
      if (!$staff) table_cell(popup("!!!", "Нямате доктор и затова не можете да виждате дните, за които е контузен играча."), 1, "tbnc", $color);
      else table_cell("<b>".$row['injured']."</b>", 1, "tbnc", $color);
   }
   else table_cell(div("img_plain_no", NO), 1, "tbnc", $color);
   if ($type != "sell") table_cell($row['banleague'], 1, "tbnc", $color);
   if ($type != "sell") table_cell($row['bancup'], 1, "tbnc", $color);
   table_cell($row['currentform'], 1, "tbnc", $color);
   table_cell(create_progress_bar($row['global']), 1, "tbnc", $color);
   if ($type == "sell") table_cell(create_link("sellplayer.php?id={$row['id']}", SELL), 1, "tbnc", $color);
   table_endrow();
   table_player_row($row['id'], 10);
}
table_end();
pageend();
?>
