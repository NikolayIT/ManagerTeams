<?php
/*
File name: takeinvite.php
Last change: Sat Feb 09 08:52:46 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
mkglobal("multy", true);
function invite($frname, $fremail, $inviter, $mailer)
{
   global $USER;
   $ip = getip();
   $signuplink = ADDRESS."signup.php?inviter={$USER['id']}";
   $address = ADDRESS."index.php?inviter={$USER['id']}";
   $mess = INVITATION_MAIL;
   $mess = str_replace("{friendname}", $frname, $mess);
   $mess = str_replace("{address}", $address, $mess);
   $mess = str_replace("{gamename}", GAME_NAME, $mess);
   $mess = str_replace("{inviter}", $inviter, $mess);
   $mess = str_replace("{inviterip}", $ip, $mess);
   $mess = str_replace("{friendmail}", $fremail, $mess);
   $mess = str_replace("{signuplink}", $signuplink, $mess);
   $headers = "Content-type: text/html; charset=".ENCODING."\r\nFrom: {$mailer}\r\nReply-To: {$mailer}";
   $res = mail($fremail, GAME_NAME." ".INVITATION_BY." $inviter", $mess, $headers);
   return $res;
}
if ($multy == 1)
{
   mkglobal("inviter:mailer", true);
   if (empty($inviter) || empty($mailer)) info(MISSING_DATA, ERROR);
   $sqlinviter = sqlsafe($inviter);
   $sqlmailer = sqlsafe($mailer);
   pagestart(INVITE_FRIENDS);
   head(INVITE_FRIENDS);
   $s = 0; $u = 0;
   for ($i = 0; $i <= 9; $i++)
   {
      mkglobal("frname{$i}:fremail{$i}", true);
      if (!empty($GLOBALS["frname{$i}"]) && !empty($GLOBALS["fremail{$i}"]))
      {
         $res = true;
         $frname = $GLOBALS["frname{$i}"];
         $fremail = $GLOBALS["fremail{$i}"];
         $sqlfrname = sqlsafe($frname);
         $sqlfremail = sqlsafe($fremail);
         prnt(SENDING_INVITE_TO.": {$frname} ({$fremail}) - ");
         $res = sql_get("SELECT `id` FROM `invitetries` WHERE `tomail` = {$sqlfremail}", __FILE__, __LINE__) > 0 ? false : true;
         if ($res) $res = invite($frname, $fremail, $inviter, $mailer);
         $uid = $USER['id'] > 0 ? $USER['id'] : 0;
         if ($res) sql_query("INSERT INTO `invitetries` (`time`, `userid`, `ip`, `fromname`, `frommail`, `toname`, `tomail`) VALUES (NOW(), {$uid}, ".sqlsafe(getip()).", {$sqlinviter}, {$sqlmailer}, {$sqlfrname}, {$sqlfremail})", __FILE__, __LINE__);
         if ($res) { prnt(SUCCESS, true); $s++; }
         else { prnt(ERROR, true); $u++; }
      }
   }
   br();
   prnt(SUCCESSFULLY_SENT_INVITES.": {$s}", true);
   prnt(UNSUCCESSFUL_INVITES.": {$u}", true);
   prnt("<b>".create_link($_COOKIE['back'], GO_BACK)."</b>");
   pageend();
}
else
{
   mkglobal("frname:fremail:inviter:mailer", true);
   if (empty($frname) || empty($fremail) || empty($inviter) || empty($mailer)) info(MISSING_DATA, ERROR);
   $res = invite($frname, $fremail, $inviter, $mailer);
   if (!$res) info(SENDING_MAIL_ERROR, ERROR);
   info(INVITATION_SUCCESS, SUCCESS);
}
?>
