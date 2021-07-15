<?php
/*
File name: invite.php
Last change: Thu Jan 24 11:57:18 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(INVITE_FRIENDS);
head(INVITE_FRIENDS);
prnt(INVITE_TEXT);
br(2);
form_start("takeinvite.php", "POST");
table_start(false, 0, "specialtable");
table_row(YOUR_NAME, input("text", "inviter", $USER['realname']), 1, "specialtb");
table_row(YOUR_EMAIL, input("text", "mailer", $USER['email']), 1, "specialtb");
table_end();
br();
prnt("<b>Внимание:</b><br>1. За всяка валидна покана получавате по ".INVITATION_BONUS."€ кредити в играта<br>2. За валидна покана се счита покана, при която потребителят се е регистрирал в играта чрез изпратена от вас покана.<br>3. Моля изпращайте покани само на хора които познавате!<br>4. Не изпращайте безразборно покани!<br>5. Ако открием, че злоупотребявате с поканите, ще получите наказание.<br>6. На един и същ e-mail адрес не може да се изпраща повече от 1 покана.<br>7. Поканеният от вас човек НЕ трябва да потвърждава регистрацията си от вашия компютър!");
br(2);
head(FRIENDS);
input("hidden", "multy", "1", "", true);
table_start(false, 0, "specialtable");
for ($i = 0; $i <= 9; $i++)
{
   table_startrow();
   table_th(NAME);
   table_cell(input("text", "frname{$i}"), 1, "specialtb");
   table_th(EMAIL);
   table_cell(input("text", "fremail{$i}"), 1, "specialtb");
   table_endrow();
}
table_startrow();
table_th("");
table_th(input("submit", "", INVITE));
table_th(input("reset", "", RESET));
table_th("");
table_endrow();
table_end();
form_end();
pageend();
?>
