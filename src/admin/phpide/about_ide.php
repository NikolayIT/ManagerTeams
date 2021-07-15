<?php
#
# class Getcount, only loaded if this is run at original location (www.ekenberg.se)
# sets global variable $Getcount_is_loaded if loaded
#
@include("./getcount.php");
$Web = new Web;
class Web
{
   var $GPL_link		= "<A HREF='http://www.gnu.org/copyleft/gpl.html'>GNU General Public License</A>";
   var $PHP_link		= "<A HREF='http://www.php.net'>PHP</A>";
   var $Apache_link		= "<A HREF='http://www.apache.org'>Apache</A>";
   var $IIS_link		= "<A HREF='http://www.microsoft.com'>Microsoft IIS</A>";
   var $Xitami_link		= "<A HREF='http://www.imatix.com'>Xitami</A>";
   var $VMware_link		= "<A HREF='http://www.vmware.com'>VMware</A>";
   var $Homepage_url		= "http://www.ekenberg.se/php/ide/";
   var $Screenshot_main_url	= "http://www.ekenberg.se/php/ide/images/ide.php-main_window.gif";
   var $Screenshot_options_url	= "http://www.ekenberg.se/php/ide/images/ide.php-options.gif";
   var $tar_gz_filename		= "ide.php-1.5.2.tar.gz";
   var $zip_filename		= "ide.php-1.5.2.zip";
   function Web()
   {
      global $HTTP_GET_VARS;
      $this->Out	= new Page;
      $this->Text	= new Text($this);
      if (! $HTTP_GET_VARS['label'])
      {
         $HTTP_GET_VARS['label'] = "news";		// default
      }
      print $this->Out->html_top();
      print "<DIV ALIGN='CENTER'>\n";
      print "<H2>I D E . P H P</H2>\n";
      print "</DIV>\n";
      // Important output starts here
      print $this->Out->start_box_table(600);
      print "<TR><TD>\n";
      while (list($label,$header) = each($this->Text->Info_sections))
      {
         print "<H4><A HREF='{$GLOBALS['PHP_SELF']}?label=$label' TARGET='_self'>$header</A></H4>\n";
         if ($label == $HTTP_GET_VARS['label'])
         {
            print $this->Out->begin_invisible_table("85%", array("CELLPADDING='1'", "CELLSPACING='0'", "ALIGN='center'", "BGCOLOR='#000000'"));
            print "<TR><TD>\n";
            print $this->Out->begin_invisible_table("100%", array("CELLPADDING='20'", "CELLSPACING='0'", "ALIGN='center'", "BGCOLOR='{$this->Out->Box_bgcolor}'"));
            print "<TR><TD>\n";
            print $this->Text->$label();
            print "</TD></TR></TABLE>\n";
            print "</TD></TR></TABLE>\n";
         }
      }
      print "</TD></TR>\n";
      print $this->Out->end_box_table();
      print "<BR>\n";
      print $this->Out->start_box_table(600);
      print "<TR><TD>\n";
      print "<H4>Update notification</H4>\n";
      print $this->Out->begin_invisible_table("85%", array("CELLPADDING='1'", "CELLSPACING='0'", "ALIGN='center'", "BGCOLOR='#000000'"));
      print "<TR><TD>\n";
      print $this->Out->begin_invisible_table("100%", array("CELLPADDING='20'", "CELLSPACING='0'", "ALIGN='center'", "BGCOLOR='{$this->Out->Box_bgcolor}'"));
      print "<TR><TD>\n";
      print "<P><SMALL>Enter your email address below to receive notification by email
         when new versions of Ide.php are released. Your address will be kept private and used
         only for this purpose.</SMALL></P>\n";
      print "<FORM METHOD='post' ACTION='http://www.ekenberg.se/cgi-bin/subscribe_gm.cgi' TARGET='_self'>\n";
      print "<INPUT TYPE='text' NAME='address'>\n";
      print "<BR>\n";
      print "<SELECT NAME='action'>\n";
      print "<OPTION VALUE='subscribe'>Subscribe\n";
      print "<OPTION VALUE='unsubscribe'>Unsubscribe\n";
      print "</SELECT>\n";
      print "<INPUT TYPE='submit' VALUE='  OK  '>\n";
      print "<INPUT TYPE='hidden' NAME='list' VALUE='ide.php-announce'>\n";
      #   print "<INPUT TYPE='hidden' NAME='email_alert' VALUE='0'>\n";
      #   print "<INPUT TYPE='hidden' NAME='alert_address' VALUE='ide.php@ekenberg.se'>\n";
      print "<INPUT TYPE='hidden' NAME='mail_subscriber' VALUE='1'>\n";
      print "<INPUT TYPE='hidden' NAME='from_address' VALUE='ide.php@ekenberg.se'>\n";
      print "<INPUT TYPE='hidden' NAME='welcome_message_subject' VALUE='Welcome to ide.php-announce@ekenberg.se!'>\n";
      print "<INPUT TYPE='hidden' NAME='welcome_message' VALUE='Your email address was added. Announcements about Ide.php will be sent to you.
To unsubscribe from this list, please use the form at URL: http://www.ekenberg.se/php/ide/'>\n";
      print "<INPUT TYPE='hidden' NAME='subscribe_url' VALUE='www.ekenberg.se/php/ide/'>\n";
      print "<INPUT TYPE='hidden' NAME='unsubscribe_url' VALUE='www.ekenberg.se/php/ide/'>\n";
      print "<INPUT TYPE='hidden' NAME='fail_url' VALUE='www.ekenberg.se/php/ide/'>\n";
      print "</FORM>\n";
      print "</TD></TR></TABLE>\n";
      print "</TD></TR></TABLE>\n";
      print "</TD></TR>\n";
      print $this->Out->end_box_table();
      // Important output ends here
      print $this->Out->html_bottom();
   }
} // end class Web
#
# class Text
#
class Text
{
   var $Super;
   var $Info_sections	= array("what"		=> "What is Ide.php?",
   "news"		=> "News",
   "try"		=> "Try it out",
   "documentation"	=> "Documentation",
   "security"	=> "Security",
   "download"	=> "Download",
   "license"	=> "License",
   "requirements"	=> "Requirements",
   "contact"	=> "Contact");
   function Text($super)
   {
      $this->Super = $super;
   }
   function news()
   {
      if (file_exists("./Changes.txt"))
      {
         $changes_array = array_reverse(file("./Changes.txt"));
         for ($i=0;$i<sizeof($changes_array);$i++)
         {
            $changes_array[$i] = ereg_replace("^([-0-9]+)[[:space:]]+(.+)", "<small>\\1</small> \\2", htmlentities($changes_array[$i]));
         }
      }
      else
      {
         $changes_array[0] = "Please visit the Ide.php <A HREF='{$this->Super->Homepage_url}'>homepage</A> to view recent changes!";
      }
      $ret .= "<H5>Releases</H5>
            <UL>
            <LI><small>2004-08-21</small> Released version 1.5.2
            <LI><small>2003-05-16</small> Released version 1.5.1
            <LI><small>2002-04-11</small> Released version 1.5
            <LI><small>2001-08-28</small> Released version 1.4
            <LI><small>2000-08-21</small> Released version 1.3
            <LI><small>2000-06-13</small> Released version 1.2
            <LI><small>2000-06-03</small> Released version 1.1
            <LI><small>2000-05-26</small> Initial release, version 1.0
            </UL>
            <H5>Changes</H5>
            <UL>\n";
      while (list(,$row) = each($changes_array))
      {
         $ret .= "<LI>$row\n";
      }
      $ret .= "</UL>";
      return($ret);
   }
   function what()
   {
      $ret .= "<P>Ide.php is a web-based editor for quick development of server-side code.
            It offers a rapid prototyping environment, letting the user test and 
            save snippets of code with minimal overhead.</P>
            <P>Ide.php was primarily written for {$this->Super->PHP_link}, but has been extended
            to enable development in any server-side scripting language available on the server
            where it's running, like SSI, ASP, JSP, SSJS, even CGI!</P>
            <P>Ide.php eliminates the need to use several
            separate programs (text editor, FTP program and web browser)
            for web development, since all work is done directly through the browser.
            This helps to shorten development time, and is also very helpful
            when learning a new scripting language, like {$this->Super->PHP_link}.</P>
            <P>Ide.php requires {$this->Super->PHP_link} version 4 or higher.</P>";
      return($ret);
   }

