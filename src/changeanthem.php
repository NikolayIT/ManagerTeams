<?php
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("edit", true);
additionaldata(true);
function endswith($str, $needle)
{
   return preg_match('/\Q' . $needle . '\E$/', $str);
}
function getvalidfilename($filename)
{
	return (endswith($filename, ".mp3") || endswith($filename, ".mp3"));
}
if ($edit == "yes")
{
	$target_path = "static/sounds/";
	if (!getvalidfilename($_FILES['file']['name'])) info("Това не е валиден файл! Разрешени формати: само .mp3", ERROR, true);
	if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_path.$_FILES['file']['name'])) info("Грешка при преместването на файла (невалидни права?)!", ERROR, true);
	rename($target_path.$_FILES['file']['name'], $target_path."{$TEAM['id']}.mp3");
	info("Химна на отбора е качен успешно!", SUCCESS, true);
}
else
{
	pagestart("Качване на химн на отбора");
	head("Качване на химн на отбора");
	?><form enctype="multipart/form-data" action="changeanthem.php" method="POST"><?php
	input("hidden", "edit", "yes", "", true);
	input("hidden", "MAX_FILE_SIZE", "16777216", "", true);
	table_start(false);
	table_startrow();
	?><th>Файл:</th><td class="tb"><input name="file" type="file" size="50" /><br />Максимален размер: 16MB<br />Разрешен формат: само .mp3!</td><?php
	table_endrow();
	table_startrow();
	table_th(input("submit", "", "Качване", ""), 2);
	table_endrow();
	table_end();
	form_end();
	pageend();
}
?>