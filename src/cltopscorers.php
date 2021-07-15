<?php
define("IN_GAME", true);
include("common.php");
limit();
pagestart("Champions League דמכלאיסעמנט");
head("Champions League דמכלאיסעמנט");

$data = sql_query("SELECT *,
(SELECT `name` FROM `teams` WHERE `id` = `players`.`team`) AS `teamname` FROM `players`, `players_stats`
WHERE `players`.`id` = `players_stats`.`id`
ORDER BY `players_stats`.`cur_cl_goals` DESC LIMIT ".BEST_LIMIT, __FILE__, __LINE__);

$pos = 0;
$last = "";
table_start();
table_header("", NAME, GOALS, TEAM);
while ($row = mysql_fetch_assoc($data))
{
   $pos++;
   table_startrow();
   if ($row['cur_cl_goals'] != $last) table_cell($pos);
   else table_cell($pos);
   $last = $row['cur_cl_goals'];
   table_player_name($row['id'], $row['name'], $row['shortname']);
   table_cell($row['cur_cl_goals']);
   table_cell(create_link("teamdetails.php?id={$row['team']}", $row['teamname']));
   table_endrow();
   table_player_row($row['id'], 4);
}
table_end();
pageend();
?>
