<?php
define("IN_GAME", true);
include("common.php");
limit();

pagestart("�������� 50 ������������ �� ������� � ������ ��");
head("�������� 50 ������������ �� ������� � ������ ��");
$views = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `profileviews`.`user1`) AS `user1name` FROM `profileviews` WHERE `user2` = '{$USER['id']}' AND `user1` != {$USER['id']} ORDER BY `time` DESC LIMIT 50", __FILE__, __LINE__);
if (!$views) prnt("����� �� � ���������� ������� ��� ������ ��");
else
{
  table_start();
  table_header(MANAGER, "���������", TIME);
  while ($row = mysql_fetch_assoc($views))
  {
	 table_startrow();
	 table_cell(create_link("viewprofile.php?id={$row['user1']}", $row['user1name']));
	 table_cell($row['type'] == "viewprofile" ? "������� ��" : "������ ��");
	 table_cell($row['time']);
	 table_endrow();
  }
  table_end();
}
pageend();
?>