<?php
/*
File name: matchreport.php
Last change: Fri Jan 25 09:00:14 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();

mkglobal("id:type", false);
if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR, true);
$check = sql_get("SELECT `id` FROM `match` WHERE `id` = {$id}", __FILE__, __LINE__);
if (!$check) info(WRONG_ID, ERROR, true);
if ($type == "xml")
{
   limit(UC_VIP_USER);
   header("Content-Type: application/xml; charset=utf-8");
   include("include/match_information.php");
   $match = new MatchInformation($id);
   print $match->create_xml();
}
else if ($type == "text")
{
   include("./include/match_information.php");
   $match = new MatchInformation($id);
   pagestart(MATCH_REPORT);
   print $match->create_text();
   pageend();
}
else if ($type == "flex")
{
   limit(UC_VIP_USER);
   pagestart(MATCH_REPORT);
   head("Flex match report alpha");
   ?>
   <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
		id="flex" width="725" height="570"
		codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
		<param name="movie" value="flex.swf" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#869ca7" />
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="flashVars" value="mid=<?=$id?>&lang=<?=$lang?>"/>
		<embed src="flex.swf" quality="high" bgcolor="#869ca7"
			width="725" height="570" name="flex" align="middle"
			play="true"
			loop="false"
			flashVars="mid=<?=$id?>&lang=<?=$lang?>"
			quality="high"
			allowScriptAccess="sameDomain"
			type="application/x-shockwave-flash"
			pluginspage="http://www.adobe.com/go/getflashplayer">
		</embed>
   </object>
   <?php
   pageend();
}
else if ($type == "download")
{
   limit(UC_VIP_USER);
   die("Comming soon!");
}
else
{
   pagestart(MATCH_REPORT);
   $match = sql_data("SELECT *,
   (SELECT `name` FROM `teams` WHERE `id` = `match`.`hometeam`) AS `homename`,
   (SELECT `name` FROM `teams` WHERE `id` = `match`.`awayteam`) AS `awayname`
   FROM `match` WHERE `id` = {$id}", __FILE__, __LINE__);
   $theid = "000".$id;
   $l = strlen($theid);
   $filename = "./cache/matches/{$theid[$l-4]}/{$theid[$l-3]}/{$theid[$l-2]}/{$theid[$l-1]}/{$id}.txt.gz";
   if (file_exists($filename))
   {
      head("Репортаж за мача {$match['homename']} - {$match['awayname']} {$match['homescore']} : {$match['awayscore']}");
      print("&bull; ".create_link("matchreport.php?id={$id}&type=text", "<b>Вижте текстовия репортаж от мача >>></b>")."<br>");
      print("&bull; ".create_link("matchreport.php?id={$id}&type=flex", "<b>Вижте флаш репортажа от мача (Само за VIP!) >>></b>")."<br>");
      br();
      head(LATEST_COMMENTS);
      $comments = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `match_comments`.`from`) AS `fromname` FROM `match_comments` WHERE `match` = {$id} ORDER BY `time` DESC LIMIT 20", __FILE__, __LINE__);
      if (mysql_num_rows($comments) == 0) prnt(NO_COMMENTS);
      else
      {
         table_start();
         if (limit_cover(UC_MODERATOR)) table_header(TIME, MANAGER, COMMENT, DELETE);
         else table_header(TIME, MANAGER, COMMENT);
         while ($row = mysql_fetch_assoc($comments))
         {
            table_startrow();
            table_cell($row['time']);
            table_cell(create_link("viewprofile.php?id={$row['from']}", $row['fromname']));
            table_cell(bbcode($row['text']), 1, "tbwrap");
            if (limit_cover(UC_MODERATOR)) table_cell(create_link("comment_match.php?do=delete&id={$row['id']}", DELETE));
            table_endrow();
         }
         table_end();
         create_button("comment_match.php?do=showall&id={$id}", SHOW_ALL);
      }
      br(2);
      head(POST_COMMENT);
      form_start("comment_match.php?do=post&id={$id}", "POST");
      table_start(false);
      table_row(MESSAGE, textarea("", "text", 50, 6, "", false));
      table_startrow();
      table_th(input("submit", "", SEND), 2);
      table_endrow();
      table_end();
      form_end();
   }
   else info(MATCH_NOT_FOUND_TEXT, MATCH_NOT_FOUND);
   pageend();
}
?>
