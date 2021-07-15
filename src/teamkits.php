<?php
/*
File name: teamkits.php
Last change: Sat Feb 09 11:16:39 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
function create_color_select($id, $curr, $type)
{
   $colors =     array("000000", "FFFFFF", "FFFF00", "FF0000", "00FF00", "0000FF", "808080", "ADD8E6",     "FF1493", "EE82EE", "FF7F00", "106400",     "9ACD32");
   $colornames = array("Black" , "White" , "Yellow", "Red"   , "Green" , "Blue"  , "Gray",   "White blue", "Pink",   "Violet", "Orange", "Dark green", "Yellowgreen");
   $i = 0;
   $colorselect = "<select id='{$id}' name='{$id}' onchange=\"RefreshImage('{$type}kits', GetImageSrc('{$type}'))\">";
   foreach ($colornames as $color)
   {
      $value = $colors[$i++];
      $colorselect .= option($value, $color, $value == $curr);
   }
   $colorselect .= end_select();
   return $colorselect;
}
pagestart(KITS);
?>
<script language="JavaScript">
function GetImageSrc(type)
{
   var tshirt = document.getElementById(type+"tshirt").options[document.getElementById(type+"tshirt").selectedIndex].value;
   var shorts = document.getElementById(type+"shorts").options[document.getElementById(type+"shorts").selectedIndex].value;
   var socks = document.getElementById(type+"socks").options[document.getElementById(type+"socks").selectedIndex].value;
   return "aj_get_kit.php?type="+type+"&c1="+tshirt+"&c2="+shorts+"&c3="+socks;
}
function RefreshImage(imageid, src)
{
   document.getElementById(imageid).src = src;
}
</script>
<?php
head(HOME_TEAM_KITS);
prnt("<table width='100%' style='border:0px;'><tr><td width='50%' align='left' valign='top'>");
prnt(HOME_TSHIRT.": ".create_color_select("hometshirt", $TEAM['hometshirt'], "home"), true);
prnt(HOME_SHORTS.": ".create_color_select("homeshorts", $TEAM['homeshorts'], "home"), true);
prnt(HOME_SOCK.": ".create_color_select("homesocks", $TEAM['homesocks'], "home"), true);
prnt("</td><td width='50%' align='left' valign='top' style='padding-left:5px;'>");
prnt("<img id=\"homekits\" src=\"aj_get_kit.php?type=home&c1={$TEAM['hometshirt']}&c2={$TEAM['homeshorts']}&c3={$TEAM['homesocks']}\" alt=\"".HOME_TEAM_KITS."\"/>");
prnt("</td></tr></table>");
prnt("<br>", true);

head(AWAY_TEAM_KITS);
prnt("<table width='100%' style='border:0px;'><tr><td width='50%' align='left' valign='top'>");
prnt(AWAY_TSHIRT.": ".create_color_select("awaytshirt", $TEAM['awaytshirt'], "away"), true);
prnt(AWAY_SHORTS.": ".create_color_select("awayshorts", $TEAM['awayshorts'], "away"), true);
prnt(AWAY_SOCKS.": ".create_color_select("awaysocks", $TEAM['awaysocks'], "away"), true);
prnt("</td><td width='50%' align='left' valign='top' style='padding-left:5px;'>");
prnt("<img id=\"awaykits\" src=\"aj_get_kit.php?type=away&c1={$TEAM['awaytshirt']}&c2={$TEAM['awayshorts']}&c3={$TEAM['awaysocks']}\" alt=\"".AWAY_TEAM_KITS."\"/>");
prnt("</td></tr></table>");
pageend();
?>
