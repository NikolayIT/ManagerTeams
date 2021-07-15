<?php
define("IN_GAME", true);
include("common.php");
limit();

pagestart("Лотария");
head("Лотария");
mkglobal("do", true);
if ($do == "newticket")
{
   add_to_money_history("Билет за лотарията", -LOTTERY_TICKET, $TEAM['id'], true);
   sql_query("INSERT INTO `lottery` (`team`, `user`) VALUES('{$TEAM['id']}', '{$USER['id']}')", __FILE__, __LINE__);
   $id = mysql_insert_id();
   print("<b>Успешно си купихте билет за лотарията.<br />Номерчето на билета е #{$id}.<br />Можете да си купите произволен брой билети.<br />Колкото повече билети имате, толкова по-голям е шансът ви да спечелите!</b><br /><br />");
}
$count = sql_get("SELECT COUNT(*) FROM `lottery`", __FILE__, __LINE__);
print("<b>Победител от вчера: {$config['lottery_winner']}</b><br />");
print("<b>Купени билети за днес: {$count}</b><br />");
print("<b>Победителят получава: ".($count*LOTTERY_TICKET*LOTTERY_WIN)."€</b><br />");
$userticketscount = sql_get("SELECT COUNT(*) FROM `lottery` WHERE `user` = '{$USER['id']}'", __FILE__, __LINE__);
$usertickets =  implode(", #", sql_array("SELECT * FROM `lottery` WHERE `user` = '{$USER['id']}'", __FILE__, __LINE__));
print("<b>Купени билети от вас днес: {$userticketscount}</b> (#$usertickets)<br /><br />");
?>
<form action="lottery.php" method="POST">
	<input type="hidden" name="do" value="newticket" />
	<input type="submit" value="Купете си билет за лотарията на цена 100000€" />
</form>
<?php
pageend();
?>