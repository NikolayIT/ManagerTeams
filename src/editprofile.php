<?php
/*
File name: editprofile.php
Last change: Sat Jan 19 12:26:00 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("edit", true);
additionaldata(true);
if ($edit == "yes")
{
   //mkglobal("realname:teamname:stadname:sex:favteam:contlang:site:avatar:owntext:showmail:mailreports", true);
   mkglobal("realname:sex:favteam:contlang:site:avatar:owntext:showmail:mailreports", true);
   if (strlen($realname) < $realname_minlen) info(SHORT_REALNAME, ERROR);
   if (strlen($realname) > 50) info(LONG_REALNAME, ERROR);
   if (!is_valid_name($realname)) info(WRONG_REALNAME, ERROR);
   $realname2 = sqlsafe($realname);
   /*
   if (strlen($teamname) < $teamname_minlen) info(SHORT_TEAMNAME, ERROR);
   if (strlen($teamname) > 32) info(LONG_TEAMNAME, ERROR);
   if (!is_valid_name($teamname)) info(WRONG_TEAMNAME, ERROR);
   $teamname2 = sqlsafe($teamname);
   $ch = sql_get("SELECT COUNT(`id`) FROM `teams` WHERE `name` = {$teamname2}", __FILE__, __LINE__);
   if ($ch != 0) info(TEAMNAME_TAKEN, ERROR);

   if (strlen($stadname) < $stadiumname_minlen) info(SHORT_STADIUMNAME, ERROR);
   if (strlen($stadname) > 32) info(LONG_STADIUMNAME, ERROR);
   if (!is_valid_name($stadname)) info(WRONG_STADIUMNAME, ERROR);
   $stadname2 = sqlsafe($stadname);
   $ch = sql_get("SELECT COUNT(`id`) FROM `stadiums` WHERE `name` = {$stadname2}", __FILE__, __LINE__);
   if ($ch != 0) info(STADIUMNAME_TAKEN, ERROR);
   */
   if ($sex == "f") $sex2 = sqlsafe("Female");
   else $sex2 = sqlsafe("Male");
   $favteam2 = sqlsafe($favteam);
   $contlang2 = sqlsafe($contlang);
   $site2 = sqlsafe($site);
   $avatar2 = sqlsafe($avatar);
   $owntext2 = sqlsafe($owntext, false, false);
   $showmail2 = sqlsafe($showmail);
   $mailreports2 = sqlsafe($mailreports);
   sql_query("UPDATE `users` SET `realname` = {$realname2}, `sex` = {$sex2}, `favteam` = {$favteam2}, `site` = {$site2}, `avatar` = {$avatar2}, `owntext` = {$owntext2}, `contlang` = {$contlang2}, `showmail` = {$showmail2}, `mailreports` = {$mailreports2} WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
   //sql_query("UPDATE `teams` SET `name` = {$teamname2} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
   //sql_query("UPDATE `stadiums` SET `name` = {$stadname2} WHERE `id` = {$STADIUM['id']}", __FILE__, __LINE__);
   info(PROFILE_SUCCESSFULLY_CHANGED, SUCCESS, true);
}
else
{
   pagestart(EDIT_PROFILE);
   head(EDIT_PROFILE);
   form_start("editprofile.php", "POST");
   input("hidden", "edit", "yes", "", true);
   table_start(false);
   table_row(REAL_NAME, input("text", "realname", $USER['realname']));
   //table_row(TEAM_NAME, input("text", "teamname", $TEAM['name']));
   //table_row(STADIUM_NAME, input("text", "stadname", $STADIUM['name']));
   //table_row(EMAIL, input("text", "mail", $USER['email']));
   table_row(SEX, select("sex").option("m", MALE, $USER['sex'] == "Male").option("f", FEMALE, $USER['sex'] == "Female").end_select());
   table_row(FAVOURITE_TEAMS, input("text", "favteam", $USER['favteam']));
   table_row(CONTACT_LANGUAGES, input("text", "contlang", $USER['contlang']));
   table_row(WEB_SITE, input("text", "site", $USER['site']));
   table_row(AVATAR, input("text", "avatar", $USER['avatar']));
   table_row(OWN_TEXT, textarea($USER['owntext'], "owntext", 30, 4, "", false));
   table_row(SHOW_EMAIL_ADDRESS, check_box("showmail", "yes", $USER['showmail'] == "yes").SHOW_MY_EMAIL_ADDRESS);
   table_row(EMAIL_REPORTS, check_box("mailreports", "yes", $USER['mailreports'] == "yes").SEND_ME_EMAIL_REPORTS." (VIP Users only!)");
   table_startrow();
   table_th(input("reset", "", RESET, ""));
   table_th(input("submit", "", CHANGE, ""));
   table_endrow();
   table_end();
   form_end();
   pageend();
}
?>
