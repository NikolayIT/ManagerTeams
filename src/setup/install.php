<?php
$config_file = "config.php";
$sql_file = "./include/newgame.sql";

function print_flush2($text, $bold = false)
{
	if ($bold) print("<b>{$text}</b><br>\n");
	else print("{$text}<br>\n");
	flush();
	ob_flush();
}

if ($_POST['do'])
{
	set_time_limit(0);
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <title>ManagerTeams - Installer</title>
	  <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
	  <link href="./styles/new/style.css" type="text/css" rel="stylesheet">
   </head>
   <body>
      <center>
      <div id="general">
      <table class="main">
	     <tr>
		    <td colspan="4" id="content_td"><div id="content">
			   <h1>Инсталация на ManagerTeams</h1>
	<?php
	
	// sql data
	if (!@mysql_connect($_POST['dbserver'], $_POST['dbuser'], $_POST['dbpass'])) die("<br><b>Грешни MySQL данни!</b>");
	if (!@mysql_select_db($_POST['dbname'])) die("<br><b>MySQL таблицата не съществува!</b>");
	include("./include/parse.class.php");
	$parseObj = new parse($sql_file);
	print_flush2("Start parsing sql file...", true);
	$parseObj->startParsing();
	print_flush2("MySQL tables imported successfully!", true);
	// Open config.php and edit it
	$file = fopen("config.php", "r");
	$text = fread($file, filesize("config.php"));
	fclose($file);
	$text = str_replace("\$db_host = \"\";", "\$db_host = \"{$_POST['dbserver']}\";", $text);
	$text = str_replace("\$db_name = \"\";", "\$db_name = \"{$_POST['dbname']}\";", $text);
	$text = str_replace("\$db_user = \"\";", "\$db_user = \"{$_POST['dbuser']}\";", $text);
	$text = str_replace("\$db_pass = \"\";", "\$db_pass = \"{$_POST['dbpass']}\";", $text);
	$file = fopen("config.php", "w");
	fwrite($file, $text);
	fclose($file);
	// Create directories
	@mkdir("./cache/", 0777);
	@mkdir("./cache/polls", 0777);
	@mkdir("./cache/matches", 0777);
	for ($i = 0; $i <= 9; $i++)
	{
		@mkdir("./cache/matches/{$i}/", 0777);
		for ($j = 0; $j <= 9; $j++)
		{
			@mkdir("./cache/matches/{$i}/{$j}/", 0777);
			for ($k = 0; $k <= 9; $k++)
			{
				@mkdir("./cache/matches/{$i}/{$j}/{$k}/", 0777);
				for ($l = 0; $l <= 9; $l++) @mkdir("./cache/matches/{$i}/{$j}/{$k}/{$l}/", 0777);
			}
		}
	}
	// Start game
	$onlyconn = true;
	define("IN_GAME", true);
	include("common.php");
	start_game();
	?>
			   <a href="index.php"><b>Отидете на началната страница</b></a>
			</td>
         </tr>
      </table>
   </body>
</html>
	<?php
}
else
{
include("config.php");
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
   <head>
      <title><?=GAME_NAME?> <?=GAME_VERSION?> - Installer</title>
	  <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
	  <link href="./styles/new/style.css" type="text/css" rel="stylesheet">
   </head>
   <body>
      <center>
      <div id="general">
      <table class="main">
	     <tr>
		    <td colspan="4" id="content_td"><div id="content">
			   <h1>Инсталация на <?=GAME_NAME?> <?=GAME_VERSION?></h1>
	           <form action="install.php" method="POST"><table id="installer">
                  <tr><td>Сървър за базата данни:</td><td><input type="text" name="dbserver" value="localhost" /></td></tr>
		          <tr><td>Таблица в базата данни:</td><td><input type="text" name="dbname" value="" /></td></tr>
		          <tr><td>Потребител за базата данни:</td><td><input type="text" name="dbuser" value="" /></td></tr>
		          <tr><td>Парола за базата данни:</td><td><input type="password" name="dbpass" value="" /></td></tr>
                  <tr><td colspan=2><input type="submit" value="Започни инсталацията!" name="do"/></td></tr>
	           <table></form>
			</td>
         </tr>
      </table>
   </body>
</html>
	<?php
}
?>
