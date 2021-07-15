<?php
/*
File name: friendlyreject.php
Last change: Mon Apr 28 10:02:21 EEST 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);

// Get id
mkglobal("id");
if (empty($id) || !is_numeric($id) || $id < 1) info(WRONG_ID, ERROR);

// Get data for the invitation
$friendly = sql_data("SELECT * FROM `friendly_invitations` WHERE `id` = {$id}", __FILE__, __LINE__);

// Check for errors
if (!$friendly) info(INVITATION_ALREADY_REJECTED, ERROR);
if ($friendly['toteam'] != $TEAM['id']) info(INVITATION_NOT_FOR_YOU, ERROR);
if ($TEAM['id'] == $friendly['fromteam']) info(INVITATION_NOT_FOR_YOU, ERROR);

// Remove friendly invitation
sql_query("DELETE FROM `friendly_invitations` WHERE `id` = {$id}", __FILE__, __LINE__);

// Send message
$to = sql_get("SELECT `id` FROM `users` WHERE `team` = {$friendly['fromteam']}", __FILE__, __LINE__);
send_game_message($to, "{_FRIENDLY_MATCH_REJECTED_FROM_} {$TEAM['name']}", "{$TEAM['name']} {_REJECTED_YOUR_FRIEDNLY_MATCH_}!");
info(INVITATION_REJECTED, SUCCESS);
?>
