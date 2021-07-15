<?php
/*
File name: cup.php
Last change: Wed Jan 09 09:12:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart("Стани ВИП");
limit();
mkglobal("do");
if ($_GET['admin_vip_add'] == 1)
{
	limit(UC_ADMIN);
	$uid = $_GET['uid'];
      $time = sql_get("SELECT ADDTIME((SELECT IF(`vipuntil` < NOW(), NOW(), `vipuntil`) FROM `users` WHERE `id` = '{$uid}'), '30 0:0:0')", __FILE__, __LINE__);
      if ($time) sql_query("UPDATE `users` SET `class` = GREATEST(3, `class`), `vipuntil` = '{$time}' WHERE `id` = '{$uid}'", __FILE__, __LINE__);
      info("Успешно добавихте 30 дена към своя ВИП статуса на потребителя.<br>Неговият ВИП статус ще изтече на: {$time}", SUCCESS);
}
if ($_GET['admin_vip_remove'] == 1)
{
	limit(UC_ADMIN);
	$uid = $_GET['uid'];
      $time = sql_get("SELECT ADDTIME((SELECT IF(`vipuntil` < NOW(), NOW(), `vipuntil`) FROM `users` WHERE `id` = '{$uid}'), '-30 0:0:0')", __FILE__, __LINE__);
      if ($time) sql_query("UPDATE `users` SET `class` = GREATEST(3, `class`), `vipuntil` = '{$time}' WHERE `id` = '{$uid}'", __FILE__, __LINE__);
      info("Успешно премахнахте 30 дена към своя ВИП статуса на потребителя.<br>Неговият ВИП статус ще изтече на: {$time}", SUCCESS);
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
      prnt("Успешно добавихте 30 дена към своя ВИП статус.<br>Вашият ВИП статус ще изтече на: {$time}");
   }
   else
   {
      head(ERROR);
      prnt("Невалиден код!<br>Моля проверете кода си и го въведете отново.<br>Ако сте сигурни че сте направили всичко точно, но системата не приема вашия код, моля свържете се с нас на: managerteams@yahoo.com");
   }
   $code = sqlsafe($code);
   sql_query("INSERT INTO `paytries` (`time`, `ip`, `user`, `text`, `success`, `type`) VALUES (".get_date_time().", ".sqlsafe(getip()).", '{$USER['id']}', {$code}, '{$res}', 'vip')", __FILE__, __LINE__);
}
else
{
   head("Стани ВИП с електронно плащане през ePay.bg");
   prnt("За да станете VIP в играта можете да използвате системата за разплащания ePay.bg. До 1 работен ден след получаването на плащането ние ще добавим вашия ВИП статус.<br />При проблеми с плащането моля да ни пишете във форума.<br /><b><center>Цена: 2,40 лв с ДДС<br>Срок: 30 дни</b></center>");
	?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="2.40">
<input type=hidden name=DESCR value="30 дни VIP статус за потербител <?=$USER['username']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/vipstart.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="Плащане on-line" title="Плащане on-line" border="0">
</form></center>
	<?php
   head("Стани ВИП със СМС");
   prnt("За да станете ВИП потребител в играта (или да продължите ВИП членството си с 30 дена, ако вече сте ВИП потребител), трябва да изпратите СМС с текст <b>MTVIP</b> на кратък номер: <b>1092</b> (и за 3-те мобилни оператора) на цена 2,40 лв. (с ДДС).<br>До 1 минута след изпращането на СМС-а ще получите като отговор СМС със 6 символен код.<br>Използвайте кода, за да добавите 30 дена към вашия ВИП статус.<br>За да разберете какво печелите като ВИП потребител, моля вижте <a href='vipabout.php'>ТУК</a><br><br><br>");
   prnt("Моля въведете долу в полето 6 символния код, който сте получили като СМС:");
   br(2);
   prnt("<center>");
   form_start("vipstart.php", "POST");
   input("hidden", "do", "1", "", true);
   prnt("Код: ", true);
   input("text", "code", "", "code", true);
   br(2);
   hide_input("submit", "", "Стани ВИП!", true);
   form_end();
   prnt("</center>");
}
pageend();
?>
