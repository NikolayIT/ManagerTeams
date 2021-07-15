<?php
/*
File name: config.php
Last change: 
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);

mkglobal("do");
if ($do)
{
   mkglobal("name:value");
   $value = sqlsafe($value);
   $name = sqlsafe($name);
   sql_query("UPDATE `config` SET `value` = {$value} WHERE `name` = {$name}", __FILE__, __LINE__);
   print("Value updated successfully!!!<br><br>");
}
$data = sql_query("SELECT * FROM `config` ORDER BY `id` ASC", __FILE__, __LINE__);
head("Settings");
table_start();
table_header("id", "name", "set", "value");
while ($row = mysql_fetch_assoc($data))
{
   table_startrow();
   table_cell($row['id']);
   table_cell($row['name']);
   if ($row['name'] == "cleaning") table_cell(create_link("admin.php?module=settings&do=1&name=cleaning&value=0", "Set 0"));
   else table_cell("");
   table_cell($row['value']);
   table_endrow();
}
table_end();
?>
