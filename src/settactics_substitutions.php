<?php
define("IN_GAME", true);
include("common.php");
mkglobal("id:back", true);
$id = sqlsafe($id);
if (empty($id) || !is_numeric($id)) info(WRONG_ID, ERROR);
$id = sql_get("SELECT `id` FROM `tactics` WHERE `id` = {$id}", __FILE__, __LINE__);
if ($id <= 0) info(WRONG_ID, ERROR);
$match = sql_get("SELECT `id` FROM `match` WHERE ((`hometeam` = {$TEAM['id']} AND `hometactic` = {$id}) OR (`awayteam` = {$TEAM['id']} AND `awaytactic` = {$id})) AND `played` = 'no'", __FILE__, __LINE__);
if ($TEAM['tactic1'] == $id || $TEAM['tactic2'] == $id || $TEAM['tactic3'] == $id || $TEAM['tactic4'] == $id || $TEAM['tactic5'] == $id || $match > 0)
{
   $q = "";
   foreach ($_POST as $key => $value) if (strstr($key, "sub") !== false) $q .= ", ".sqlsafe_name($key)." = ".sqlsafe($value);
   sql_query("UPDATE `tactics` SET `id` = {$id}{$q} WHERE `id` = {$id}", __FILE__, __LINE__);
   info(SUBSTITUTIONS_SUCCESSFULLY_SET.create_button($back, GO_BACK, false, false, false), SUCCESS, false);
}
else info(WRONG_ID, ERROR);
?>
