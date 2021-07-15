<?php
/*
File name: messages.php
Last change: Sun Jan 27 21:46:28 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
$captionfind = array("{_TRAINING_INFORMATION_FOR_}", "{_YOU_HAVE_BOUGHT_}", "{_YOU_HAVE_SOLD_}", "{_FRIENDLY_MATCH_ACCEPTED_FROM_}", "{_FRIENDLY_MATCH_REJECTED_FROM_}", "{_FRIENDLY_MATCH_OFFERED_FROM_}");
$captionrepl = array(TRAINING_INFORMATION_FOR, YOU_HAVE_BOUGHT, YOU_HAVE_SOLD, FRIENDLY_MATCH_ACCEPTED_FROM, FRIENDLY_MATCH_REJECTED_FROM, FRIENDLY_MATCH_OFFERED_FROM);
pagestart(MESSAGES);
function paging($pageNum, $where, $pageadd, $from = true, $to = true, $read = true, $addtext = "", $rowsPerPage = 15, $del = false)
{
   global $captionfind, $captionrepl;
   $numrows = sql_get("SELECT COUNT(`id`) FROM `messages` {$where}", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT `id`, `caption`, `timesent`, `fromname`, `fromid`, `readstatus`, `toid`, `toname` FROM `messages` {$where} ORDER BY `timesent` DESC LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   if ($del) form_start("messages.php?do=delspec", "POST");
   table_start();
   table_startrow();
   if ($read) table_th(STATUS);
   if ($from) table_th(FROMM);
   if ($to) table_th(TO);
   table_th(SUBJECT);
   table_th(TIME);
   if ($del) table_th(DELETE);
   table_endrow();
   while ($row = mysql_fetch_assoc($result))
   {
      table_startrow();
      if ($read)
      {
         if ($row['readstatus'] == 'no' && $row['toid'] != 0) table_cell("<div class='img_unread_message' title='Unread' />");
         else table_cell("");
      }
      if ($from)
      {
         if ($row['fromid'] == 0) table_cell(GAME_NAME);
         else table_cell(create_link("viewprofile.php?id={$row['fromid']}", $row['fromname']));
      }
      if ($to)
      {
         if ($row['toid'] == 0) table_cell(ALL);
         else table_cell(create_link("viewprofile.php?id={$row['toid']}", $row['toname']));
      }
      $caption = $row['caption'];
      if ($row['fromid'] == 0) $caption = str_replace($captionfind, $captionrepl, $caption);
      table_cell(create_link("messages.php?do=view&id={$row['id']}", $caption));
      table_cell($row['timesent']);
      if ($del) table_cell(input("checkbox", "msg_{$row['id']}", "yes"));
      table_endrow();
   }
   table_end();
   br();
   $maxPage = ceil($numrows/$rowsPerPage);
   $next = "";
   $last = "";
   $first = "";
   $prev = "";
   if ($pageNum > 1)
   {
      $page = $pageNum - 1;
      $prev = " ".create_link("{$pageadd}&page={$page}", "[".PREVIOUS_PAGE."]");
      $first = " ".create_link("{$pageadd}&page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}&page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}&page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}", true);
   br();
   if ($del) $delbutt = input("submit", "", DELETE_SELECTED_MESSAGES);
   else $delbutt = "";
   prnt("{$delbutt}");
   if ($del) form_end();
   prnt("$addtext");
   return true;
}

mkglobal("do", false);
if ($do == "inbox")
{
   mkglobal("page", false);
   if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   head(INBOX);
   $addtext = create_button("messages.php?do=deleteallmsg", DELETE_ALL_MESSAGES, false, false, false).create_button("messages.php?do=deleteallsystemmsg", DELETE_ALL_GAME_MESSAGES, false, false, false);
   if (!paging($page, "WHERE `toid` = {$USER['id']}", "messages.php?do=inbox", true, false, true, $addtext, 15, true)) info(YOUR_INBOX_IS_EMPTY, "", false);
}
else if ($do == "gamemessages")
{
   mkglobal("page", false);
   if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   head(GAME_MESSAGES);
   $addtext = create_button("messages.php?do=deleteallsystemmsg", DELETE_ALL_GAME_MESSAGES, false, false, false);
   if (!paging($page, "WHERE `toid` = {$USER['id']} AND `fromid` = 0", "messages.php?do=gamemessages", true, false, true, $addtext, 15, true)) info(NO_GAME_MESSGAES, "", false);
}
else if ($do == "unread")
{
   mkglobal("page", false);
   if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   head(UNREAD);
   if (!paging($page, "WHERE `toid` = {$USER['id']} AND `readstatus` = 'no'", "messages.php?do=unread", true, false, true)) info(NO_UNREAD_MESSGAES, "", false);
}
else if ($do == "outbox")
{
   mkglobal("page", false);
   if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   head(OUTBOX);
   //if(!paging($page, "WHERE `fromid` = {$USER['id']} AND `toid` != 0", "messages.php?do=outbox", false, true, true, create_button("messages.php?do=deletealloutmsg", DELETE_ALL_MESSAGES), 15, true)) info(YOUR_OUTBOX_IS_EMPTY, OUTBOX, false);
   if(!paging($page, "WHERE `fromid` = {$USER['id']} AND `toid` != 0", "messages.php?do=outbox", false, true, true)) info(YOUR_OUTBOX_IS_EMPTY, "", false);
}
else if ($do == "announcements")
{
   mkglobal("page", false);
   if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   head(PRESS_ANNOUNCES);
   if(!paging($page, "WHERE `toid` = 0", "messages.php?do=announcements", true, false, false)) info(NO_ANNOUNCEMENTS, "", false);
}
else if ($do == "myannouncements")
{
   limit(UC_VIP_USER);
   mkglobal("page", false);
   if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
   if (empty($page)) $page = 1;
   head(MY_ANNOUNCES);
   if(!paging($page, "WHERE `toid` = 0 AND `fromid` = {$USER['id']}", "messages.php?do=myannouncements", false, false, false)) info(YOU_DIDNT_POST_ANY_ANNOUNCEMENTS, "", false);
}
else if ($do == "compose")
{
   mkglobal("to");
   $to = htmlspecialchars($to);
   head(COMPOSE);
   form_start("messages.php?do=send", "POST");
   table_start(false);
   table_row(TO, input("text", "to", $to));
   table_row(SUBJECT, input("text", "caption"));
   table_row(MESSAGE, textarea("", "text", 50, 6, "", false));
   table_startrow();
   table_th(input("submit", "", SEND), 2);
   table_endrow();
   table_end();
   form_end();
}
else if ($do == "view")
{
   mkglobal("id", false);
   if (empty($id)) info(WRONG_ID, ERROR);
   $id2 = sqlsafe($id);
   $mess = sql_data("SELECT * FROM messages WHERE id = $id2 AND ((toid = $USER[id] OR fromid = $USER[id]) OR toid = 0)", __FILE__, __LINE__);
   if (!$mess) info(WRONG_ID, ERROR);
   $replyto = "(".create_link("messages.php?do=compose&to={$mess['fromname']}", REPLY).")";
   if ($mess['fromid'] == 0 || $mess['fromid'] == $USER['id']) $replyto = "";
   // Caption
   $caption = $mess['caption'];
   if ($mess['fromid'] == 0) $caption = str_replace($captionfind, $captionrepl, $caption);
   head(MESSAGE.": {$caption} (".create_link("messages.php?do=delete&id={$id}", DELETE).")");
   table_start();
   if ($mess['fromid'] != 0) table_row(FROMM, create_link("viewprofile.php?id={$mess['fromid']}", $mess['fromname'])." ".$replyto);
   else table_row(FROMM, GAME_NAME);
   if ($mess['toid'] != 0) table_row(TO, create_link("viewprofile.php?id={$mess['toid']}", $mess['toname']));
   else table_row(TO, ALL);
   table_row(SENT, $mess['timesent']);
   table_row(SUBJECT, $caption);
   table_startrow();
   table_th(MESSAGE, 2);
   table_endrow();
   table_startrow();
   // Message text
   $message = stripslashes($mess['message']);
   $find = array("{_YOUR_PLAYER_}", "{_HAS_INCREASED_WITH_1_POINT_HIS_}", "{_YOU_HAVE_BOUGHT_}", "{_YOU_HAVE_SOLD_}", "{_FROM_}", "{_TO_}", "{_ACCEPTED_YOUR_FRIEDNLY_MATCH_}", "{_REJECTED_YOUR_FRIEDNLY_MATCH_}", "{_OFFERED_YOU_A_FRIENDLY_MATCH_ON_}", "{_TO_ACCEPT_IT_CLICK_}", "{_TO_REJECT_IT_CLICK_}", "{_HERE_}");
   $repl = array(YOUR_PLAYER, HAS_INCREASED_WITH_1_POINT_HIS, YOU_HAVE_BOUGHT, YOU_HAVE_SOLD, _FROM, _TO, _ACCEPTED_YOUR_FRIEDNLY_MATCH, _REJECTED_YOUR_FRIEDNLY_MATCH, _OFFERED_YOU_A_FRIENDLY_MATCH_ON, TO_ACCEPT_IT_CLICK, TO_REJECT_IT_CLICK, _HERE);
   if ($mess['fromid'] == 0) $message = str_replace($find, $repl, $message);
   table_cell(bbcode($message), 2, "tbwrap");
   table_endrow();
   table_end();
   br();
   create_button("messages.php?do=inbox", INBOX);
   create_button("messages.php?do=outbox", OUTBOX);
   create_button("messages.php?do=announcements", PRESS_ANNOUNCES);
   if ($mess['toid'] == $USER['id']) sql_query("UPDATE `messages` SET `readstatus` = 'yes' WHERE `id` = {$mess['id']}", __FILE__, __LINE__);
}
else if ($do == "send")
{
   mkglobal("to:caption:text", true);
   if (empty($to) || empty($caption) || empty($text)) info(MISSING_DATA, ERROR);
   if ($USER['username'] == $to && !$DEBUG) info(CANT_SEND_MESSAGES_TO_YOURSELF, ERROR);
   $to2 = sqlsafe($to);
   $caption2 = sqlsafe($caption);
   $text2 = sqlsafe($text, false, false);
   $userid = sql_get("SELECT `id` FROM `users` WHERE `username` = {$to2}", __FILE__, __LINE__);
   if (!$userid) info(WRONG_USER, ERROR);
   $fromname = sqlsafe($USER['username']);
   sql_query("INSERT INTO `messages` (`timesent`, `fromid`, `fromname`, `toid`, `toname`, `caption`, `message`) VALUES (".get_date_time().", {$USER['id']}, {$fromname}, {$userid}, {$to2}, {$caption2}, {$text2})", __FILE__, __LINE__);
   info(MESSAGE_SENT, SUCCESS);
}
else if ($do == "delete")
{
   mkglobal("id");
   if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
   $id = sqlsafe($id);
   if (limit_cover(UC_ADMIN)) sql_query("DELETE FROM `messages` WHERE `id` = {$id}", __FILE__, __LINE__);
   else sql_query("DELETE FROM `messages` WHERE `toid` = {$USER['id']} AND `id` = {$id}", __FILE__, __LINE__);
   head(MESSAGE_DELETING);
   prnt(THE_MESSAGE_DELETED_SUCCESSFULLY, true);
   create_button("messages.php?do=inbox", INBOX);
}
else if ($do == "delspec")
{
   foreach ($_POST as $key => $value)
   {
      if (substr($key, 0, 4) == "msg_" && $value == "true");
      {
         $key = sqlsafe(str_replace("msg_", "", $key));
         sql_query("DELETE FROM `messages` WHERE `toid` = {$USER['id']} AND `id` = {$key}", __FILE__, __LINE__);
      }
   }
   head(MESSAGES_DELETING);
   prnt(THE_MESSAGES_DELETED_SUCCESSFULLY, true);
   create_button("messages.php?do=inbox", INBOX);
}
else if ($do == "deleteallmsg")
{
   head(MESSAGES_DELETING);
   mkglobal("confirm", false);
   if ($confirm != 'yes')
   {
      prnt(ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGES, true);
      prnt(create_link("messages.php?do=deleteallmsg&confirm=yes", YES_DELETE_MESSAGES), true);
      prnt("<b>".create_link($_COOKIE['back'], GO_BACK)."</b>");
   }
   else
   {
      sql_query("DELETE FROM `messages` WHERE `toid` = {$USER['id']}", __FILE__, __LINE__);
      prnt(THE_MESSAGES_DELETED_SUCCESSFULLY, true);
      create_button("messages.php?do=inbox", INBOX);
   }
}
else if ($do == "deleteallsystemmsg")
{
   head(DELETE_ALL_GAME_MESSAGES);
   mkglobal("confirm", false);
   if ($confirm != 'yes')
   {
      prnt(ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGES, true);
      prnt(create_link("messages.php?do=deleteallsystemmsg&confirm=yes", YES_DELETE_MESSAGES), true);
      prnt("<b>".create_link($_COOKIE['back'], GO_BACK)."</b>");
   }
   else
   {
      sql_query("DELETE FROM `messages` WHERE `toid` = {$USER['id']} AND `fromid` = 0", __FILE__, __LINE__);
      prnt(THE_MESSAGES_DELETED_SUCCESSFULLY, true);
      create_button("messages.php?do=inbox", INBOX);
   }
}
/*
else if ($do == "deletealloutmsg")
{
head("Delete outbox messages");
mkglobal("confirm", false);
if ($confirm != 'yes')
{
prnt(ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGES, true);
prnt(create_link("messages.php?do=deletealloutmsg&confirm=yes", YES_DELETE_MESSAGES), true);
prnt("<b>".create_link($_COOKIE['back'], GO_BACK)."</b>");
}
else
{
sql_query("DELETE FROM `messages` WHERE `fromid` = {$USER['id']} AND `toid` != 0", __FILE__, __LINE__);
prnt(THE_MESSAGES_DELETED_SUCCESSFULLY, true);
create_button("messages.php?do=inbox", INBOX);
}
}
*/
else if ($do == "sendann")
{
   limit(UC_VIP_USER);
   head(SEND_ANNOUNCE);
   form_start("messages.php?do=dosendann", "POST");
   table_start(false);
   table_row(SUBJECT, input("text", "caption"));
   table_row(MESSAGE, textarea("", "text", 50, 6, "", false));
   table_startrow();
   table_th(input("submit", "", SEND), 2);
   table_endrow();
   table_end();
   form_end();
}
else if ($do == "dosendann")
{
   limit(UC_VIP_USER);
   mkglobal("caption:text", true);
   if (empty($caption) || empty($text)) info(MISSING_DATA, ERROR, true);
   $caption2 = sqlsafe($caption);
   $text2 = sqlsafe($text, false, false);
   $fromname = sqlsafe($USER['username']);
   sql_query("INSERT INTO `messages` (`timesent`, `fromid`, `fromname`, `toid`, `caption`, `message`) VALUES (".get_date_time().", {$USER['id']}, {$fromname}, '0', {$caption2}, {$text2})", __FILE__, __LINE__);
   info (ANNOUNCEMENT_SENT, SUCCESS);
}
else
{
   head(MESSAGES);
   create_special_link("messages.php?do=inbox", INBOX, INBOX_TEXT);
   create_special_link("messages.php?do=gamemessages", GAME_MESSAGES, GAME_MESSAGES_TEXT);
   create_special_link("messages.php?do=outbox", OUTBOX, OUTBOX_TEXT);
   create_special_link("messages.php?do=compose", COMPOSE, COMPOSE_TEXT);
   create_special_link("messages.php?do=announcements", PRESS_ANNOUNCES, PRESS_ANNOUNCES_TEXT);
   create_special_link("messages.php?do=myannouncements", MY_ANNOUNCES, MY_ANNOUNCES_TEXT);
   create_special_link("messages.php?do=sendann", SEND_ANNOUNCE, SEND_ANNOUNCE_TEXT);
}
pageend();
?>
