<?php
/*
File name: userssearch.php
Last change: Sat May 10 11:02:31 EEST 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);

$searcitems = array("invitedby", "class", "ip");
$where = "";
foreach ($searcitems as $item)
{
   if(isset($_GET["{$item}"]))
   {
      $data = $_GET["{$item}"];
      $data = sqlsafe($data);
      if (!$where) $where .= "WHERE `$item` = {$data}";
      else $where .= " AND `$item` = {$data}";
   }
}

$data = sql_query("SELECT *, `invitedby` AS `inviter`, (SELECT `name` FROM `teams` WHERE `id` = `users`.`team`) AS `teamname`, (SELECT `username` FROM `users` WHERE `id` = `inviter`) AS `invitername` FROM `users` {$where} ORDER BY `username` ASC", __FILE__, __LINE__);
head("Users search");
table_start();
table_header("ID", USERNAME, EMAIL, TEAM, "Invited by", "IP", CLASSS);
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell(create_link("viewprofile.php?id={$row['id']}", $row['username']));
   table_cell($row['email']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   if ($row['invitedby']) table_cell(create_link("viewprofile.php?id={$row['invitedby']}", $row['invitername']));
   else table_cell("Никой");
   table_cell(create_link("admin.php?module=ipinfo&ip={$row['ip']}", $row['ip']));
   table_cell(create_link("admin.php?module=userssearch&class={$row['class']}", get_user_class_name($row['class'])));
   table_endrow();
}
table_end();
?>
