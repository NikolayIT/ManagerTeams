<?php
/*
File name: newpass.php
Last change: Sun Jan 27 22:30:21 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart(NEW_PASSWORD);
mkglobal("new", true);
mkglobal("conf", false);
function generatePassword($length = 8)
{
   $possible = "0123456789abcdefghijklmnopqrstuvwxyz";
   $password = "";
   $i = 0;
   while ($i < $length)
   { 
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      if (!strstr($password, $char))
      { 
         $password .= $char;
         $i++;
      }
   }
   return $password;
}

if ($conf)
{
   mkglobal("user:id", false);
   $user = sqlsafe($user);
   $usr = sql_data("SELECT `id`, `secret`, `username` FROM `users` WHERE `username` = {$user} LIMIT 1", __FILE__, __LINE__);
   if ($usr['id'] > 0)
   {
      $cid = gen_confirmation_id($usr['username'], $usr['secret']);
      if ($id == $cid)
      {
         sql_query("UPDATE `users` SET `passhash` = `newpass` WHERE `id` = {$usr['id']} LIMIT 1", __FILE__, __LINE__);
         info(PASSWORD_CHANGED, SUCCESS, false);
      }
      else info(INVALID_CONFIRMATION_ID, ERROR, false);
   }
   else info(WRONG_ID, ERROR, false);
}
else if ($new)
{
   mkglobal("us:ml");
   if (empty($us) || empty($ml)) info(MISSING_DATA, ERROR);
   $user2 = sqlsafe($us);
   $mail2 = sqlsafe($ml);
   $usr = sql_data("SELECT `id`, `username`, `realname`, `secret`, `email` FROM `users` WHERE `username` = {$user2} AND `email` = {$mail2}", __FILE__, __LINE__);
   if (strlen($usr['secret']) == 16 && $usr['id'] > 0)
   {
      $newpass = generatePassword(8);
      $newpass2 = sqlsafe(gen_pass_hash($newpass, $usr['secret']));
      sql_query("UPDATE `users` SET `newpass` = $newpass2 WHERE `id` = {$usr['id']}", __FILE__, __LINE__);
      $confid = gen_confirmation_id($usr['username'], $usr['secret']);
      $link = ADDRESS."newpass.php?conf=1&user={$usr['username']}&id={$confid}";
      $mess = FORGPASS_MAIL;
      $mess = str_replace("{realname}", $usr['realname'], $mess);
      $mess = str_replace("{ip}", getip(), $mess);
      $mess = str_replace("{username}", $usr['username'], $mess);
      $mess = str_replace("{email}", $usr['email'], $mess);
      $mess = str_replace("{link}", $link, $mess);
      $mess = str_replace("{password}", $newpass, $mess);
      $mess = str_replace("{gamename}", GAME_NAME, $mess);
      $headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: ".EMAIL_ADDRESS."\r\nReply-To: ".EMAIL_ADDRESS;
      $res = mail($usr['email'], GAME_NAME." "._PASSWORD_RESET_CONFIRMATION, $mess, $headers);
      if (!$res) info(SENDING_MAIL_ERROR, ERROR);
      info(CHECK_MAIL_FOR_CONFIRMATION_MAIL, SUCCESS, false);
   }
   else info(WRONG_USERNAME_OR_EMAIL, ERROR);
}
else
{
   head(FORGOT_PASS);
   form_start("newpass.php", "POST");
   input("hidden", "new", "1", "", true);
   table_start(false, 0);
   table_row(USERNAME, input("textbox", "us"), 1, "");
   table_row(EMAIL, input("textbox", "ml"), 1, "");
   table_startrow();
   table_th(input("submit", "", SEND, "invite_reset"), 2);
   table_endrow();
   table_end();
   form_end();
}
pageend();
?>
