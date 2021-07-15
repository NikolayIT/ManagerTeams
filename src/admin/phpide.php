<?php
/*
File name: phpide.php
Last change: Sat Jan 12 12:19:49 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
define("PAGE_ADDRESS", "admin.php?module=phpide");
include("./admin/phpide/index.php");
?>
