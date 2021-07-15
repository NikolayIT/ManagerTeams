<?php
/*
File name: aj_get_player.php
Last change: Sat Jan 12 18:14:13 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
define("DONT_UPDATE", true);
include("common.php");
header("Content-Type: text/html; charset=".ENCODING);
if (!$USER) die(SESSION_TIMED_OUT);
mkglobal("id");
if (empty($id) || !is_numeric($id)) die(WRONG_ID);
$player = sql_data("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players` WHERE `id` = {$id}", __FILE__, __LINE__);
if (!$player) die(INVALID_PLAYER);
prnt("<div id=\"player_info\">");
table_start(true, 0);
table_startrow();
if ($player['picture']) prnt("<td class=\"td\"><img height=\"175px\" width=\"175px\" src=\"{$player['picture']}\" /></td>");
prnt("<td class=\"td\">");
table_start(true, 0);
table_row(POSSITION, $player['possition']);
table_row(AGE, "{$player['age']}");
table_row(TEAM, create_link("teamdetails.php?id={$player['team']}", $player['teamname'])." ({$player['number']})");
table_row(FORM, $player['currentform']);
table_row(BEST_FORM, $player['bestform']);
table_row(TRAINING, get_abb_name($player['training']));
table_row(WEIGHT, $player['weight']." Kg");
table_row(HEIGHT, $player['height']." cm");
table_end();
prnt("</td>");
prnt("<td class=\"td\">");
table_start(true, 0);
table_row(GLOBAL_RATING, $player['global']);
table_row(FITNESS, $player['fitness']."%");
table_row(EXPERIENCE, $player['experience']."%");
table_row(AGGRESSION, $player['aggression']."%");
table_row(BALL_CONTROL, $player['ballcontrol']."%");
table_row(SPEED, $player['speed']."%");
table_row(PASSING, $player['passing']."%");
table_row(STAMINA, $player['stamina']."%");
table_end();
prnt("</td>");
prnt("<td class=\"td\">");
table_start(true, 0);
$arr = get_special_abb($player['possition']);
foreach ($arr as $value) table_row(get_abb_name($value), $player[get_abb_id($value)]."%");
table_end();
prnt("</td>");
table_endrow();
table_end();
$note = sql_data("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `players_notes`.`fromid`) AS `manager` FROM `players_notes` WHERE `player` = {$id}", __FILE__, __LINE__);
if ($note) prnt("<b>".create_link("viewprofile.php?id={$note['fromid']}", $note['manager'])."</b> ({$note['time']}): {$note['text']}");
prnt("</div>");
?>
