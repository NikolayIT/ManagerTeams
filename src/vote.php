<?php
/*
File name: vote.php
Last change: Tue Jun 24 19:00:18 EEST 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
define("VOTING", true);
include("common.php");
$ip = sqlsafe($ip);
sql_query("UPDATE `ips` SET `vote` = ".get_date_time().", `voted` = 'yes' WHERE `ip` = {$ip}", __FILE__, __LINE__);
header("Location: http://bgtop.net/in.php/1204661646");
?>
