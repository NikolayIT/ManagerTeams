<?php
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("edit:name", true);
additionaldata(true);
if ($edit == "team")
{
	if ($TEAM['money'] < 20000000) info(NOT_ENOUGHT_MONEY, ERROR);
	if ($name == $TEAM['name'])
	{
		info("���� �������� ��� �� ������, �������� �� ���������.", ERROR);
		die();
	}
	// check if exists
	$checkid = sql_get("SELECT `id` FROM `teams` WHERE `name` = ".sqlsafe($name), __FILE__, __LINE__);
	if ($checkid)
	{
		info("����� �� ������ �� �� � �������, ������ ���� ��� ����� � ������ ���. ���� ������� �� ����� � �������� ������.", ERROR);
		die();
	}
	// check it
	if (strlen($name) < $teamname_minlen) info(SHORT_TEAMNAME, ERROR);
	if (strlen($name) > 32) info(LONG_TEAMNAME, ERROR);
	if (!is_valid_name($name)) info(WRONG_TEAMNAME, ERROR);
	// change it
	sql_query("UPDATE `teams` SET `name` = ".sqlsafe($name)." WHERE `id` = '{$TEAM['id']}'", __FILE__, __LINE__);
	add_to_money_history("Team name changed!", -20000000, $TEAM['id'], true);
	info("����� �� ������ � ��������� �������!", SUCCESS, true);
}
else if ($edit == "stadium")
{
	if ($TEAM['money'] < 20000000) info(NOT_ENOUGHT_MONEY, ERROR);
	if ($name == $STADIUM['name'])
	{
		info("���� �������� ��� �� ��������, �������� �� ���������.", ERROR);
		die();
	}
	// check if exists
	$checkid = sql_get("SELECT `id` FROM `stadiums` WHERE `name` = ".sqlsafe($name), __FILE__, __LINE__);
	if ($checkid)
	{
		info("����� �� �������� �� �� � �������, ������ ���� ��� ������� � ������ ���. ���� ������� �� ����� � �������� ������.", ERROR);
		die();
	}
	// check it
	if (strlen($name) < $stadiumname_minlen) info(SHORT_STADIUMNAME, ERROR);
	if (strlen($name) > 32) info(LONG_STADIUMNAME, ERROR);
	if (!is_valid_name($name)) info(WRONG_STADIUMNAME, ERROR);
	// change it
	sql_query("UPDATE `stadiums` SET `name` = ".sqlsafe($name)." WHERE `id` = '{$STADIUM['id']}'", __FILE__, __LINE__);
	add_to_money_history("Stadium name changed!", -20000000, $TEAM['id'], true);
	info("����� �� �������� � ��������� �������!", SUCCESS, true);
}
else
{
	pagestart("������� �� ������� �� ������ � ��������");
	
	head("������� �� ����� �� ������");
	?><form action="changename.php" method="POST"><?php
	input("hidden", "edit", "team", "", true);
	table_start(false);
	table_startrow();
	table_th("<b>���� ��� �� ������:</b>");
	table_th(input("text", "name", $TEAM['name'], ""));
	table_endrow();
	table_startrow();
	table_th("��������� �� ����� �� ������ ������ 20000000 �", 2);
	table_endrow();
	table_startrow();
	table_th(input("submit", "", "�������", ""), 2);
	table_endrow();
	table_end();
	form_end();
	
	head("������� �� ����� �� ��������");
	?><form action="changename.php" method="POST"><?php
	input("hidden", "edit", "stadium", "", true);
	table_start(false);
	table_startrow();
	table_th("<b>���� ��� �� ��������:</b>");
	table_th(input("text", "name", $STADIUM['name'], ""));
	table_endrow();
	table_startrow();
	table_th("��������� �� ����� �� �������� ������ 20000000 �", 2);
	table_endrow();
	table_startrow();
	table_th(input("submit", "", "�������", ""), 2);
	table_endrow();
	table_end();
	form_end();
	
	pageend();
}