<?php
/*
File name: index.php
Last change: Thu Jan 24 11:36:27 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
if ($USER)
{
   pagestart(OVERVIEW);
   additionaldata(true);
   //head(OVERVIEW);
   table_start(true, 0, "");
   table_startrow();
   prnt("<td width=\"50%\" align=\"left\" valign=\"top\">");
   $country = sql_data("SELECT * FROM `countries` WHERE `id` = '{$USER['country']}'", __FILE__, __LINE__);
   $div = sql_get("SELECT `id` FROM `match_type` WHERE `name` = '{$TEAM['league']}'", __FILE__, __LINE__);
   table_start();
   table_startrow();
   table_th(TEAM, "2");
   table_endrow();
   table_row(NAME, create_link("teamdetails.php?id={$TEAM['id']}", "<b>{$TEAM['name']}</b>")." ".create_link("ranking.php?country={$USER['country']}", create_image("images/flags/{$country['flagpic']}", 20)." {$country['name']}"));
   table_row(FOUNDED, $USER['registred']);
   table_row(TEAMSPIRIT, create_progress_bar($TEAM['teamspirit']));
   table_row(FAN_BASE, $TEAM['fanbase']);
   if ($TEAM['fansatisfaction'] <= 10) $fantext = FANSATISFACTION_TEXT_1;
   else if ($TEAM['fansatisfaction'] <= 30) $fantext = FANSATISFACTION_TEXT_2;
   else if ($TEAM['fansatisfaction'] <= 40) $fantext = FANSATISFACTION_TEXT_3;
   else if ($TEAM['fansatisfaction'] <= 60) $fantext = FANSATISFACTION_TEXT_4;
   else if ($TEAM['fansatisfaction'] <= 80) $fantext = FANSATISFACTION_TEXT_5;
   else if ($TEAM['fansatisfaction'] <= 90) $fantext = FANSATISFACTION_TEXT_6;
   else if ($TEAM['fansatisfaction'] <= 100) $fantext = FANSATISFACTION_TEXT_7;
   table_row(FAN_SATISFACTION, create_progress_bar($TEAM['fansatisfaction']).$fantext);
   table_row(MONEY, create_link("moneyhistory.php", shortnumber($TEAM['money'])." ˆ"));
   table_row(DIVISION, create_link("leagueranking.php?id={$div}", substr($TEAM['league'], 1)));
   if ($TEAM['cup'] == 'yes') table_row(CUP, div("img_plain_yes", YES));
   else table_row(CUP, div("img_plain_no", NO));
   table_end();
   br();

   $recmessages = sql_get("SELECT COUNT(`id`) FROM `messages` WHERE `toid` = {$USER['id']}", __FILE__, __LINE__);
   $sentmessages = sql_get("SELECT COUNT(`id`) FROM `messages` WHERE `fromid` = {$USER['id']} AND `toid` != 0", __FILE__, __LINE__);
   table_start();
   table_startrow();
   table_th(MESSAGES, "2");
   table_endrow();
   table_row(INBOX, create_link("messages.php?do=inbox", "<b>{$recmessages} "._MESSAGES."</b>"));
   table_row(UNREAD, create_link("messages.php?do=unread",  "{$unreadmessages} "._MESSAGES));
   table_row(OUTBOX, create_link("messages.php?do=outbox",  "{$sentmessages} "._MESSAGES));
   table_end();
   br();

   $eastseats = calculate_seats($STADIUM['eastseats']);
   $westseats = calculate_seats($STADIUM['westseats']);
   $northseats = calculate_seats($STADIUM['northseats']);
   $southseats = calculate_seats($STADIUM['southseats']);
   $vipseats = calculate_vipseats($STADIUM['vipseats']);
   $allseats = $eastseats + $westseats + $northseats + $southseats + $vipseats;
   table_start();
   table_startrow();
   table_th(STADIUM, "2");
   table_endrow();
   table_row(NAME, create_link("stadium.php", "<b>{$STADIUM['name']}</b>"));
   table_row(SEATS, $allseats." (".create_link("stadiumseats.php?type=eastseats", "E:{$eastseats}")."/".create_link("stadiumseats.php?type=westseats", "W:{$westseats}")."/".create_link("stadiumseats.php?type=northseats", "N:{$northseats}")."/".create_link("stadiumseats.php?type=southseats", "S:{$southseats}")."/".create_link("stadiumseats.php?type=vipseats", "V:{$vipseats}").")");
   table_row(PARKINGS, create_link("stadiumupgrades.php?type=parkings", calculate_parkings($STADIUM['parking'])));
   table_row(BARS, create_link("stadiumupgrades.php?type=bars", calculate_bars($STADIUM['bars'])));
   table_row(TOILETS, create_link("stadiumupgrades.php?type=toilets", calculate_toilets($STADIUM['toilets'])));
   table_row(GRASS, create_link("stadiumupgrades.php?type=grass", calculate_grass($STADIUM['grass'])));
   table_row(LIGHTS, create_link("stadiumupgrades.php?type=lights", calculate_lights($STADIUM['lights'])));
   table_row(BOARDS, create_link("stadiumupgrades.php?type=boards", calculate_boards($STADIUM['boards'])));
   table_row(YOUTHCENTER, create_link("stadiumupgrades.php?type=youthcenter", calculate_youthcenter($STADIUM['youthcenter'])));
   table_row(ROOF, create_link("stadiumupgrades.php?type=roof", calculate_roof($STADIUM['roof'])));
   table_row(HEATER, create_link("stadiumupgrades.php?type=heater", calculate_heater($STADIUM['heater'])));
   table_row(SPRINKLER, create_link("stadiumupgrades.php?type=sprinkler", calculate_sprinkler($STADIUM['sprinkler'])));
   table_row(FAN_SHOP, create_link("stadiumupgrades.php?type=fanshop", calculate_fanshop($STADIUM['fanshop'])));
   table_end();

   prnt("</td><td width='50%' align='left' valign='top' style='padding-left:12px;'>");
?>
<center><script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/bg_BG"></script><script type="text/javascript">FB.init("61505f3947897a151b6dfa3881107668");</script><fb:fan profile_id="224612579388" stream="" connections="12" width="350" css="http://managerteams.com/fb.css"></fb:fan></center>
<?php
   table_start();
   table_startrow();
   table_th(MANAGER, "2");
   table_endrow();
   table_row(USERNAME, create_link("viewprofile.php?id={$USER['id']}", "<b>{$USER['username']}</b>"));
   table_row(REAL_NAME, $USER['realname']);
   table_row(EMAIL, create_link("mailto:{$USER['email']}", $USER['email']));
   table_row(LAST_LOGIN, $USER['lastlogin']);
   table_row(LAST_ACTION, get_date_time(false));
   table_row(CLASSS, get_user_class_name($USER['class']));
   table_row(POINTS, $USER['points']);
   table_row("+ / = / -", "{$USER['wins']} / {$USER['draws']} / {$USER['loses']}");
   table_row(GOAL_DIFFERENCE, "{$USER['goalsscored']} - {$USER['goalsconceded']}");
   table_end();
   br();

   table_start();
   table_startrow();
   table_th(PLAYERS, "2");
   table_endrow();
   table_row(ALL, create_link("playersview.php", "<b>{$players_} "._PLAYERS."</b>"));
   table_row(INJURED, create_link("playersview.php?type=injured", "{$injured_} "._PLAYERS));
   table_row(BANNED, create_link("playersview.php?type=banned", "{$banned_} "._PLAYERS));
   table_row(TRAINING, create_link("training.php", ($players_ - $injured_)." "._PLAYERS));
   table_end();

   prnt("</td>");
   table_endrow();
   table_end();
   br();
   ?>
   <div id="news_box">
      <div id="news_title"><?=NEWS?></div>
      <div id="news_content">
         <?php include("./cache/tdmiddleleft.cache"); ?>
      </div>
   </div><br><div></div>
   <?php
}
else
{
   pagestart(INDEX);
   head(START_TITLE);
   prnt(START_TEXT);
   ?>
   <object width="1" height="1">
      <embed src="include/fan_mp3.swf" width="1" height="1"></embed>
   </object>
   <div id="news_box">
      <div id="news_title"><?=NEWS?></div>
      <div id="news_content">
         <?php include("./cache/tdmiddleleft.cache"); ?>
      </div>
   </div>
   <?php
}
pageend();
?>
