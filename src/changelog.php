<?php
/*
File name: changelog.php
Last change: Tue Jan 15 22:27:26 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
pagestart(CHANGELOG);
?>
<h2><?=GAME_NAME?> 1.68 (24-09-2011)</h2>
- New: Change team name and stadium name<br>
<h2><?=GAME_NAME?> 1.67 (06-08-2011)</h2>
- Bug fixes in Champions League<br>
- Other small fixes<br>
<h2><?=GAME_NAME?> 1.66 (23-05-2011)</h2>
- New: Champions League!<br>
<h2><?=GAME_NAME?> 1.65 (08-07-2010)</h2>
- New: Tean anthem!<br>
- New: Changing tactics names<br>
<h2><?=GAME_NAME?> 1.64 (29-04-2010)</h2>
- New: Lottery!<br>
- New: Who is viewing your profile and team!<br>
<h2><?=GAME_NAME?> 1.63 (27-04-2010)</h2>
- New: New staff person: Accountant!<br>
- Players and staff salary changes<br>
<h2><?=GAME_NAME?> 1.62 (23-04-2010)</h2>
- New: Pressconference module!<br>
- Some bugs fixed<br>
<h2><?=GAME_NAME?> 1.61 (31-08-2009)</h2>
- New: Football news module!<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.60 (03-03-2009)</h2>
- One year ManagerTeams!<br>
- Friendly cups with passwords<br>
- Friendly cup money limit changed to 2000000<br>
- Added installer of the game<br>
- Some fixes with the game starting module<br>
<h2><?=GAME_NAME?> 1.59 (27-01-2009)</h2>
- Match simulation version 1.03 (Fixed bug with win bonus in substitutes)<br>
- Match information version 1.05 (Shows win bonus in the team stats)<br>
<h2><?=GAME_NAME?> 1.58 (06-01-2009)</h2>
- Match simulation version 1.02 (Man of the match, fixed bug with some stats)<br>
- Match information version 1.04 (Man of the match, players form)<br>
<h2><?=GAME_NAME?> 1.57 (01-01-2009)</h2>
- Added polls<br>
- A lot of database structure changes (for smaller database size)<br>
- A lot of bugs fixed<br>
- Some design fixes<br>
- Year changed to 2009<br>
<h2><?=GAME_NAME?> 1.56 (11-12-2008)</h2>
- Added links to leagues and friendly cups in the team fixtures<br>
- Some interface fixes<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.55 (22-11-2008)</h2>
- Added trophies for all teams from season 2 to now<br>
- Match of the week added<br>
- Match simulation version 1.01 (Incomes from tv rights added)<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.54 (04-11-2008)</h2>
- IP limitations for the transfers and friendly cups<br>
- Managers can posts comments for every match<br>
- Managers can posts comments for every league<br>
- Transfer reports can be sent to administrators<br>
- Fixed seroius bug with slashes when adding text to database<br>
- Some bugs fixed<br>
<h2><?=GAME_NAME?> 1.53 (01-11-2008)</h2>
- Managers can add pictures for every player<br>
- Some limitations for teams without scout, coach and doctor<br>
- Match information version 1.03 (added pictures for some of the events in the comments)<br>
<h2><?=GAME_NAME?> 1.52 (31-10-2008)</h2>
- Match information version 1.02 (some fixes)<br>
- Added: Hospital stadium modul<br>
- Added: Healing players<br>
- Some bugs fixed<br>
<h2><?=GAME_NAME?> 1.51 (28-10-2008)</h2>
- Match information version 1.01 (more information, some fixes)<br>
- Some game improvements<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.50 (29-09-2008)</h2>
- Season 6 started!<br>
- Match simulation version 1.00 - The new official simulator<br>
- Match information version 1.00 - The official class for unpacking match data<br>
- MT TCO Flash report v1.00 - The official flash reports<br>
<h2><?=GAME_NAME?> 1.49 (28-09-2008)</h2>
- Match simulation version 1.00-rc1 (not official simulator, yet)<br>
- MT TCO Flash report v1.00 release candidate 1<br>
- Some database stucture changes for the new simulation<br>
- Some bugs fixed<br>
<h2><?=GAME_NAME?> 1.48 (27-09-2008)</h2>
- Match simulation version 1.00-b1 (not official simulator, yet)<br>
- MT TCO Flash report v1.00 beta1<br>
- Some database stucture changes for the new simulation<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.47 (22-09-2008)</h2>
- Preliminary substitutions for all tactics<br>
- Copying tactics from selection 1, 2, 3, 4 and 5<br>
- New transfer list: The 25 most expensive transfers<br>
- New transfer list: the 25 best players in the transfers history<br>
- Only users registred before at least 1 week will be able to use loans and transfers<br>
<h2><?=GAME_NAME?> 1.46 (20-09-2008)</h2>
- Match simulation version 1.00-a2 (not official simulator, yet)<br>
- Match information version 1.00-a2<br>
- MT TCO Flash report v1.00 alpha2<br>
- Some database stucture changes for the new simulation<br>
- Fixed bug with team names<br>
<h2><?=GAME_NAME?> 1.45 (19-09-2008)</h2>
- New stadium module: Fan shop<br>
- Overview of all stadiums<br>
- Some bugs fixed<br>
<h2><?=GAME_NAME?> 1.44 (15-09-2008)</h2>
- New stadium module: VIP seats<br>
- Friendly cups list reverted<br>
- Some players wages changes<br>
- Some small interface changes<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.43 (08-09-2008)</h2>
- New module for the administrators - check for transfers from same IP<br>
- Fixed bug with admin IP information for teams with same IP<br>
- Fixed bug with inactive mails<br>
- Fixed bug with transfer price (all teams will get 90% of the transfer price)<br>
- Few other small bugs fixed<br>
<h2><?=GAME_NAME?> 1.42 (27-08-2008)</h2>
- Code changes for the new simulator<br>
- Fixed bug with players names<br>
- Team kits picture is now transparent<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.41 (21-08-2008)</h2>
- Some changes in the code and in the database structure for the new simulator<br>
- Fixed bug with new players from youthcenter<br>
- League and cup history bugs fixed<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.40 (10-08-2008)</h2>
- All ips are traced and all info about them is logged in the db for more security<br>
- Some changes in the code and in the database structure for the new match simulator<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.39 (29-07-2008)</h2>
- Security log system implemented in the game<br>
- Match simulator updated to version 0.9.11 (Home and away team shares the incomes from the friendly matches (60%-40%))<br>
- Fixed some common query errors<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.38 (21-07-2008)</h2>
- Friendly cup maximum fee is now 1 000 000<br>
- Friendly cup tax for the game - 15% (winner wins 65%, second in the cup wins 20%)<br>
- Fixed bug with cups - no money taken from the cup creator<br>
- New loan type - 20 000 000 for 2 years (For VIP users only)<br>
- Some changes in stadium update prices<br>
- Fixed a lot of bugs<br>
<h2><?=GAME_NAME?> 1.37 (07-07-2008)</h2>
- Season 4 started!<br>
- Added star next to the vip users names<br>
- 6 new colors added to the team kits<br>
- Fixed bug with players names (containing "\r\n" characters)<br>
- Fixed 2 critical bugs when performing the 3 weeks cleanup<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.36 (02-07-2008)</h2>
- Send mails to inactive users<br>
- Add 24 weak players to teams without enought players to play (15)<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.35 (29-06-2008)</h2>
- Prize for the topscorer in th league<br>
- Fine for the rought player in the league<br>
- Some more information in the shortlist<br>
- Some bugs fixed<br>
<h2><?=GAME_NAME?> 1.34 (24-06-2008)</h2>
- New module: Get a player from the youthcenter (youthcenter.php)<br>
- You win 25000 for every vote for us<br>
- Invitation bonus changed from 200000 to 250000<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.33 (16-06-2008)</h2>
- Only 90% of the transfer price is going to seller<br>
- Logout redirects to forum<br>
- Players from youthcenter comes at age 16<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.32 (11-06-2008)</h2>
- Maximal bet limited to 5000<br>
- Bet limits: 3 non-played matches<br>
- Some changes in the bet coeficients<br>
- Fixed design bug with header pictures<br>
<h2><?=GAME_NAME?> 1.31 (09-06-2008)</h2>
- Warn teams that are under the zero with money<br>
- Administration module for reviewing the teams that are under the zero with moneys (for ADMINISTRATORS only)<br>
- Match simulator updated to version 0.9.10 (Fixed serious bug with sold players plaing in seller and buyer)<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.30 (08-06-2008)</h2>
- Implemented return loans before the deadline<br>
- In team details page: list all matches with your team<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.29 (06-06-2008)</h2>
- Some improvements in the coders mails (mails that are sended every hour to the coder of the site with server informtion)<br>
- Match simulator updated to version 0.9.9 (Added global rating information, Rediced home team advantage, Reduced random advantages for both teams)<br>
- Fixed bug with VIP status renew<br>
- Friendly invite message translated<br>
<h2><?=GAME_NAME?> 1.28 (01-06-2008)</h2>
- Implemented players notes (this notes are shown in the players info)<br>
- Dealing faster with transfers<br>
- Match simulator updated to version 0.9.8 (Fixed bug with penalties after cup matches)<br>
- Some changes in the players info<br>
<h2><?=GAME_NAME?> 1.27 (31-05-2008)</h2>
- Implemented best players of the week ranking on the index page (before log in)<br>
- Some changes in the coeficients (no bets for the surely wins and the surely draws)<br>
- Fixed critical bug with users signups (creating new teams without any players)<br>
- Match simulator updated to version 0.9.7 (Updating users week points, Fixed bug with fans staisfaction)<br>
<h2><?=GAME_NAME?> 1.26 (28-05-2008)</h2>
- Fixed bugs with the statistics<br>
- Match simulator updated to version 0.9.6 (Incomes from friendly games reduced twice, Fixed bug with bets when technical win)<br>
- Some optimizations in the database with players statistics<br>
- Number of players training increased with 50%<br>
- Bonus for invitation incresed to 200 000<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.25 (26-05-2008)</h2>
- Season 3 started!<br>
- New help system added (wiki style) - <?=USER_GUIDE_ADDRESS?><br>
- New bets module implemented<br>
- Match simulator updated to version 0.9.5 (Some changes for the bets module)<br>
- Some changes in the menu<br>
- Fixed bug with players age when starting new season<br>
<h2><?=GAME_NAME?> 1.24 (24-05-2008)</h2>
- Match simulator updated to version 0.9.4 (Result depends on some more random things)<br>
- Coeficients added in all match lists<br>
- A lot of small bugs fixed<br>
<h2><?=GAME_NAME?> 1.23 (20-05-2008)</h2>
- Money bonuses for first and second place in the leagues - 1000000 and 500000<br>
- Money bonuses for the first 4 teams in the cup - 2000000, 1000000 and 500000<br>
- Some more history events in team history<br>
- Match simulator updated to version 0.9.3 (Some changes for the new "bets" module (Soon!))<br>
- Fixed bug with teams sorting in league ranking<br>
<h2><?=GAME_NAME?> 1.22 (13-05-2008)</h2>
- Players in team limits: minimum - 16 and maximum - 40<br>
- Friendly cups fee limited to 40000<br>
<h2><?=GAME_NAME?> 1.21 (10-05-2008)</h2>
- Some improvements in the SMS stats script (for ADMINISTRATORS only)<br>
- List all users with the same ip, class or inviter (for ADMINISTRATORS only)<br>
- Player stats implemented in cuptopscorers.php<br>
- Fliendly cups lists are pageable<br>
- Bug with stats fixed<br>
<h2><?=GAME_NAME?> 1.20 (06-05-2008)</h2>
- Added friends box in the left panel (under the teams box) (FOR VIP USERS ONLY)<br>
- Added friendly cups history (with finished cups)<br>
- Youthcenter changed: greater raitings and 1 player per month<br>
- Changes in left panel with advertise box<br>
- Friednly cup list now shows only not finished cups<br>
- Some interface changes and small bugs fixed<br>
<h2><?=GAME_NAME?> 1.19 (05-05-2008)</h2>
- Fixed bug with names in offercontract.php<br>
- Design bugs in newpass.php fixed<br>
- Some grammar errors fixed<br>
- A lot of small bugs fixed<br>
<h2><?=GAME_NAME?> 1.18 (29-04-2008)</h2>
- Invitation bonuses implemented<br>
- Added invitations stats script (for ADMINISTRATORS only)<br>
- Every hour some system stats are sended to coders mail (for ADMINISTRATORS only)<br>
- Some changes and improvements in friendlycupview.php (ranking sorted, interface fixes)<br>
- Some AJAX optimizations<br>
<h2><?=GAME_NAME?> 1.17 (28-04-2008)</h2>
- Added new game messages and some translations done in messages<br>
- Implemented friendly invitations rejection<br>
- Implemented sendig e-mail reports to VIP users<br>
- More team history events<br>
- Some changes in ranking.php (20 users per page, design fixes)<br>
- Players information in buyplayer.php added<br>
- Match simulator updated to version 0.9.2 (Incomes from matches changed, Mail report implemented, No goalkeeper bug fixed)<br>
- Some small bug fixes<br>
<h2><?=GAME_NAME?> 1.16 (27-04-2008)</h2>
- CSS files optimized and formatted<br>
- Added ip and last active page in online users page (FOR MODERATORS ONLY)<br>
- Meta tag "expires" fixed<br>
- Some small interface changes<br>
<h2><?=GAME_NAME?> 1.15 (26-04-2008)</h2>
- Friendly cups are officialy ready<br>
- Implemented players global rating recalculation<br>
- Match simulator updated to version 0.9.1 (Some changes for friendly cups)<br>
- Reduced global rating for new teams players<br>
- Some optimizations with money history data<br>
<h2><?=GAME_NAME?> 1.14 (25-04-2008)</h2>
- Match simulator updated to version 0.9.0 (Technical results implemented, Some balace fixes, Injury chance improved little bit)<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.13 (22-04-2008)</h2>
- Players fitness implemented<br>
- Some bugs with friendly cups fixed<br>
- Fixed bug with the statistics after a new season is started<br>
- Fixed ratings when new player generating<br>
- Grammar errors fixed<br>
<h2><?=GAME_NAME?> 1.12 (14-04-2008)</h2>
- Season 2 started!<br>
- Players potential removed<br>
- Added sms stats script (for ADMINISTRATORS only)<br>
- Added settings script (for ADMINISTRATORS only)<br>
- Fixed bug with demoting teams after the season is over<br>
- Fixed bug with match generating for next season<br>
- Fixed bug with adding winner in group A in history<br>
- Fixed bug with friendly cups not generating next rounds<br>
- Fixed bug with players stats not nulled after the season is over<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.11 (06-04-2008)</h2>
- Implemented deleting of teams (for ADMINISTRATORS only)<br>
- Some balance fixes in stadium upgrade prices<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.10 (05-04-2008)</h2>
- Friendly cups implemented (this module is still in beta and needs seriously testing!)<br>
- A lot of unnecessary files deleted<br>
- Match simulator updated to version 0.8.7 (Some changes for the friendly cups)<br>
<h2><?=GAME_NAME?> 1.09 (04-04-2008)</h2>
- When player boost his ability, a message will be sent to his owner<br>
- The VIP time is show in the VIP users profiles<br>
- Readed messages will be saved on database for 1 week, unreaded - for 1 month<br>
- Match simulator updated to version 0.8.6 (Goal chance increased, Injury chance reduced)<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.08 (02-04-2008)</h2>
- Added cheaters script (for multyaccounting users) (for ADMINISTRATORS only)<br>
- Spacial text implemented (text at the top of the real page)<br>
- Match simulator updated to version 0.8.5 (Some balance fixes in the simulator core, Fixed bug with away teams tactic same as home teams tactic)<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 1.07 (30-03-2008)</h2>
- Youthcenter now produces players<br>
- Match simulator updated to version 0.84 (Fixed bug with player nicknames in the reports)<br>
- Fixed bug with player names and stats in leaguetopscorers.php<br>
- Fixed bug with teams playing friendly matches at the same time<br>
- Fixed showing of nicknames on playernumbers.php<br>
- Fixed some other bugs<br>
<h2><?=GAME_NAME?> 1.06 (26-03-2008)</h2>
- Buying money with SMS implemented<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.05 (20-03-2008)</h2>
- VIP system implemented<br>
- New messsages information is bolded when there are any unread messages<br>
- Added some new first and last bulgarian names<br>
- Changing of team kits is now for VIP users only<br>
<h2><?=GAME_NAME?> 1.04 (18-03-2008)</h2>
- VIP Information added<br>
- Added colors in the players list when editing tactics<br>
- When editing tactic for a match the current tactic is set as default<br>
- Match simulator updated to version 0.83 (Fixed some division by zero problems)<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.03 (14-03-2008)</h2>
- Some additional information in the page headers added (online, registred managers and team fans)<br>
- Match simulator updated to version 0.82 (Attack, midfield and defence stats fixed)<br>
- Youthcenter upgrade prices doubled<br>
- Fixed bug with stats scripts<br>
- Bugs with javascript checks in signup script fixed<br>
- Profile editing bug fixed<br>
<h2><?=GAME_NAME?> 1.02 (12-03-2008)</h2>
- Team global rating implemented<br>
- Match simulator updated to version 0.81 (Attack, midfield and defence stats implemented)<br>
- Fixed bug with language combobox<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 1.01 (08-03-2008)</h2>
- Match simulator updated to version 0.80 (Injury chance reduced, fixed bug with player nicknames, fixed some mysql errors)<br>
- A lot of bugs fixed<br>
<h2><?=GAME_NAME?> 1.00 (03-03-2008)</h2>
- First official season started!<br>
- Spanish language added (still in beta) (10x to PacHo for the translation!)<br>
- Team history, manager history and money history translated<br>
- Some game and design bugs fixed<br>
<h2><?=GAME_NAME?> 0.99-beta (01-03-2008)</h2>
- Match simulator updated to version 0.75 (Fully translated, some players stats and match stats implemented, design improvements and bugs fixes)<br>
- Bugs fixed<br>
<h2><?=GAME_NAME?> 0.98-beta (29-02-2008)</h2>
- Match simulator updated to version 0.72 (Injury chance reduced)<br>
- A lot of bugs fixed<br>
- Some design bugs fixed<br>
<h2><?=GAME_NAME?> 0.97-beta (27-02-2008)</h2>
- A lot of design bugs fixed (design should be OK in all browsers)<br>
<h2><?=GAME_NAME?> 0.96-beta (25-02-2008)</h2>
- Match simulator updated to version 0.70 (Injuries and substitusions implemented, some fixes and improvements)<br>
- Statistics implemented<br>
- Design changes<br>
<h2><?=GAME_NAME?> 0.95-beta (24-02-2008)</h2>
- Match simulator updated to version 0.60 (Some fixes and improvements)<br>
- Design changes<br>
<h2><?=GAME_NAME?> 0.94-beta (23-02-2008)</h2>
- Match simulator updated to version 0.50 (Match simulations rewrittens)<br>
- Design changes<br>
<h2><?=GAME_NAME?> 0.93-beta (17-02-2008)</h2>
- Some changes/fixes with players and staff (added age, removed born date, training fixes)<br>
- Design changes<br>
<h2><?=GAME_NAME?> 0.92-beta (14-02-2008)</h2>
- Completely new design<br>
<h2><?=GAME_NAME?> 0.91-beta (09-02-2008)</h2>
- All scripts starting with "t" translated and optimized<br>
- All scripts starting with "v" translated and optimized<br>
<h2><?=GAME_NAME?> 0.90-beta (08-02-2008)</h2>
- Individual tactics implemented<br>
- All scripts starting with "s" translated and optimized<br>
<h2><?=GAME_NAME?> 0.89-beta (04-02-2008)</h2>
- Some translations and script optimizations made<br>
<h2><?=GAME_NAME?> 0.88-beta (29-01-2008)</h2>
- Fixed bugs with advansing to next season<br>
- All scripts starting with "p" translated and optimized<br>
- All scripts starting with "r" translated and optimized<br>
<h2><?=GAME_NAME?> 0.87-beta (27-01-2008)</h2>
- All scripts starting with "m" translated and optimized<br>
- All scripts starting with "n" translated and optimized<br>
- All scripts starting with "o" translated and optimized<br>
<h2><?=GAME_NAME?> 0.86-beta (25-01-2008)</h2>
- All scripts starting with "l" translated and optimized<br>
<h2><?=GAME_NAME?> 0.85-beta (24-01-2008)</h2>
- All scripts starting with "i" translated and optimized<br>
<h2><?=GAME_NAME?> 0.84-beta (22-01-2008)</h2>
- All scripts starting with "h" translated and optimized<br>
<h2><?=GAME_NAME?> 0.83-beta (21-01-2008)</h2>
- A lot of design changes (for the complete new design of the game) - changed left panel in the game<br>
<h2><?=GAME_NAME?> 0.82-beta (20-01-2008)</h2>
- All scripts starting with "f" translated and optimized<br>
- All scripts starting with "g" translated and optimized<br>
<h2><?=GAME_NAME?> 0.81-beta (19-01-2008)</h2>
- All scripts starting with "c" translated and optimized<br>
- All scripts starting with "�" translated and optimized<br>
- Some bugs fixed<br>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.80-beta (18-01-2008)</h2>
- Match simulator updated to version 0.13 (Fixed bug with cup stats)<br>
- Some translations and script optimizations made<br>
- Fixed bug with cup stats when new user registration<br>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.78-beta (17-01-2008)</h2>
- Another html validation<br>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.77-beta (16-01-2008)</h2>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.76-beta (15-01-2008)</h2>
- Promotion/demotion of the teams (when starting new season) implemented<br>
- All scripts starting with "b" translated and optimized<br>
- Fixed bug with language box<br>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.75-beta (14-01-2008)</h2>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.74-beta (13-01-2008)</h2>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.73-beta (12-01-2008)</h2>
- Some translations made<br>
- All scripts starting with "a" translated and optimized<br>
- All admin scripts optimized<br>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.72-beta (11-01-2008)</h2>
- Some translations made (left pannel fully translatable)<br>
- Match simulator updated to version 0.12 (Fixed division by 0 bug)<br>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.71-beta (10-01-2008)</h2>
- A lot of design changes (for the complete new design of the game)<br>
<h2><?=GAME_NAME?> 0.70-beta (09-01-2008)</h2>
- The entire menu is fully translatable<br>
- Fixed bug with stadium upgrades when new user created<br>
<h2><?=GAME_NAME?> 0.69-beta (08-01-2008)</h2>
- Some translations made<br>
- Fixed bug in tactics<br>
<h2><?=GAME_NAME?> 0.68-beta (07-01-2008)</h2>
- Some translations made<br>
<h2><?=GAME_NAME?> 0.67-beta (06-01-2008)</h2>
- Some translations made (manager menu)<br>
<h2><?=GAME_NAME?> 0.66-beta (05-01-2008)</h2>
- Fixed training generation (this fixes error on signup and restarting the game)<br>
- Some abstractional changes in code<br>
- A lot of design fixes. (signup.php is now "Valid HTML 4.01 Transitional")<br>
- Some code cleanups<br>
<h2><?=GAME_NAME?> 0.65-beta (04-01-2008)</h2>
- Some language improvements (the system remembers your language everywhere)<br>
- Some changes in right panel<br>
- Added friendly cup view, creating and subscription<br>
- Added links to counries in ranking script<br>
- Some optimizations in money history<br>
- A lot of design fixes. (index.php is now "Valid HTML 4.01 Transitional")<br>
- 1 common database query optimized<br>
<h2><?=GAME_NAME?> 0.62-beta (03-01-2008)</h2>
- Added individual tactics for each match<br>
- 2 common database queries optimized<br>
- Some cleanup optimizations<br>
<h2><?=GAME_NAME?> 0.61-beta (02-01-2008)</h2>
- The module with single friendly matches completed<br>
- Added VIP menu<br>
- Added players list in tactics<br>
- Some color changes in design<br>
- Fixed bug with moneys (system gets money for loans from all teams instead of the debtor)<br>
<h2><?=GAME_NAME?> 0.60-beta (01-01-2008)</h2>
- Tactics implemented (5 tactics for each team)<br>
- Added some friendly game scripts (list of friendly games, offer friendly game, fixtures, results)<br>
- Fixed a lot of issues with the code<br>
<h2><?=GAME_NAME?> 0.53-beta (29-12-2007)</h2>
- In all news there are now link to the news creator<br>
- Fixed all text sizes<br>
- Fixed style bug (with text wrap)<br>
<h2><?=GAME_NAME?> 0.52-beta (24-12-2007)</h2>
- Shortlist added<br>
- All stadium upgrades now have a picture<br>
- Added buying and selling transfers list<br>
- Added list of all transfers<br>
- Fixed few issues with transfers<br>
- Fixed bug with sending staff persons to course while they are currently on course<br>
<h2><?=GAME_NAME?> 0.51-beta (23-12-2007)</h2>
- Transfer list module added<br>
- Added buying players module<br>
- Match simulator updated to version 0.11 (Progress bars fixes)<br>
- Fixed few bugs with players contracts<br>
- Few design bugs fixed<br>
- Some game images changed<br>
<h2><?=GAME_NAME?> 0.50-beta (22-12-2007)</h2>
- Game logo changed<br>
- Left panel changed (no more images, more stats)<br>
- Some game images changed<br>
- A lot of design changes<br>
- Few design bugs fixed<br>
<h2><?=GAME_NAME?> 0.46-beta (19-12-2007)</h2>
- Added sell players script<br>
- Added new menu - "Transfers"<br>
<h2><?=GAME_NAME?> 0.45-beta (18-12-2007)</h2>
- Design changed<br>
- Few design bugs fixed<br>
<h2><?=GAME_NAME?> 0.43-beta (17-12-2007)</h2>
- Added online php editor (modified IDE.PHP 1.5.2) (for ADMINS only)<br>
<h2><?=GAME_NAME?> 0.42-beta (14-12-2007)</h2>
- Added friends module (add, view, remove friends) (for VIP users only)<br>
- Added team kits module<br>
- Added server status script (for ADMINS only)<br>
- Few changes in the main menu<br>
<h2><?=GAME_NAME?> 0.41-beta (13-12-2007)</h2>
- Match simulator updated to version 0.10 (More stats)<br>
- Few bugs fixed<br>
<h2><?=GAME_NAME?> 0.40-beta (11-12-2007)</h2>
- Test season re-strated again<br>
- Added 3 new stadium upgrades: roof, heater, sprinkler<br>
- Added links to team players, history, fixtures and results in the team details<br>
- Added link to manager history in the manager profile<br>
- Some small interface / code fixes<br>
- Fixed bug with staff salaries when no staff members are hired<br>
<h2><?=GAME_NAME?> 0.39-beta (10-12-2007)</h2>
- Added mysql tables status script (for ADMINS only)<br>
<h2><?=GAME_NAME?> 0.38-beta (09-12-2007)</h2>
- Team staff module added (hire staff members, deal with their contracts, send them on courses)<br>
<h2><?=GAME_NAME?> 0.37-beta (08-12-2007)</h2>
- Fixed bug with trainings (Thanks to jonyps)<br>
- Fixed bug with adding event to team history when registering new player<br>
<h2><?=GAME_NAME?> 0.36-beta (06-12-2007)</h2>
- Training effects implemented<br>
- Fixed bug with players numbers<br>
<h2><?=GAME_NAME?> 0.35-beta (03-12-2007)</h2>
- Game name changed to "ManagerTeams"<br>
- Team sponsors module added<br>
- Added sponsors add/edit/delete script (for ADMINS only)<br>
<h2><?=GAME_NAME?> 0.33-beta (02-12-2007)</h2>
- Added ability to get loans from our bank<br>
- Fixed bugs with sending mails for inactivity<br>
- Match simulator updated to version 0.09 (Increased wins from matches)<br>
<h2><?=GAME_NAME?> 0.32-beta (01-12-2007)</h2>
- Some database optimizations for history events<br>
- Added history event for team when registering new manager<br>
- Some changes and fixes in index.php<br>
- Fixed bug with taking moneys from team when doing upgrade<br>
- Fixed bug with moneys when team starts game<br>
- Some other small interface changes and fixes<br>
<h2><?=GAME_NAME?> 0.31-beta (30-11-2007)</h2>
- Added comments for each manager (in the profiles)<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 0.30-beta (29-11-2007)</h2>
- Stadium upgrades completed (added 11 upgradable modules) (in separate menu)<br>
- Added league history script<br>
- A lot of menu re-arranges<br>
- Money history script made paged<br>
- Fixed bug with deleting inactive users<br>
<h2><?=GAME_NAME?> 0.29-beta (27-11-2007)</h2>
- Added mail sender for remind users whose accounts are going to be deleted for inactivity<br>
- Some small bugs fixed<br>
<h2><?=GAME_NAME?> 0.28-beta (26-11-2007)</h2>
- Added players numbers script (for changing players numbers) (for VIP users only)<br>
- Added php code executor (for ADMINS only)<br>
- Added php code viewer (for ADMINS only)<br>
- Implemented deleting selected messages in inbox<br>
- Announcements made for VIP users only<br>
<h2><?=GAME_NAME?> 0.27-beta (21-11-2007)</h2>
- Added manager wins, draws, loses, goals scored and goals conceded stats<br>
- Match simulator updated to version 0.08<br>
- Some changes (and fixes) in index.php<br>
- Some changes (and fixes) in ranking.php<br>
- Some changes (and fixes) in search.php<br>
- Some re-arranges in menues<br>
<h2><?=GAME_NAME?> 0.26-beta (20-11-2007)</h2>
- Added players nickname script (for changing players names) (for VIP users only)<br>
- Some fixes in players scripts<br>
<h2><?=GAME_NAME?> 0.25-beta (19-11-2007)</h2>
- Test season re-started<br>
- Added mysql query executor (for ADMINS only)<br>
- Added php info script (for ADMINS only)<br>
- Added errors view (for ADMINS only)<br>
- Added money history<br>
- Fixed starting new season<br>
- Fixed player wages<br>
- Fixed player contracts<br>
<h2><?=GAME_NAME?> 0.24-beta (18-11-2007)</h2>
- Added player contracts module<br>
- Match simulator updated to version 0.07<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 0.23-beta (16-11-2007)</h2>
- Added holiday mode (for VIP users only)<br>
- Removed 'SW' players<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 0.22-beta (15-11-2007)</h2>
- Added cross table script (for VIP users only)<br>
<h2><?=GAME_NAME?> 0.21-beta (13-11-2007)</h2>
- Added onine managers script (for VIP users only)<br>
- Added send message link in manager profile page<br>
- Fixed bug with team names in match report (Thanks to gnusen(at)gmail(dot)com)<br>
<h2><?=GAME_NAME?> 0.20-beta (12-11-2007)</h2>
- Test season re-started<br>
- Fixed some small bugs<br>
<h2><?=GAME_NAME?> 0.17-beta (11-11-2007)</h2>
- Added additional lable in right side of the menu<br>
- Optimized cup match simulation (few times faster)<br>
- Match simulator updated to version 0.06<br>
- Some interface improvements<br>
- Fixed bugs with cup match generation<br>
<h2><?=GAME_NAME?> 0.16-beta (07-11-2007)</h2>
- Some small possible bugs fixed<br>
- Match simulator updated to version 0.05<br>
<h2><?=GAME_NAME?> 0.15-beta (06-11-2007)</h2>
- Added cup games script<br>
- Added cup topscorers stats<br>
- Added cup cards stats<br>
- Added league history<br>
- Added cup history<br>
- Updated Rules<br>
- Updated "About script"<br>
- Updated FAQ<br>
- Fixed bug with stats when user signup<br>
<h2><?=GAME_NAME?> 0.10-beta (05-11-2007)</h2>
- First working public release<br>
- First test season started<br>
- Match simulator version 0.01 added<br>
<h2>Project started</h2>
- Project started on 27.09.2007<br>
<?php
pageend();
?>
