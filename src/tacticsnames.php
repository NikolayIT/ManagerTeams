<?php
define("IN_GAME", true);
include("common.php");
limit(UC_VIP_USER);
mkglobal("edit", true);
additionaldata(true);
if ($edit == "yes")
{
   mkglobal("tacticname1:tacticname2:tacticname3:tacticname4:tacticname5", true);
   $tacticname1 = sqlsafe($tacticname1);
   $tacticname2 = sqlsafe($tacticname2);
   $tacticname3 = sqlsafe($tacticname3);
   $tacticname4 = sqlsafe($tacticname4);
   $tacticname5 = sqlsafe($tacticname5);
   sql_query("UPDATE `users` SET `tacticname1` = {$tacticname1}, `tacticname2` = {$tacticname2}, `tacticname3` = {$tacticname3}, `tacticname4` = {$tacticname4}, `tacticname5` = {$tacticname5} WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
   info("Имената на тактитките са успешно променени", SUCCESS, true);
}
else
{
   pagestart("Промяна на имената на тактиките");
   head("Промяна на имената на тактиките");
   form_start("tacticsnames.php", "POST");
   input("hidden", "edit", "yes", "", true);
   table_start(false);
   table_row(MAIN_SELECTION, input("text", "tacticname1", $USER['tacticname1']));
   table_row(SELECTION." 2", input("text", "tacticname2", $USER['tacticname2']));
   table_row(SELECTION." 3", input("text", "tacticname3", $USER['tacticname3']));
   table_row(SELECTION." 4", input("text", "tacticname4", $USER['tacticname4']));
   table_row(SELECTION." 5", input("text", "tacticname5", $USER['tacticname5']));
   table_startrow();
   table_th(input("reset", "", RESET, ""));
   table_th(input("submit", "", CHANGE, ""));
   table_endrow();
   table_end();
   form_end();
   pageend();
}
?>
