<?php
/*
File name: aj_get_kit.php
Last change: Sat Jan 12 18:12:52 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
define("DONT_UPDATE", true);
include("common.php");
limit();

function checkcolor($color)
{
   $hexchars = "_1234567890ABCDEFabcdef";
   if ($color == "") return false;
   for ($i = 0; $i < strlen($color); $i++) if (strpos($hexchars, $color[$i]) == false) return false;
   return true;
}
function fill_image($img, $x, $y, $color)
{
   $col = sscanf($color, '%2x%2x%2x');
   $col = imagecolorallocate($img, $col[0], $col[1], $col[2]);
   imagefill($img, $x, $y, $col);
}
$c1 = "";
$c2 = "";
$c3 = "";
mkglobal("type:c1:c2:c3:dont");
if ($type != "away") $type = "home";
if (empty($c1) || !checkcolor($c1)) $c1 = $TEAM["{$type}tshirt"];
if (empty($c2) || !checkcolor($c2)) $c2 = $TEAM["{$type}shorts"];
if (empty($c3) || !checkcolor($c3)) $c3 = $TEAM["{$type}socks"];

$img = imagecreatefrompng("images/kit.png");
$imgWidth = imagesx($img);
$imgHeight = imagesy($img);

fill_image($img, 45, 25, $c1);
fill_image($img, 45, 100, $c2);
fill_image($img, 30, 120, $c3);
fill_image($img, 70, 120, $c3);

$c1 = sqlsafe($c1);
$c2 = sqlsafe($c2);
$c3 = sqlsafe($c3);
if (!$dont && limit_cover(UC_VIP_USER)) sql_query("UPDATE `teams` SET `{$type}tshirt` = {$c1}, `{$type}shorts` = {$c2}, `{$type}socks` = {$c3} WHERE `id` = {$TEAM['id']}", __FILE__, __LINE__);

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
