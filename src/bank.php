<?php
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
if (!sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'accountant'", __FILE__, __LINE__)) info("Нямате счетоводител и не можете да използвате банката!", ERROR);

$time = array(7, 14, 30, 60, 90, 180, 365, 730);
$coef = array(1, 2.2, 5, 12, 20, 50, 100, 200);
mkglobal("do:ret");
if ($ret)
{
   add_to_money_history("{_WITHDRAW_}", +$TEAM['bank_in'], $TEAM['id'], true);
   sql_query("UPDATE `teams` SET `bank_in` = 0, `bank_out` = 0 WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
   info(WITHDRAW_SUCCESSFUL, SUCCESS, true);
}
else if ($do)
{
   mkglobal("money:for");
   $money += 0;
   $for += 0;
   if ($for < 0 || $for >= count($time)) info(INVALID_SCTIPT_CALL, ERROR);
   if ($money <= 0) info(MISSING_DATA, ERROR);
   if ($TEAM['money'] < $money) info(NOT_ENOUGHT_MONEY, ERROR);
   $money = sqlsafe($money);
   $new = sqlsafe(ceil((100 + $coef[$for]) * $money / 100));
   $until = get_date_time(true, +$time[$for]*TIME_DAY);
   add_to_money_history("{_DEPOSIT_}", -$money, $TEAM['id'], true);
   sql_query("UPDATE `teams` SET `bank_in` = {$money}, `bank_out` = {$new}, `bank_until` = {$until} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
   info(DEPOSIT_SUCCESSFUL, SUCCESS, true);
}
else
{
   pagestart(BANK);
   head(BANK);
   if ($TEAM['bank_out'])
   {
      prnt(YOU_HAVE_DEPOSTED_MONEY.": ".$TEAM['bank_in'].MONEY_SIGN."<br>");
      prnt(YOU_WILL_RECIEVE.": ".$TEAM['bank_out'].MONEY_SIGN." ".ON." ".$TEAM['bank_until']."<br>");
      prnt(create_link("bank.php?ret=1", WITHDRAW));
   }
   else
   {
      print(BANK_TEXT."<br><br>");
      form_start("bank.php");
      print(MONEY.": ".input("textbox", "money", "0")."<br><br>");
      print(TIME.": ");
      select("for", "", true);
      for ($i = 0; $i < count($time); $i++)
      {
         option($i, "{$time[$i]} ".DAYS." -> +{$coef[$i]}%", $i == 0, true);
      }
      end_select(true);
      br(2);
      input("submit", "do", DEPOSIT_THESE_MONEY, "", true);
      form_end();
   }
   pageend();
}
?>