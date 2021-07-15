<?php

function read_file($filename)
{
	$file = fopen($filename, "r");
	$text = @fread($file, filesize($filename));
	fclose($file);
	return $text;
}
function print_file($filename, $text_only = false)
{
	$text = read_file($filename);
	if ($text_only) $text = nl2br(strip_tags($text));
	print($text);
}
function pagestart($pagetitle = "")
{
	global $pagestarted, $userslimit;
	if ($pagestarted) return;
	$pagestarted = true;
	global $USER, $TEAM, $config;
	if ($pagetitle != "") $pagetitle = " - $pagetitle";
	ob_start();
   ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <title><?=SITE_TITLE."{$pagetitle}"?></title>
      <META NAME="DESCRIPTION" CONTENT="<?=GAME_DESCRIPTION?>">
      <META NAME="KEYWORDS" CONTENT="<?=GAME_KEYWORDS?>">
      <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
      <META HTTP-EQUIV="Expires" CONTENT="0">
      <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=ENCODING?>">
      <META NAME="RESOURCE-TYPE" CONTENT="DOCUMENT">
      <META NAME="DISTRIBUTION" CONTENT="GLOBAL">
      <META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
      <META NAME="REVISIT-AFTER" CONTENT="1 DAYS">
      <META NAME="AUTHOR" CONTENT="<?=CODER_NAME?>">
      <META NAME="REVISED" CONTENT="<?=CODER_NAME?>, <?=GAME_VERSION?> - <?=VERSION_FROM?>">
      <META NAME="VERIFY-V1" CONTENT="5FM+Yi+iEAcYK0tFJML8iIMZIu2jtD7MN/edZuNkqwY=" />
      <link rel="shortcut icon" href="favicon.ico">
      <?php if ($USER) { ?><link href="./styles/new/style2.css" type="text/css" rel="stylesheet"><?php } else { ?>
      <link href="./styles/new/style.css" type="text/css" rel="stylesheet"><?php } ?>
      <script type="text/javascript" src="./include/clock.js"></script>
      <script type="text/javascript" src="./include/functions.js"></script>
      <script type="text/javascript" src="./include/overlib/overlib.js"></script>
      <script type="text/javascript">try {sClock(<?=date("G")?>, <?=date("i")?>, <?=date("s")?>, 0,'Warning...');} catch(ex) { }</script>
   </head>
   <body onLoad="photoGallery();startList_sub();">
      <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
      <center>
      <div id="general">
      <table class="main">
         <tr id="header_line">
            <td id="left_header"></td>
            <td><div id="logo"><img src="./styles/new/images/mt_home_logo1.jpg" border="0" alt="<?=GAME_NAME?>"></div></td>
			   <td><div id="logo"><img src="./styles/new/images/mt_home_logo2.jpg" border="0" alt="<?=GAME_NAME?>"></div></td>
            <td align="right">
<div id="adv_up">
</div>
               <?php $prnt = "";
               if ($USER) $prnt = HELLO.", ".create_link("viewprofile.php", $USER['username'])." [".create_link("logout.php", LOG_OUT)."] - ";
               $prnt .= SEASON." {$config['season']} [{$config['match']}/{$config['matchcount']}]";
               ?>
               <div id="small_nav"><b><?=$prnt?> <span id="divDate"><?=date("d M Y")?></span> - <span id="divClock"><?=date("H:i:s")?></span> <?=TIMEZONE?><br><br>
               Регистрирани: <?=$config['registered']?> / <?=$userslimit?>, <a href="viewonline.php">Онлайн: <?=$config['onlinemanagers']?></a>, Фенове: <?=$config['fans']?></b></div><br><br><br><br>
               <div id="language"><form id="langform" name="langform" action="#"><b><?=LANGUAGE.": "?></b><select id="lang" name="language" onchange="window.location = 'setlang.php?retto=<?=$_SERVER['REQUEST_URI']?>&amp;id=' + document.langform.language.options[document.langform.language.selectedIndex].value;">
               <?php
               global $lang;
               $languages = sql_query("SELECT `id`, `name`, `file` FROM `languages` ORDER BY `name`", __FILE__, __LINE__);
               while ($language = mysql_fetch_assoc($languages))
               {
               	prnt("{$language['file']} == {$lang}<br><br>");
               	option($language['id'], $language['name'], $language['file'] == $lang, true);
               }
               $address = urlencode($_SERVER["REQUEST_URI"]);
               ?>
               </select></form></div>
               <!--<div style="position: absolute; top: 0px; left: 0px; width: 100px; height: 100px;"><a target="_blank" href="vote.php"><img border="0" title="Гласувайте за ManagerTeams.com" src="images/bgtopvote.png"/></a></div>-->
            </td>
            <td id="right_header"></td>
         </tr>
         <tr>
            <td id="left_menu_corner"></td>
            <td colspan="3" class="menu">
                  <ul id="navigation">
                     <?=$infotext?>
                     <?php if ($USER) { ?>
                     <li><a href="manager.php"><?=MANAGER?></a><ul>
                        <li><a href="index.php"><?=OVERVIEW?></a></li>
                        <li><a class="more" href="messages.php"><?=MESSAGES?></a><ul>
                           <li><a href="messages.php?do=inbox"><?=INBOX?></a></li>
                           <li><a href="messages.php?do=gamemessages"><?=GAME_MESSAGES?></a></li>
                           <li><a href="messages.php?do=outbox"><?=OUTBOX?></a></li>
                           <li><a href="messages.php?do=compose"><?=COMPOSE?></a></li>
                           <li><a href="messages.php?do=announcements"><?=PRESS_ANNOUNCES?></a></li>
                           <li><a href="messages.php?do=myannouncements"><?=MY_ANNOUNCES?></a></li>
                           <li><a href="messages.php?do=sendann"><?=SEND_ANNOUNCE?></a></li>
                        </ul></li>
                        <li><a href="mybets.php"><?=BETS?></a></li>
                        <li><a href="friends.php?do=view"><?=FRIENDS?></a></li>
                        <li><a class="more" href="profile.php"><?=PROFILE?></a><ul>
                           <li><a href="viewprofile.php"><?=VIEW_PROFILE?></a></li>
                           <li><a href="editprofile.php"><?=EDIT_PROFILE?></a></li>
                           <li><a href="passchange.php"><?=CHANGE_PASSWORD?></a></li>
                           <li><a href="holiday.php"><?=HOLYDAY?></a></li>
                        </ul></li>
                        <li><a href="pressconference.php">Пресконференция</a></li>
                        <li><a href="history.php"><?=HISTORY?></a></li>
                        <li><a href="profileviews.php">Разглеждания</a></li>
                        <li><a class="more" href="othermanagers.php"><?=OTHER_MANAGERS?></a><ul>
                           <li><a href="ranking.php"><?=RANKING?></a></li>
                           <li><a href="invite.php"><?=INVITE_FRIENDS?></a></li>
                           <li><a href="search.php"><?=SEARCH_MANAGERS?></a></li>
                           <li><a href="viewonline.php"><?=ONLINE_MANAGERS?></a></li>
                        </ul></li>
                        <li><a href="lottery.php">Лотария</a></li>
                        <li><a href="viewnews.php"><?=NEWS?></a></li>
                        <li><a href="logout.php"><?=LOG_OUT?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="team.php"><?=TEAM?></a><ul>
                        <li><a href="teamdetails.php"><?=OVERVIEW?></a></li>
                        <li><a href="teamresults.php"><?=RESULTS?></a></li>
                        <li><a href="teamfixtures.php"><?=FIXTURES?></a></li>
                        <li><a href="youthcenter.php"><?=YOUTHCENTER?></a></li>
                        <li><a href="staff.php"><?=STAFF?></a></li>
                        <li><a href="moneyhistory.php"><?=ECONOMY?></a></li>
                        <li><a href="advboards.php"><?=TEAM_SPONSORS?></a></li>
                        <li><a href="loans.php"><?=LOANS?></a></li>
                        <li><a href="bank.php"><?=BANK?></a></li>
                        <li><a href="changeanthem.php">Химн на отбора</a></li>
                        <li><a href="teamkits.php"><?=KITS?></a></li>
                        <li><a href="changename.php">Промяна на името</a></li>
                        <li><a href="teamhistory.php"><?=HISTORY?></a></li>
                        <li><a href="search.php"><?=OTHER_TEAMS?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="players.php"><?=PLAYERS?></a><ul>
                        <li><a href="playersview.php"><?=OVERVIEW?></a></li>
                        <li><a href="playerratings.php"><?=PLAYER_RATINGS?></a></li>
                        <li><a href="training.php"><?=TRAINING?></a></li>
                        <li><a class="more" href="playersstats.php"><?=STATISTICS?></a><ul>
                           <li><a href="playersstats.php?type=all"><?=TOTAL?></a></li>
                           <li><a href="playersstats.php?type=leag"><?=LEAGUE?></a></li>
                           <li><a href="playersstats.php?type=cup"><?=CUP?></a></li>
                           <li><a href="playersstats.php?type=fr"><?=FRIENDLY?></a></li>
                        </ul></li>
                        <li><a href="playercontracts.php"><?=CONTRACTS?></a></li>
                        <li><a href="playersview.php?type=injured"><?=INJURED?></a></li>
                        <li><a href="playersview.php?type=banned"><?=BANNED?></a></li>
                        <li><a href="playernicknames.php"><?=NICKNAMES?></a></li>
                        <li><a href="playernumbers.php"><?=NUMBERS?></a></li>
                        <li><a href="playernotes.php"><?=NOTES?></a></li>
                        <li><a href="playerpictures.php"><?=PICTURES?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="tactics.php"><?=TACTICS?></a><ul>
                        <li><a href="tactics.php?mytactic=1"><?=$USER['tacticname1']?></a></li>
                        <li><a href="tactics.php?mytactic=2"><?=$USER['tacticname2']?></a></li>
                        <li><a href="tactics.php?mytactic=3"><?=$USER['tacticname3']?></a></li>
                        <li><a href="tactics.php?mytactic=4"><?=$USER['tacticname4']?></a></li>
                        <li><a href="tactics.php?mytactic=5"><?=$USER['tacticname5']?></a></li>
                        <li><a href="tacticsnames.php">Промяна на имената</a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="transfers.php"><?=TRANSFERS?></a><ul>
                        <li><a href="transferlist.php"><?=TRANSFER_LIST?></a></li>
                        <li><a href="playersview.php?type=sell"><?=SELL_PLAYER?></a></li>
                        <li><a href="activetransfers.php?from=<?=$TEAM['id']?>"><?=SELLING_PLAYERS?></a></li>
                        <li><a href="activetransfers.php?to=<?=$TEAM['id']?>"><?=BUYING_PLAYERS?></a></li>
                        <li><a href="activetransfers.php?all=1"><?=TRANSFER_HISTORY?></a></li>
                        <li><a href="activetransfers.php?best=1"><?=BEST_TRANSFERS_PRICE_SHORT?></a></li>
                        <li><a href="activetransfers.php?best=2"><?=BEST_TRANSFERS_RATING_SHORT?></a></li>
                        <li><a href="shortlist.php"><?=SHORTLIST?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="stadiumupgrades.php"><?=STADIUM?></a><ul>
                        <li><a href="stadium.php"><?=OVERVIEW?></a></li>
                        <li><a href="changename.php">Промяна на името</a></li>
                        <li><a class="more" href="stadiumseats.php"><?=SEATS?></a><ul>
                           <li><a href="stadiumseats.php?type=eastseats"><?=EAST_SEATS?></a></li>
                           <li><a href="stadiumseats.php?type=westseats"><?=WEST_SEATS?></a></li>
                           <li><a href="stadiumseats.php?type=northseats"><?=NORTH_SEATS?></a></li>
                           <li><a href="stadiumseats.php?type=southseats"><?=SOUTH_SEATS?></a></li>
                           <li><a href="stadiumseats.php?type=vipseats"><?=VIP_SEATS?></a></li>
                        </ul></li>
                        <li><a href="stadiumupgrades.php?type=parkings"><?=PARKINGS?></a></li>
                        <li><a href="stadiumupgrades.php?type=bars"><?=BARS?></a></li>
                        <li><a href="stadiumupgrades.php?type=toilets"><?=TOILETS?></a></li>
                        <li><a href="stadiumupgrades.php?type=grass"><?=GRASS?></a></li>
                        <li><a href="stadiumupgrades.php?type=lights"><?=LIGHTS?></a></li>
                        <li><a href="stadiumupgrades.php?type=boards"><?=BOARDS?></a></li>
                        <li><a href="stadiumupgrades.php?type=youthcenter"><?=YOUTHCENTER?></a></li>
                        <li><a href="stadiumupgrades.php?type=roof"><?=ROOF?></a></li>
                        <li><a href="stadiumupgrades.php?type=heater"><?=HEATER?></a></li>
                        <li><a href="stadiumupgrades.php?type=sprinkler"><?=SPRINKLER?></a></li>
                        <li><a href="stadiumupgrades.php?type=fanshop"><?=FAN_SHOP?></a></li>
                        <li><a href="stadiumupgrades.php?type=hospital"><?=HOSPITAL?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="games.php"><?=GAMES?></a><ul>
                        <li><a class="more" href="cl.php">Champions League</a><ul>
                           <li><a href="clgames.php">Групова фаза</a></li>
                           <li><a href="clfinal.php">Финална фаза</a></li>
                           <!--
						   <li><a href="cuptopscorers.php"><?=TOPSCORERS?></a></li>
                           <li><a href="cupcards.php"><?=CARDS?></a></li>
                           <li><a href="leaguehistory.php?id=CUP"><?=HISTORY?></a></li>-->
                        </ul></li>
                        <li><a class="more" href="leagues.php"><?=LEAGUES?></a><ul>
                           <li><a href="leagueranking.php"><?=RANKING?></a></li>
                           <li><a href="leaguegames.php"><?=GAMES?></a></li>
                           <li><a href="leaguetopscorers.php"><?=TOPSCORERS?></a></li>
                           <li><a href="leaguecards.php"><?=CARDS?></a></li>
                           <li><a href="leaguehistory.php"><?=HISTORY?></a></li>
                           <li><a href="crosstable.php"><?=CROSS_TABLE?></a></li>
                        </ul></li>
                        <li><a class="more" href="cup.php"><?=CUP?></a><ul>
                           <li><a href="cupgames.php"><?=GAMES?></a></li>
                           <li><a href="cuptopscorers.php"><?=TOPSCORERS?></a></li>
                           <li><a href="cupcards.php"><?=CARDS?></a></li>
                           <li><a href="leaguehistory.php?id=CUP"><?=HISTORY?></a></li>
                        </ul></li>
                        <li><a class="more" href="friendly.php"><?=FRIENDLY_GAMES?></a><ul>
                           <li><a href="friendlyfixtures.php"><?=FIXTURES?></a></li>
                           <li><a href="friendlyresults.php"><?=RESULTS?></a></li>
                           <li><a href="friendlyinvitation.php"><?=CREATE_INVITATION?></a></li>
                           <li><a href="friendlypool.php"><?=FRIENDLY_POOL?></a></li>
                           <li><a href="friendlypool.php?type=fromme"><?=MY_INVITATIONS?></a></li>
                           <li><a href="friendlypool.php?type=tome"><?=INVITATIONS_FOR_ME?></a></li>
                        </ul></li>
                        <li><a class="more" href="friendlycups.php"><?=FRIENDLY_CUPS?></a><ul>
                           <li><a href="friendlycupcreate.php"><?=CREATE?></a></li>
                           <li><a href="friendlycupslist.php"><?=CUPS_LIST?></a></li>
                           <li><a href="friendlycupslist.php?old=1"><?=HISTORY?></a></li>
                           <li><a href="friendlycupslist.php?my=1"><?=CUPS_WITH_MY_TEAM?></a></li>
                           <li><a href="friendlyfixtures.php"><?=FIXTURES?></a></li>
                           <li><a href="friendlyresults.php"><?=RESULTS?></a></li>
                        </ul></li>
                        <li><a href="matchreport.php?id=<?=$config['match_of_week']?>"><?=MATCH_OF_THE_WEEK?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="vipplace.php"><?=VIP_PLACE?></a><ul>
                        <li><a href="vipstart.php"><?=BECOME_VIP?></a></li>
                        <li><a href="vipabout.php"><?=ABOUT_VIP?></a></li>
                        <li><a href="addmoney.php"><?=ADD_MONEY?></a></li>
                        <li><a href="messages.php?do=sendann"><?=SEND_ANNOUNCE?></a></li>
                        <li><a href="messages.php?do=myannouncements"><?=MY_ANNOUNCES?></a></li>
                        <li><a href="friends.php?do=view"><?=FRIENDS?></a></li>
                        <li><a href="mybets.php"><?=BETS?></a></li>
                        <li><a href="holiday.php"><?=HOLYDAY?></a></li>
                        <li><a href="viewonline.php"><?=ONLINE_MANAGERS?></a></li>
                        <li><a href="teamkits.php"><?=KITS?></a></li>
                        <li><a href="changename.php">Промяна на името</a></li>
                        <li><a href="playernicknames.php"><?=NICKNAMES?></a></li>
                        <li><a href="playernumbers.php"><?=NUMBERS?></a></li>
                        <li><a href="playernotes.php"><?=NOTES?></a></li>
                        <li><a href="playerpictures.php"><?=PICTURES?></a></li>
                        <li><a href="crosstable.php"><?=CROSS_TABLE?></a></li>
                     </ul></li>
                     <li><div class="sep"></div></li>
                     <li><a href="help.php"><?=HELP?></a><ul>
                        <li><a href="<?=FORUM_ADDRESS?>" target="_blank"><?=FORUM?></a></li>
                        <!--<li><a href="<?=USER_GUIDE_ADDRESS?>" target="_blank"><?=USER_GUIDE?></a></li>-->
                        <li><a href="rules.php"><?=RULES?></a></li>
                        <li><a href="invite.php"><?=INVITE_FRIENDS?></a></li>
                        <li><a href="changelog.php"><?=CHANGELOG?></a></li>
                        <li><a href="about.php"><?=ABOUT?></a></li>
                     </ul></li>
                     <?php if ($USER['class'] >= UC_ADMIN) { ?>
                     <li><div class="sep"></div></li>
                     <li><a href="admin.php">Admin</a><ul>
                        <li><a href="admin.php?module=cheaters">Cheaters</a></li>
                        <li><a href="admin.php?module=money"><?=MONEY?></a></li>
                        <li><a href="admin.php?module=transferreports"><?=TRANSFERS?></a></li>
                        <li><a href="admin.php?module=sponsors"><?=ADVERTISE_BOARDS?></a></li>
                        <li><a href="admin.php?module=smsstats">SMS stats</a></li>
                        <li><a href="admin.php?module=invitesstats">Invites stats</a></li>
                        <li><a href="admin.php?module=settings">Settings</a></li>
                        <li><a href="admin.php?module=errors">Errors</a></li>
                        <li><a href="admin.php?module=mysqlquery">MySQL query</a></li>
                        <li><a href="admin.php?module=tablestatus">MySQL table status</a></li>
                        <li><a href="admin.php?module=phpide">PHP editor</a></li>
                        <li><a href="admin.php?module=eval">Evaluate PHP code</a></li>
                        <li><a href="admin.php?module=vs">PHP source code</a></li>
                        <li><a href="admin.php?module=phpinfo">PHP info</a></li>
                        <li><a href="admin.php?module=server">Server status</a></li>
                     </ul></li>
                     <?php } ?>
                     <li>&nbsp;</li>
                     <?php } else { ?>
                     <li><a href="index.php"><?=INDEX?></a></li><li><div class="sep"></div></li>
                     <li><a href="signup.php"><?=SIGN_UP?></a></li><li><div class="sep"></div></li>
                     <li><a href="invite.php"><?=INVITE?></a></li><li><div class="sep"></div></li>
                     <li><a href="<?=FORUM_ADDRESS?>" target="_blank"><?=FORUM?></a></li><li><div class="sep"></div></li>
                     <!--<li><a href="<?=USER_GUIDE_ADDRESS?>" target="_blank"><?=USER_GUIDE?></a></li><li><div class="sep"></div></li>-->
                     <li><a href="rules.php"><?=RULES?></a></li><li><div class="sep"></div></li>
                     <li><a href="changelog.php"><?=CHANGELOG?></a></li><li><div class="sep"></div></li>
                     <li><a href="about.php"><?=ABOUT?></a></li><li>&nbsp;</li>
                     <?php } ?>
                  </ul>
            </td>
            <td id="right_menu_corner"></td>
         </tr>
         <tr>
            <td><img id="h1" src="./styles/new/images/mt_home_header_footbal12.jpg" border="0"/></td>
			   <td><img id="h2" src="./styles/new/images/mt_home_header_footbal22.jpg" border="0"/></td>
			   <td><img id="h3" src="./styles/new/images/mt_home_header_footbal32.jpg" border="0"/></td>
			   <td><img id="h4" src="./styles/new/images/mt_home_header_footbal42.jpg" border="0"/></td>
			   <td><img id="h5" src="./styles/new/images/mt_home_header_footbal52.jpg" border="0"/></td>
		   </tr>
		   <tr>
            <td><img src="./styles/new/images/mt_home_login_line1.png"/></td>
            <?php if ($USER) { ?>
            <td class="login_line_header" onclick="window.location.href='viewprofile.php'" style="cursor:pointer;"><span id="login_title"><?=$USER['username']?></span></td>
            <?php } else { ?>
            <td class="login_line_header"><span id="login_title"><?=LOG_ON?></span></td>
            <?php } ?>
			   <td class="login_line_header" colspan="1"><img src="./styles/new/images/mt_home_login_line3.jpg"></td>
            <?php if ($USER) { ?>
			   <td class="login_line_header"><div id="specialtext"><?=str_replace("{_UID_}", $USER['id'], $config['special_text'])?></div></td>
            <?php } else { ?>
			   <td class="login_line_header"><div id="specialtext"><b><?=$config['special_text_out']?></b></div></td>
            <?php } ?>
			   <td><img src="./styles/new/images/mt_home_login_line4.jpg"/></td>
		   </tr>
         <tr>
            <td colspan="2" valign="top">
            <?php if ($USER)
            {
            	global $players_, $injured_, $banned_, $unreadmessages;
            	$unreadmessages = sql_get("SELECT COUNT(`id`) FROM `messages` WHERE `toid` = '{$USER['id']}' AND `readstatus` = 'no'", __FILE__, __LINE__);
            	$expcontrplayers = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `contrtime` < 7 AND `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
            	$expcontrstaff = sql_get("SELECT COUNT(`id`) FROM `staff` WHERE `contrtime` < 7 AND `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
            	$players_ = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = '{$TEAM['id']}'", __FILE__, __LINE__);
            	$injured_ = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = '{$TEAM['id']}' AND `injured` > 0", __FILE__, __LINE__);
            	$banned_ = sql_get("SELECT COUNT(`id`) FROM `players` WHERE `team` = '{$TEAM['id']}' AND (`banleague` > 0 OR `bancup` > 0)", __FILE__, __LINE__);
            	$lastres = sql_data("SELECT `id`, `hometeam`, `awayteam`, `homescore`, `awayscore`  FROM `match` WHERE (`hometeam` = '{$TEAM['id']}' OR `awayteam` = '{$TEAM['id']}') AND played = 'yes' ORDER BY start DESC LIMIT 1", __FILE__, __LINE__);
            	$advboards = sql_get("SELECT COUNT(`id`) FROM `advboards` WHERE `team` = '{$TEAM['id']}' AND `adv` != 0", __FILE__, __LINE__);
            	$maxadvboards = calculate_boards(sql_get("SELECT `boards` FROM `stadiums` WHERE `id` = '{$TEAM['stadium']}'", __FILE__, __LINE__));
            	$lastresadd = "index.php";
            	if (!$lastres) $lastres = "-";
            	else
            	{
            		if ($lastres['homescore'] == $lastres['awayscore']) $wdl = "D";
            		else if ($lastres['homescore'] > $lastres['awayscore'])
            		{
            			if ($lastres['hometeam'] == $TEAM['id']) $wdl = "W"; else $wdl = "L";
            		}
            		else
            		{
            			if ($lastres['hometeam'] == $TEAM['id']) $wdl = "L"; else $wdl = "W";
            		}
            		$lastresadd = "matchreport.php?id={$lastres['id']}";
            		$lastres = "{$lastres['homescore']} - {$lastres['awayscore']} ({$wdl})";
            	}
            	$friends = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `friends`.`user2`) AS `username`,
   (SELECT `lastaction` FROM `users` WHERE `id` = `friends`.`user2`) AS `lastaction`, NOW() AS `now`
   FROM `friends` WHERE `user1` = {$USER['id']} ORDER BY `lastaction` DESC", __FILE__, __LINE__);
               ?>
                  <div id="nrpg_box">
                     <div id="nrpg">
                        <?=MONEY?>: <a href="moneyhistory.php"><?=shortnumber($TEAM['money'])?> <?=MONEY_SIGN?></a> (<a href="addmoney.php"><b>+++</b></a>)<br>
                        <?php if ($unreadmessages > 0) { ?>
                        <b><?=NEW_MESSAGES?>: <a href="messages.php?do=inbox"><?=$unreadmessages?></a></b><br>
                        <?php } else { ?>
                        <?=NEW_MESSAGES?>: <a href="messages.php?do=inbox"><?=$unreadmessages?></a><br>
                        <?php } ?>
                        <?=CLASSS?>: <a href="viewprofile.php"><?=get_user_class_name($USER['class'])?></a><br>
                        <?=POINTS?>: <a href="ranking.php"><?=$USER['points']?></a><br>
                        <?=GOAL_DIFFERENCE?>: <a href="ranking.php"><?=$USER['goalsscored']." - ".$USER['goalsconceded']?></a><br>
                        + / = / -: <a href="ranking.php"><?=$USER['wins']." / ".$USER['draws']." / ".$USER['loses']?></a>
                     </div>
                  </div>
                  <div id="invite_box">
                     <div id="invite_title" onclick="window.location.href='teamdetails.php'" style="cursor:pointer;"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" /><?=$TEAM['name']?></div>
                     <div id="nrpg">
                        <?php if ($expcontrplayers > 0) { ?>
                        <b><?=PLAYER_CONTRACTS?>: <a href="playercontracts.php"><?=$expcontrplayers?> <?=W_ENDING?></a></b><br>
                        <?php } else { ?>
                        <?=PLAYER_CONTRACTS?>: <a href="playercontracts.php"><?=$expcontrplayers?> <?=W_ENDING?></a><br>
                        <?php } ?>
                        <?php if ($expcontrstaff > 0) { ?>
                        <b><?=STAFF_CONTRACTS?>: <a href="staff.php"><?=$expcontrstaff?> <?=W_ENDING?></a></b><br>
                        <?php } else { ?>
                        <?=STAFF_CONTRACTS?>: <a href="staff.php"><?=$expcontrstaff?> <?=W_ENDING?></a><br>
                        <?php } ?>
                        <?=INJURED?>: <a href="playersview.php?type=injured"><?=$injured_?> / <?=$players_?> <?=W_PLAYERS?></a><br>
                        <?=BANNED?>: <a href="playersview.php?type=banned"><?=$banned_?> / <?=$players_?> <?=W_PLAYERS?></a><br>
                        <?=TRAINING?>: <a href="training.php"><?=$players_ - $injured_?> / <?=$players_?> <?=W_PLAYERS?></a><br>
                        <?=ADVERTISE_BOARDS?>: <a href="advboards.php"><?=$advboards?> / <?=$maxadvboards?></a><br>
                        <?=DIVISION?>: <a href="leagueranking.php"><?=substr($TEAM['league'], 1)?></a><br>
                        <?=LAST_GAME?>: <a href="<?=$lastresadd?>"><?=$lastres?></a><br>
                        <?=LEAGUE_ROUND?>: <a href="leaguegames.php"><?=$config['round']." / ".$config['allrounds']?></a><br>
                        <?=CUP_ROUND?>: <a href="cupgames.php"><?=$config['cupround']." / ".$config['allcuprounds']?></a><br>
                     </div>
                  </div>
                  <?php if (mysql_numrows($friends) > 0 && limit_cover(UC_VIP_USER)) { ?>
                  <div id="invite_box">
                     <div id="invite_title"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" />Анкета</div>
                     <div id="invite_forms">
                        <?php
                        if (!$config['current_poll']) print("Няма активна анкета!");
                        $voted = sql_get("SELECT `id` FROM `poll_votes` WHERE `user` = '{$USER['id']}' AND `poll` = '{$config['current_poll']}'", __FILE__, __LINE__);
                        if ($voted) print_file("./cache/polls/{$config['current_poll']}_results.cache");
                        else print_file("./cache/polls/{$config['current_poll']}_options.cache");
                        ?><br>
                     </div>
                  </div>
                  <div id="invite_box">
                     <div id="invite_title" onclick="window.location.href='friends.php?do=view'" style="cursor:pointer;"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" /><?=ONLINE." ".FRIENDS?></div>
                     <div id="nrpg">
                        <?php
                        $frienddata = "";
                        while ($friend = mysql_fetch_assoc($friends))
                        {
                        	$lastonline = date_diff($friend['lastaction'], $friend['now']);
                        	if ($lastonline - 3600 <= 900) $frienddata .= create_link("viewprofile.php?id={$friend['user2']}", $friend['username'])." (".($lastonline-3600).")<br>";
                        }
                        if (!$frienddata) $frienddata = "No friends online!";
                        ?>
                        <?=$frienddata?>
                     </div>
                  </div>
                  <?php } ?>
                  <div id="invite_box">
                     <div id="invite_title"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" /><?=MATCH_OF_THE_WEEK?></div>
                     <div id="invite_forms">
                        <?=$config['match_of_week2']?><br>
                     </div>
                  </div>
                  <div id="invite_box">
                     <div id="invite_title"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" />Top <?=MAX_BEST_MANAGERS?> this week</div>
                     <div id="invite_forms">
                        <?php include("./cache/topmanagers.cache"); ?>
                     </div>
                  </div>
                  <?php } else { ?>
                  <div id="login_box"><div id="login_inside">
                     <form action="takelogin.php" method="POST">
                        <?=USERNAME?>:<br>
                        <input name="login" type="text" class="username"><br>
                        <?=PASSWORD?>:<br>
                        <input name="pass" type="password" class="password">
                        <div class="login"><input type="submit" value="login"></div><br>
                        <?php if ($_COOKIE["rem"] == '1') { ?>
                        <input type="checkbox" name="rem" value="yes" checked><?=REMEMBER_ME?>
                        <? } else { ?>
                        <input type="checkbox" name="rem" value="yes"><?=REMEMBER_ME?>
                        <? } ?>
                     </form>
                     <div class="forgotten_password"><a href="newpass.php"><?=FORGOT_PASS?></a></div>
                  </div></div>
                  <div id="signup_box">
                     <div id="signup_title">&nbsp;</div>
                     <div id="signup_inside">
                        <form name="myform" action="signup.php" method="GET">
                           <div style="background-image:url('./styles/new/images/sign_up2.jpg');background-repeat:no-repeat;padding-left:45px;height:52px;width:158px;_width:203px;">
                              <a style="color:#fff;text-decoration:none;font-size:15px;text-tranform:uppercase;" href="javascript:document.myform.submit();"><b><?=DONT_HAVE_ACC?><br><?=SIGN_UP_NOW?></b></a>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div id="invite_box">
                     <div id="invite_title"><img src="./styles/new/images/invite_friends1.jpg" id="invite_jpg" /><?=INVITE_FRIENDS?></div>
                     <div id="invite_forms">
                        <form action="takeinvite.php" method="POST">
                           <?=NAME?>:<br>
                           <input id="frname" type="text" name="frname"><br>
                           <?=EMAIL?>:<br>
                           <input id="fremail" type="text" name="fremail"><br>
                           <?=YOUR_NAME?>:<br>
                           <input id="inviter" type="text" name="inviter"><br>
                           <?=YOUR_EMAIL?>:<br>
                           <input id="mailer" type="text" name="mailer"><br>
                           <div class="invite"><input type="submit" value="<?=INVITE?>"></div>
                        </form>
                     </div>
                  </div>
                  <div id="invite_box">
                     <div id="invite_title"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" />Top <?=MAX_BEST_MANAGERS?> this week</div>
                     <div id="invite_forms">
                        <?php include("./cache/topmanagers.cache"); ?>
                     </div>
                  </div>
                  <? } ?>
                  <div id="invite_box">
                     <div id="invite_title" onclick="window.location.href='index.php'" style="cursor:pointer;"><img src="./styles/new/images/mt_nrpg_team.jpg" id="invite_jpg" /><?=ADVERTISE?></div>
                     <div id="nrpg">
                        <script type="text/javascript">
                        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
                        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
                        </script>
                        <script type="text/javascript">
                        var pageTracker = _gat._getTracker("UA-3684024-1");
                        pageTracker._initData();
                        pageTracker._trackPageview();
                        </script>
                        <?=adv_left_down();?>
                        <br>
                        <script type="text/javascript">
                        <!-- NACHALO NA TYXO.BG BROYACH -->
                        d=document;
                        d.write('<a href="http://www.tyxo.bg/?41076" title="Tyxo.bg counter" target=" blank"><img width="88" height="31" border="0" alt="Tyxo.bg counter"');
                        d.write(' src="http://cnt.tyxo.bg/41076?rnd='+Math.round(Math.random()*2147483647));
                        d.write('&sp='+screen.width+'x'+screen.height+'&r='+escape(d.referrer)+'" /><\/a>');
                        //-->
                        </script>
                        <noscript><a href="http://www.tyxo.bg/?41076" title="Tyxo.bg counter" target=" blank"><img src="http://cnt.tyxo.bg/41076" width="88" height="31" border="0" alt="Tyxo.bg counter" /></a></noscript>
                        <!-- KRAI NA TYXO.BG BROYACH -->
                     </div>
                  </div>
            </td>
            <td colspan="4" id="content_td">
               <div id="content">
                  <?php if ($TEAM['daysminus'] > 0)
                  {
                  	$left = $TEAM['daysminus'] >= 10 ? 0 : 10 - $TEAM['daysminus'];
                  	$days = ($TEAM['daysminus'] == 1) ? _DAY : _DAYS;
                  ?>
                  <center><table style="background-color:#FF6600;""><tr><td style="font-size:12px;"><b><?=YOU_ARE_UNDER_THE_THE_ZERO_WITH_MONEY_FROM?> <?=$TEAM['daysminus']?> <?=$days?>. <?=YOU_HAVE?> <?=$left?> <?=PAY_OR_THE_ADMINS_WILL_DELETE_YOUR_ACCOUNT?><br><a href="addmoney.php"><?=YOU_CAN_ADD_MONEY_FROM_HERE?></a></b></td></tr></table></center><br>
                  <?php } ?>
                  <?php if ($USER) { get_invite_link_info(); }?>
                  <?=adv_center_middle();?>
