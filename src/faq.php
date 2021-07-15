<?php
/*
File name: faq.php
Last change: Fri Jan 11 21:25:07 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart("FAQ");
head("FAQ");
if ($_COOKIE["lang"] == 1)
{
   prnt("English version coming soon...", true);
   br();
   prnt("<b>When I should use holiday mode?</b><br>
1. When you won't be able to play the game for some time.<br>
2. When you will go to holiday and won't be able to play.<br>
3. When you need rest from the game.<br>
<br>
<b>What are the privileges of the holiday mode?</b><br>
1. Your account will not be deleted for inactivity.<br>
2. Whe system will control you account.<br>
<br>
Note: The holiday mode can be actived as long as you want!<br>
<br>
You should know that this extra is for VIP users only!<br>
The moneys we recieve from VIP users helps us to improve the game!", true);
}
else if ($_COOKIE["lang"] == 2)
{
   bline("Колко продължава един сезон?", "Точно 6 семици ({$config['matchcount']} дена)");
   bline("Кога се играят мачовете за лигата?", "Всеки ден от понеделник до петък в 18 часа CET");
   bline("Кога се играят мачовете за купата?", "Всяка събота в 12 и 18 часа CET, както и в неделя в 18 часа CET");
   bline("Кога се играят приятелските мачове?", "Приятелски мачове се играят на всеки 6 часа");
   bline("Какво е CET?", "CET е централното европейско време (това е -1 час спрямо българското)");
   bline("Мога ли да поканя приятел в играта?", "Да, можете да го направите от <a href=\"invite.php\">ТУК</a>");
   bline("Защо играта е по-бавна между 18-19 CET?", "Тогава се симулират мачовете от лигата както и от купата докато играете и е нормално да усещате леко забавяне при зареждането на страницител");
   bline("Къде ми изчезна футболиста?", " Футболистите \"изчезват\" от играта когато контрактът им с вашия клуб изтече и вие не го подновите. Друг вариант някой футболист да \"изчезне\" е да навърши ".PLAYERS_OLD." години (прекратява спортната си кариера)");
   bline("Къде ми изчезна човек от екипа на отбора?", "Хората от екипа на отбора (треньор, доктор, скаут) \"изчезват\" от играта само когато навършат ".STAFF_OLD." години (прекратяват спортната си кариера)");
   bline("Какво става когато стана на минус с парите?", "Не можете да използвате парите си (да правите ъпгрейди по стадиона, да купувате играчи и т.н.), няма да можете да подновявате контрактите на футблистите и след като те изтекат, те ще напуснат клуба ви, в следствие на което ще останете с по-малко играчи от необходимия минимум и ще губите служебно мачовете си с по 3-0");
   bline("Кога и как се извършват тренировките?", "Тренировките се извършват на всеки 6 часа и част от футболистите (по-малко от 1 %) качват показателите, които мениджърите са им задали да качват. Един футболист може да качи само 100 показателя, както и всеки показател може да се качи най-много до 100.");
}
else prnt(COMING_SOON);
pageend();
?>
