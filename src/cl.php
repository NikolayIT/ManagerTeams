<?php
/*
File name: cup.php
Last change: Wed Jan 09 09:12:04 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart("Champions League");
head("Champions League");
create_special_link("clgames.php", "������� ����", "������� ����");
create_special_link("clfinal.php", "������� ����", "������� ����");
pageend();
?>
