<?php
/*
File name: addmoney.php
Last change:
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart("������ ����");
limit();
mkglobal("do");
?>
<style>
A.epay-button             { border: solid  1px #FFF; background-color: #168; padding: 6px; color: #FFF; background-image: none; font-weight: normal; padding-left: 20px; padding-right: 20px; }
A.epay-button:hover       { border: solid  1px #ABC; background-color: #179; padding: 6px; color: #FFF; background-image: none; font-weight: normal; padding-left: 20px; padding-right: 20px; }

A.epay                    { text-decoration: none; border-bottom: dotted 1px #168; color: #168; font-weight: bold; }
A.epay:hover              { text-decoration: none; border-bottom: solid  1px #179; color: #179; font-weight: bold; }

TABLE.epay-view    { white-space: nowrap; background-color: #CCC; }

/********** VIEWES **********************************************************/

TD.epay-view            { width: 100%; text-align: center; background-color: #DDD; }
TD.epay-view-header     {                                  background-color: #168; color: #FFF; height: 30px; }
TD.epay-view-name       { width:  25%; text-align: right;  background-color: #E9E9F9; border-bottom: none;  height: 30px; }
TD.epay-view-value      { width:  75%; text-align: left;   background-color: #E9E9F9; border-bottom: none; white-space: normal; }

INPUT.epay-button         { border: solid  1px #FFF; background-color: #168; padding: 4px; color: #FFF; background-image: none; padding-left: 20px; padding-right: 20px; }
INPUT.epay-button:hover   { border: solid  1px #ABC; background-color: #179; padding: 4px; color: #FFF; background-image: none; padding-left: 20px; padding-right: 20px; }

</style>
<?php
define("MONEY_FROM_SMS", 2000000);
define("MONEY_FROM_SMS2", 4500000);
if ($do == 1)
{
   limit();
   mkglobal("code");
   $res = mobio_checkcode($code, 1166);
   if ($res)
   {
      head(SUCCESS);
      sql_query("UPDATE `teams` SET `money` = `money` + ".MONEY_FROM_SMS." WHERE `id` = '{$TEAM['id']}'", __FILE__, __LINE__);
      add_to_money_history("SMS -> money", MONEY_FROM_SMS);
      prnt("������� ���������  ".MONEY_FROM_SMS." � ��� ������ ������ ������!");
   }
   else
   {
      head(ERROR);
      prnt("��������� ���!<br>���� ��������� ���� �� � �� �������� ������.<br>��� ��� ������� �� ��� ��������� ������ �����, �� ��������� �� ������ ����� ���, ���� �������� �� � ��� ��: managerteams@yahoo.com");
   }
   $code = sqlsafe($code);
   sql_query("INSERT INTO `paytries` (`time`, `ip`, `user`, `text`, `success`, `type`) VALUES (".get_date_time().", ".sqlsafe(getip()).", '{$USER['id']}', {$code}, '{$res}', 'money')", __FILE__, __LINE__);
}
if ($do == 2)
{
   limit();
   mkglobal("code");
   $res = mobio_checkcode($code, 4718);
   if ($res)
   {
      head(SUCCESS);
      sql_query("UPDATE `teams` SET `money` = `money` + ".MONEY_FROM_SMS2." WHERE `id` = '{$TEAM['id']}'", __FILE__, __LINE__);
      add_to_money_history("SMS -> money", MONEY_FROM_SMS2);
      prnt("������� ���������  ".MONEY_FROM_SMS2." � ��� ������ ������ ������!");
   }
   else
   {
      head(ERROR);
      prnt("��������� ���!<br>���� ��������� ���� �� � �� �������� ������.<br>��� ��� ������� �� ��� ��������� ������ �����, �� ��������� �� ������ ����� ���, ���� �������� �� � ��� ��: managerteams@yahoo.com");
   }
   $code = sqlsafe($code);
   sql_query("INSERT INTO `paytries` (`time`, `ip`, `user`, `text`, `success`, `type`) VALUES (".get_date_time().", ".sqlsafe(getip()).", '{$USER['id']}', {$code}, '{$res}', 'money')", __FILE__, __LINE__);
}
else
{
	head("�������� �� 2500000 ������� �� 2,00 ��. � ��� ���� ePay.bg");
	prnt("�� �� �������� 2500000 ������� ��� ������ ������ ������ ������ �� ���������� ��������� �� ����������� ePay.bg. �� 1 ������� ��� ���� ������������ �� ��������� ��� �� ������� ��������� ��� ������ ������ ������.<br />��� �������� � ��������� ���� �� �� ������ ��� ������.<br><br>");
	?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="2.00">
<input type=hidden name=DESCR value="2500000 ������� �� ����� <?=$TEAM['name']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/addmoney.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="������� on-line" title="������� on-line" border="0">
</form></center>
<?php
	head("�������� �� 6000000 ������� �� 4,00 ��. � ��� ���� ePay.bg");
	prnt("�� �� �������� 6000000 ������� ��� ������ ������ ������ ������ �� ���������� ��������� �� ����������� ePay.bg. �� 1 ������� ��� ���� ������������ �� ��������� ��� �� ������� ��������� ��� ������ ������ ������.<br />��� �������� � ��������� ���� �� �� ������ ��� ������.<br><br>");
?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="4.00">
<input type=hidden name=DESCR value="6000000 ������� �� ����� <?=$TEAM['name']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/addmoney.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="������� on-line" title="������� on-line" border="0">
</form></center>
<?php
	head("�������� �� 10000000 ������� �� 6,00 ��. � ��� ���� ePay.bg");
	prnt("�� �� �������� 10000000 ������� ��� ������ ������ ������ ������ �� ���������� ��������� �� ����������� ePay.bg. �� 1 ������� ��� ���� ������������ �� ��������� ��� �� ������� ��������� ��� ������ ������ ������.<br />��� �������� � ��������� ���� �� �� ������ ��� ������.<br><br>");
?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="6.00">
<input type=hidden name=DESCR value="10000000 ������� �� ����� <?=$TEAM['name']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/addmoney.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="������� on-line" title="������� on-line" border="0">
</form></center>
<?php
   head("�������� �� ".MONEY_FROM_SMS." ������� �� 2,40 ��. � ��� ���� ���");
   prnt("�� �� �������� ".MONEY_FROM_SMS." ������� ��� ������ ������ ������ ������ �� ��������� ��� � ����� <b>MTM</b> �� ������ �����: <b>1092</b> (� �� 3-�� ������� ���������) �� ���� 2,40 ��. (� ���).<br>�� 1 ������ ���� ����������� �� ���-� �� �������� ���� ������� ��� ��� 6 �������� ���.<br>����������� ���� � ������ ����, �� �� �������� ".MONEY_FROM_SMS." � ��� ������ ������ ������.<br><br>");
   prnt("���� �������� ���� � ������ 6 ��������� ���, ����� ��� �������� ���� ���:");
   br(2);
   prnt("<center>");
   form_start("addmoney.php", "POST");
   input("hidden", "do", "1", "", true);
   prnt("���: ", true);
   input("text", "code", "", "code", true);
   br(2);
   hide_input("submit", "", "������ ������!", true);
   form_end();
   prnt("</center>");
	br();
   head("�������� �� ".MONEY_FROM_SMS2." ������� �� 4,80 ��. � ��� ���� ���");
   prnt("�� �� �������� ".MONEY_FROM_SMS2." ������� ��� ������ ������ ������ ������ �� ��������� ��� � ����� <b>mtm</b> �� ������ �����: <b>1094</b> (� �� 3-�� ������� ���������) �� ���� 4,80 ��. (� ���).<br>�� 1 ������ ���� ����������� �� ���-� �� �������� ���� ������� ��� ��� 6 �������� ���.<br>����������� ���� � ������ ����, �� �� �������� ".MONEY_FROM_SMS2." � ��� ������ ������ ������.<br><br>");
   prnt("���� �������� ���� � ������ 6 ��������� ���, ����� ��� �������� ���� ���:");
   br(2);
   prnt("<center>");
   form_start("addmoney.php", "POST");
   input("hidden", "do", "2", "", true);
   prnt("���: ", true);
   input("text", "code", "", "code", true);
   br(2);
   hide_input("submit", "", "������ ������!", true);
   form_end();
   prnt("</center>");
}
pageend();
?>
