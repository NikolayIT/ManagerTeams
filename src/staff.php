<?php
define("IN_GAME", true);
include("common.php");
limit();
pagestart(STAFF);
$types = array("coach", "doctor", "scout", "accountant");
$names = array(COACHES, DOCTORS, SCOUTS, "—четоводител");
$i = 0;
foreach ($types as $type)
{
   $staff = sql_data("SELECT * FROM `staff` WHERE `team` = {$TEAM['id']} AND `type` = '{$type}'", __FILE__, __LINE__);
   head($names[$i++]);
   if ($staff)
   {
      if ($staff['atcourse'] == "yes") $courseinfo = YES_UNTIL." ".$staff['courseuntil'];
      else $courseinfo = NO;
      table_start();
      table_row2(NAME, $staff['name'], AGE, $staff['age']);
      table_row2(RATING, create_progress_bar($staff['rating']), WAGE, $staff['wage']." И / "._DAY);
      table_row2(AT_COURSE, $courseinfo, CONTRACT_LEFT, $staff['contrtime']." "._DAYS);
      table_end();
      create_button("staffsack.php?type={$type}", SACK." {$type}", false, false);
      create_button("staffcontract.php?id={$staff['id']}", CONTRACT_NEGOTIATIONS, false, false);
      create_button("staffview.php?type={$type}", POSSIBLE_REPLACEMENTS, false, false);
      create_button("staffcourse.php?type={$type}", ARRANGE_COURSE, false, false);
      br(2);
   }
   else
   {
      prnt(YOU_DONT_HAVE_A." {$type}.", true);
      create_link("staffview.php?type={$type}", HIRE." ".$type, true);
      br(2);
   }
   br();
}
pageend();
?>