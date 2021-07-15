<?php
define("IN_GAME", true);
include("common.php");
limit();

mkglobal("id:option");
$id = sqlsafe(0 + $id);
$option = sqlsafe(0 + $option);

$poll = sql_data("SELECT * FROM `polls` WHERE `id` = {$id} AND `active` = 'yes'", __FILE__, __LINE__);
if (!$poll) info("Тази анкета не е активна!", ERROR);

if ($option > 20 || $option < 0) info("Невалиден отговор!", ERROR);

$check = sql_get("SELECT `id` FROM `poll_votes` WHERE `user` = {$USER['id']} AND `poll` = {$id}", __FILE__, __LINE__);
if ($check) info("Вие вече сте гласуваали за тази анкета!", ERROR);

sql_query("INSERT INTO `poll_votes` (`user`, `poll`, `option`) VALUES ({$USER['id']}, {$id}, {$option})", __FILE__, __LINE__);
cache_polls_resuls();
info("Успешно гласувахте в анкетата. Благодарим Ви! Вашето мнение е важно за нас.", "Успешно гласуване");
?>