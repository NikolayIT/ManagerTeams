<?php
function add_topic($title, $text, $fid)
{
   $title = urlencode($title);
   $text = urlencode($text);
   return file_get_contents("http://managerteams.com/add_topic.php?fid={$fid}&my_subject={$title}&my_text={$text}");
}


define("IN_GAME", true);
include("common.php");
limit(UC_ADMIN);

$teams = sql_query("SELECT * FROM `teams` ORDER BY `id` ASC", __FILE__, __LINE__);
while ($theteam = mysql_fetch_assoc($teams))
{
   $fid = add_topic($theteam['name'], "Your comments about team [url=http://managerteams.com/teamdetails.php?id={$theteam['id']}]".trim(str_replace("\n", "", str_replace("\r", "", $theteam['name'])))."[/url]", 51);
   sql_query("UPDATE `teams` SET `fid` = {$fid} WHERE `id` = {$theteam['id']}", __FILE__, __LINE__);
}
?>