<?php
/*
File name: teamdetails.php
Last change: Sat Feb 09 10:44:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id:edit", false);
function get_trophies($id)
{
   $ret = "";
   $trophies = sql_query("SELECT * FROM `trophies` WHERE `team` = {$id} AND `type` != 'friendly' ORDER BY `id` ASC", __FILE__, __LINE__);
   while ($trophy = mysql_fetch_assoc($trophies)) $ret .= "<div class=\"{$trophy['type']}\" title=\"{$trophy['name']} (".SEASON." {$trophy['season']})\"></div>";
   $friendly_trophies = sql_get("SELECT COUNT(`id`) FROM `trophies` WHERE `team` = {$id} AND `type` = 'friendly'", __FILE__, __LINE__);
   $last = $friendly_trophies;
   while ($last >= 500)
   {
      $last -= 500;
      $ret .= "<div class=\"img_cup_500\" title=\"500 ".FRIENDLY_CUPS."\"></div>";
   }
   while ($last >= 100)
   {
      $last -= 100;
      $ret .= "<div class=\"img_cup_100\" title=\"100 ".FRIENDLY_CUPS."\"></div>";
   }
   while ($last >= 50)
   {
      $last -= 50;
      $ret .= "<div class=\"img_cup_50\" title=\"50 ".FRIENDLY_CUPS."\"></div>";
   }
   while ($last >= 20)
   {
      $last -= 20;
      $ret .= "<div class=\"img_cup_20\" title=\"20 ".FRIENDLY_CUPS."\"></div>";
   }
   while ($last >= 10)
   {
      $last -= 10;
      $ret .= "<div class=\"img_cup_10\" title=\"10 ".FRIENDLY_CUPS."\"></div>";
   }
   $trophies = sql_query("SELECT * FROM `trophies` WHERE `team` = {$id} AND `type` = 'friendly' ORDER BY `id` DESC LIMIT {$last}", __FILE__, __LINE__);
   while ($trophy = mysql_fetch_assoc($trophies)) $ret .= "<div class=\"{$trophy['type']}\" title=\"{$trophy['name']} (".SEASON." {$trophy['season']})\"></div>";
   return $ret;
}
if (empty($id) || !is_numeric($id) || $id == $TEAM['id'])
{
   additionaldata(true);
   $id = $TEAM['id'];
   $theteam = $TEAM;
   $stad = $STADIUM;
   $usr = $USER;
   $friendlylink = "";
}
else
{
   $id = sqlsafe($id);
   $theteam = sql_data("SELECT * FROM `teams` WHERE `id` = '{$id}'", __FILE__, __LINE__);
   $stad = sql_data("SELECT * FROM `stadiums` WHERE `id` = '{$theteam['stadium']}'", __FILE__, __LINE__);
   $usr = sql_data("SELECT * FROM `users` WHERE `team` = '{$id}'", __FILE__, __LINE__);
   $friendlylink = " (".create_link("friendlyinvitation.php?to={$id}", OFFER_FRIENDLY_MATCH).")";
}
if (!$theteam) info(WRONG_ID, ERROR);
$deleteteam = "";
if (limit_cover(UC_ADMIN)) $deleteteam = " (".create_link("admin.php?module=delete&theteam={$id}", DELETE).")";
pagestart(TEAM_DETAILS.": {$theteam['name']}");

if ($edit && limit_cover(UC_ADMIN))
{
   mkglobal("add:addr:remove:remover");
   $add = sqlsafe($add);
   $remove = sqlsafe($remove);
   add_to_money_history($addr, $add, $id, true);
   add_to_money_history($remover, -$remove, $id, true);
   head(SUCCESS);
   print("<b>Отборът е променен успешно!</b>");
   $theteam = sql_data("SELECT * FROM `teams` WHERE `id` = '{$id}'", __FILE__, __LINE__);
}

head(TEAM_DETAILS.": {$theteam['name']}{$friendlylink}{$deleteteam}");


$players = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = {$theteam['id']}", __FILE__, __LINE__);
table_start();
table_row(TEAM_NAME, "{$theteam['name']} ({$id})");
table_row(GLOBAL_RATING, create_progress_bar($theteam['global']));
if (empty($usr['registred'])) table_row(MANAGER, "N.A.");
else table_row(MANAGER, create_link("viewprofile.php?id={$usr['id']}", $usr['username']));
table_row(STADIUM_NAME, create_link("stadium.php?id={$stad['id']}", $stad['name']));
$fantext = "";
if ($theteam['fansatisfaction'] <= 10) $fantext = FANSATISFACTION_TEXT_1;
else if ($theteam['fansatisfaction'] <= 30) $fantext = FANSATISFACTION_TEXT_2;
else if ($theteam['fansatisfaction'] <= 40) $fantext = FANSATISFACTION_TEXT_3;
else if ($theteam['fansatisfaction'] <= 60) $fantext = FANSATISFACTION_TEXT_4;
else if ($theteam['fansatisfaction'] <= 80) $fantext = FANSATISFACTION_TEXT_5;
else if ($theteam['fansatisfaction'] <= 90) $fantext = FANSATISFACTION_TEXT_6;
else if ($theteam['fansatisfaction'] <= 100) $fantext = FANSATISFACTION_TEXT_7;
table_row(FAN_SATISFACTION, create_progress_bar($theteam['fansatisfaction']).$fantext);
table_row(FAN_BASE, $theteam['fanbase']);
if (empty($usr['registred'])) table_row(FOUNDED, $config['started']);
else table_row(FOUNDED, $usr['registred']);
$div = sql_get("SELECT `id` FROM `match_type` WHERE `name` = '{$theteam['league']}'", __FILE__, __LINE__);
table_row(DIVISION, "<a href='leagueranking.php?id={$div}'>".substr($theteam['league'], 1)."</a>");
if ($theteam['cup'] == 'yes') table_row(CUP, div("img_plain_yes", YES));
else table_row(CUP, div("img_plain_no", NO));
table_row(LEAGUE.": ".GOAL_DIFFERENCE, "{$theteam['goalsscored']} - {$theteam['goalsconceded']}");
table_row(LEAGUE.": "."+ / = / -", "{$theteam['wins']} / {$theteam['draws']} / {$theteam['loses']}");
table_row(PLAYERS, $players);
if ($usr['id'] == $USER['id'] || limit_cover(UC_MODERATOR)) table_row(MONEY, shortnumber($theteam['money'])." €");
table_row(TEAMSPIRIT, create_progress_bar($theteam['teamspirit']));
if (!file_exists("static/sounds/{$theteam['id']}.mp3")) table_row("Химн на отбора", "Този отбор няма химн!");
else table_row("Химн на отбора", "<span id='preview'>Трябва да имате инсталиран флаш, за да можете да прослушвате химните</span>
<script type='text/javascript' src='static/swfobject.js'></script>
<script type='text/javascript'>
	var s1 = new SWFObject('static/player.swf','player','500','24','9');
	s1.addParam('allowfullscreen','true');
	s1.addParam('allowscriptaccess','always');
	s1.addParam('flashvars','file=./static/sounds/{$theteam['id']}.mp3');
	s1.write('preview');
</script>");
table_row(KITS, "<img id='homekits' src='aj_get_kit.php?type=home&c1={$theteam['hometshirt']}&c2={$theteam['homeshorts']}&c3={$theteam['homesocks']}&dont=yes' alt='Home team kits'/><img id='homekits' src='aj_get_kit.php?type=away&c1={$theteam['awaytshirt']}&c2={$theteam['awayshorts']}&c3={$theteam['awaysocks']}&dont=yes' alt='Away team kits'/>");

table_end();
create_button("teamresults.php?id={$id}", RESULTS, false, false);
create_button("teamfixtures.php?id={$id}", FIXTURES, false, false);
create_button("teamhistory.php?id={$id}", HISTORY, false, false);
create_button("playersview.php?id={$id}", OVERVIEW, false, false);

br(2);

head("Пресконференция");
if ($usr['pressconference'])
{
	$data = unserialize($usr['pressconference']);
	if ($data)
	{
		table_start(false);
		foreach($data as $q => $a)
		{
			$q = htmlspecialchars(str_replace(array("www", "http", "_"), " ", $q));
			$a = htmlspecialchars(str_replace(array("www", "http"), " ", $a));
			print("<tr>");
			print("<th style=\"white-space: normal;\">{$q}</th>");
			print("<td class=\"tb\" style=\"white-space: normal;\">{$a}</td></tr>");
		}
		table_end();
	}
	else print("<b>Този отбор още не е правил пресконференция.</b><br />");
}
else print("<b>Този отбор още не е правил пресконференция.</b><br />");

br(0);

head(TROPHIES);
table_start();
table_startrow();
table_cell(get_trophies($id), 1, "tbwhite");
table_endrow();
table_end();


$matches = sql_query("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`, (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname`, (SELECT `name` FROM `match_type` WHERE `id` = `match`.`type`) AS `typename` FROM `match` WHERE (`hometeam` = {$TEAM['id']} AND `awayteam` = {$id}) OR (`hometeam` = {$id} AND `awayteam` = {$TEAM['id']}) ORDER BY `start` ASC", __FILE__, __LINE__);
if (mysql_num_rows($matches) > 0)
{
   head(GAMES);
   table_start();
   table_header(TYPE, START, HOME, AWAY, RESULT);
   while ($match = mysql_fetch_assoc($matches))
   {
      table_startrow();
      if ($match['typename'] != "") table_cell($match['typename']);
      else table_cell(FRIENDLY);
      table_cell($match['start']);
      table_cell(create_link("teamdetails.php?id={$match['hometeam']}", $match['homename']));
      table_cell(create_link("teamdetails.php?id={$match['awayteam']}", $match['awayname']));
      if ($match['played'] == 'yes') table_cell(create_link("matchreport.php?id={$match['id']}", $match['homescore']." - ".$match['awayscore']));
      else table_cell("&nbsp;&nbsp; - &nbsp;&nbsp;");
      table_endrow();
   }
   table_end();
}
if (limit_cover(UC_ADMIN))
{
   head(ADMINISTRATOR);
   print("Добавяне на пари:<br>");
   form_start("teamdetails.php?id={$id}&edit=1");
   print "Сума: ".input("textbox", "add", "0");
   print " Причина: ".input("textbox", "addr", "");
   print " ".input("submit", "", "Добави парите!");
   br(2);
   print("Глоба:<br>");
   form_start("teamdetails.php?id={$id}&edit=1");
   print "Сума: ".input("textbox", "remove", "0");
   print " Причина: ".input("textbox", "remover", "");
   print " ".input("submit", "", "Глоби!");
   form_end();
}
if ($usr['id']) sql_query("INSERT INTO `profileviews` (`user1`, `user2`, `type`, `time`) VALUES ('{$USER['id']}', '{$usr['id']}', 'teamdetails', NOW())", __FILE__, __LINE__);
pageend();
?>
