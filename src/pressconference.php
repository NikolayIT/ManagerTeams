<?php
define("IN_GAME", true);
include("common.php");
limit();
mkglobal("edit");
if ($edit == "yes")
{
	$data = sqlsafe(serialize($_POST));
	sql_query("UPDATE `users` SET `pressconference` = {$data} WHERE `id` = {$USER['id']}", __FILE__, __LINE__);
	info("Вашето изявление е направено успешно! Можете да го видите на страницата на отбора си, както и на страницата на вашия профил.", SUCCESS, true);
}
else
{
	pagestart("Пресконференция");
	head("Пресконференция");
	form_start("pressconference.php?edit=yes", "POST");
	table_start(false);
	foreach($press as $i => $q)
	{
		print("<tr>");
		print("<th style=\"white-space: normal;\">{$q['question']}</th>");
		print("<td class=\"tb\">");
		if ($q['type'] == "select")
		{
			select("{$q['question']}", "", true);
			foreach($q['options'] as $j => $option)
				option($option, $option, $j == 0, true);
			end_select(true);
		}
		else if ($q['type'] == "text")
		{
			input("text", "{$q['question']}", "", "", true);
		}
		else if ($q['type'] == "textbox")
		{
			textarea("", "{$q['question']}", 30, 4, "", true);
		}
		print("</td></tr>");
	}
	table_startrow();
	table_th(input("submit", "", "Направи изявлението", ""), 2);
	table_endrow();
	table_end();
	form_end();
	pageend();
}
?>