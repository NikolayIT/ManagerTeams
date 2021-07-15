<?php
/*
File name: sponsors.php
Last change: Sat Jan 12 13:21:49 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$script = "sponsors";
$scriptname = "Sponsors";

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
   $success = true;
   if(!mkglobal("do", false)) info("Invalid action!", ERR);
   if(!mkglobal("name", true) || $name == "") info("Invalid sponsor name!", ERR);
   if(!mkglobal("url", true) || $url == "") info("Invalid sponsor web address!", ERR);
   if(!mkglobal("image", true) || $image == "") info("Invalid sponsor baner!", ERR);
   if(!mkglobal("days", true) || $days == "") info("Invalid sponsor campain duration!", ERR);
   if(!mkglobal("money", true) || $money == "") info("Invalid sponsor money!", ERR);
   if(!mkglobal("until", true) || $until == "") info("Invalid sponsor campain availability duration!", ERR);
   $sqlname = sqlsafe($name, false, false);
   $sqlurl = sqlsafe($url, false, false);
   $sqlimage = sqlsafe($image, false, false);
   $sqldays = sqlsafe($days, false, false);
   $sqlmoney = sqlsafe($money, false, false);
   $sqluntil = sqlsafe($until, false, false);
   if ($do == "add")
   {
      sql_query("INSERT INTO `advertising` (`name`, `url`, `img`, `days`, `money`, `until`) VALUES ({$sqlname}, {$sqlurl}, {$sqlimage}, {$sqldays}, {$sqlmoney}, {$sqluntil})", __FILE__, __LINE__);
      prnt("<b>Sponsor sucesssfuly added</b>");
      br(2);
   }
   else if ($do == "edit")
   {
      if (!mkglobal("id", false)) info("Invalid id!", ERR);
      $id2 = sqlsafe($id);
      $res = sql_query("UPDATE `advertising` SET `name` = {$sqlname}, `url` = {$sqlurl}, `img` = {$sqlimage}, `days` = {$sqldays}, `money` = {$sqlmoney}, `until` = {$sqluntil} WHERE id = {$id2}", __FILE__, __LINE__);
      prnt("<b>Sponsor edited successfuly</b>");
      br(2);
   }
   else info("<b>Invalid action!</b>", ERR);
}
mkglobal("do:id", false);
if ($do != "" && $id != "")
{
   $id2 = sqlsafe($id);
   if ($do == "delete")
   {
      sql_query("DELETE FROM `advertising` WHERE `id` = {$id2}", __FILE__, __LINE__);
      prnt("<b>Sponsor successfuly deleted!</b>");
      br(2);
   }
   else if ($do == "edit")
   {
      $data = sql_data("SELECT * FROM `advertising` WHERE `id` = {$id2}", __FILE__, __LINE__);
      if (!$data) info("Invalid id!", ERR);
      head("Edit sponsor ({$id2})");
      form_start("admin.php?module={$script}&do={$do}&id={$id}", "POST");
      table_start(false);
      table_row("Name", input("text", "name", $data['name'], "", false, 60));
      table_row("URL", input("text", "url", $data['url'], "", false, 60));
      table_row("Image", input("text", "image", $data['img'], "", false, 60));
      table_row("Days", input("text", "days", $data['days'], "", false, 60));
      table_row("Money", input("text", "money", $data['money'], "", false, 60));
      table_row("Until", input("text", "until", $data['until'], "", false, 60));
      table_cell("<center>".input("submit", "", "Edit sponsor")."</center>", 2);
      table_end();
      form_end();
      br();
   }
   else info("Invalid action!", ERR);
}
head("Active sponsors");
table_start();
table_header("Id", "Name", "Image", "Days", "Money", "Until", "Edit", "Delete");
$res = sql_query("SELECT * FROM `advertising` ORDER BY `until` ASC, `id` DESC", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($res))
{
   table_startrow();
   table_cell($row['id']);
   table_cell(create_link($row['url'], $row['name']));
   table_cell(create_image($row['img']));
   table_cell($row['days']);
   table_cell($row['money']);
   table_cell($row['until']);
   table_cell(create_link("{$_SERVER['PHP_SELF']}?module={$script}&do=edit&id={$row['id']}", "Edit"));
   table_cell(create_link("{$_SERVER['PHP_SELF']}?module={$script}&do=delete&id={$row['id']}", "Delete"));
   table_endrow();
}
table_end();
br();
head("Add sponsor");
form_start("{$_SERVER['PHP_SELF']}?module={$script}&do=add", "POST");
table_start(false);
table_row("Name", input("text", "name", $gamename, "", false, 60));
table_row("URL", input("text", "url", $address, "", false, 60));
table_row("Image", input("text", "image", "images/logo.png", "", false, 60));
table_row("Days", input("text", "days", "30", "", false, 60));
table_row("Money", input("text", "money", "150000", "", false, 60));
table_row("Until", input("text", "until", get_date_time(false, 60*60*24*30), "", false, 60));
table_cell("<center>".input("submit", "", "Add this sponsor")."</center>", 2);
table_end();
form_end();
br();
?>
