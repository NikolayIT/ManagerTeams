<?php
/*
File name: cup.php
Last change: Wed Jan 09 09:12:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(VIP_PLACE);
head(VIP_PLACE);
create_special_link("vipstart.php", "����� ���", "����� ���������� �� ���� ��� ������ �� ������� ��� ����������");
create_special_link("vipabout.php", "������� ���", "����� ��������, ����� ���������� ���� ��� ����������");
create_special_link("addmoney.php", "������ ����", "����� ��� ������ �� �������� 2000000 ��� ������ ������");
create_special_link("messages.php?do=sendann", SEND_ANNOUNCE, SEND_ANNOUNCE_TEXT);
create_special_link("messages.php?do=myannouncements", MY_ANNOUNCES, MY_ANNOUNCES_TEXT);
create_special_link("friends.php?do=view", FRIENDS, FRIENDS_TEXT);
create_special_link("mybets.php", BETS, BETS);
create_special_link("holiday.php", HOLYDAY, HOLYDAY_TEXT);
create_special_link("viewonline.php", ONLINE_MANAGERS, ONLINE_MANAGERS_TEXT);
create_special_link("teamkits.php", KITS, KITS_TEXT);
create_special_link("changename.php", "������� �� �����", "������� �� ����� �� ������ � ��������");
create_special_link("playernicknames.php", NICKNAMES, NICKNAMES_TEXT);
create_special_link("playernumbers.php", NUMBERS, NUMBERS_TEXT);
create_special_link("playernotes.php", NOTES, PLAYER_NOTES);
create_special_link("crosstable.php", CROSS_TABLE, CROSS_TABLE_TEXT);
pageend();
?>
