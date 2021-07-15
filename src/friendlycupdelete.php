<?php
define("IN_GAME", true);
include("common.php");
limit(UC_ADMIN);
mkglobal("id");
if (!$id || !is_numeric($id)) info(WRONG_ID, ERR);

$id = sqlsafe($id);

sql_query("DELETE FROM `match_type` WHERE `id` = {$id}", __FILE__, __LINE__);
sql_query("DELETE FROM `friendly_participants` WHERE `type` = {$id}", __FILE__, __LINE__);
sql_query("DELETE FROM `match` WHERE `type` = {$id}", __FILE__, __LINE__);

info("Купата е изтрита успешно!", "Изтриване на купа");
?>