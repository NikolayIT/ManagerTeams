<?php
define("IN_GAME", true);
include("common.php");

sql_query("UPDATE `match` SET `hometeam` = FLOOR(1 + RAND() * 40000) WHERE `hometeam` = 0 AND `type` = 3281", __FILE__, __LINE__);
sql_query("UPDATE `match` SET `awayteam` = FLOOR(1 + RAND() * 40000) WHERE `awayteam` = 0 AND `type` = 3281", __FILE__, __LINE__);
sql_query("UPDATE `config` SET `value` = '0' WHERE `id` = 21", __FILE__, __LINE__);
?>
