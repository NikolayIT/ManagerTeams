<?php
/*
File name: invite.php
Last change: Thu Jan 24 11:57:18 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(INVITE_FRIENDS);
head(INVITE_FRIENDS);
prnt(INVITE_TEXT);
br(2);
form_start("takeinvite.php", "POST");
table_start(false, 0, "specialtable");
table_row(YOUR_NAME, input("text", "inviter", $USER['realname']), 1, "specialtb");
table_row(YOUR_EMAIL, input("text", "mailer", $USER['email']), 1, "specialtb");
table_end();
br();
prnt("<b>��������:</b><br>1. �� ����� ������� ������ ���������� �� ".INVITATION_BONUS."� ������� � ������<br>2. �� ������� ������ �� ����� ������, ��� ����� ������������ �� � ����������� � ������ ���� ��������� �� ��� ������.<br>3. ���� ���������� ������ ���� �� ���� ����� ���������!<br>4. �� ���������� ����������� ������!<br>5. ��� �������, �� ��������������� � ��������, �� �������� ���������.<br>6. �� ���� � ��� e-mail ����� �� ���� �� �� ������� ������ �� 1 ������.<br>7. ���������� �� ��� ����� �� ������ �� ����������� ������������� �� �� ����� ��������!");
br(2);
head(FRIENDS);
input("hidden", "multy", "1", "", true);
table_start(false, 0, "specialtable");
for ($i = 0; $i <= 9; $i++)
{
   table_startrow();
   table_th(NAME);
   table_cell(input("text", "frname{$i}"), 1, "specialtb");
   table_th(EMAIL);
   table_cell(input("text", "fremail{$i}"), 1, "specialtb");
   table_endrow();
}
table_startrow();
table_th("");
table_th(input("submit", "", INVITE));
table_th(input("reset", "", RESET));
table_th("");
table_endrow();
table_end();
form_end();
pageend();
?>
