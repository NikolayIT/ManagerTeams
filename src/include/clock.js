/*
<!-- Original:  Tomleung (lok_2000_tom@hotmail.com) This tag should not be removed-->
<!--Server time ticking clock v2.0 Updated by js-x.com-->*/
function MakeArrayday(size)
{
  this.length = size;
  for(var i = 1; i <= size; i++)
    this[i] = "";
  return this;
}
function MakeArraymonth(size)
{
  this.length = size;
  for(var i = 1; i <= size; i++)
    this[i] = "";
  return this;
}

var hours;
var minutes;
var seconds;
var secsTillWarning;
var warning;
var timer=null;

function sClock(h, m, s, s2, w)
{
  hours=h;
  minutes=m;
  seconds=s;
  secsTillWarning=s2;
  warning=w;
  if(timer){clearInterval(timer);timer=null;}
  timer=setInterval("work();",1000);
}

function twoDigit(_v)
{
  if(_v<10)_v="0"+_v;
  return _v;
}

function work()
{
  if (!document.layers && !document.all && !document.getElementById) return;
  var runTime = new Date();
  var shours = hours;
  var sminutes = minutes;
  var sseconds = seconds;
  //if (secsTillWarning-- == 0)
  //{
    //if (warning != String.Empty)
    //  alert(warning);
  //}
    
  if (shours >= 24)
  {
    shours-=24;
  }
  sminutes=twoDigit(sminutes);
  sseconds=twoDigit(sseconds);
  shours  =twoDigit(shours  );
  movingtime = ""+ shours + ":" + sminutes +":"+sseconds;
  if (document.getElementById)
    document.getElementById("divClock").innerHTML=movingtime;
  else if (document.layers)
  {
    document.layers.divClock.document.open();
    document.layers.divClock.document.write(movingtime);
    document.layers.divClock.document.close();
  }
  else if (document.all)
    divClock.innerHTML = movingtime;

  if(++seconds>59)
  {
    seconds=0;
    if(++minutes>59)
    {
      minutes=0;
      if(++hours>23)
      {
        hours=0;
      }
    }
  }  
}

//http://scripts.franciscocharrua.com/countdown-clock.php
//18/01/2006 :  Tweaked by Sjarel 
function countdown(Time_Left)
{  
  if(Time_Left < 0)
    Time_Left = 0;
    
  newTime_Left = Time_Left - 1;
  
  Time_Left %= (60 * 60 * 24);
  h = Math.floor(Time_Left / (60 * 60));
  Time_Left %= (60 * 60);
  m = Math.floor(Time_Left / 60);
  Time_Left %= 60;
  s = Time_Left;
  
  movtime = "";
  if (h > 0)
  {
    movtime += h + ":";
    if (m < 10)
      movtime += "0" + m + ":";
    else
      movtime += m + ":";
    if (s < 10)
      movtime += "0" + s;
    else
      movtime += s;
  }
  else
  {
    if (m > 0)
    {
      movtime += m + ":";
      if (s < 10)
        movtime += "0" + s;
      else
        movtime += s;
    }
    else
      movtime += s ;
  }
  
  if (document.getElementById)
    document.getElementById("divCountdown").innerHTML=movtime;
  else if (document.layers)
  {
    document.layers.divCountdown.document.open();
    document.layers.divCountdown.document.write(movtime);
    document.layers.divCountdown.document.close();
  }
  else if (document.all)
    divCountdown.innerHTML = movtime;
  
  //Recursive call, keeps the clock ticking.
  setTimeout('countdown(' + newTime_Left + ');', 1000);
}
