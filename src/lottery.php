<?php
define("IN_GAME", true);
include("common.php");
limit();

pagestart("�������");
head("�������");
mkglobal("do", true);
if ($do == "newticket")
{
   add_to_money_history("����� �� ���������", -LOTTERY_TICKET, $TEAM['id'], true);
   sql_query("INSERT INTO `lottery` (`team`, `user`) VALUES('{$TEAM['id']}', '{$USER['id']}')", __FILE__, __LINE__);
   $id = mysql_insert_id();
   print("<b>������� �� ������� ����� �� ���������.<br />��������� �� ������ � #{$id}.<br />������ �� �� ������ ���������� ���� ������.<br />������� ������ ������ �����, ������� ��-����� � ������ �� �� ���������!</b><br /><br />");
}
$count = sql_get("SELECT COUNT(*) FROM `lottery`", __FILE__, __LINE__);
print("<b>��������� �� �����: {$config['lottery_winner']}</b><br />");
print("<b>������ ������ �� ����: {$count}</b><br />");
print("<b>����������� ��������: ".($count*LOTTERY_TICKET*LOTTERY_WIN)."�</b><br />");
$userticketscount = sql_get("SELECT COUNT(*) FROM `lottery` WHERE `user` = '{$USER['id']}'", __FILE__, __LINE__);
$usertickets =  implode(", #", sql_array("SELECT * FROM `lottery` WHERE `user` = '{$USER['id']}'", __FILE__, __LINE__));
print("<b>������ ������ �� ��� ����: {$userticketscount}</b> (#$usertickets)<br /><br />");
?>
<form action="lottery.php" method="POST">
	<input type="hidden" name="do" value="newticket" />
	<input type="submit" value="������ �� ����� �� ��������� �� ���� 100000�" />
</form>
<?php
pageend();
?>