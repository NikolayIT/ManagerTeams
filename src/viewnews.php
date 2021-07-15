<?php
define("IN_GAME", true);
include("common.php");

function paging($pageNum, $pageadd, $rowsPerPage = 15)
{
   $numrows = sql_get("SELECT COUNT(`id`) FROM `news`", __FILE__, __LINE__);
   if ($numrows == 0) return false;
   $offset = ($pageNum - 1) * $rowsPerPage;
   // output
   $result = sql_query("SELECT * FROM `news` ORDER BY `time` DESC, `id` DESC LIMIT {$offset}, {$rowsPerPage}", __FILE__, __LINE__);
   table_start();
   table_header(TITLE, DATE);
   while ($row = mysql_fetch_assoc($result))
   {
      table_startrow();
      table_cell("<a href=\"viewnews.php?id={$row['id']}\">{$row['name']}</a>");
      table_cell($row['time']);
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
      $prev = " ".create_link("{$pageadd}?page={$page}", "[".PREVIOUS_PAGE."]");
      $first = " ".create_link("{$pageadd}?page=1", "[".FIRST_PAGE."]")." ";
   }
   if ($pageNum < $maxPage)
   {
      $page = $pageNum + 1;
      $next = " ".create_link("{$pageadd}?page={$page}", "[".NEXT_PAGE."]")." ";
      $last = " ".create_link("{$pageadd}?page={$maxPage}", "[".LAST_PAGE."]")." ";
   }
   prnt("{$first} {$prev} ".PAGE." <b>{$pageNum}</b> "._OF." <b>{$maxPage}</b> "._PAGES." {$next} {$last}");
   return true;
}


mkglobal("id");
if (is_numeric($id) && $id > 0)
{
   $id = sqlsafe($id);
   $news = sql_data("SELECT * FROM `news` WHERE `id` = {$id}", __FILE__, __LINE__);
   pagestart($news['name']);
   head($news['name']);
   print($news['content']);
   print("<div class=\"clear\"></div><hr />");
   print("Новината е публикувана в: <a href='{$news['from']}' target='_blank'>{$news['from']}</a> на {$news['time']}");
   
   
      br(2);
      head(LATEST_COMMENTS);
      $comments = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `news_comments`.`from`) AS `fromname` FROM `news_comments` WHERE `news` = {$id} ORDER BY `time` DESC LIMIT 20", __FILE__, __LINE__);
      if (mysql_num_rows($comments) == 0) prnt(NO_COMMENTS);
      else
      {
         table_start();
         if (limit_cover(UC_MODERATOR)) table_header(TIME, MANAGER, COMMENT, DELETE);
         else table_header(TIME, MANAGER, COMMENT);
         while ($row = mysql_fetch_assoc($comments))
         {
            table_startrow();
            table_cell($row['time'], 1, "tb", "", "width:20px;");
            table_cell(create_link("viewprofile.php?id={$row['from']}", $row['fromname']), 1, "tb", "", "width:20px;");
            table_cell(bbcode($row['text']), 1, "tbwrap");
            if (limit_cover(UC_MODERATOR)) table_cell(create_link("comment_news.php?do=delete&id={$row['id']}", DELETE));
            table_endrow();
         }
         table_end();
         create_button("comment_news.php?do=showall&id={$id}", SHOW_ALL);
      }
      br(2);
	  if (limit_cover())
	  {
      head(POST_COMMENT);
      form_start("comment_news.php?do=post&id={$id}", "POST");
      table_start(false);
      table_row(MESSAGE, textarea("", "text", 50, 6, "", false));
      table_startrow();
      table_th(input("submit", "", SEND), 2);
      table_endrow();
      table_end();
      form_end();
	  }
   
   pageend();
}
else
{
	mkglobal("page", false);
	if (!empty($page) && !is_numeric($page)) info(INVALID_PAGE_NUMBER, ERROR);
	if (empty($page)) $page = 1;
	pagestart(NEWS. ", ".PAGE." ".$page);
	head(NEWS. ", ".PAGE." ".$page);
	if (!paging($page, "viewnews.php", 15)) prnt(DONT_HAVE_MONEY_HISTORY);
	pageend();
}
?>