   function try()
   {
      $ret .= "<P>For security reasons, you can <U>not</U> try Ide.php at this website!</P>
            <P>I'm sorry to have tricked you into reading this, but it's
            very important to understand the security issues involved in using Ide.php.
            Please read more about security <A HREF='{$GLOBALS['PHP_SELF']}?label=security' TARGET='_self'>below</A>.</P>
            <P>By popular request, here are a few screenshots of Ide.php in action:</P>
            <UL>
            <LI><A HREF='{$this->Super->Screenshot_main_url}'>Main code window (version 1.3)</A>
            <LI><A HREF='{$this->Super->Screenshot_options_url}'>Options dialog (version 1.3)</A>
            </UL>";
      return($ret);
   }

   function documentation()
   {
      $ret .= "<H5>Installation</H5>
            <P>Installation of Ide.php is easy:</P>
            <OL>
            <LI><A HREF='{$GLOBALS['PHP_SELF']}?label=download' TARGET='_self'>Download</A>
            the latest distribution from this website.
            <LI>Unzip or untar the contents of the distribution archive in
            the directory were you want to put Ide.php. Obviously, this directory
            has to reside somewhere in your web space.
            <LI>Make sure that the web server has write access to the directory
            where you put Ide.php, the 'data' directory (which was created
            when unpacking the archive), and the files in the 'data' directory.
            If you don't know how to do this, contact your system administrator.
            On my Linux system it's: 'chmod 777 . ./data ./data/*', executed
            in the chosen directory.
            <LI>Be sure to read the section about <A HREF='{$GLOBALS['PHP_SELF']}?label=security' TARGET='_self'>
            security</A>, and take relevant steps for protection.
            </OL>
            <H5>Usage</H5>
            <P>Using Ide.php should be fairly self explanatory. In your web browser,
            go to the URL where you unpacked Ide.php. The main page should appear.
            If it doesn't, you should adjust your web server to accept 'index.php' as a Directory Index.
            The big textarea is where your code goes. This could be any code that's acceptable on your server,
            like PHP, HTML, ASP, CGI etc.</P>
            <UL>
            <LI>The current context is determined by the suffix set in the lower middle: 'Run as'.
            To execute your code in ASP context, select '.asp' in the 'Run as' list. 
            (Obviously, your server has to support ASP for this to work). Additional suffixes can
            be added through the 'Options' dialog.
            <LI>To view the result, press '- RUN -'. The output of your code should appear in a separate browser window.
            <LI>Additional adjustments can be made through the 'Options' dialog. Notably, running CGI (Perl) on UNIX
            may require changed settings for file permissions and line endings.
            <LI>Different browsers handle HTML entities in textareas (&amp;gt; etc in the edited code) in different ways.
            Adjustments for this can be made in the 'Options' dialog.
            <LI>'Fancy view' shows your code in a colorful way through the built-in PHP source viewer. Optionally it can
            also show line numbers. If you are using password protection for Ide.php (you are, aren't you?), you will
            most likely have to enter your username/password in the Options dialog to make 'Fancy view' work correctly.
            <LI>Use 'Save as tpl' to save your current code as a template. Press 'Open tpl' to open
            the template. The template could be the basic &lt;HTML&gt;...&lt;/HTML&gt; stuff to avoid typing it every time.
            <LI>'Clear' will erase all code in the code-window.
            <LI>To the right is the File dialog for the 'data' directory. Use this to open or erase files from your
            private code archive. Use the 'Save as' button in the second row to save the current code in your archive.
            <LI>The size of the code-window is adjustable through the fields at the bottom of the page.
            Press 'Save settings' to make the new size your default.
            </UL>";
      return($ret);
   }

   function security()
   {
      $ret .= "<P>Ide.php is a powerful tool, which gives the user ability to execute
            arbitrary server-side code on the webserver where it resides. For this reason,
            it's also a very <U>dangerous</U> tool if it's not set up in a secure way.</P>
            <P>The secure way to set up Ide.php is to arrange that
            <U>no one else</U> has access to it. This can be done in two ways:</P>
            <UL>
            <LI>Use a personal webserver. This is the preferred alternative.
            Get {$this->Super->Apache_link} & {$this->Super->PHP_link} and install them on your computer. If that's not possible,
            consider using {$this->Super->VMware_link} to set up a second operating system in your computer,
            wherein you can run {$this->Super->Apache_link} & {$this->Super->PHP_link}.
            <LI>Use password protection. If you have to put Ide.php on a public webserver,
            you should put it in a password protected area. Since password protection often uses
            HTTP Authentication, this could cause a problem if you're
            using Ide.php to write and test code that sends its own HTTP Authentication headers.
            </UL>
            <P>Depending on the amount of <A HREF='{$GLOBALS['PHP_SELF']}?label=contact' TARGET='_self'>feedback</A> received,
            I'm considering integrating
            a cookie-based authentication scheme into Ide.php, which would allow for easy and secure setup,
            while eliminating potential conflicts using HTTP Authentication headers.</P>";
      return($ret);
   }

   function download()
   {
      $unix_downloads = $this->num_downloads("tar.gz");
      $win_downloads  = $this->num_downloads("zip");
      $ret .= "<P>Ide.php is distributed as a compressed archive, choose the type you prefer:</P>
            <UL>
            <LI><A HREF='./getfile.php?filename={$this->Super->tar_gz_filename}'>{$this->Super->tar_gz_filename}</A> - Unix style$unix_downloads
            <LI><A HREF='./getfile.php?filename={$this->Super->zip_filename}'>{$this->Super->zip_filename}</A> - Windows style$win_downloads
            </UL>";
      return($ret);
   }

   function license()
   {
      $ret .= "<P>Ide.php is distributed under the {$this->Super->GPL_link}</P>";
      return($ret);
   }

   function requirements()
   {
      $ret .= "<P>Ide.php requires {$this->Super->PHP_link} version 4 or higher.</P>
            <P>Ide.php needs a JavaScript enabled web browser to run correctly.
            It's been tested and is known to work with IE4/5 and Netscape 4.5</P>
            <P>Ide.php has been confirmed to run well with the following web servers:
            <UL>
            <LI>{$this->Super->Apache_link}
            <LI>{$this->Super->IIS_link}
            <LI>{$this->Super->Xitami_link}
            </UL>
            <P>If you get it running on some other server - please 
             <A HREF='{$GLOBALS['PHP_SELF']}?label=contact' TARGET='_self'>drop me a line</A>
            to tell what server you're using and how Ide.php works with it.";
      return($ret);
   }

   function contact()
   {
      $ret .= "<P>Ide.php is developed by <A HREF='mailto:johan@ekenberg.se'>Johan Ekenberg</A>,
            a Swedish Internet consultant who, besides web development with {$this->Super->PHP_link},
            does a lot of Perl, C, Linux and bass playing.</P>
            <P>Please use the address <A HREF='mailto:ide.php@ekenberg.se'>ide.php@ekenberg.se</A>
            for email related to Ide.php</P>";
      return($ret);
   }

   function num_downloads($filetype)
   {
      if ($GLOBALS['Getcount_is_loaded'])
      {
         $GC = new Getcount;
         $num_downloads = $GC->get_count($filetype);
         if ($num_downloads)
         {
            return (" ($num_downloads downloads)");
         }
      }
      return;
   }

}
#
# class Page
#
class Page
{
   var $Bgcolor		= "#FFE56A";
   var $Box_bgcolor	= "#FFFFDD";
   var $Link_color	= "#0A0AA0";
   var $Alink_color	= "#0000CC";
   var $Vlink_color	= "#464686";
   function start_box_table($width)
   {
      $ret .= $this->begin_invisible_table($width, array("CELLPADDING='1'", "CELLSPACING='0'", "ALIGN='center'", "BGCOLOR='#000000'"));
      $ret .= "<TR><TD>\n";
      $ret .= $this->begin_invisible_table($width, array("CELLPADDING='12'", "CELLSPACING='0'", "WIDTH='100%'", "BGCOLOR='{$this->Bgcolor}'"));
      return ($ret);
   }
   function end_box_table()
   {
      $ret .= "</TABLE></TD></TR></TABLE>\n";
      return($ret);
   }
   function begin_invisible_table($width, $attr="")
   {
      $ret  = "<TABLE WIDTH='$width' BORDER='0' ";
      $ret .= (is_array($attr) ? join(" ", $attr) : NULL) . ">\n";
      return ($ret);
   }
   function end_invisible_table()
   {
      $ret = "</TABLE>\n";
      return ($ret);
   }
   function html_top()
   {
      $ret .= "<HTML><HEAD>\n";
      $ret .= "<BASE TARGET='_blank'>\n";
      $ret .= "<TITLE>About IDE.PHP</TITLE>\n";
      $ret .= "{$this->CSS_code}\n";
      $ret .= "</HEAD>\n";
      $ret .= "<BODY BGCOLOR='{$this->Bgcolor}' LINK='{$this->Link_color}' ALINK='{$this->Alink_color}' VLINK='{$this->Vlink_color}'>\n";
      return ($ret);
   }
   function html_bottom()
   {
      return "</BODY></HTML>\n";
   }
   function Page()
   {
      $this->CSS_code =
      "<STYLE TYPE='text/css'>
   <!--
   A {
      text-decoration: none;
   }
   A:HOVER {
      color: {$this->Alink_color};
   }
   INPUT {
      font-family: Arial, 'MS Sans Serif', Helvetica;
      font-size: 10pt;
   }
   BODY {
      font-family: Arial, 'MS Sans Serif', Helvetica;
   }
   TD {
      font-family: Verdana,Geneva,Arial,Helvetica;
      font-size: 10pt;
   }
   H2 {
      font-family: Verdana,Geneva,Arial,Helvetica;
      font-size: 20pt;
      font-weight: 500;
   }
   H4 {
      font-family: Verdana,Geneva,Arial,Helvetica;
      font-size: 13pt;
      font-style: Italic;
      font-weight: 500;
      margin-left: 40pt;
   }
   H5 {
      font-family: Verdana,Geneva,Arial,Helvetica;
      font-size: 10pt;
   }
   P {
      text-indent: 10pt;
      margin-left: 10pt;
      margin-right: 15pt;
   }
   SMALL {
      font-family: Verdana,Geneva,Arial,Helvetica;
      font-size: 8pt;
   }
   BLOCKQUOTE {
      font-family: Verdana,Geneva,Arial,Helvetica;
      font-size: 10pt;
      text-indent: 10pt;
      margin-left: 20pt;
      margin-right: 25pt;
      background-color: {$this->Box_bgcolor};
      padding-left: 15pt;
      padding-right: 15pt;
      padding-top: 5pt;
      padding-bottom: 5pt;
   }
   OL {
      margin-left: 20pt;
      margin-right: 25pt;
   }
   UL {
      margin-left: 13pt;
      margin-right: 25pt;
      list-style-type: square;
   }
   LI {
      margin-top: 4pt;
   }
   -->
   </STYLE>\n";
   }
}
?>
