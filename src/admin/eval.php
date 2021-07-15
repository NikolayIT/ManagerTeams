<?php
/*
File name: eval.php
Last change: Sat Jan 12 11:27:09 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
$script = "eval";
function eval_html($string)
{
   /* $string = preg_replace("/\?>(.*?)(<\?php|<\?)/si", "echo \"\\1\";",$string); */
   $string = str_replace("\\\"", "\"", $string);
   $string = str_replace("\\'", "'", $string);
   return $string;
}

$code = eval_html($_POST['thecode']);
if ($_GET['eval'] == 'yes')
{
   set_time_limit(0);
   eval($code);
   br(2);
}
head("Execute code");
form_start("{$_SERVER['PHP_SELF']}?module={$script}&eval=yes", "POST");
textarea($code, "thecode", 70, 20, "", true);
br();
input("submit", "", "Execute code", "", true);
form_end();
?>
