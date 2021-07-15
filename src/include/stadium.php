<?php
function generate_stadium($name)
{
   $name = $name . " stad.";
   sql_query("INSERT INTO `stadiums` (`name`) VALUES ('{$name}');", __FILE__, __LINE__);
   return mysql_insert_id();
}
function stadium_modules($id)
{
   $field = "";
   $functionsid = "";
   $name = "";
   $script = "";
   switch ($id)
   {
      case "eastseats":
         $field = "eastseats";
         $functionsid = "seats";
         $name = EAST_SEATS;
         $script = "stadiumseats.php";
         break;
      case "westseats":
         $field = "westseats";
         $functionsid = "seats";
         $name = WEST_SEATS;
         $script = "stadiumseats.php";
         break;
      case "northseats":
         $field = "northseats";
         $functionsid = "seats";
         $name = NORTH_SEATS;
         $script = "stadiumseats.php";
         break;
      case "southseats":
         $field = "southseats";
         $functionsid = "seats";
         $name = SOUTH_SEATS;
         $script = "stadiumseats.php";
         break;
      case "vipseats":
         $field = "vipseats";
         $functionsid = "vipseats";
         $name = VIP_SEATS;
         $script = "stadiumseats.php";
         break;
      case "parkings":
         $field = "parking";
         $functionsid = "parkings";
         $name = PARKINGS;
         $script = "stadiumupgrades.php";
         break;
      case "bars":
         $field = "bars";
         $functionsid = "bars";
         $name = BARS;
         $script = "stadiumupgrades.php";
         break;
      case "toilets":
         $field = "toilets";
         $functionsid = "toilets";
         $name = TOILETS;
         $script = "stadiumupgrades.php";
         break;
      case "grass":
         $field = "grass";
         $functionsid = "grass";
         $name = GRASS;
         $script = "stadiumupgrades.php";
         break;
      case "lights":
         $field = "lights";
         $functionsid = "lights";
         $name = LIGHTS;
         $script = "stadiumupgrades.php";
         break;
      case "boards":
         $field = "boards";
         $functionsid = "boards";
         $name = BOARDS;
         $script = "stadiumupgrades.php";
         break;
      case "youthcenter":
         $field = "youthcenter";
         $functionsid = "youthcenter";
         $name = YOUTHCENTER;
         $script = "stadiumupgrades.php";
         break;
      case "roof":
         $field = "roof";
         $functionsid = "roof";
         $name = ROOF;
         $script = "stadiumupgrades.php";
         break;
      case "heater":
         $field = "heater";
         $functionsid = "heater";
         $name = HEATER;
         $script = "stadiumupgrades.php";
         break;
      case "sprinkler":
         $field = "sprinkler";
         $functionsid = "sprinkler";
         $name = SPRINKLER;
         $script = "stadiumupgrades.php";
         break;
      case "fanshop":
         $field = "fanshop";
         $functionsid = "fanshop";
         $name = FAN_SHOP;
         $script = "stadiumupgrades.php";
         break;
      case "hospital":
         $field = "hospital";
         $functionsid = "hospital";
         $name = HOSPITAL;
         $script = "stadiumupgrades.php";
         break;
      default:
         break;
   }
   $ret = array();
   $ret['field'] = $field;
   $ret['functionsid'] = $functionsid;
   $ret['name'] = $name;
   $ret['script'] = $script;
   return $ret;
}
function parkings_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
      case 10: return 200000000;
   }
}
function parkings_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
      case 10: return 365;
   }
}
function calculate_parkings($level)
{
   switch ($level)
   {
      case 0: return 0;
      case 1: return 10;
      case 2: return 20;
      case 3: return 50;
      case 4: return 100;
      case 5: return 200;
      case 6: return 500;
      case 7: return 1000;
      case 8: return 2000;
      case 9: return 5000;
      case 10: return 10000;
      case 11: return 15000;
   }
}
function bars_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
   }
}
function bars_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
   }
}
function calculate_bars($level)
{
   switch ($level)
   {
      case 0: return 0;
      case 1: return 1;
      case 2: return 2;
      case 3: return 3;
      case 4: return 5;
      case 5: return 8;
      case 6: return 10;
      case 7: return 15;
      case 8: return 25;
      case 9: return 50;
   }
}
function toilets_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
   }
}
function toilets_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
   }
}
function calculate_toilets($level)
{
   switch ($level)
   {
      case 0: return 0;
      case 1: return 5;
      case 2: return 10;
      case 3: return 25;
      case 4: return 50;
      case 5: return 100;
      case 6: return 250;
      case 7: return 500;
      case 8: return 1000;
      case 9: return 2500;
   }
}