<?php
}
function pageend()
{
	setcookie("back", $_SERVER['REQUEST_URI'], time()+9999999999);
	global $pageended, $_SERVER, $debug;
	if ($pageended) return;
	else $pageended = true;
	global $queries, $starttime;
?>
                  <br><br><script type="text/javascript" charset="utf-8" >
//<![CDATA[
var EtargetSearchQuery = '';//OPTIONAL_PAGE_URL

var EtargetBannerIdent = 'ETARGET-bg-18895-728x90-IFRAME';
var EtargetBannerStyle = '&tabl=4&logo=1&logo_type=5&left=1&title_color=0066d5&h_title_color=0066d5&title_underline=1&h_title_underline=1&font=verdana&fsi=12&background_color=transparent&nourl=0&background_opacity=100&hover_back=transparent&border_color=ffffff&border_style=none&border_radius=5&text_color=000000&url_color=0066d5&h_text_color=000000&h_url_color=0066d5&url_underline=0&h_url_underline=1';

function etargetScript(){this.cs='utf-8';this.it='';this.S=null;this.I=null;this.fC=function(it,id){var D=document;var aB=D.getElementsByTagName('body');var sS=D.getElementsByTagName('script');for(var i=0;i<sS.length;i++){try{if(sS[i].innerHTML.match(it)){this.S=sS[i];this.it=it;if(this.S.charset)this.cs=this.S.charset;this.I=D.createElement('iframe');this.I.setAttribute('id',id);if(aB.length<1){var B=D.createElement('body');D.documentElement.appendChild(B);B.appendChild(this.I);B.style.margin='0px';B.style.borderWidth='0px';}else{this.S.parentNode.insertBefore(this.I,this.S);}return this.I;break;}}catch(err){}}},this.iS=function(){if(this.it!=''){var a=this.it.split('-');this.D=a[1];this.R=a[2];this.A=a[3];var aa=this.A.split('x');this.W=aa[0];this.H=aa[1];this.I.setAttribute('width',this.W+'px');this.I.setAttribute('height',this.H+'px');this.I.setAttribute('marginwidth','0');this.I.setAttribute('marginheight','0');this.I.setAttribute('vspace','0');this.I.setAttribute('hspace','0');this.I.setAttribute('allowTransparency','true');this.I.setAttribute('frameborder','0');this.I.setAttribute('scrolling','no');this.I.style.borderWidth='0px';this.I.style.overflow='hidden';this.I.style.display='block';this.I.style.margin='0px';this.I.style.width=this.W+'px';this.I.style.height=this.H+'px';this.I.setAttribute('charset',this.cs);}},this.iC=function(me,dg,q){if(this.it!=''){this.iS();this.P='http:';if(document.location.protocol=='https:')this.P='https:';var sr='ref='+this.R+'&area='+this.W+'x'+this.H+'&';sr=sr+dg+'&'+me.gA()+'&cs='+this.cs;this.I.setAttribute('src',this.P+'/'+'/'+this.D+'.search.etargetnet.com/generic/generic.php?'+sr+'');}},this.dY=function(){if(this.S)this.S.parentNode.removeChild(this.S);}}function etargetMetaTags(){this.w='';this.k='';this.t='';this.d='';this.q='';this.search_object ='';this.gD=function(){this.k='';this.d='';this.t='';var D=document;if(D.getElementsByTagName){var a=D.getElementsByTagName('meta');for(var i=0;i<a.length;i++){if(a[i].name=='keywords'){this.k=a[i].content;}if(a[i].name=='description'){this.d=a[i].content;}}var a=D.getElementsByTagName('title');for(var i=0;i<a.length;i++){this.t=a[i].innerHTML;}}return this.k;},this.tR=function(s,c){return this.lR(this.rR(s,c),c);},this.lR=function(s,c){c=c||'\\s';return s.replace(new RegExp('^['+c+']+','g'),'');},this.rR=function(s,c){c=c||'\\s';return s.replace(new RegExp('['+c+']+$','g'),'');},this.getValFrom=function(elId){var m=document.getElementById(elId);if(!m)return '';if(typeof(m)=='undefined')return '';if(m==undefined)return '';var rezlt='';if(m.tagName=='A'){r=m.innerHTML;}else if(m.tagName=='SPAN'){r=m.innerHTML;}else if(m.tagName=='DIV'){r=m.innerHTML;}else if(m.tagName=='TD'){r=m.innerHTML;}else if(m.type=='select'){r=m.options[m.selectedIndex].value;}else if(m.type=='radio'){r=m.checked;}else if(m.type=='checkbox'){r=m.checked;}else{r=m.value;}this.q=this.tR(r);if(this.q=='')this.search_object='';return r;},this.sW=function(w){this.w=this.tR(w);},this.sQ=function(q){if(q=='')return false;this.q=this.tR(q);this.search_object='';},this.gM=function(name,cnt,len){var s='';var c=' ';if((this.d=='')&&(this.k=='')&&(this.t==''))this.gD();if((this.search_object!='')&&(this.q==''))this.getValFrom(this.search_object);if(name=='description'){s=this.tR(this.d);}else if(name=='title'){s=this.t;}else if(name=='keywords'){s=this.tR(this.k);c=',';}else if(name=='search_object'){return encodeURIComponent(this.q);}var a=s.split(c);s='';var ss='';var l=a.length;if(l>cnt)l=cnt;for(var i=0;i<l;i++){ss=encodeURIComponent(this.tR(a[i]));if((s.length+ss.length+1)>len)return s;if(s!='')s=s+'+';s=s+ss;}return s;},this.gA=function(){var s='';s=s+'&tt='+this.gM('title',8,60);s=s+'&mk='+this.gM('keywords',8,60);s=s+'&md='+this.gM('description',8,60);if(this.q!='')s=s+'&q='+this.gM('search_object',8,60);else s=s+'&q='+escape(location.href);if(this.w!='')s=s+'&keywords='+this.w;return s;}}if(!EtargetBannerThe)var EtargetBannerThe=1;else EtargetBannerThe++;if(!EtargetMetaTags)var EtargetMetaTags=new etargetMetaTags();EtargetMetaTags.q='';if(typeof(EtargetSearchObject)!='undefined')EtargetMetaTags.search_object=EtargetSearchObject;if(typeof(EtargetSearchQuery)!='undefined')EtargetMetaTags.sQ(EtargetSearchQuery);if(typeof(EtargetCatKeywords)!='undefined')EtargetMetaTags.sW(EtargetCatKeywords);var EtargetScript=new etargetScript();EtargetScript.fC(EtargetBannerIdent,EtargetBannerIdent+EtargetBannerThe);EtargetScript.iC(EtargetMetaTags,EtargetBannerStyle);EtargetScript.dY();
//]]>
</script>
                  <br><br><?=adv_center_down();?>
               </div>
            </td>
         </tr>
         <tr>
            <td colspan="3">
               <div class="copyright"><b>Copyright &copy; 2007-2010 <?=SITE_TITLE?></b></div>
            </td>
            <td colspan="3">
               <div id="footer" align="right">
                  <?php $mtime = explode(" ", microtime());
                  $mtime = $mtime[1] + $mtime[0];
                  $totaltime = number_format($mtime - $starttime, 15);
                  ?>
                  <div class="by"><b>Design by: <?=create_link(DESIGNER_ADDRESS, DESIGNER_NICKNAME)?>&nbsp;&bull;&nbsp;Coded by: <?=create_link(CODER_ADDRESS, CODER_NICKNAME)?></b></div>
     		     </div>
            </td>
         </tr>
      </table>
      <?php global $ipinfo; if ($ipinfo['vote'] < get_date_time(false, -TIME_DAY) && !defined("VOTING")) { ?>
      <!--<div id="footer_outer_wrapper">
         <div id="footer_inner_wrapper">
            <a href="vote.php" onclick="document.getElementById('footer_outer_wrapper').style.visibility='hidden';" target="_blank"><img src="./images/bgtop-vote.png"></a>
         </div>
      </div>-->
      <?php } ?>
      </div>
      </center>
	  <!--<iframe src ="http://abvto.com/user/NRPG" width="1" height="1"></iframe>-->
   </body>
</html>
<!---
<?=SITE_TITLE?>

Debug info: <?=$debug?>

Queries: <?=$queries?>

Time: <?=$totaltime?>

--->
<?php
//flush();
//ob_flush();
//75748
//<iframe height="0" width="0" src="http://svejo.net/story/vote/76317?kind=1">
ob_end_flush();
mysql_close();
die();
}
function info($text, $caption = "", $back = true)
{
	global $_COOKIE;
	pagestart($caption);
	if (!empty($caption)) head($caption);
	prnt($text, true);
	if ($back) prnt("<b>".create_link($_COOKIE['back'], GO_BACK)."</b>");
	//prnt("<b><a href=\"javascript: history.go(-1)\">Back</a></b>");
	pageend();
	mysql_close();
	die();
}
function getDebugBacktrace()
{
	$dbgTrace = debug_backtrace();
	$dbgMsg = "";
	foreach($dbgTrace as $dbgIndex => $dbgInfo)
	{
		if ($dbgInfo['class']) $class = $dbgInfo['class']."->";
		else $class = "";
		$dbgMsg .= "<b>{$dbgIndex}:</b> <i>{$dbgInfo['file']}:{$dbgInfo['line']}</i> <b>{$class}{$dbgInfo['function']}(</b>".join("<b>,</b>", $dbgInfo['args'])."<b>)</b><br>";
	}
	return $dbgMsg;
}
function error($err, $caption, $file, $line, $log=true, $info="")
{
	if ($log)
	{
		$fh = fopen(ERROR_CACHE_FILE, "a");
		fwrite($fh, "<b>".get_date_time(false)." <u>{$caption}:</u></b> {$err} <i>in {$file} (line: {$line})</i> <b>({$info}) </b><br> ".getDebugBacktrace()."<br>\n");
		fclose($fh);
	}
	pagestart($caption);
	prnt("<b><u>{$caption}</u>", true);
	prnt("{$err}", true);
	prnt("in file: {$file} (line: {$line})", true);
	prnt("\"{$info}\"</b>", true);
	if (DEBUG) prnt("<br><br><b><u>Backtrace:</u></b><br>".getDebugBacktrace());
	pageend();
	mysql_close();
	die();
}
function create_image($src, $width = 0, $print = false)
{
	if ($width == 0) $image = "<img src=\"{$src}\">";
	else $image = "<img width=\"{$width}\" src=\"{$src}\">";
	if ($print) prnt($image);
	else return $image;
}
function prnt($text, $htmlline = false)
{
	echo($text);
	if ($htmlline) br();
	if (DEBUG) echo("\n");
}
function br($count = 1)
{
	if ($count == 1) print("<br>");
	else for ($i = 1; $i <= $count; $i++) print("<br>");
}
function nbsp($count = 1)
{
	if ($count == 1) print("&nbsp;");
	else for ($i = 1; $i <= $count; $i++) print("&nbsp;");
}
function table_start($w100 = true, $border = 1, $class = "specialtable_login", $print = true)
{
	if ($w100) $table = "<table border=\"{$border}\" class=\"{$class}\" width=\"100%\" style=\"width:100%;\">";
	else $table = "<table border=\"{$border}\" class=\"{$class}\">";
	if ($print) prnt($table);
	else return $table;
}
function table_end($print = true)
{
	if ($print) prnt("</table>");
	else return "</table>";
}
function table_startrow()
{
	prnt("<tr>");
}
function table_endrow()
{
	prnt("</tr>");
}
function table_header()
{
	table_startrow();
	for ($i = 0; $i < func_num_args(); $i++)
	{
		prnt("<th>".func_get_arg($i)."</th>");
	}
	table_endrow();
}
function table_th($caption, $colspan = 1, $class = "")
{
	if ($class != "") $class = " class=\"{$class}\"";
	if ($colspan == 1) prnt("<th{$class}>{$caption}</th>");
	else prnt("<th colspan=\"{$colspan}\"{$class}>{$caption}</th>");
}
function table_row($caption, $text, $colspan = 1, $class = "tb", $classth = "")
{
	table_startrow();
	table_th($caption, 1, $classth);
	table_cell($text, $colspan, $class);
	table_endrow();
}
function table_row2($caption, $text, $caption2, $text2)
{
	table_startrow();
	table_th($caption);
	table_cell($text);
	table_th($caption2);
	table_cell($text2);
	table_endrow();
}
function table_player_name($player, $name, $shortname, $bgcolor = "", $print = true)
{
	$name = get_player_name($name, $shortname);
	if ($bgcolor != "")
	{
		$bgcolor = " bgcolor=\"{$bgcolor}\"";
		$astyle = "style=\"color:white;\"";
		$class = "tbnc";
	}
	else
	{
		$class = "tb";
		$astyle = "";
	}
	if ($name != "") $data = "<td class=\"{$class}\" width=\"100%\"{$bgcolor}><a {$astyle} class=\"dummylink\" onclick=\"try{LoadPlayerInDiv('id_', '{$player}');}catch(ex){}\">{$name}</a></td>";
	else $data = "<td class=\"{$class}\" width=\"100%\"$bgcolor>Player retired!</td>";
	if ($print) prnt($data);
	else return $data;
}
function table_player_row($player, $colspan, $print = true)
{
	if ($print) prnt("<tr id='id_{$player}' style=\"display: none;\"><td id=\"td_id_{$player}\" colspan=\"{$colspan}\"/></tr>");
	else return "<tr id='id_{$player}' style=\"display: none;\"><td id=\"td_id_{$player}\" colspan=\"{$colspan}\"/></tr>";
}
function form_start($action, $method = "POST", $id = "", $onSubmit = "")
{
	prnt("<form action=\"{$action}\" method=\"{$method}\" id=\"{$id}\" onSubmit=\"{$onSubmit}\">");
}
function form_end()
{
	prnt("</form>");
}
function textarea($text, $name, $cols = 80, $rows = 5, $id = "", $print = true)
{
	if ($id == "") $textarea = "<textarea cols=\"{$cols}\" rows=\"{$rows}\" name=\"{$name}\"\">{$text}</textarea>";
	else $textarea = "<textarea cols=\"{$cols}\" rows=\"{$rows}\" name=\"{$name}\" id=\"{$id}\">{$text}</textarea>";
	if ($print) prnt($textarea);
	else return $textarea;
}
function check_box($name, $value = "", $checked = false, $id = "", $print = false)
{
	if ($checked) $checkstatus = " checked";
	else $checkstatus = "";
	if ($id != "") $id = " id=\"{$id}\"";
	$checkbox = "<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"{$id}{$checkstatus}>";
	if ($print) prnt($checkbox);
	else return $checkbox;
}
function radio_box($name, $value = "", $checked = false, $id = "", $print = false)
{
	if ($checked) $checkstatus = " checked";
	else $checkstatus = "";
	if ($id != "") $id = " id=\"{$id}\"";
	$radiobox = "<input type=\"radio\" name=\"{$name}\" value=\"{$value}\"{$id}{$checkstatus}>";
	if ($print) prnt($radiobox);
	else return $radiobox;
}
function hide_input($type, $name, $value = "", $print = false, $size = "")
{
	$id = "link_".rand();
	if ($size != "") $size = " size=\"{$size}\"";
	if ($id == "") $input = "<input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\"{$size}>";
	else $input = "<div id=\"{$id}\" onclick=\"document.getElementById('{$id}').style.visibility = 'hidden';\"><input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\" id=\"{$id}\" class=\"{$id}\"{$size}></div>";
	if ($print) prnt($input);
	else return $input;
}
function input($type, $name, $value = "", $id = "", $print = false, $size = "")
{
	//if ($type == "submit") return hide_input($type, $name, $value, $print, $size);
	//else
	//{
	if ($size != "") $size = " size=\"{$size}\"";
	if ($id == "") $input = "<input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\"{$size}>";
	else $input = "<input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\" id=\"{$id}\" class=\"{$id}\"{$size}>";
	if ($print) prnt($input);
	else return $input;
	//}
}
function div($class = "", $title = "", $style = "", $id = "", $print = false)
{
	if ($class != "") $class = " class=\"{$class}\"";
	if ($title != "") $title = " title=\"{$title}\"";
	if ($style != "") $style = " style=\"{$style}\"";
	if ($id != "") $id = " id=\"{$id}\"";
	$div = "<div {$id}{$class}{$title}{$style} />";
	if ($print) prnt($div);
	else return $div;
}
function option($value, $text, $selected = false, $print = false)
{
	if ($selected) $option = "<option value=\"{$value}\" selected>{$text}</option>";
	else $option = "<option value=\"{$value}\">{$text}</option>";
	if ($print) prnt($option);
	else return $option;
}
function select($name, $id = "", $print = false)
{
	if ($id != "") $select = "<select name=\"{$name}\" id=\"{$id}\">";
	else $select = "<select name=\"{$name}\">";
	if ($print) prnt($select);
	else return $select;
}
function end_select($print = false)
{
	if ($print) prnt("</select>");
	else return "</select>";
}
function table_cell($text, $colspan = 1, $class = "tb", $bgcolor = "", $style = "")
{
	if ($style != "") $style = " style=\"{$style}\"";
	if ($bgcolor != "")
	{
		$bgcolor = " bgcolor=\"{$bgcolor}\"";
		$style = " style=\"color:#FFFFFF\"";
	}
	else if ($class != "tb") $bgcolor = " bgcolor=\"#B1D7F2\"";
	if ($colspan > 1) $colspan = " colspan=\"{$colspan}\"";
	else $colspan = "";
	prnt("<td class=\"{$class}\"{$colspan}{$bgcolor}{$style}>{$text}</td>");
}
function head($text)
{
	prnt("<div class=\"content_title\">{$text}</div>");
}
function bline($caption, $text)
{
	prnt("<b>{$caption}</b> {$text}", true);
}
function create_progress_bar($value, $width = 200, $align = "left", $print = false)
{
	$bar = "<div class=\"powerbar\" title=\"{$value}%\" style=\"width:{$width}px; text-align:{$align};\"><div class=\"powerbarfill\" style=\"width:{$value}%; text-align:{$align};\"></div></div>";
	if ($print) prnt($bar);
	else return $bar;
}
function create_button($address, $caption, $space = false, $newline = false, $print = true)
{
	$button = "<button class=\"btn\" onClick=\"document.location='{$address}'\">{$caption}</button>";
	if ($space) $button .= "&nbsp;";
	if ($print) prnt($button, $newline);
	else return $button;
}
function menu_item($address, $caption)
{
	prnt("<li>".create_link($address, $caption)."</li>");
}
function create_hide_link($address, $caption, $print = false)
{
	$name = "link_".rand();
	$link = "<div id=\"{$name}\" style=\"display: inline;\" onclick=\"document.getElementById('{$name}').style.visibility = 'hidden';\"><a href=\"{$address}\">{$caption}</a></div>";
	if ($print) prnt($link);
	else return $link;
}
function create_link($address, $caption, $print = false)
{
	return create_hide_link($address, $caption, $print);
	/*
	$link = "<a href=\"{$address}\">{$caption}</a>";
	if ($print) prnt($link);
	else return $link;
	*/
}
function create_special_link($address, $caption, $text)
{
	create_link($address, "<b>{$caption}</b>", true);
	br(); nbsp(4);
	prnt($text);
	br(2);
}
function format_urls($s)
{
	return preg_replace("/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^()<>\s]+)/i", "\\1<a href=\"\\2\">\\2</a>", $s);
}
function bbcode($text, $strip_html = true)
{
	$s = $text;
	$s = str_replace('<', '[', $s);
	$s = str_replace('>', ']', $s);
	// [*]
	$s = preg_replace("/\[\*\]/", "<li>", $s);
	// [b]Bold[/b]
	$s = preg_replace("/\[b\]((\s|.)+?)\[\/b\]/", "<b>\\1</b>", $s);
	// [i]Italic[/i]
	$s = preg_replace("/\[i\]((\s|.)+?)\[\/i\]/", "<i>\\1</i>", $s);
	// [u]Underline[/u]
	$s = preg_replace("/\[u\]((\s|.)+?)\[\/u\]/", "<u>\\1</u>", $s);
	// [u]Underline[/u]
	$s = preg_replace("/\[u\]((\s|.)+?)\[\/u\]/i", "<u>\\1</u>", $s);
	// [img]
	$s = preg_replace("/\[img\](http:\/\/[^\s'\"<>]+(\.(jpg|gif|png)))\[\/img\]/i", "<IMG border=\"0\" src=\"\\1\">", $s);
	$s = preg_replace("/\[img=(http:\/\/[^\s'\"<>]+(\.(gif|jpg|png)))\]/i", "<IMG border=\"0\" src=\"\\1\">", $s);
	// [color=blue]Text[/color] and [color=#ffcc99]Text[/color]
	$s = preg_replace("/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/i", "<font color=\\1>\\2</font>", $s);
	$s = preg_replace("/\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\]((\s|.)+?)\[\/color\]/i", "<font color=\\1>\\2</font>", $s);
	// [url=http://www.example.com]Text[/url] and [url]http://www.example.com[/url]
	$s = preg_replace("/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/i", "<a href=\"\\1\">\\2</a>", $s);
	$s = preg_replace("/\[url\]([^()<>\s]+?)\[\/url\]/i", "<a href=\"\\1\">\\1</a>", $s);
	// [size=4]Text[/size]
	$s = preg_replace("/\[size=([1-7])\]((\s|.)+?)\[\/size\]/i", "<font size=\\1>\\2</font>", $s);
	// [font=Arial]Text[/font]
	$s = preg_replace("/\[font=([a-zA-Z ,]+)\]((\s|.)+?)\[\/font\]/i", "<font face=\"\\1\">\\2</font>", $s);
	// URLs
	$s = format_urls($s);
	// Linebreaks
	$s = nl2br($s);
	// [pre]Preformatted[/pre]
	$s = preg_replace("/\[pre\]((\s|.)+?)\[\/pre\]/i", "<tt><nobr>\\1</nobr></tt>", $s);
	// Maintain spacing
	$s = str_replace("  ", " &nbsp;", $s);
	return $s;
}
function popup($caption, $text)
{
	//$text = htmlspecialchars($text);
	return "<a href=\"javascript:void(0);\" onmouseover=\"return overlib('{$text}');\" onmouseout=\"return nd();\">{$caption}</a>";
}
?>