<?php
define("IN_GAME", true);
include("common.php");
limit();

mkglobal("do:id", false);
if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
$id = sqlsafe($id);

if ($do == "post")
{
   mkglobal("text");
   if (empty($text)) info(MISSING_DATA, ERROR);
   $text = sqlsafe($text);
   sql_query("INSERT INTO `news_comments` (`news`, `from`, `text`, `time`) VALUES ({$id}, {$USER['id']}, {$text}, ".get_date_time().")", __FILE__, __LINE__);
   info(COMMENT_SUCCESSFULLY_POSTED, SUCCESS);
}
else if ($do == "showall")
{
   $news = sql_data("SELECT * FROM `news` WHERE `id` = {$id}", __FILE__, __LINE__);
   pagestart(COMMENTS_FOR." \"{$news['name']}\"");
   head(COMMENTS_FOR." \"{$news['name']}\"");
   $comments = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `news_comments`.`from`) AS `fromname` FROM `news_comments` WHERE `news` = {$id} ORDER BY `time` DESC", __FILE__, __LINE__);
   if (!$comments) prnt(NO_COMMENTS);
   else
   {
      table_start();
      if (limit_cover(UC_MODERATOR) || $USER['id'] == $id) table_header(TIME, MANAGER, COMMENT, DELETE);
      else table_header(TIME, MANAGER, COMMENT);
      while ($row = mysql_fetch_assoc($comments))
      {
         table_startrow();
         table_cell($row['time']);
         table_cell(create_link("viewprofile.php?id={$row['from']}", $row['fromname']));
         table_cell(bbcode($row['text']), 1, "specialtbwrap");
         if (limit_cover(UC_MODERATOR)) table_cell(create_link("comment_news.php?do=delete&id={$row['id']}", DELETE));
         table_endrow();
      }
      table_end();
      create_link($_COOKIE['back'], GO_BACK);
   }
   pageend();
}
else if ($do == "delete")
{
   limit(UC_MODERATOR);
   mkglobal("confirm");
   if ($confirm == 'yes')
   {
      sql_query("DELETE FROM `news_comments` WHERE `id` = {$id} LIMIT 1", __FILE__, __LINE__);
      info(THE_MESSAGE_DELETED_SUCCESSFULLY, SUCCESS, false);
   }
   else
   {
      pagestart(MESSAGE_DELETING);
      head(MESSAGE_DELETING);
      prnt(ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGE, true);
      create_link("comment_news.php?do=delete&id={$id}&confirm=yes", YES_DELETE_THE_MESSAGE, true);
      br();
      create_link($_COOKIE['back'], GO_BACK, true);
      pageend();
   }
}
else info(INVALID_SCTIPT_CALL, ERROR);
?>
