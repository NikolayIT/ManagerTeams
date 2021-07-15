<?php
/*
File name: mysqlquery.php
Last change: Sat Jan 12 11:38:46 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$script = "mysqlquery";
if (isset($_POST['submitquery']))
{
   if (get_magic_quotes_gpc()) $_POST['query'] = stripslashes($_POST['query']);
   head("Query");
   prnt(nl2br($_POST['query']));
   br(2);
   $result = sql_query($_POST['query'], __FILE__, __LINE__);
   if ($result)
   {
      if (@mysql_num_rows($result))
      {
         head("Result");
         table_start();
         table_startrow();
         for ($i=0; $i<mysql_num_fields($result); $i++)
         {
            prnt("<th>".mysql_field_name($result,$i)."</th>");
         }
         table_endrow();
         while ($row = mysql_fetch_row($result))
         {
            table_startrow();
            for ($i=0;$i<mysql_num_fields($result);$i++)
            {
               table_cell($row[$i]);
            }
            table_endrow();
         }
         table_end();
      }
      else prnt("<b>Query OK: ".mysql_affected_rows()." rows affected.</b>");
   }
   else prnt("<b>Query Failed:</b> ".mysql_error());
   //prnt('<hr />');
}
head("SQL Query");
form_start("{$_SERVER['PHP_SELF']}?module={$script}", "POST");
textarea(htmlspecialchars($_POST['query']), "query", 70, 5, "", true);
br();
input("submit", "submitquery", "Submit query", "", true);
form_end();
?>
