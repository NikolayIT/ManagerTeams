<?php
/*
File name: faq.php
Last change: Fri Jan 11 21:25:07 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart("FAQ");
head("FAQ");
if ($_COOKIE["lang"] == 1)
{
   prnt("English version coming soon...", true);
   br();
   prnt("<b>When I should use holiday mode?</b><br>
1. When you won't be able to play the game for some time.<br>
2. When you will go to holiday and won't be able to play.<br>
3. When you need rest from the game.<br>
<br>
<b>What are the privileges of the holiday mode?</b><br>
1. Your account will not be deleted for inactivity.<br>
2. Whe system will control you account.<br>
<br>
Note: The holiday mode can be actived as long as you want!<br>
<br>
You should know that this extra is for VIP users only!<br>
The moneys we recieve from VIP users helps us to improve the game!", true);
}
else if ($_COOKIE["lang"] == 2)
{
   bline("����� ���������� ���� �����?", "����� 6 ������ ({$config['matchcount']} ����)");
   bline("���� �� ������ �������� �� ������?", "����� ��� �� ���������� �� ����� � 18 ���� CET");
   bline("���� �� ������ �������� �� ������?", "����� ������ � 12 � 18 ���� CET, ����� � � ������ � 18 ���� CET");
   bline("���� �� ������ ������������ ������?", "���������� ������ �� ������ �� ����� 6 ����");
   bline("����� � CET?", "CET � ����������� ���������� ����� (���� � -1 ��� ������ �����������)");
   bline("���� �� �� ������ ������� � ������?", "��, ������ �� �� ��������� �� <a href=\"invite.php\">���</a>");
   bline("���� ������ � ��-����� ����� 18-19 CET?", "������ �� ��������� �������� �� ������ ����� � �� ������ ������ ������� � � �������� �� ������� ���� �������� ��� ����������� �� �����������");
   bline("���� �� ������� ����������?", " ������������ \"��������\" �� ������ ������ ���������� �� � ����� ���� ������ � ��� �� �� ���������. ���� ������� ����� ��������� �� \"�������\" � �� ������� ".PLAYERS_OLD." ������ (���������� ��������� �� �������)");
   bline("���� �� ������� ����� �� ����� �� ������?", "������ �� ����� �� ������ (�������, ������, �����) \"��������\" �� ������ ���� ������ �������� ".STAFF_OLD." ������ (����������� ��������� �� �������)");
   bline("����� ����� ������ ����� �� ����� � ������?", "�� ������ �� ���������� ������ �� (�� ������� �������� �� ��������, �� �������� ������ � �.�.), ���� �� ������ �� ����������� ����������� �� ����������� � ���� ���� �� �������, �� �� �������� ����� ��, � ��������� �� ����� �� �������� � ��-����� ������ �� ����������� ������� � �� ������ �������� �������� �� � �� 3-0");
   bline("���� � ��� �� ��������� ������������?", "������������ �� ��������� �� ����� 6 ���� � ���� �� ������������ (��-����� �� 1 %) ������ ������������, ����� ����������� �� �� ������ �� ������. ���� ��������� ���� �� ���� ���� 100 ����������, ����� � ����� ��������� ���� �� �� ���� ���-����� �� 100.");
}
else prnt(COMING_SOON);
pageend();
?>
