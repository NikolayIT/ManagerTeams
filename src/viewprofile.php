<?php
/*
File name: viewprofile.php
Last change: Sat Feb 09 12:17:51 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id");
if (!is_numeric($id) || $id < 0 || empty($id)) $id = $USER['id'];
$id = sqlsafe($id);
$usr = sql_data("SELECT *, (SELECT `name` FROM `teams` WHERE `id` = `users`.`team`) AS `teamname`, (SELECT `name` FROM `languages` WHERE `id` = `users`.`language`) AS `language` FROM `users` WHERE `id` = {$id}", __FILE__, __LINE__);
if (!$usr) info(WRONG_ID, ERROR);
$country = sql_data("SELECT * FROM `countries` WHERE `id` = {$usr['country']}", __FILE__, __LINE__);
$infriendlist = sql_get("SELECT `id` FROM `friends` WHERE `user1` = '{$USER['id']}' AND `user2` = '{$id}'", __FILE__, __LINE__);
if ($infriendlist) $friendstatus = " (".create_link("friends.php?do=remove&id={$infriendlist}", REMOVE_FROM_FRIENDS).")";
else $friendstatus = " (".create_link("friends.php?do=add&id={$usr['id']}", ADD_TO_FRIENDS).")";
if ($usr['class'] >= UC_VIP_USER) $star = "<img src=\"images/star.gif\" alt=\"VIP\">";
else $star = "";
pagestart(USER_PROFILE." - {$usr['username']}");
head(USER_PROFILE." - {$usr['username']} {$star}{$friendstatus}");
if (limit_cover(UC_ADMIN))
{
	prnt(create_link("vipstart.php?admin_vip_add=1&uid={$usr['id']}", "<b>Добави 30 дни ВИП статус</b><br /><br />"));
	prnt(create_link("vipstart.php?admin_vip_remove=1&uid={$usr['id']}", "<b>Премахни 30 дни ВИП статус</b><br /><br />"));
}
table_start();
table_row(USERNAME, "{$usr['username']} {$star} ({$usr['id']}) (".create_link("messages.php?do=compose&to={$usr['username']}", SEND_MESSAGE.")"));
table_row(TEAM, create_link("teamdetails.php?id={$usr['team']}", $usr['teamname']));
table_row(REGISTERED, $usr['registred']);
table_row(LAST_LOGIN, $usr['lastlogin']);
table_row(LAST_ACTION, $usr['lastaction']);
table_row(REAL_NAME, bbcode($usr['realname']));
table_row(SEX, $usr['sex']);
if ($usr['showmail'] =='yes' || limit_cover(UC_MODERATOR)) table_row(EMAIL, create_link("mailto:{$usr['email']}", $usr['email']));
if (limit_cover(UC_MODERATOR)) table_row(CLASSS, create_link("admin.php?module=userssearch&class={$usr['class']}", get_user_class_name($usr['class'])));
else table_row(CLASSS, get_user_class_name($usr['class']));
if ($usr['class'] == UC_VIP_USER) table_row("VIP Until", $usr['vipuntil']);
if (limit_cover(UC_MODERATOR)) table_row("IP", create_link("admin.php?module=ipinfo&ip={$usr['ip']}", $usr['ip']));
if (limit_cover(UC_MODERATOR)) table_row(LANGUAGE, $usr['language']);
table_row(COUNTRY, create_image("images/flags/{$country['flagpic']}", 20)." {$country['name']}");
table_row(CONTACT_LANGUAGES, bbcode($usr['contlang']));
table_row(POINTS, $usr['points']);
table_row(FAVOURITE_TEAMS, bbcode($usr['favteam']));
table_row(WEB_SITE, create_link($usr['site'], $usr['site']));
table_row(AVATAR, create_image($usr['avatar']));
table_row(OWN_TEXT, bbcode($usr['owntext']));
table_row(HISTORY, create_link("history.php?id={$usr['id']}", VIEW_MANAGER_HISTORY));
table_end();
br();
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
br();
head(LATEST_COMMENTS_FOR_THIS_USER);
$comments = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `comments`.`from`) AS `fromname` FROM `comments` WHERE `manager` = {$id} ORDER BY `time` DESC LIMIT 20", __FILE__, __LINE__);
if (mysql_num_rows($comments) == 0) prnt(NO_COMMENTS_FOR_THIS_USER);
else
{
   table_start();
   if (limit_cover(UC_MODERATOR)) table_header(TIME, MANAGER, COMMENT, DELETE);
   else table_header(TIME, MANAGER, COMMENT);
   while ($row = mysql_fetch_assoc($comments))
   {
      table_startrow();
      table_cell($row['time']);
      table_cell(create_link("viewprofile.php?id={$row['from']}", $row['fromname']));
      table_cell(bbcode($row['text']), 1, "tbwrap");
      if (limit_cover(UC_MODERATOR)) table_cell(create_link("comment.php?do=delete&id={$row['id']}", DELETE));
      table_endrow();
   }
   table_end();
   create_button("comment.php?do=showall&id={$id}", SHOW_ALL);
}
br(2);
head(POST_COMMENT);
form_start("comment.php?do=post&id={$id}", "POST");
table_start(false);
table_row(MESSAGE, textarea("", "text", 50, 6, "", false));
table_startrow();
table_th(input("submit", "", SEND), 2);
table_endrow();
table_end();
form_end();
if ($usr['id']) sql_query("INSERT INTO `profileviews` (`user1`, `user2`, `type`, `time`) VALUES ('{$USER['id']}', '{$usr['id']}', 'viewprofile', NOW())", __FILE__, __LINE__);
pageend();
?>
