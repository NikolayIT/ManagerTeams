<?php
/**
 * @version $Id$
 * @package dinke.net
 * @copyright &copy; 2005 Dinke.net
 * @author Dragan Dinic <dragan@dinke.net>
 */

/**
 * Browser Detection class
 * contains common static method for 
 * getting browser version and os
 *
 * It only support recent and popular browsers
 * and doesn't recognize bots (like google, yahooo etc)
 * for more browser support you might want to try some other class
 *
 * usage
 * <code>
 * $browser = Browser_Detection::get_browser($_SERVER['HTTP_USER_AGENT']);
 * $os = Browser_Detection::get_os($_SERVER['HTTP_USER_AGENT']);
 * </code>
 * @access public
 */
function bd_get_browser($useragent)
{
   //check for most popular browsers first
   //unfortunately that's ie. We also ignore opera and netscape 8
   //because they sometimes send msie agent
   if(strpos($useragent,"MSIE") !== false && strpos($useragent,"Opera") === false && strpos($useragent,"Netscape") === false)
   {
      //deal with IE
      $found = preg_match("/MSIE ([0-9]{1}\.[0-9]{1,2})/",$useragent,$mathes);
      if($found)
      {
         return "Internet Explorer " . $mathes[1];
      }
   }
   elseif(strpos($useragent,"Gecko"))
   {
      //deal with Gecko based

      //if firefox
      $found = preg_match("/Firefox\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Mozilla Firefox " . $mathes[1];
      }

      //if Netscape (based on gecko)
      $found = preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Netscape " . $mathes[1];
      }

      //if Safari (based on gecko)
      $found = preg_match("/Safari\/([0-9]{2,3}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Safari " . $mathes[1];
      }

      //if Galeon (based on gecko)
      $found = preg_match("/Galeon\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Galeon " . $mathes[1];
      }

      //if Konqueror (based on gecko)
      $found = preg_match("/Konqueror\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Konqueror " . $mathes[1];
      }

      //no specific Gecko found
      //return generic Gecko
      return "Gecko based";
   }

   elseif(strpos($useragent,"Opera") !== false)
   {
      //deal with Opera
      $found = preg_match("/Opera[\/ ]([0-9]{1}\.[0-9]{1}([0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Opera " . $mathes[1];
      }
   }
   elseif (strpos($useragent,"Lynx") !== false)
   {
      //deal with Lynx
      $found = preg_match("/Lynx\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Lynx " . $mathes[1];
      }

   }
   elseif (strpos($useragent,"Netscape") !== false)
   {
      //NN8 with IE string
      $found = preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
      if($found)
      {
         return "Netscape " . $mathes[1];
      }
   }
   else
   {
      //unrecognized, this should be less than 1% of browsers (not counting bots like google etc)!
      return false;
   }
}

/**
	 * Get browsername and version
	 * @param string user agent	 
	 * @return string os name and version or false in unrecognized os
	 * @static 
	 * @access public
	 */
function bd_get_os($useragent)
{
   $useragent = strtolower($useragent);

   //check for (aaargh) most popular first
   //winxp
   if(strpos("$useragent","windows nt 5.1") !== false)
   {
      return "Windows XP";
   }
   elseif (strpos("$useragent","windows 98") !== false)
   {
      return "Windows 98";
   }
   elseif (strpos("$useragent","windows nt 5.0") !== false)
   {
      return "Windows 2000";
   }
   elseif (strpos("$useragent","windows nt 5.2") !== false)
   {
      return "Windows 2003 server";
   }
   elseif (strpos("$useragent","windows nt 6.0") !== false)
   {
      return "Windows Vista";
   }
   elseif (strpos("$useragent","windows nt") !== false)
   {
      return "Windows NT";
   }
   elseif (strpos("$useragent","win 9x 4.90") !== false && strpos("$useragent","win me"))
   {
      return "Windows ME";
   }
   elseif (strpos("$useragent","win ce") !== false)
   {
      return "Windows CE";
   }
   elseif (strpos("$useragent","win 9x 4.90") !== false)
   {
      return "Windows ME";
   }
   elseif (strpos("$useragent","mac os x") !== false)
   {
      return "Mac OS X";
   }
   elseif (strpos("$useragent","macintosh") !== false)
   {
      return "Macintosh";
   }
   elseif (strpos("$useragent","linux") !== false)
   {
      return "Linux";
   }
   elseif (strpos("$useragent","freebsd") !== false)
   {
      return "Free BSD";
   }
   elseif (strpos("$useragent","symbian") !== false)
   {
      return "Symbian";
   }
   else
   {
      return false;
   }
}
?>