function vipseats_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 5000000;
      case 5: return 10000000;
      case 6: return 25000000;
      case 7: return 50000000;
      case 8: return 100000000;
      case 9: return 200000000;
      case 10: return 500000000;
   }
}
function vipseats_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 7;
      case 2: return 15;
      case 3: return 30;
      case 4: return 40;
      case 5: return 50;
      case 6: return 70;
      case 7: return 100;
      case 8: return 200;
      case 9: return 365;
      case 10: return 730;
   }
}
function calculate_vipseats($level)
{
   switch ($level)
   {
      case 0: return 0;
      case 1: return 20;
      case 2: return 50;
      case 3: return 100;
      case 4: return 250;
      case 5: return 500;
      case 6: return 1000;
      case 7: return 2000;
      case 8: return 3000;
      case 9: return 5000;
      case 10: return 10000;
      case 11: return 15000;
   }
}

function seats_upgrade_price($level)
{
   switch ($level)
   {
      case 2: return 250000;
      case 3: return 500000;
      case 4: return 2000000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
      case 10: return 200000000;
      case 11: return 500000000;
   }
}
function seats_upgrade_time($level)
{
   switch ($level)
   {
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
      case 10: return 365;
      case 11: return 730;
   }
}
function calculate_seats($level)
{
   switch ($level)
   {
      case 1: return 100;
      case 2: return 250;
      case 3: return 500;
      case 4: return 1000;
      case 5: return 2500;
      case 6: return 5000;
      case 7: return 10000;
      case 8: return 25000;
      case 9: return 50000;
      case 10: return 100000;
      case 11: return 150000;
      case 12: return 200000;
   }
}

function grass_upgrade_price($level)
{
   switch ($level)
   {
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
      case 10: return 200000000;
   }
}
function grass_upgrade_time($level)
{
   switch ($level)
   {
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
      case 10: return 365;
   }
}
function calculate_grass($level)
{
   switch ($level)
   {
      case 1: return QUALITY_1;
      case 2: return QUALITY_2;
      case 3: return QUALITY_3;
      case 4: return QUALITY_4;
      case 5: return QUALITY_5;
      case 6: return QUALITY_6;
      case 7: return QUALITY_7;
      case 8: return QUALITY_8;
      case 9: return QUALITY_9;
   }
}

function lights_upgrade_price($level)
{
   switch ($level)
   {
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
      case 10: return 200000000;
   }
}
function lights_upgrade_time($level)
{
   switch ($level)
   {
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
      case 10: return 365;
   }
}
function calculate_lights($level)
{
   switch ($level)
   {
      case 1: return QUALITY_1;
      case 2: return QUALITY_2;
      case 3: return QUALITY_3;
      case 4: return QUALITY_4;
      case 5: return QUALITY_5;
      case 6: return QUALITY_6;
      case 7: return QUALITY_7;
      case 8: return QUALITY_8;
      case 9: return QUALITY_9;
   }
}

function boards_upgrade_price($level)
{
   switch ($level)
   {
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
      case 10: return 200000000;
   }
}
function boards_upgrade_time($level)
{
   switch ($level)
   {
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
      case 10: return 365;
   }
}
function calculate_boards($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 6;
      case 3: return 7;
      case 4: return 7;
      case 5: return 8;
      case 6: return 8;
      case 7: return 9;
      case 8: return 9;
      case 9: return 10;
   }
}

