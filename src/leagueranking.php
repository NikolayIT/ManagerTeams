<?php
/*
File name: leagueranking.php
Last change: Thu Jan 24 21:51:32 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("id", false);
if (empty($id) || !is_numeric($id))
{
   $league = $TEAM['league'];
   $id = sql_get("SELECT `id` FROM `match_type` WHERE `name` = ".sqlsafe($TEAM['league']), __FILE__, __LINE__);
}
else $league = sql_get("SELECT `name` FROM `match_type` WHERE `id` = ".sqlsafe($id), __FILE__, __LINE__);
$ln = substr($league, 1);
if ($ln == "A.1") $ln = "A";
pagestart(TOTAL_RANKING_OF_DIVISION." {$ln}");
head(TOTAL_RANKING_OF_DIVISION." {$ln}");
form_start("leagueranking.php", "GET");
select("id", "", true);
$dat = sql_query("SELECT `id`, `name` FROM `match_type` WHERE `type` = 'League'", __FILE__, __LINE__);
while ($row = mysql_fetch_assoc($dat))
{
   $leagnam = substr($row['name'], 1);
   if ($leagnam == "A.1") $leagnam = "A";
   option($row['id'], $leagnam, $row['name'] == $league, true);
}
end_select();
input("submit", "", SHOW, "", true);
form_end();
br(1);
$data = sql_query("SELECT *,
(`goalsscored` - `goalsconceded`) AS `goaldiff`
FROM `teams` WHERE `league` = '{$league}'
ORDER BY `points` DESC, `goaldiff` DESC, `goalsscored` DESC, `wins` DESC, `id` DESC", __FILE__, __LINE__);
table_start();
table_header("", NAME, TOTAL, POINTS, WINS, DRAWS, LOSES, "+", "-", GOAL_DIFFERENCE);
$pos = 0;
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   if ($pos == 1 || $pos == 2) $style = "tb2";
   else if ($pos == 11 || $pos == 12 || $pos == 13 || $pos == 14 || $pos == 15 || $pos == 16) $style = "tb3";
   else $style = "tb";
   table_startrow();
   table_cell($pos, 1, $style);
   table_cell(create_link("teamdetails.php?id={$row['id']}", $row['name']), 1, $style);
   table_cell($row['total'], 1, $style);
   table_cell($row['points'], 1, $style);
   table_cell($row['wins'], 1, $style);
   table_cell($row['draws'], 1, $style);
   table_cell($row['loses'], 1, $style);
   table_cell($row['goalsscored'], 1, $style);
   table_cell($row['goalsconceded'], 1, $style);
   table_cell($row['goaldiff'] > 0 ? "+".$row['goaldiff'] : $row['goaldiff'], 1, $style);
   table_endrow();
}
table_end();
if(limit_cover(UC_VIP_USER))
{
   br();
   head(LATEST_COMMENTS);
   $comments = sql_query("SELECT *, (SELECT `username` FROM `users` WHERE `id` = `match_type_comments`.`from`) AS `fromname` FROM `match_type_comments` WHERE `match_type` = {$id} ORDER BY `time` DESC LIMIT 20", __FILE__, __LINE__);
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
         if (limit_cover(UC_MODERATOR)) table_cell(create_link("comment_league.php?do=delete&id={$row['id']}", DELETE));
         table_endrow();
      }
      table_end();
      create_button("comment_league.php?do=showall&id={$id}", SHOW_ALL);
   }
   br(2);
   head(POST_COMMENT);
   form_start("comment_league.php?do=post&id={$id}", "POST");
   table_start(false);
   table_row(MESSAGE, textarea("", "text", 50, 6, "", false));
   table_startrow();
   table_th(input("submit", "", SEND), 2);
   table_endrow();
   table_end();
   form_end();
}
pageend();
?>
