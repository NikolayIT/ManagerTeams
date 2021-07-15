<?php
/*
File name: addmoney.php
Last change:
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart("Добави пари");
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
      prnt("Успешно добавихте  ".MONEY_FROM_SMS." € към своята клубна сметка!");
   }
   else
   {
      head(ERROR);
      prnt("Невалиден код!<br>Моля проверете кода си и го въведете отново.<br>Ако сте сигурни че сте направили всичко точно, но системата не приема вашия код, моля свържете се с нас на: managerteams@yahoo.com");
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
      prnt("Успешно добавихте  ".MONEY_FROM_SMS2." € към своята клубна сметка!");
   }
   else
   {
      head(ERROR);
      prnt("Невалиден код!<br>Моля проверете кода си и го въведете отново.<br>Ако сте сигурни че сте направили всичко точно, но системата не приема вашия код, моля свържете се с нас на: managerteams@yahoo.com");
   }
   $code = sqlsafe($code);
   sql_query("INSERT INTO `paytries` (`time`, `ip`, `user`, `text`, `success`, `type`) VALUES (".get_date_time().", ".sqlsafe(getip()).", '{$USER['id']}', {$code}, '{$res}', 'money')", __FILE__, __LINE__);
}
else
{
	head("Добавяне на 2500000 кредита за 2,00 лв. с ДДС чрез ePay.bg");
	prnt("За да добавите 2500000 кредита към своята клубна сметка можете да използвате системата за разплащания ePay.bg. До 1 работен ден след получаването на плащането ние ще добавим кредитите във вашата клубна сметка.<br />При проблеми с плащането моля да ни пишете във форума.<br><br>");
	?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="2.00">
<input type=hidden name=DESCR value="2500000 кредита за отбор <?=$TEAM['name']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/addmoney.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="Плащане on-line" title="Плащане on-line" border="0">
</form></center>
<?php
	head("Добавяне на 6000000 кредита за 4,00 лв. с ДДС чрез ePay.bg");
	prnt("За да добавите 6000000 кредита към своята клубна сметка можете да използвате системата за разплащания ePay.bg. До 1 работен ден след получаването на плащането ние ще добавим кредитите във вашата клубна сметка.<br />При проблеми с плащането моля да ни пишете във форума.<br><br>");
?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="4.00">
<input type=hidden name=DESCR value="6000000 кредита за отбор <?=$TEAM['name']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/addmoney.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="Плащане on-line" title="Плащане on-line" border="0">
</form></center>
<?php
	head("Добавяне на 10000000 кредита за 6,00 лв. с ДДС чрез ePay.bg");
	prnt("За да добавите 10000000 кредита към своята клубна сметка можете да използвате системата за разплащания ePay.bg. До 1 работен ден след получаването на плащането ние ще добавим кредитите във вашата клубна сметка.<br />При проблеми с плащането моля да ни пишете във форума.<br><br>");
?>
<center><form action="https://www.epay.bg/" method=post>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="5741678629">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="6.00">
<input type=hidden name=DESCR value="10000000 кредита за отбор <?=$TEAM['name']?>">
<input type=hidden name=URL_OK value="http://managerteams.com/payok.php">
<input type=hidden name=URL_CANCEL value="http://managerteams.com/addmoney.php">
<input type=image src="http://online.datamax.bg/epaynow/b01.gif" name=BUTTON:EPAYNOW value="" alt="Плащане on-line" title="Плащане on-line" border="0">
</form></center>
<?php
   head("Добавяне на ".MONEY_FROM_SMS." кредита за 2,40 лв. с ДДС чрез СМС");
   prnt("За да добавите ".MONEY_FROM_SMS." кредита към своята клубна сметка трябва да изпратите СМС с текст <b>MTM</b> на кратък номер: <b>1092</b> (и за 3-те мобилни оператора) на цена 2,40 лв. (с ДДС).<br>До 1 минута след изпращането на СМС-а ще получите като отговор СМС със 6 символен код.<br>Използвайте кода в полето долу, за да добавите ".MONEY_FROM_SMS." € към своята клубна сметка.<br><br>");
   prnt("Моля въведете долу в полето 6 символния код, който сте получили като СМС:");
   br(2);
   prnt("<center>");
   form_start("addmoney.php", "POST");
   input("hidden", "do", "1", "", true);
   prnt("Код: ", true);
   input("text", "code", "", "code", true);
   br(2);
   hide_input("submit", "", "Добави парите!", true);
   form_end();
   prnt("</center>");
	br();
   head("Добавяне на ".MONEY_FROM_SMS2." кредита за 4,80 лв. с ДДС чрез СМС");
   prnt("За да добавите ".MONEY_FROM_SMS2." кредита към своята клубна сметка трябва да изпратите СМС с текст <b>mtm</b> на кратък номер: <b>1094</b> (и за 3-те мобилни оператора) на цена 4,80 лв. (с ДДС).<br>До 1 минута след изпращането на СМС-а ще получите като отговор СМС със 6 символен код.<br>Използвайте кода в полето долу, за да добавите ".MONEY_FROM_SMS2." € към своята клубна сметка.<br><br>");
   prnt("Моля въведете долу в полето 6 символния код, който сте получили като СМС:");
   br(2);
   prnt("<center>");
   form_start("addmoney.php", "POST");
   input("hidden", "do", "2", "", true);
   prnt("Код: ", true);
   input("text", "code", "", "code", true);
   br(2);
   hide_input("submit", "", "Добави парите!", true);
   form_end();
   prnt("</center>");
}
pageend();
?>