function youthcenter_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 500000;
      case 2: return 1000000;
      case 3: return 2500000;
      case 4: return 5000000;
      case 5: return 10000000;
      case 6: return 25000000;
      case 7: return 50000000;
      case 8: return 100000000;
      case 9: return 200000000;
   }
}
function youthcenter_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 10;
      case 2: return 20;
      case 3: return 30;
      case 4: return 40;
      case 5: return 50;
      case 6: return 70;
      case 7: return 100;
      case 8: return 200;
      case 9: return 365;
   }
}
function calculate_youthcenter($level)
{
   switch ($level)
   {
      case 0: return QUALITY_0;
      case 1: return PRODUCE_PLAYERS_WITH_POTENCIAL." 41-60";
      case 2: return PRODUCE_PLAYERS_WITH_POTENCIAL." 51-65";
      case 3: return PRODUCE_PLAYERS_WITH_POTENCIAL." 61-70";
      case 4: return PRODUCE_PLAYERS_WITH_POTENCIAL." 66-75";
      case 5: return PRODUCE_PLAYERS_WITH_POTENCIAL." 71-80";
      case 6: return PRODUCE_PLAYERS_WITH_POTENCIAL." 76-85";
      case 7: return PRODUCE_PLAYERS_WITH_POTENCIAL." 81-90";
      case 8: return PRODUCE_PLAYERS_WITH_POTENCIAL." 86-95";
      case 9: return PRODUCE_PLAYERS_WITH_POTENCIAL." 91-100";
   }
}
function get_youthcenter($level)
{
   switch ($level)
   {
      case 0: return rand(0, 0);
      case 1: return rand(40+5, 60);
      case 2: return rand(50+5, 65);
      case 3: return rand(60+5, 70);
      case 4: return rand(65+5, 75);
      case 5: return rand(70+5, 80);
      case 6: return rand(75+5, 85);
      case 7: return rand(80+5, 90);
      case 8: return rand(85+5, 95);
      case 9: return rand(100+4, 100+10);
   }
}

function roof_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
   }
}
function roof_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
   }
}
function calculate_roof($level)
{
   switch ($level)
   {
      case 0: return QUALITY_0;
      case 1: return QUALITY_1;
      case 2: return QUALITY_2;
      case 3: return QUALITY_3;
      case 4: return QUALITY_4;
      case 5: return QUALITY_5;
      case 6: return QUALITY_6;
      case 7: return QUALITY_7;
      case 8: return QUALITY_8;
      case 9: return QUALITY_9;
   }
}

function heater_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
   }
}
function heater_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
   }
}
function calculate_heater($level)
{
   switch ($level)
   {
      case 0: return QUALITY_0;
      case 1: return QUALITY_1;
      case 2: return QUALITY_2;
      case 3: return QUALITY_3;
      case 4: return QUALITY_4;
      case 5: return QUALITY_5;
      case 6: return QUALITY_6;
      case 7: return QUALITY_7;
      case 8: return QUALITY_8;
      case 9: return QUALITY_9;
   }
}

function sprinkler_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
   }
}
function sprinkler_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
   }
}
function calculate_sprinkler($level)
{
   switch ($level)
   {
      case 0: return QUALITY_0;
      case 1: return QUALITY_1;
      case 2: return QUALITY_2;
      case 3: return QUALITY_3;
      case 4: return QUALITY_4;
      case 5: return QUALITY_5;
      case 6: return QUALITY_6;
      case 7: return QUALITY_7;
      case 8: return QUALITY_8;
      case 9: return QUALITY_9;
   }
}

function fanshop_upgrade_price($level)
{
   switch ($level)
   {
      case 1: return 250000;
      case 2: return 500000;
      case 3: return 1000000;
      case 4: return 2500000;
      case 5: return 5000000;
      case 6: return 10000000;
      case 7: return 25000000;
      case 8: return 50000000;
      case 9: return 100000000;
   }
}
function fanshop_upgrade_time($level)
{
   switch ($level)
   {
      case 1: return 5;
      case 2: return 10;
      case 3: return 20;
      case 4: return 30;
      case 5: return 40;
      case 6: return 50;
      case 7: return 70;
      case 8: return 100;
      case 9: return 200;
   }
}
function get_fanshop_income($level)
{
   switch ($level)
   {
      case 0: return 0;
      case 1: return 5000;
      case 2: return 10000;
      case 3: return 20000;
      case 4: return 50000;
      case 5: return 100000;
      case 6: return 200000;
      case 7: return 500000;
      case 8: return 700000;
      case 9: return 1000000;
   }
}
function calculate_fanshop($level)
{
   return DAILY_INCOME.": ".get_fanshop_income($level).MONEY_SIGN;
}


function hospital_upgrade_price($level)
{
   return 4000000;
}
function hospital_upgrade_time($level)
{
   return 3;
}
function calculate_hospital($level)
{
   return PLACES.": ".$level;
}

function get_additional_incoms($level)
{
   switch ($level)
   {
      case 0: return 0;
      case 1: return rand(200, 500);
      case 2: return rand(500, 750);
      case 3: return rand(750, 1000);
      case 4: return rand(1000, 1500);
      case 5: return rand(1500, 2000);
      case 6: return rand(2000, 3000);
      case 7: return rand(3000, 5000);
      case 8: return rand(5000, 7500);
      case 9: return rand(7500, 10000);
   }
}
?>
