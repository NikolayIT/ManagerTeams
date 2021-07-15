<?php
/*
File name: friends.php
Last change: Sun Jan 20 11:32:16 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);

mkglobal("do");
if ($do == "view")
{
   $friends = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `friends`.`user2`) AS `username`, 
   (SELECT `lastaction` FROM `users` WHERE `id` = `friends`.`user2`) AS `lastaction`,
   (SELECT `realname` FROM `users` WHERE `id` = `friends`.`user2`) AS `realname`
   FROM `friends` WHERE `user1` = {$USER['id']}", __FILE__, __LINE__);
   if (mysql_num_rows($friends) == 0) info(NO_FRIENDS, FRIENDS, false);
   pagestart(FRIENDS);
   head(FRIENDS);
   table_start();
   table_header("", FRIEND, REAL_NAME, LAST_ACTION, SEND_MESSAGE, REMOVE);
   $i = 0;
   while ($row = mysql_fetch_assoc($friends))
   {
      $i++;
      table_startrow();
      table_cell($i);
      table_cell(create_link("viewprofile.php?id={$row['user2']}", $row['username']));
      table_cell($row['realname']);
      table_cell($row['lastaction']);
      table_cell(create_link("messages.php?do=compose&to={$row['username']}", SEND_MESSAGE));
      table_cell(create_link("friends.php?do=remove&id={$row['id']}", REMOVE));
      table_endrow();
   }
   table_end();
   pageend();
}
else if ($do == "add")
{
   mkglobal("id");
   if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR, true);
   if ($id == $USER['id']) info(CANT_ADD_YOURSELF_IN_FRIENDLY_LIST, ERROR, true);
   $id = sqlsafe($id);
   $usr = sql_get("SELECT `username` FROM `users` WHERE `id` = {$id}", __FILE__, __LINE__);
   if (!$usr) info(WRONG_ID, ERROR, true);
   $duplicate = sql_get("SELECT `id` FROM `friends` WHERE `user1` = '{$USER['id']}' AND `user2` = '{$id}'", __FILE__, __LINE__);
   if ($duplicate) info(USER_ALREADY_IN_FRIENDLY_LIST, ERR, true);
   sql_query("INSERT INTO `friends` (`user1`, `user2`) VALUES ('{$USER['id']}', '{$id}')", __FILE__, __LINE__);
   info(USER_SUCCESSFULLY_ADDED_TO_FRIENDLY_LIST."<br>".create_link("friends.php?do=view", GO_TO_YOUR_FRIEND_LIST), SUCCESS, true);
}
else if ($do == "remove")
{
   mkglobal("id");
   if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR, true);
   $id = sqlsafe($id);
   $usr = sql_get("SELECT `user2` FROM `friends` WHERE `id` = {$id}", __FILE__, __LINE__);
   if (!$usr) info(WRONG_ID, ERROR, true);
   sql_query("DELETE FROM `friends` WHERE `id` = {$id} LIMIT 1", __FILE__, __LINE__);
   $usr = sql_get("SELECT `username` FROM `users` WHERE `id` = {$usr}", __FILE__, __LINE__);
   info(USER_SUCCESSFULLY_REMOVED_FROM_FRIENDLY_LIST."<br>".create_link("friends.php?do=view", GO_TO_YOUR_FRIEND_LIST), SUCCESS, true);
}
else info(INVALID_SCTIPT_CALL, ERROR, true);
?>
