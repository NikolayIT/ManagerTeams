<?php
if (!defined("IN_ADMIN_PANEL")) exit;
limit(UC_ADMIN);
mkglobal("id:del");
$id = sqlsafe($id);
if ($del)
{
   sql_query("DELETE FROM `transfers` WHERE `id` = {$id}", __FILE__, __LINE__);
   info("���������� � ������ �������!<br>".create_link("activetransfers.php?all=1", "<b>������� ������� �� ��������� �� �����������</b>"), SUCCESS, false);
}
$transferinfo = sql_data("SELECT *,
(SELECT `name` FROM `players` WHERE `id` = `transfers`.`player`) AS `name`,
(SELECT `shortname` FROM `players` WHERE `id` = `transfers`.`player`) AS `shortname`,
(SELECT `name` FROM `teams` WHERE `id` = `transfers`.`fromteam`) AS `seller`,
(SELECT `name` FROM `teams` WHERE `id` = `transfers`.`offerteam`) AS `buyer`
FROM `transfers` WHERE `id` = '{$id}'", __FILE__, __LINE__);
if (!$transferinfo['id']) info("���� �������� �� ����������!", ERROR);
if (!$transferinfo['buyer']) info("����� �� � ��������� ������ �� ���� �����!<br>��� ��� ��� ������ �� �������� ���� �������� ��������� �� ����� ����:<br>".
create_link("admin.php?module=checktransfer&id={$id}&del=1", "�������� ���������! (��������: ���� ����������� ��������� �� ���������� �����������!)"), ERROR);
$playerinfo = sql_data("SELECT * FROM `players` WHERE `id` = '{$transferinfo['player']}'", __FILE__, __LINE__);
$selleruid = sql_get("SELECT `id` FROM `users` WHERE `team` = '{$transferinfo['fromteam']}'", __FILE__, __LINE__);
if ($selleruid > 0) $sellerips = sql_array("SELECT `ip` FROM `logs` WHERE `uid` = '{$selleruid}' GROUP BY `ip` ORDER BY `ip`", __FILE__, __LINE__);
$buyeruid = sql_get("SELECT `id` FROM `users` WHERE `team` = '{$transferinfo['offerteam']}'", __FILE__, __LINE__);
if ($buyeruid > 0) $buyerips = sql_array("SELECT `ip` FROM `logs` WHERE `uid` = '{$buyeruid}' GROUP BY `ip` ORDER BY `ip`", __FILE__, __LINE__);
$playername = get_player_name($transferinfo['name'], $transferinfo['shortname']);
$seller = create_link("teamdetails.php?id={$transferinfo['fromteam']}", $transferinfo['seller']);
$buyer = create_link("teamdetails.php?id={$transferinfo['offerteam']}", $transferinfo['buyer']);
$sips = "";
$flag = false;
if ($selleruid > 0) foreach ($sellerips as $value)
{
	$ip = sql_get("SELECT `ip` FROM `ips` WHERE `id` = {$value}", __FILE__, __LINE__);
   if ($buyeruid > 0) if (in_array($value, $buyerips))
   {
      $sips .= create_link("admin.php?module=ipinfo&ip={$ip}", $ip)." <b>!!!!!!!!</b><br>";
      $flag = true;
   }
   else $sips .= create_link("admin.php?module=ipinfo&ip={$ip}", $ip)."<br>";
}
$bips = "";
if ($buyeruid > 0) foreach ($buyerips as $value)
{
	$ip = sql_get("SELECT `ip` FROM `ips` WHERE `id` = {$value}", __FILE__, __LINE__);
   if ($selleruid > 0) if (in_array($value, $sellerips))
   {
      $bips .= create_link("admin.php?module=ipinfo&ip={$ip}", $ip)." <b>!!!!!!!!</b><br>";
      $flag = true;
   }
   else $bips .= create_link("admin.php?module=ipinfo&ip={$ip}", $ip)."<br>";
}

head("���������� �� �������� �� {$playername} �� {$seller} �� {$buyer}");
table_start();
table_startrow();
table_th("�����");
table_player_name($transferinfo['player'], $transferinfo['name'], $transferinfo['shortname']);
table_endrow();
table_player_row($transferinfo['player'], 2);
table_row("IP-�� �� ���������", $sips);
table_row("IP-�� �� ��������", $bips);
if ($flag) table_row("����������", "<b>���� ��������� �� ������� �� ���� � ���� IP!!!</b>");
else table_row("����������", "���� ��������� �� �� ������� �� ���� � ���� IP.");
table_row("���������", create_link("admin.php?module=checktransfer&id={$id}&del=1", "�������� ���������! (��������: ���� ����������� ��������� �� ���������� �����������!)"));
table_end();
print("".create_link("activetransfers.php?all=1", "<b>������� ������� �� ��������� �� �����������</b>"."<br><br>"));
?>
