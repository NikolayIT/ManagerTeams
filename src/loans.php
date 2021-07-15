<?php
/*
File name: loans.php
Last change: Fri Jan 25 08:53:10 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
if (!sql_get("SELECT `id` FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = 'accountant'", __FILE__, __LINE__)) info("Нямате счетоводител и не можете да използвате банката!", ERROR);
if($USER['registred'] > get_date_time(false, -TIME_WEEK*4)) info("You must be registred before at least 1 month to use the loans", ERROR);
mkglobal("return");
$curloan = sql_data("SELECT * FROM `loans` WHERE `team` = {$TEAM['id']} AND `payed` = 'no' LIMIT 1", __FILE__, __LINE__);
if ($return && $curloan && limit_cover(UC_VIP_USER))
{
   $res = ($curloan['money'] / $curloan['parts']) * ($curloan['parts'] - $curloan['part']) + LOANS_RETURN_TAX_PERCENT * $curloan['money'];
   if ($TEAM['money'] < $res) info(NOT_ENOUGHT_MONEY, ERROR);
   add_to_money_history("{_LOAN_RETURNED_}", -$res, $TEAM['id'], true);
   sql_query("UPDATE `loans` SET `part` = `parts`, `payed` = 'yes' WHERE `id` = {$curloan['id']}", __FILE__, __LINE__);
   info(LOAN_RETURNED_SUCCESSFULLY, SUCCESS);
}
if ($USER['registred'] > get_date_time(false, -3600*24*LOANS_MINIMUM_DAYS)) info(NOT_PRIVILEGED_TO_ASK_LOANS.".<br>".MUST_BE_REGISTRED_BEFORE_MORE_THAN." ".LOANS_MINIMUM_DAYS." "._DAYS."!", LOANS, false);
pagestart(LOANS);
if ($curloan)
{
   $topay = $curloan['money'] / $curloan['parts'] + $curloan['money'] * $interest;
   $allpay = $curloan['money'] + $curloan['money'] * $interest * $curloan['parts'];
   $res = ($curloan['money'] / $curloan['parts']) * ($curloan['parts'] - $curloan['part']) + 0.05 * $curloan['money'];
   head(CURRENT_LOAN);
   prnt(TIME_ASKED.": {$curloan['time']}", true);
   prnt(MONEY.": {$curloan['money']} ".MONEY_SIGN, true);
   prnt(TO_PAY.": {$allpay} ".MONEY_SIGN, true);
   prnt(PART.": {$curloan['part']}", true);
   prnt(PARTS.": {$curloan['parts']}", true);
   prnt(PART_PAY.": {$topay} ".MONEY_SIGN, true);
   prnt(create_link("loans.php?return=1", "<b>".RETURN_YOUR_LOAN_NOW.". ({$res} ".MONEY_SIGN.")</b>")." (".FOR_VIP_USER_ONLY.")", true);
   br();
}
else
{
   mkglobal("ask:type");
   if ($ask == "yes" && !empty($type))
   {
      $mon = 0;
      $days = 0;
      if ($type == 1) { $mon = 100000; $days = 20; }
      else if ($type == 2) { $mon = 200000; $days = 30; }
      else if ($type == 3) { $mon = 500000; $days = 45; }
      else if ($type == 4) { $mon = 1000000; $days = 60; }
      else if ($type == 5 && limit_cover(UC_VIP_USER)) { $mon = 5000000; $days = 200; }
      else if ($type == 6 && limit_cover(UC_VIP_USER)) { $mon = 10000000; $days = 365; }
      else if ($type == 7 && limit_cover(UC_VIP_USER)) { $mon = 20000000; $days = 730; }
      else info(INVALID_SCTIPT_CALL, ERROR);
      sql_query("INSERT INTO `loans` (`team`, `money`, `part`, `parts`, `time`, `payed`) VALUES ({$TEAM['id']}, '{$mon}', 0, '{$days}', ".get_date_time().", 'no')", __FILE__, __LINE__);
      sql_query("UPDATE `teams` SET `money` = `money` + {$mon} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);
      add_to_money_history("{_SUCCESSFULLY_GOT_LOAN_}", $mon, $TEAM['id']);
      info(SUCCESSFULLY_GOT_LOAN, SUCCESS);
   }
   else
   {
      head(LOANS);
      prnt(create_link("loans.php?ask=yes&type=1", TYPE." 1: 100000 ".MONEY_SIGN." "._FOR." 20 "._DAYS), true);
      prnt(create_link("loans.php?ask=yes&type=2", TYPE." 2: 200000 ".MONEY_SIGN." "._FOR." 30 "._DAYS), true);
      prnt(create_link("loans.php?ask=yes&type=3", TYPE." 3: 500000 ".MONEY_SIGN." "._FOR." 45 "._DAYS), true);
      prnt(create_link("loans.php?ask=yes&type=4", TYPE." 4: 1000000 ".MONEY_SIGN." "._FOR." 60 "._DAYS), true);
      prnt(create_link("loans.php?ask=yes&type=5", TYPE." 5: 5000000 ".MONEY_SIGN." "._FOR." 200 "._DAYS)." (".FOR_VIP_USER_ONLY.")", true);
      prnt(create_link("loans.php?ask=yes&type=6", TYPE." 6: 10000000 ".MONEY_SIGN." "._FOR." 365 "._DAYS)." (".FOR_VIP_USER_ONLY.")", true);
      prnt(create_link("loans.php?ask=yes&type=7", TYPE." 7: 20000000 ".MONEY_SIGN." "._FOR." 730 "._DAYS)." (".FOR_VIP_USER_ONLY.")", true);
      prnt("", true);
   }
}
head(YOUR_LOANS);
$loans = sql_query("SELECT * FROM `loans` WHERE `team` = {$TEAM['id']} ORDER BY `time` DESC", __FILE__, __LINE__);
if (mysql_num_rows($loans) == 0) prnt(YOU_DONT_HAVE_ANY_LOANS_YET);
else
{
   table_start();
   table_header(DATE, MONEY, TO_PAY, PART, PARTS, PART_PAY);
   while ($loan = mysql_fetch_assoc($loans))
   {
      $topay = $loan['money'] / $loan['parts'] + $loan['money'] * $interest;
      $allpay = $loan['money'] + $loan['money'] * $interest * $loan['parts'];
      table_startrow();
      table_cell($loan['time']);
      table_cell($loan['money']." ".MONEY_SIGN);
      table_cell($allpay." ".MONEY_SIGN);
      table_cell($loan['part']);
      table_cell($loan['parts']);
      table_cell($topay." ".MONEY_SIGN);
      table_endrow();
   }
   table_end();
}
pageend();
?>
