<?php
/*
File name: tablestatus.php
Last change: Sat Jan 12 13:21:36 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$script = "eval";
$result = mysql_query("SHOW TABLE STATUS FROM `{$db_name}`");
head("MySQL table status");
table_start();
table_header("Id", "Table", "Engine", "Version", "Row format", "Rows", "Size per row", "Data size", "Max data size", "Index size", "Total size", "Data free", "Auto increment", "Create time", "Update time", "Check time", "Collation", "Checksum", "Create options", "Comment");
$i = 0;
while($array = mysql_fetch_assoc($result))
{
   $i++;
   table_startrow();
   table_cell($i);
   table_cell("<b>{$array['Name']}</b>");
   table_cell($array['Engine']);
   table_cell($array['Version']);
   table_cell($array['Row_format']);
   table_cell($array['Rows']);
   table_cell($array['Avg_row_length']);
   table_cell($array['Data_length']);
   table_cell($array['Max_data_length']);
   table_cell($array['Index_length']);
   $total = $array['Data_length']+$array['Index_length'];
   table_cell($total);
   table_cell($array['Data_free']);
   table_cell($array['Auto_increment']);
   table_cell($array['Create_time']);
   table_cell($array['Update_time']);
   table_cell($array['Check_time']);
   table_cell($array['Collation']);
   table_cell($array['Checksum']);
   table_cell($array['Create_options']);
   table_cell($array['Comment']);
   table_endrow();
}
table_end();
?>
<ul>
<li>
<b>Name</b>
The name of the table.
</li>
<li>
<b>Engine</b>
The storage engine for the table. See <a href="http://dev.mysql.com/doc/refman/5.0/en/storage-engines.html" title="Chapter 12. Storage Engines">Chapter 12, <i>Storage Engines</i></a>.
</li>
<li>
<b>Version</b>
The version number of the table's .frm file.
</li>
<li>
<b>Row_format</b>
The row storage format (Fixed, Dynamic, Compressed, Redundant, Compact). The format of InnoDB tables is reported as Redundant or Compact.
</li>
<li>
<b>Rows</b>
The number of rows. Some storage engines, such as MyISAM, store the exact count. For other storage engines, such as InnoDB, this value is an approximation, and may vary from the actual value by as much as 40 to 50%. In such cases, use SELECT COUNT(*) to obtain an accurate count.<br>
The Rows value is NULL for tables in the INFORMATION_SCHEMA database.
</li>
<li>
<b>Avg_row_length</b>
The average row length.
</li>
<li>
<b>Data_length</b>
The length of the data file.
</li>
<li>
<b>Max_data_length</b>
The maximum length of the data file. This is the total number of bytes of data that can be stored in the table, given the data pointer size used.
</li>
<li>
<b>Index_length</b>
The length of the index file.
</li>
<li>
<b>Data_free</b>
The number of allocated but unused bytes.
</li>
<li>
<b>Auto_increment</b>
The next AUTO_INCREMENT value.
</li>
<li>
<b>Create_time</b>
When the table was created.
</li>
<li>
<b>Update_time</b>
When the data file was last updated. For some storage engines, this value is NULL. For example, InnoDB stores multiple tables in its tablespace and the data file timestamp does not apply.
</li>
<li>
<b>Check_time</b>
When the table was last checked. Not all storage engines update this time, in which case the value is always NULL.
</li>
<li>
<b>Collation</b>
The table's character set and collation.
</li>
<li>
<b>Checksum</b>
The live checksum value (if any).
</li>
<li>
<b>Create_options</b>
Extra options used with CREATE TABLE. The original options supplied when CREATE TABLE is called are retained and the options reported here may differ from the active table settings and options.
</li>
<li>
<b>Comment</b>
The comment used when creating the table (or information as to why MySQL could not access the table information). 
</li>
</ul>
<?php
pageend();
?>
