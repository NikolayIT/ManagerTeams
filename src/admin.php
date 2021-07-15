<?php
/*
File name: admin.php
Last change: Sat Jan 12 10:39:13 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_ADMIN);

mkglobal("module", false);
if ($module)
{
   define("IN_ADMIN_PANEL", true);
   $module = str_replace("/", "", $module);
   pagestart("Administration :: {$module}");
   include("./admin/{$module}.php");
   prnt("<center>".create_link("admin.php", "<b>Go to the admin panel</b>")."</center>");
   pageend();
}
else
{
   pagestart("Administration");
   head("Administration");
   create_special_link("admin.php?module=cheaters", "Cheaters", "View multyaccounting ips");
   create_special_link("admin.php?module=money", MONEY, "Teams under the moeny line");
   create_special_link("admin.php?module=transferreports", TRANSFERS, "View reported transfers");
   create_special_link("admin.php?module=news", NEWS, "Edit, add and remove news");
   create_special_link("admin.php?module=sponsors", ADVERTISE_BOARDS, "Edit, add and remove sponsors");
   create_special_link("admin.php?module=smsstats", "SMS stats", "View SMS stats");
   create_special_link("admin.php?module=inivtesstats", "Invites stats", "View invites stats");
   create_special_link("admin.php?module=settings", "Settings", "View game settings");
   create_special_link("admin.php?module=errors", "Errors", "View game errors");
   create_special_link("admin.php?module=mysqlquery", "MySQL query", "Execute MySQL query");
   create_special_link("admin.php?module=tablestatus", "MySQL tables status", "Review MySQL tables");
   create_special_link("admin.php?module=phpide", "PHP editor", "Edit PHP files online");
   create_special_link("admin.php?module=eval", "Evaluate PHP code", "Evaluate PHP code");
   create_special_link("admin.php?module=vs", "PHP source code", "View PHP source code of all files");
   create_special_link("admin.php?module=phpinfo", "PHP info", "Useful PHP information");
   create_special_link("admin.php?module=server", "Server status", "View server load and uptime");
   pageend();
}
?>
