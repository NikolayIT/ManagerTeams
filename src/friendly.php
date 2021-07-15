<?php
/*
File name: friendly.php
Last change: Wed Jan 09 09:16:19 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
limit();
pagestart(FRIENDLY);
head(FRIENDLY);
create_special_link("friendlyfixtures.php", FIXTURES, FRIENDLY_FIXTURES_TEXT);
create_special_link("friendlyresults.php", RESULTS, FRIENDLY_RESULTS_TEXT);
create_special_link("friendlyinvitation.php", CREATE_INVITATION, CREATE_INVITATION_TEXT);
create_special_link("friendlypool.php", FRIENDLY_POOL, FRIENDLY_POOL_TEXT);
create_special_link("friendlypool.php?type=fromme", MY_INVITATIONS, MY_INVITATIONS_TEXT);
create_special_link("friendlypool.php?type=tome", INVITATIONS_FOR_ME, INVITATIONS_FOR_ME_TEXT);
pageend();
?>
