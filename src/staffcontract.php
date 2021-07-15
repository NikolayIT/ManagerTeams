<?php
/*
File name: staffcontract.php
Last change: Thu Feb 07 15:32:26 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
if ($TEAM['money'] < 0) info(NOT_ENOUGHT_MONEY, ERROR);
mkglobal("id");
$id = sqlsafe($id);
$staff = sql_data("SELECT * FROM `staff` WHERE `id` = {$id} AND (`team` = 0 OR `team` = {$TEAM['id']})", __FILE__, __LINE__);
if (!$staff) prnt(WRONG_ID, ERROR);
mkglobal("do:years");
if ($do == "yes" && !empty($years) && is_numeric($years) && $years > 0 && $years < 4)
{
   $days = $years * $config['matchcount'];
   $wage = $staff['rating'] * constant("STAFF_WAGE_Y{$years}");
   sql_query("UPDATE `staff` SET `team` = 0, `contrtime` = 0 WHERE `type` = '{$staff['type']}' AND `team` = {$TEAM['id']}", __FILE__, __LINE__);
   sql_query("UPDATE `staff` SET `team` = {$TEAM['id']}, `contrtime` = {$days}, `wage` = {$wage} WHERE `id` = {$id}", __FILE__, __LINE__);
   info(CONTRACT_ACCEPTED."<br>".create_link("staff.php", GO_TO_STAFF_OVERVIEW), SUCCESS, false);
}
pagestart(OFFER_CONTRACT_FOR." ".$staff['name']);
head(OFFER_CONTRACT_FOR." ".$staff['name']);
prnt(NAME.": {$staff['name']}", true);
prnt(AGE.": ".$staff['age'], true);
prnt(RATING.": {$staff['rating']}<br>", true);
$off1 = STAFF_WAGE_Y1 * $staff['rating'];
$off2 = STAFF_WAGE_Y2 * $staff['rating'];
$off3 = STAFF_WAGE_Y3 * $staff['rating'];
prnt(OFFER." 1: ".WAGE.": {$off1} ".FOR_1_SEASON." ".create_link("staffcontract.php?id={$id}&do=yes&years=1", ACCEPT), true);
prnt(OFFER." 2: ".WAGE.": {$off2} ".FOR_2_SEASONS." ".create_link("staffcontract.php?id={$id}&do=yes&years=2", ACCEPT), true);
prnt(OFFER." 3: ".WAGE.": {$off3} ".FOR_3_SEASONS." ".create_link("staffcontract.php?id={$id}&do=yes&years=3", ACCEPT), true);
pageend();
?>
