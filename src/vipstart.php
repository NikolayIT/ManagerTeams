<?php
/*
File name: cup.php
Last change: Wed Jan 09 09:12:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart("����� ���");
limit();
mkglobal("do");
if ($_GET['admin_vip_add'] == 1)
{
	limit(UC_ADMIN);
	$uid = $_GET['uid'];
      $time = sql_get("SELECT ADDTIME((SELECT IF(`vipuntil` < NOW(), NOW(), `vipuntil`) FROM `users` WHERE `id` = '{$uid}'), '30 0:0:0')", __FILE__, __LINE__);
      if ($time) sql_query("UPDATE `users` SET `class` = GREATEST(3, `class`), `vipuntil` = '{$time}' WHERE `id` = '{$uid}'", __FILE__, __LINE__);
      info("������� ��������� 30 ���� ��� ���� ��� ������� �� �����������.<br>�������� ��� ������ �� ������ ��: {$time}", SUCCESS);
}
if ($_GET['admin_vip_remove'] == 1)
{
	limit(UC_ADMIN);
	$uid = $_GET['uid'];
      $time = sql_get("SELECT ADDTIME((SELECT IF(`vipuntil` < NOW(), NOW(), `vipuntil`) FROM `users` WHERE `id` = '{$uid}'), '-30 0:0:0')", __FILE__, __LINE__);
      if ($time) sql_query("UPDATE `users` SET `class` = GREATEST(3, `class`), `vipuntil` = '{$time}' WHERE `id` = '{$uid}'", __FILE__, __LINE__);
      info("������� ����������� 30 ���� ��� ���� ��� ������� �� �����������.<br>�������� ��� ������ �� ������ ��: {$time}", SUCCESS);
}
else if ($do == 1)
{
   limit();
   mkglobal("code");
   $res = mobio_checkcode($code, 1156);
   if (!$res) $res = mobio_checkcode($code, 3384);
   if ($res)
   {
      head(SUCCESS);
      $time = sql_get("SELECT ADDTIME((SELECT IF(`vipuntil` < NOW(), NOW(), `vipuntil`) FROM `users` WHERE `id` = '{$USER['id']}'), '30 0:0:0')", __FILE__, __LINE__);
      if ($time) sql_query("UPDATE `users` SET `class` = '3', `vipuntil` = '{$time}' WHERE `id` = '{$USER['id']}'", __FILE__, __LINE__);
      prnt("������� ��������� 30 ���� ��� ���� ��� ������.<br>������ ��� ������ �� ������ ��: {$time}");
   }
   else
   {
      head(ERROR);
      prnt("��������� ���!<br>���� ��������� ���� �� � �� �������� ������.<br>��� ��� ������� �� ��� ��������� ������ �����, �� ��������� �� ������ ����� ���, ���� �������� �� � ��� ��: managerteams@yahoo.com");
   }
   $code = sqlsafe($code);
   sql_query("INSERT INTO `paytries` (`time`, `ip`, `user`, `text`, `success`, `type`) VALUES (".get_date_time().", ".sqlsafe(getip()).", '{$USER['id']}', {$code}, '{$res}', 'vip')", __FILE__, __LINE__);
}
else
{
   head("����� ��� � ���������� ������� ���� ePay.bg");
   prnt("�� �� ������� VIP � ������ ������ �� ���������� ��������� �� ����������� ePay.bg. �� 1 ������� ��� ���� ������������ �� ��������� ��� �� ������� ����� ��� ������.<br />��� �������� � ��������� ���� �� �� ������ ��� ������.<br /><b><center>����: 2,40 �� � ���<br>����: 30 ���</b></center>");
	?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="2.40">
<input type=hidden name=DESCR value="30 ��� VIP ������ �� ���������� <?=$USER['username']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/vipstart.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="������� on-line" title="������� on-line" border="0">
</form></center>
	<?php
   head("����� ��� ��� ���");
   prnt("�� �� ������� ��� ���������� � ������ (��� �� ���������� ��� ���������� �� � 30 ����, ��� ���� ��� ��� ����������), ������ �� ��������� ��� � ����� <b>MTVIP</b> �� ������ �����: <b>1092</b> (� �� 3-�� ������� ���������) �� ���� 2,40 ��. (� ���).<br>�� 1 ������ ���� ����������� �� ���-� �� �������� ���� ������� ��� ��� 6 �������� ���.<br>����������� ����, �� �� �������� 30 ���� ��� ����� ��� ������.<br>�� �� ��������� ����� �������� ���� ��� ����������, ���� ����� <a href='vipabout.php'>���</a><br><br><br>");
   prnt("���� �������� ���� � ������ 6 ��������� ���, ����� ��� �������� ���� ���:");
   br(2);
   prnt("<center>");
   form_start("vipstart.php", "POST");
   input("hidden", "do", "1", "", true);
   prnt("���: ", true);
   input("text", "code", "", "code", true);
   br(2);
   hide_input("submit", "", "����� ���!", true);
   form_end();
   prnt("</center>");
}
pageend();
?>
