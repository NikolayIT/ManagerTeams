<?php
/*
File name: news.php
Last change: Sat Jan 12 12:15:53 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$script = "news";
$scriptname = "News";
$caption = "";
$text = "";
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
   $success = true;
   if (!mkglobal("do", false)) info("Invalid action!", ERROR);
   if (!mkglobal("caption", true) || $caption == "") info("Не сте посочили валидно заглавие на новината!", ERROR);
   if (!mkglobal("short", true) || $short == "") info("Не сте посочили валиден кратък текст за новината!", ERROR);
   if (!mkglobal("text", true) || $text == "") info("Не сте посочили валиден текст за новината!", ERROR);
   $sqlcaption = sqlsafe($caption, false, false);
   $sqltext = sqlsafe($text, false, false);
   $sqlshort = sqlsafe($short, false, false);
   $sqluser = sqlsafe($USER['username']);
   if ($do == "add")
   {
      sql_query("INSERT INTO `news` (`caption`, `short`, `text`, `added`, `addedby`) VALUES ({$sqlcaption}, {$sqlshort}, {$sqltext}, ".get_date_time().", {$sqluser})", __FILE__, __LINE__);
      prnt ("<b>Успешно добавихте новината!</b>");
      br(2);
      cleanup("last_update15", 0, true);
   }
   else if ($do == "edit")
   {
      if (!mkglobal("id", false)) info("Invalid id!", ERROR);
      $id2 = sqlsafe($id);
      $res = sql_query("UPDATE `news` SET `caption` = {$sqlcaption}, `short` = {$sqlshort}, `text` = {$sqltext}, `addedby` = {$sqluser} WHERE `id` = {$id2}", __FILE__, __LINE__);
      prnt ("<b>Успешно редактирахте новината!</b>");
      br(2);
      cleanup("last_update15", 0, true);
   }
   else info("Invalid action!", ERROR);
}
mkglobal("do:id", false);
if ($do != "" && $id != "")
{
   $id2 = sqlsafe($id);
   if ($do == "delete")
   {
      sql_query("DELETE FROM `news` WHERE `id` = $id2", __FILE__, __LINE__);
      prnt ("<b>Успешно изтрихте новината!</b>");
      br(2);
      cleanup("last_update15", 0, true);
   }
   else if ($do == "edit")
   {
      $data = sql_data("SELECT `text`, `caption`, `short` FROM `news` WHERE `id` = {$id2}", __FILE__, __LINE__);
      if (!$data) info("Invalid id!", ERROR);
      head("Edit news ({$id2})");
      form_start("admin.php?module={$script}&do={$do}&id={$id}", "POST");
      table_start(false, 1);
      table_startrow();
      table_row("Caption", input("text", "caption", $data['caption']));
      table_row("Short text", input("short", "caption", $data['short'], "", false, 70));
      table_row("Text", textarea($data['text'], "text", 60, 4, "", false));
      table_cell("<center>".input("submit", "", "Edit news")."</center>", 2);
      table_end();
      form_end();
      br();
   }
   else info("Invalid action!", ERROR);
}
head("Active news");
table_start();
table_header("Id", "Author", "Caption", "Short text", "Time", "Edit", "Delete");
$res = sql_query("SELECT * FROM `news` ORDER BY `added` DESC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($res))
{
   table_startrow();
   table_cell($row['id']);
   table_cell($row['addedby']);
   table_cell(create_link("viewnews.php?id={$row['id']}", $row['caption']));
   table_cell(bbcode(substr($row['short'], 0, 40))."...");
   table_cell($row['added']);
   table_cell(create_link("{$_SERVER['PHP_SELF']}?module={$script}&do=edit&id={$row['id']}", "Edit"));
   table_cell(create_link("{$_SERVER['PHP_SELF']}?module={$script}&do=delete&id={$row['id']}", "Delete"));
   table_endrow();
}
table_end();
br();
head("Add news");
form_start("{$_SERVER['PHP_SELF']}?module={$script}&do=add", "POST");
table_start(false);
table_row("Caption", input("text", "caption", $caption));
table_row("Short text", input("text", "short", $text, "", false, 70));
table_row("Text", textarea($text, "text", 60, 4, "", false));
table_cell("<center>".input("submit", "", "Add this news")."</center>", 2);
table_end();
form_end();
?>
