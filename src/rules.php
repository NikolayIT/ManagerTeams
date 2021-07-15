<?php
/*
File name: rules.php
Last change: Tue Jan 29 21:49:15 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart(RULES);
head(RULES);
if ($_COOKIE["lang"] == 1)
{
?>
<b>CORE RULES OF THIS SITE:</b><br>
- <b><?=SITE_TITLE?></b> (“THE GAME”) in its current state is a FREE browser based football manager, the only rules you will need to comply with are written in this text.<br>
- THE GAME is NOT Open Source.<br>
- You are not allowed to exchange, lend or sell your account for any at all profits of any kind!<br>
- You will not be compensated for any errors, holes, exploits, hacks or any other form of cheats discovered in THE GAME that ware used against you! In some cases the team may give you some sort of compensation, but ONLY if they decide it is fair.<br>
- The team will NOT be held responsible for any damages whatsoever caused by THE GAME.<br>
- The team has the full right to modify any part of THE GAME as they see fit without warning the player of any changes to THE GAME.<br>
- The team has the full right to deny access to THE GAME at any time in the cases when that is needed in order to do maintenance, updates or any other events that require the THE GAME to be offline without warning the player.<br>
- Every illegal access to the game, its server or its database is logged and considered a criminal act which will be punished according to the laws of The Republic of Bulgaria.<br>
<br>
<b>NON-COMPLIANCE WITH THE FOLLOWING RULES WILL LEAD TO BAN BY IP!</b><br>
- If a player finds a bug, exploit, hole, or any other sort of bug in THE GAME, he must report it to the administration immediately. If a player uses any such bug in THE GAME he will be punished by an IP ban.<br>
- Hacking or logging into another player’s account or doing changes to it is punished by an IP ban.<br>
- If a way is discovered to go around the system’s protections, to do any sort of malicious damage is considered a criminal act and will be punished according to the laws of The Republic of Bulgaria.<br>
- Use of bots, clickers, macros or any other such third party application is forbidden.<br>
- Any sort of threat, virtual or in real life against another player or a member of the administration will be punished by an IP ban.<br>
- All sorts of spam, flood, DDoS against the server are absolutely forbidden.<br>
- All unauthorized advertisements are forbidden.<br>
- Multi-accounting is forbidden, or also “babysitting” other accounts is forbidden.<br>
- Advertisements of other browser based managers is forbidden, unless it is authorized by the administration.<br>
<br>
<b>NON-COMPLIANCE WITH THE FOLLOWING RULES WILL LEAD TO DEACTIVATION OF YOUR ACCOUNT!</b>
- Every player can spend unlimited time online, but be aware that it can lead to serious health problems.<br>
- Every player can have only one account.<br>
- If a player exploits a reported bug his account will be wiped. If a player is active in reporting bugs he will receive a reward.<br>
- All account containing uncensored words will be deleted.<br>
<br>
<b>NON-COMPLIANCE WITH THE FOLLOWING RULES WILL LEAD TO A WARNING!</b><br>
- You cannot dispute the decisions of the administrators or moderators.<br>
- You are forbidden to use a proxy of any sort to lie about your IP.<br>
- Any sort of discrimination be it on political, ethnical or any other sort are forbidden.<br>
- Propagation of violence and hate are forbidden.<br>
- All forms of pointless, idiotic or uncultured expressions are forbidden.<br>
- You are allowed only one warning, if a second warning needs to be issued your account will be deleted.<br>
<br>
<b>MAIN CONCEPTS:</b><br>
- <b><u>Multi-accounting</u></b> is a condition where one player has more than one active account.<br>
- <b><u>“Babysitting”</u></b> is a condition where one user has access to multiple accounts belonging to different people which gave him access to their accounts in order for him to “look after” their account.<br>
- <b><u>The staff</u></b> are all owners, programmers, administrators, moderators and people who have an active role in the development of THE GAME.<br>
- <b><u>Player (user)</u></b> is every person who owns a registered account for THE GAME, registrations are free and by the own will of the person.<br>
- <b><u>Bots</u></b> are all applications/scripts which automate THE GAME in any form (ex. simulation of activity of a given account).<br>
- <b><u>IP address</u></b> is a unique number which resembles a phone number, which is used by all sort of devices connected to the World Wide Web to identify them on the network.<br>
<br>
<b>Users with a registration in THE GAME declare that they have read and agree to obey the rules. In an event that they get punished they agree to serve their punishment.</b><br>
All users take care of their personal information and that they will not give it to other people. In an event that personal information is leaked, the team will not be held responsible.<br>
The team retains the right to correct/change the rules without notice.<br>
Administrators and moderators retain the right to punish a player if they decide that the player has done something morally wrong and is not described in the rules.<br>
<center><b>---=== Last update: 26.02.2008 19:48 ===---</b></center>
<?php
}
else if ($_COOKIE["lang"] == 2)
{
?>
<b>ОСНОВНИ ПРАВИЛА НА САЙТА:</b><br>
- Играта <b><?=SITE_TITLE?></b> в настоящия си вид е БЕЗПЛАТЕН уеб базиран футболен мениджър и единственото условие за регистрация е съгласието с настоящите правила.<br>
- Играта НЕ е с Отворен Код (Open Source).<br>
- Никой няма право да заменя, преотстъпва или продава своя акаунт срещу каквито и да е облаги!<br>
- Никой не отговаря, ако играч пострада от програмни грешки или грешки, причинени от екипа на играта! Все пак, ако екипът прецени, може да му бъде дадено някакво обезщетение под формата на кредити в играта.<br>
- Екипът не носи отговорност за настъпили вреди (свързани с играта) от страна на потребители вследствие участието им в играта.<br>
- Екипът си запазва правото да внася корекции във всички аспекти на играта, без да уведомява предварително за това.<br>
- Екипът си запазва правото да спира достъпа до играта в случаите, когато това е необходимо, без да уведомява предварително за това. Това се отнася както за планирани спирания заради поддръжка на сървърите, така и при инцидентни или аварийни ситуации.<br>
- Всеки неправомерен достъп до играта, до нейния сървър и/или нейната база данни се записва и виновникът се наказва според действащото законодателство на Република България!<br>
<br>
<b>Освобождаване от отговорност:</b><br>
<b><?=GAME_NAME?></b> не носи отговорност, ако при нарушения на работоспособността на програмното или техническото осигуряване на други оператори в Интернет или на телекомуникационните връзки в и/или извън страната, регистриран потребител не може да ползва частично или напълно възможностите на предоставените услуги.<br>
Успешното получаване на sms-ите зависи от натовареността на sms центъра, покритието на мрежата на мобилния оператор на потребителя, неговия модел мобилен телефон, а също и от правилното запаметяване.<br>
Допустимо е в пикови часове и дни (празнични или др.) да има забавяне при получаването на sms-ите в рамките на 24 часа.<br>
<b><?=GAME_NAME?></b> не носи отговорност ако при получаването на sms потребителят изтрие неволно или от незнание получения sms.<br>
<br>
<b>НЕСПАЗВАНЕТО НА СЛЕДНИТЕ ПРАВИЛА ВОДИ ДО БАН ПО IP!</b><br>
- Ако играч открие бъг в играта, той е длъжен незабавно да уведоми администрацията. За използването и недокладването на бъг, играчът се наказва с БАН по IP.<br>
- При пряко влизане в чужд акаунт без знанието на собственика, т.е. хакване и непряко намесване в играта на чужд акаунт, нарушителят се наказва с БАН по IP.<br>
- При открит начин за заобикаляне на системата, при умишлен опит за затрудняване на работата на сървърите, както и при опит за манипулиране на кодовете на играта, нарушителят се наказва с БАН по IP. Ако нарушителят е нанесъл някакви физически повреди на сървъра той подлежи на разследване и ще бъде задължен от законовите органи да заплати нанесените щети!<br>
- Използването на всякакъв вид ботове е забранено!<br>
- Всякакъв вид изнудване и заплахи (физически, в сравнение с „виртуалния живот”), които се отправят към играчи, могат да доведат до БАН по IP.<br>
- Забранени са всякакви видове спам, флууд, DDoS и т.н. на сървъра!<br>
- Забраняват се всякакви неоторизирани реклами, без разрешение от администратор.<br>
- Бейбиситингът както и мултиакаунтингът са забранени!<br>
- Реклама на друга онлайн мениджърска игра (възможно е да се направи изключение, но само след споразумение)<br>
<br>
<b>НЕСПАЗВАНЕТО НА СЛЕДНИТЕ ПРАВИЛА ВОДИ ДО ДЕАКТИВИРАНЕ НА АКАУНТА!</b><br>
- Всеки играч има право да бъде неограничено време онлайн и да развива отбора си, но имайте предвид, че дългото седене пред компютъра може да ви навреди.<br>
- Всеки играч има право на не повече от ЕДИН акаунт.<br>
- Ако играч използва докладван, но неотстранен бъг, играчът се санкционира с изтриване на акаунта, а всеки който ни помогне с откриването както и докладва бъгове ще бъде поощряван!<br>
- Всички акаунти на играчи с обидни и нецензурни nick-ове ще бъдат изтрити!<br>
<br>
<b>НЕСПАЗВАНЕТО НА СЛЕДНИТЕ ПРАВИЛА ВОДИ ДО ПРЕДУПРЕЖДЕНИЕ!</b><br>
- Нямате право да оспорвате решенията на администраторите и модераторите на играта!<br>
- Нямате право да влизате през проксита (лъжейки системата за собственото си IP)!<br>
- Забранява се използването на обиди или дискриминация на верска, политическа, етническа, сексуална, расова и всякакъв вид друга основа.<br>
- Забранява се насаждане, пропагандиране и деклариране на омраза и насилие.<br>
- Забраняват се безмислени, просташки и некултурни изрази.<br>
- Имате право само на 1 предупреждение! При второ предупреждение губите акаунта си!<br>
<br>
<b>ОСНОВНИ ПОНЯТИЯ:</b><br>
- <b><u>Мултиакаунтинг</u></b> е положение, при което един потребител притежава повече от един активен акаунт.<br>
- <b><u>Бейбиситинг</u></b> е положение, при което един потребител влиза в акаунтите на други потребители с цел "да ги наглежда".<br>
- <b><u>Екип на играта</u></b> са всички собственици, програмисти, администратори, модератори и хора, които активно участват в разработката й.<br>
- <b><u>Играч (потребител)</u></b> е всеки човек, регистрирал акаунт в играта. Регистрациите са доброволни и безплатни.<br>
- <b><u>Ботове</u></b> се наричат автоматизирани програми (както и скриптове) под всякаква форма, симулиращи активност в даден акаунт.<br>
- <b><u>IP адресът</u></b> е уникален номер, много наподобяващ телефонен номер, който се използва от машини (обикновено компютри), за да се свързват едни с други, когато изпращат информация през Интернет<br>
<br>
<b>Потребителите с регистрацията на акаунт в играта декларират, че са прочели тези условия и са съгласни с изложеното в тях, че ще спазват правилата за интелигентна и коректна игра и че са съгласни да изтърпяват наказанията, посочени по-горе, ако екипът прецени, че това е необходимо.</b><br>
Потребителите се задължават да се грижат за личната информация и да не я предоставят на други лица. При предоставяне на такава, екипът няма да поема никакви отговорности.<br>
Екипът си запазва правото да променя/допълва тези правила без предупреждение.<br>
Администраторите и модераторите на играта си запазват правото да наказват по лично усмотрение играчи, нарушили правилата по начин, неописан тук, съобразно конкретната ситуация.<br>
<center><b>---=== Последна промяна: 21.01.2010 16:00 ===---</b></center>
<?php
}
else if ($_COOKIE["lang"] == 3)
{
?>
<b>REGLAS PRINCIPALES DE LA PAGINA:</b><br>
- El juego <b><?=SITE_TITLE?></b> es un gratuito,una pagina web hecha para simular un manager de futbol y solo tiene que aceptar las reglas para registrarse.<br>
- El juego no lleva codigo libre (Open Source).<br>
- Nadie tiene el derecho de vender su cuenta por dinero o beneficio alguno!<br>
- Nadie es responsable, si el jugador sufre algun error, hechos por el equipo de administraci?n! Pero si el equipo admite el error, el jugador recibira una prima en creditos para el juego.<br>
- El equipo no lleva ninguna responsabilidad si ocurren errores (relacionados con el juego) de parte del usuario cuando use el juego.<br>
- El equipo directivo tiene derechos de actualizar cualquier cosa, sin que tenga que avisar de antemano.<br>
- El equipo directivo tiene el derecho de prohibir la entrada en los casos, cuando esto es necesario, sin que tenga que avisar previamente esto. Esto trata tanto para paros programados del servidor por actualizaciones , tambien por algun fallo tecnico del servidor.<br>
- Cualquier acceso sin autorizaci?n, en el servidor y/o su base de datos se guarda y el causante de esto es juzgado por las normas de Bulgaria!<br>
<br>
<b>EL QUE NO CUMPLA LAS SIGUIENTES NORMAS SERA BANEADO SU IP!</b><br>
- Si el jugador encuentra un bug en el sistema, es necesario avisar a los administradores. Si este uza el bug sera baneado.<br>
- Al entrar en otra cuenta sin el permiso del due?o de dicha cuenta, es decir "hackeo" y el que lo ha hecho recibe ban en la IP.<br>
- Al encontrar un metodo para explotar el sistema, como tambien por intentar penetrar en el y controlarlo sera baneada la IP. Si el que lo ha hecho ha da?ado el sistema sera investigado el caso y sera obligado a pagar los da?os causados!<br>
- El uzo de cualquier tipo de bots esta prohibido!<br>
- Cualquier tipo de chantaje (fisico, „en la vida virtual”), que se dirijan a los jugadores del servidor, recibiran IP.<br>
- Estan prohibidos cualquier tipo de spam, flood, DDoS etc... hacia el servidor!<br>
- Prohibidas cualquier tipo de publicidad, sin autorizaci?n del administrador.<br>
- Multicuentas estan prohibidas!<br>
- Publicidad de otro juego on-line (a no ser autorizado por el administrador).<br>
<br>
<b>AL NO CUMPLIR LAS SIGUIENTES NORMAS LA CUENTA DEL USUARIO SERA DESACTIVADA!</b><br>
- Cualquier jugador tiene el permiso de estar todo el tiempo que quiera en linea y actualizar su equipo continuamente, pero tener en cuenta que estar mucho tiempo delante del ordenador puede afectaros negativamente.<br>
- Todos los jugadores tienen permiso solo a una cuenta.<br>
- Si el jugador utiliza un bug existente comunicado a los administradores, pero que no se aya resuelto aun ,el jugador sera sansionado con borrado de cuenta.Y el que comunique o ayude a resolverlos sera premiado!<br>
- Todos los usuarios, apodos etc... con palabras racistas o insultos seran borrados!<br>
<br>
<b>AL NO CUMPLIR ESTAS NORMAS SERA AVISADO!</b><br>
- No teneis derecho a contradecir a los administradores o moderadores del juego!<br>
- No tienes permiso de entrar via proxys (esconder tu IP real)!<br>
- Prohibido insultar, hacer bromas pesadas, discriminaci?n sexual, rasismo etc...!<br>
- Se prohibe propaganda de maltratos discriminaci?n o insultos.<br>
- Se prohibe la utilizaci?n de lenguaje absurdo.<br>
- Teneis derecho solo a un aviso! Al segundo aviso se le borrara la cuenta!<br>
<br>
<b>Reglas generales:</b><br>
- <b><u>Cuentas multiples</u></b> es que un usario tiene mas de una cuenta activa.<br>
- <b><u>Babysiting</u></b> cualquier tipo de control sobre una cuenta ajena.<br>
- <b><u>Equipo administrativo</u></b> son todos los due?os, programadores, administradores, moderadores y gente, que trabaja en el desarrollo del juego.<br>
- <b><u>Jugador (usuario)</u></b> es toda persona registrada en el juego que son gratuitas.<br>
- <b><u>Bots</u></b> son programas automaticos (como los scripts) en cualquier modo, simulando la actividad de la cuenta.<br>
- <b><u>IP adres</u></b> es el "nombre" de su ordenador en internet, que se usa para comunicarse con los otros.<br>
<br>
<b>Usuarios registrados con una cuenta en el juego y declaran que han leido estas reglas y que las seguiran eticamente e intentar que sea un juego limpio, y que estan deacuerdo con los castigos que seran tomados expuestos mas arriba.</b><br>
El usuario esta obligado a proteger sus datos y que no las de a nadie. Si el usuario da sus datos a otra personas el equipo no tiene ninguna culpa de eso.<br>
El equipo directivo coge los derechos para actualizar/cambiar estos datos.<br>
Los administradores y moderadores pueden avisarle por otra razon no expuesta aqui si lo consideran necesario.<br>
<center><b>---=== Ultima actualizaci?n: 05.11.2007 22:30 ===---</b></center>
<?php
}
else prnt(COMING_SOON);
pageend();
?>
