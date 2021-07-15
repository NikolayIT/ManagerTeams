<?php
define("IN_GAME", true);
include("common.php");
limit();
pagestart(TRAINING);
head(TRAINING);
prnt(TRAINING_HEAD_TEXT);
br(2);
create_button("settraining.php?auto=1", AUTOMATIC_TRAINING);
br(2);
form_start("settraining.php", "POST");
// Goalkeepers
head(KEEPERS);
table_start();
$players = sql_query("SELECT `id`, `name`, `shortname`, `training`, `possition`, `injured` FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'GK') ORDER BY possition ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if ($row['injured'] == 0) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   $training = select("p_{$row['id']}");
   $arr = get_trainig_abb($row['possition']);
   foreach ($arr as $value) $training .= option($value, get_abb_name($value), $row['training'] == $value);
   $training .= end_select();
   table_cell($training);
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
br();
// Defenders
head(DEFENDERS);
table_start();
$players = sql_query("SELECT `id`, `name`, `shortname`, `training`, `possition`, `injured` FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LB' || `possition` = 'CB' || `possition` = 'RB') ORDER BY possition ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if ($row['injured'] == 0) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   $training = select("p_{$row['id']}");
   $arr = get_trainig_abb($row['possition']);
   foreach ($arr as $value) $training .= option($value, get_abb_name($value), $row['training'] == $value);
   $training .= end_select();
   table_cell($training);
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
// Defensive Midfielders
head(DEFENSIVE_MIDFIELDERS);
table_start();
$players = sql_query("SELECT `id`, `name`, `shortname`, `training`, `possition`, `injured` FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LBM' || `possition` = 'CBM' || `possition` = 'RBM') ORDER BY possition ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if ($row['injured'] == 0) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   $training = select("p_{$row['id']}");
   $arr = get_trainig_abb($row['possition']);
   foreach ($arr as $value) $training .= option($value, get_abb_name($value), $row['training'] == $value);
   $training .= end_select();
   table_cell($training);
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
// Midfielders
head(MIDFIELDERS);
table_start();
$players = sql_query("SELECT `id`, `name`, `shortname`, `training`, `possition`, `injured` FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LM' || `possition` = 'CM' || `possition` = 'RM') ORDER BY possition ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if ($row['injured'] == 0) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   $training = select("p_{$row['id']}");
   $arr = get_trainig_abb($row['possition']);
   foreach ($arr as $value) $training .= option($value, get_abb_name($value), $row['training'] == $value);
   $training .= end_select();
   table_cell($training);
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
// Attacking Midfielders
head(FORWARD_MIDFIELDERS);
table_start();
$players = sql_query("SELECT `id`, `name`, `shortname`, `training`, `possition`, `injured` FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LFM' || `possition` = 'CFM' || `possition` = 'RFM') ORDER BY possition ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if ($row['injured'] == 0) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   $training = select("p_{$row['id']}");
   $arr = get_trainig_abb($row['possition']);
   foreach ($arr as $value) $training .= option($value, get_abb_name($value), $row['training'] == $value);
   $training .= end_select();
   table_cell($training);
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
// Attackers
head(STRIKERS);
table_start();
$players = sql_query("SELECT `id`, `name`, `shortname`, `training`, `possition`, `injured` FROM `players` WHERE `team` = {$TEAM['id']} AND (`possition` = 'LF' || `possition` = 'CF' || `possition` = 'RF') ORDER BY possition ASC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($players))
{
   table_startrow();
   table_cell("<b>{$row['possition']}</b>", 1, "specialtb", get_color_for_possition($row['possition']));
   table_player_name($row['id'], $row['name'], $row['shortname']);
   if ($row['injured'] == 0) table_cell(div("img_plain_yes", YES));
   else table_cell(div("img_plain_no", NO));
   $training = select("p_{$row['id']}");
   $arr = get_trainig_abb($row['possition']);
   foreach ($arr as $value) $training .= option($value, get_abb_name($value), $row['training'] == $value);
   $training .= end_select();
   table_cell($training);
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
br();
input("submit", "", SAVE_TRAINING, "", true);
form_end();
pageend();
?>