<?php
/*$res = sql_query("SELECT * FROM `staff` WHERE `type` = 'doctor'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($res))
{
	sql_query("INSERT INTO `staff` (`name`,  `type`, `rating`, 	`age`) VALUES ('{$row['name']}', 'accountant', '{$row['rating']}', '{$row['age']}')");
}*/
/*
File name: errors.php
Last change: Sat Jan 12 11:24:17 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);

mkglobal("delete", false);
if ($delete == "yes")
{
   $fh = fopen(ERROR_CACHE_FILE, "w");
   fclose($fh);
   info("Error cashe successfully truncated!", SUCCESS);
}
else
{
   if (file_exists(ERROR_CACHE_FILE))
   {
      head("Errors (".create_link("admin.php?module=errors&delete=yes", "Delete errors").")");
      $fh = fopen(ERROR_CACHE_FILE, "r");
      $data = @fread($fh, filesize(ERROR_CACHE_FILE));
      fclose($fh);
      prnt($data);
   }
   else info("No errors!", "Errors");
}
?>
