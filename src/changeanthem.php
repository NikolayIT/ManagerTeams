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
	if (!getvalidfilename($_FILES['file']['name'])) info("���� �� � ������� ����! ��������� �������: ���� .mp3", ERROR, true);
	if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_path.$_FILES['file']['name'])) info("������ ��� ������������� �� ����� (��������� �����?)!", ERROR, true);
	rename($target_path.$_FILES['file']['name'], $target_path."{$TEAM['id']}.mp3");
	info("����� �� ������ � ����� �������!", SUCCESS, true);
}
else
{
	pagestart("������� �� ���� �� ������");
	head("������� �� ���� �� ������");
	?><form enctype="multipart/form-data" action="changeanthem.php" method="POST"><?php
	input("hidden", "edit", "yes", "", true);
	input("hidden", "MAX_FILE_SIZE", "16777216", "", true);
	table_start(false);
	table_startrow();
	?><th>����:</th><td class="tb"><input name="file" type="file" size="50" /><br />���������� ������: 16MB<br />�������� ������: ���� .mp3!</td><?php
	table_endrow();
	table_startrow();
	table_th(input("submit", "", "�������", ""), 2);
	table_endrow();
	table_end();
	form_end();
	pageend();
}
?>