<?php
//die("Temporaly unavailable!");
/*
File name: signup.php
Last change: Mon Feb 04 10:10:42 EET 2008
Copyright: NRPG (c) 2008
*/
define("IN_GAME", true);
include("common.php");
if ($USER) redirect("index.php");
if (!$allownewusers) info(NO_NEW_USERS, SORRY);
$users = sql_get("SELECT COUNT(`id`) FROM `users`", __FILE__, __LINE__);
if ($users >= $userslimit) info(NO_NEW_USERS, SORRY);

pagestart(SIGN_UP);
?>
<script language="JavaScript" type="text/javascript">
function a()
{
   return(b() && c2() && d() && e() && f() && g() && h() && i())
}
function b()
{
   var j=document.getElementById("username").value;
   if(j.length<<?=$username_minlen?>)
   {
      alert('<?=SHORT_USERNAME?>');
      return false;
   }
   if(j.length>16)
   {
      alert('<?=LONG_USERNAME?>');
      return false;
   }
   return true;
}
function c2()
{
   var j=document.getElementById("password").value
   var k=document.getElementById("passagain").value
   if(j!=k)
   {
      alert('<?=PASS_NOT_MATCH?>')
      return false
   }
   if(j.length<<?=$password_minlen?>)
   {
      alert('<?=SHORT_PASS?>')
      return false
   }
   if(j.length><?=$password_maxlen?>)
   {
      alert('<?=LONG_PASS?>')
      return false
   }
   return true
}
function d()
{
   var j=document.getElementById("realname").value
   if(j.length<<?=$realname_minlen?>)
   {
      alert('<?=SHORT_REALNAME?>')
      return false
   }
   if(j.length>50)
   {
      alert('<?=LONG_REALNAME?>')
      return false
   }
   return true
}
function e()
{
   var j=document.getElementById("teamname").value
   if(j.length<<?=$teamname_minlen?>)
   {
      alert('<?=SHORT_TEAMNAME?>')
      return false
   }
   if(j.length>32)
   {
      alert('<?=LONG_TEAMNAME?>')
      return false
   }
   return true
}
function f()
{
   var j=document.getElementById("stadium").value
   if(j.length<<?=$stadiumname_minlen?>)
   {
      alert('<?=SHORT_STADIUMNAME?>')
      return false
   }
   if(j.length>32)
   {
      alert('<?=LONG_STADIUMNAME?>')
      return false
   }
   return true
}
function g()
{
   var j=document.getElementById("email").value
   var filter=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
   if(!filter.test(j))
   {
      alert('<?=WRONG_EMAIL?>')
      return false
   }
   if(j.length>50)
   {
      alert('<?=LONG_EMAIL?>')
      return false
   }
   return true
}
function h()
{
   var l=document.getElementById("rules1").checked
   if(l!=true)
   {
      alert('<?=ONE_TEAM_ONLY?>')
      return false
   }
   return true
}
function i()
{
   var l=document.getElementById("rules2").checked
   if(l!=true)
   {
      alert('<?=MUST_AGREE_RULES?>')
      return false
   }
   return true
}
</script>
<?php
head(SIGN_UP);
prnt(SIGNUP_TEXT);
br(2);
form_start("takesignup.php", "POST", "signup", "return a()");
table_start(false, 0, "specialtable");

table_startrow();
table_th(USERNAME);
table_cell(input("text", "username", "", "username"), 1, "specialtb");
table_cell(ALLOWED_CHARACTERS.": a-z, A-Z, 0-9, \"_\"", 1, "specialtb");
table_endrow();

table_startrow();
table_th(PASSWORD);
table_cell(input("password", "password", "", "password"), 1, "specialtb");
table_cell(ALL_CHARACTERS_ARE_ALLOWED, 1, "specialtb");
table_endrow();

table_startrow();
table_th(PASS_AGAIN);
table_cell(input("password", "passagain", "", "passagain"), 1, "specialtb");
table_cell(ALL_CHARACTERS_ARE_ALLOWED, 1, "specialtb");
table_endrow();

table_startrow();
table_th(REAL_NAME);
table_cell(input("text", "realname", "", "realname"), 1, "specialtb");
table_cell(ALLOWED_CHARACTERS.": a-z, A-Z, \"_\"", 1, "specialtb");
table_endrow();

table_startrow();
table_th(TEAM_NAME);
table_cell(input("text", "teamname", "", "teamname"), 1, "specialtb");
table_cell(ALLOWED_CHARACTERS.": a-z, A-Z, 0-9, \"_\"", 1, "specialtb");
table_endrow();

table_startrow();
table_th(STADIUM_NAME);
table_cell(input("text", "stadium", "", "stadium"), 1, "specialtb");
table_cell(ALLOWED_CHARACTERS.": a-z, A-Z, 0-9, \"_\"", 1, "specialtb");
table_endrow();

table_startrow();
table_th(EMAIL);
table_cell(input("text", "email", "", "email"), 1, "specialtb");
table_cell(SHOULD_BE_VALID_EMAIL_ADDRESS, 1, "specialtb");
table_endrow();

table_startrow();
table_th(COUNTRY);
$data = sql_query("SELECT `id`, `name` FROM `countries` ORDER BY `name`", __FILE__, __LINE__);
$countries = "";
while ($row = mysql_fetch_assoc($data)) $countries .= option($row['id'], $row['name']);
table_cell(select("country", "country").$countries.end_select(), 1, "specialtb");
table_cell("", 1, "specialtb");
table_endrow();

table_startrow();
table_th(input("checkbox", "rules1", "yes", "rules1").I_HAVE_ONE_TEAM_ONLY, 2);
table_cell(ONE_TEAM_ONLY, 1, "specialtb");
table_endrow();

table_startrow();
table_th(input("checkbox", "rules2", "yes", "rules2").I_AGREE_RULES, 2);
table_cell(create_link("rules.php", MUST_AGREE_RULES), 1, "specialtb");
table_endrow();

table_startrow();
table_th(input("submit", "", SIGN_UP), 2);
table_th("");
table_endrow();

table_end();
form_end();

pageend();
?>
