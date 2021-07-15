<?php
/*
File name: phpinfo.php
Last change: Sat Jan 12 12:31:39 EET 2008
Copyright: NRPG (c) 2008
*/
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);

ob_start();
phpinfo();
preg_match ("%<style type=\"text/css\">(.*?)</style>.*?(<body>.*</body>)%s", ob_get_clean(), $matches);
echo "<div class='phpinfodisplay'><style type='text/css'>\n", join("\n", array_map(create_function('$i', 'return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );'), preg_split('/\n/', $matches[1]))), "</style>\n", $matches[2], "\n</div>\n";
?>
