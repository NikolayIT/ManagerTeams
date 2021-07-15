<?php
/*******************************************************************************\
*    IDE.PHP, a web based editor for quick PHP development                     *
*    Copyright (C) 2000  Johan Ekenberg                                        *
*                                                                              *
*    This program is free software; you can redistribute it and/or modify      *
*    it under the terms of the GNU General Public License as published by      *
*    the Free Software Foundation; either version 2 of the License, or         *
*    (at your option) any later version.                                       *
*                                                                              *
*    This program is distributed in the hope that it will be useful,           *
*    but WITHOUT ANY WARRANTY; without even the implied warranty of            *
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             *
*    GNU General Public License for more details.                              *
*                                                                              *
*    You should have received a copy of the GNU General Public License         *
*    along with this program; if not, write to the Free Software               *
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA  *
*                                                                              *
*    To contact the author regarding this program,                             *
*    please use this email address: <ide.php@ekenberg.se>                      *
\*******************************************************************************/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);
include ("./admin/phpide/Page.phpclass");
include ("./admin/phpide/Conf.phpclass");
$Ide = new Ide;
class Ide
{
   var $code, $alert_message, $success_message;
   var $IDE_homepage_url	= "http://www.ekenberg.se/php/ide/";
   var $GPL_link		= "<A HREF='http://www.gnu.org/copyleft/gpl.html'>GNU General Public License</A>";
   var $PHP_link		= "<A HREF='http://www.php.net'>PHP</A>";
   var $IDE_version		= "1 . 5";
   function Ide()
   {
      global $HTTP_POST_VARS, $HTTP_GET_VARS;
      $this->Conf = new Conf;
      $this->Out  = new Page;
      /*
      ** Remove slashes if necessary, put code in $this->code
      */
      if (isset($HTTP_POST_VARS['code']))
      {
         if (get_magic_quotes_gpc()) {
            $this->code = stripslashes($HTTP_POST_VARS['code']);
         }
         else
         {
            $this->code = $HTTP_POST_VARS['code'];
         }
      }
      /*
      ** Get code from code file if not submitted through form.
      */
      if ((! isset($this->code)) && (file_exists($this->Conf->Code_file)))
      {
         $this->code = join ("", (file ($this->Conf->Code_file)) );
      }
      /*
      ** Since the code is displayed in a <TEXTAREA>, it can't contain the tag </TEXTAREA>,
      ** since that would break our editor :/ Thus we replace it with </ideTEXTAREA>
      ** and put it in $this->textarea_safe_code. The reverse substitution is first
      ** performed on $this->code, to restore any previous replacements.
      */
      $this->code			= eregi_replace("</ide(TEXTAREA)>", "</\\1>", $this->code);
      $this->textarea_safe_code	= $this->make_textarea_safe($this->code);
      /*
      ** Htmlentities are not literally shown inside TEXTAREA in some (all?) browsers.
      */
      if ($this->Conf->Protect_entities)
      $this->code		= eregi_replace("(&amp;)+&", "&", $this->code);
      /*
      ** Remove \r\f if desired, needed for cgi on UNIX
      */
      if ($this->Conf->Unix_newlines)
      {
         $this->code		= preg_replace("/[\r\f]/", "", $this->code);
      }
      /*
      ** What file are we working with?
      */
      $this->Conf->Current_file = $HTTP_POST_VARS['Current_file'] ? $HTTP_POST_VARS['Current_file'] : $this->Conf->Tmp_file;
      /*
      ** Check our environment.
      */
      if ($error = $this->Conf->Is_bad_environment())
      {
         print $this->Out->html_top();
         print "<H3><BLOCKQUOTE>$error</BLOCKQUOTE></H2>\n";
         print $this->Out->html_bottom();
         //exit;
      }
      /*
      ** Always save the code in our code and tmp files
      */
      if (isset($this->code))
      {
         $FH_CODE = fopen ($this->Conf->Code_file, "w");
         $FH_TMP  = fopen ($this->Conf->Tmp_file, "w");
         fputs  ($FH_CODE, $this->code);
         fputs  ($FH_TMP, $this->code);
         fclose ($FH_CODE);
         fclose ($FH_TMP);
      }
      /*
      ** These options are saved every time
      */
      $this->Conf->save_to_file(array('Eval_suffix'));
      /*
      ** Set file permissions as desired
      */
      if ($this->Conf->Eval_executable)
      {
         chmod ($this->Conf->Tmp_file, 0755);
      }
      else
      {
         chmod ($this->Conf->Tmp_file, 0644);
      }
      /*
      ** Print-and-exit-immediately stuff
      */
      if ($HTTP_GET_VARS['action'] == "fancy_view_source")
      {
         print $this->fancy_view_source();
         //exit;
      }
      if ($HTTP_POST_VARS['action'] == "about")
      {
         print $this->Out->html_top();
         print $this->about_page();
         print $this->Out->html_bottom();
         ////exit;
      }
      if ($HTTP_POST_VARS['action'] == "options")
      {
         if ($HTTP_POST_VARS['options_action'] == "add_suffix")
         {
            $add_suffix = ereg_replace("^\.*(.+)", ".\\1", trim($HTTP_POST_VARS['add_remove_suffix']));
            if ($add_suffix && (! in_array($add_suffix, $this->Conf->Eval_suffix_list)))
            {
               $this->Conf->Eval_suffix_list[] = $add_suffix;
            }
            $this->options_page_save(array('Fancy_view_line_numbers', 'Protect_entities',
            'Eval_executable', 'Unix_newlines', 'Eval_suffix_list'));
         }
         elseif ($HTTP_POST_VARS['options_action'] == "remove_suffix")
         {
            $remove_suffix = ereg_replace("^\.*(.+)", ".\\1", trim($HTTP_POST_VARS['add_remove_suffix']));
            if ($remove_suffix && (in_array($remove_suffix, $this->Conf->Eval_suffix_list)))
            {
               reset ($this->Conf->Eval_suffix_list);
               for ($i=0; $i<sizeof($this->Conf->Eval_suffix_list); $i++)
               {
                  if (ereg("^$remove_suffix$", $this->Conf->Eval_suffix_list[$i]))
                  {
                     unset($this->Conf->Eval_suffix_list[$i]);
                  }
               }
            }
            $this->options_page_save(array('Fancy_view_line_numbers', 'Protect_entities',
            'Eval_executable', 'Unix_newlines', 'Eval_suffix_list'));
         }
         print $this->Out->html_top();
         print $this->options_page();
         print $this->Out->html_bottom();
         //exit;
      }
      /*
      ** Print top of page
      */
      print $this->Out->html_top();
      /*
      ** Act according to 'action'
      */
      if ($HTTP_POST_VARS['action'] == "eval")
      {
         print $this->js_open_code_window ($this->Conf->Tmp_file);
      }
      elseif ($HTTP_POST_VARS['action'] == "source_viewer")
      {
         print $this->js_open_code_window (PAGE_ADDRESS."&action=fancy_view_source&file={$this->Conf->Tmp_file}");
      }
      elseif ($HTTP_POST_VARS['action'] == "save_as") {
         if (! strlen($HTTP_POST_VARS['save_as_filename'])) {
            $this->alert_message = "Can't save file without a filename!!";
         }
         elseif ((! $HTTP_POST_VARS['overwrite_ok']) && (file_exists("{$this->Conf->Data_dir}/{$HTTP_POST_VARS['save_as_filename']}"))) {
            $this->alert_message = "The file <B>{$this->Conf->Data_dir}/{$HTTP_POST_VARS['save_as_filename']}</B> already exists!
                   Please choose another name, or check \"Replace\".";
         }
         else {
            if ($FH_SAVEAS = @fopen ("{$this->Conf->Data_dir}/{$HTTP_POST_VARS['save_as_filename']}", "w")) {
               fputs  ($FH_SAVEAS, $this->code);
               fclose ($FH_SAVEAS);
               $this->success_message = "Current code was saved to file: <B>{$this->Conf->Data_dir}/{$HTTP_POST_VARS['save_as_filename']}</B>!";
            }
            else {
               $this->alert_message = "Could not save to file <B>{$this->Conf->Data_dir}/{$HTTP_POST_VARS['save_as_filename']}</B>: $php_errormsg";
            }
            $this->Conf->Current_file = "{$this->Conf->Data_dir}/{$HTTP_POST_VARS['save_as_filename']}";
         }
      }
      elseif ($HTTP_POST_VARS['action'] == "open_file") {
         $this->textarea_safe_code = join ("", (file ("{$this->Conf->Data_dir}/{$HTTP_POST_VARS['code_file_name']}")));
         if (get_magic_quotes_runtime()) $this->textarea_safe_code = stripslashes($this->textarea_safe_code);
         $this->textarea_safe_code = $this->make_textarea_safe($this->textarea_safe_code);
         $this->Conf->Current_file = "{$this->Conf->Data_dir}/{$HTTP_POST_VARS['code_file_name']}";
         $HTTP_POST_VARS['save_as_filename'] = $HTTP_POST_VARS['code_file_name'];
      }
      elseif ($HTTP_POST_VARS['action'] == "erase_file") {
         if (unlink ("{$this->Conf->Data_dir}/{$HTTP_POST_VARS['code_file_name']}")) {
            $this->Conf->Current_file = $this->Conf->Tmp_file;
            $HTTP_POST_VARS['save_as_filename'] = $HTTP_POST_VARS['overwrite_ok'] = "";
            $this->success_message = "The file <B>{$this->Conf->Data_dir}/{$HTTP_POST_VARS['code_file_name']}</B> was erased!";
         }
      }
      elseif ($HTTP_POST_VARS['action'] == "save_size") {
         $this->Conf->save_to_file(array('Code_cols', 'Code_rows'));
      }
      elseif ($HTTP_POST_VARS['action'] == "show_template") {
         $this->textarea_safe_code = $this->make_textarea_safe($this->Conf->Code_template);
      }
      elseif ($HTTP_POST_VARS['action'] == "save_as_template") {
         $this->Conf->Code_template = $this->code;
         $this->Conf->save_to_file(array('Code_template'));
      }
      elseif ($HTTP_POST_VARS['action'] == "save_options") {
         $this->options_page_save(array('Fancy_view_line_numbers', 'Protect_entities',
         'Eval_executable', 'Unix_newlines', 'Http_auth_username', 'Http_auth_password',));
         $this->success_message = "Ide.php options were saved!";
      }

      /*
      ** Print the main page and exit
      */
      print $this->main_page();
      print $this->Out->html_bottom();
      //exit;
   }

   /*
   ** Functions
   */

   function options_page() {
      $fancy_view_line_numbers_checked = $this->Conf->Fancy_view_line_numbers ? "CHECKED" : "";
      $protect_entities_checked = $this->Conf->Protect_entities ? "CHECKED" : "";
      $eval_executable_checked = $this->Conf->Eval_executable ? "CHECKED" : "";
      $unix_newlines_checked = $this->Conf->Unix_newlines ? "CHECKED" : "";
      reset($this->Conf->Eval_suffix_list);
      $sections = array(
      "<P><INPUT TYPE='CHECKBOX' NAME='Fancy_view_line_numbers' VALUE='1' $fancy_view_line_numbers_checked>
	   Print line numbers in 'Fancy view'</P>",
	   "<P><INPUT TYPE='CHECKBOX' NAME='Protect_entities' VALUE='1' $protect_entities_checked>
	   Protect HTML entities (IE4/5)</P>",
	   "<P CLASS='indentall'>Suffix list:<BR><I>&nbsp;" . join(" &nbsp;", $this->Conf->Eval_suffix_list) . "</I></P>\n
	 <P CLASS='indentall'>Add/remove suffix:
	   <BR><INPUT TYPE='text' NAME='add_remove_suffix' SIZE='8'>
	   &nbsp; <INPUT TYPE='submit' VALUE='Add' onClick='document.options_form.options_action.value=\"add_suffix\"; document.options_form.action.value=\"options\"'>
	   <INPUT TYPE='submit' VALUE='Remove' onClick='document.options_form.options_action.value=\"remove_suffix\"; document.options_form.action.value=\"options\"'></P>\n
	 <P><INPUT TYPE='CHECKBOX' NAME='Eval_executable' VALUE='1' $eval_executable_checked>Make executable (CGI on UNIX)</P>\n
	 <P><INPUT TYPE='CHECKBOX' NAME='Unix_newlines' VALUE='1' $unix_newlines_checked>
	   Use UNIX newlines (CGI on UNIX)</P>",
	   "<P CLASS='indentall'>To make 'Fancy view' work under password protection:<P>
         <P CLASS='indentall'><TABLE BORDER='0' WIDTH='70%' CELLPADDING='0' CELLSPACING='0'>
         <TR><TD><P CLASS='noindent'>Username:</P></TD>
         <TD><P CLASS='noindent'>Password:</P></TD></TR>
         <TR><TD><INPUT TYPE='text' NAME='Http_auth_username' SIZE='12' VALUE='{$this->Conf->Http_auth_username}'></TD>
         <TD><INPUT TYPE='text' NAME='Http_auth_password' SIZE='12' VALUE='{$this->Conf->Http_auth_password}'></TD></TR></TABLE>
         </P>");
	   $ret .= "<DIV ALIGN='CENTER'>\n";
	   $ret .= "<H2>I D E . P H P &nbsp; O P T I O N S</H2></DIV>\n";
	   $ret .= "<FORM NAME='options_form' METHOD='POST' ACTION='".PAGE_ADDRESS."'>\n";
	   $ret .= "<INPUT TYPE='hidden' NAME='action' VALUE='save_options'>\n";
	   $ret .= "<INPUT TYPE='hidden' NAME='options_action' VALUE=''>\n";
	   while (list(,$content) = each($sections)) {
	      $ret .= "<BR>\n";
	      $ret .= $this->Out->info_box(400, $content);
	   }
	   $ret .= "<BR><DIV ALIGN='CENTER'>\n";
	   $ret .= "<A HREF='javascript: document.options_form.submit()' CLASS='netscapesucks'>[ r e t u r n ]</A></DIV>\n";
	   $ret .= "</FORM>\n";
	   return($ret);
   }

   function about_page() {
      $sections = array(
      "<P><B>I d e . p h p &nbsp; v e r s i o n &nbsp; {$this->IDE_version}</B></P>\n",
      "<P>Ide.php is distributed under the {$this->GPL_link}</P>",
      "<P>Ide.php is developed by <A HREF='mailto:johan@ekenberg.se'>Johan Ekenberg</A>,
           a Swedish Internet consultant who, besides web development with {$this->PHP_link}, does a lot of Perl, C, Linux and bass playing.</P>\n",
           "<P>Visit the <A HREF='{$this->IDE_homepage_url}'>Ide.php homepage</A>.\n",
           "<P>Feedback and suggestions are always welcome, please use the address
           <A HREF='mailto:ide.php@ekenberg.se'>ide.php@ekenberg.se</A> for email related to Ide.php</P>");

           $ret .= "<DIV ALIGN='CENTER'>\n";
           $ret .= "<H2>A B O U T &nbsp; I D E . P H P</H2></DIV>\n";

           while (list(,$content) = each($sections)) {
              $ret .= "<BR>\n";
              $ret .= $this->Out->info_box(400, $content);
           }
           $ret .= "<BR><DIV ALIGN='CENTER'>\n";
           $ret .= "<A HREF='".PAGE_ADDRESS."' CLASS='netscapesucks'>[ r e t u r n ]</A></DIV>\n";
           return($ret);
   }

   function main_page() {
      global $HTTP_POST_VARS;
      $suffix_list_selected[$this->Conf->Eval_suffix] = "SELECTED";
      while (list(,$suffix) = each($this->Conf->Eval_suffix_list)) {
         $suffix_select_options .= "<OPTION VALUE='$suffix' {$suffix_list_selected[$suffix]}>$suffix\n";
      }
      $ret .= "<H2>I D E . P H P</H2>\n";
      $ret .= "<FORM NAME='main_form' METHOD='POST' ACTION='".PAGE_ADDRESS."'>\n";
      $ret .= "<INPUT TYPE='hidden' NAME='action' VALUE=''>\n";
      $ret .= "<INPUT TYPE='hidden' NAME='Current_file' VALUE='{$this->Conf->Current_file}'>\n";
      $ret .= $this->Out->begin_invisible_table("", array("CELLPADDING='1'", "CELLSPACING='0'", "ALIGN='center'"));
      $ret .= "<TR><TD>\n";
      $ret .= "<FONT COLOR='{$this->Conf->Alert_message_color}' FACE='MS Sans Serif, Arial'>{$this->alert_message}</FONT>\n";
      $ret .= "<FONT COLOR='{$this->Conf->Success_message_color}' FACE='MS Sans Serif, Arial'>{$this->success_message}</FONT>\n";
      $ret .= "</TD</TR>\n";
      $ret .= "<TR><TD>\n";
      $ret .= $this->Out->start_box_table();
      $ret .= "<TR BGCOLOR='#FFE56A'><TD>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='- RUN -' onClick='main_form.action.value=\"eval\"; main_form.submit()'></TD>\n";
      $ret .= "<TD ALIGN='center'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Fancy view' onClick='main_form.action.value=\"source_viewer\"; main_form.submit()'></TD>\n";
      $ret .= "<TD ALIGN='center'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Open tpl' onClick='main_form.action.value=\"show_template\"; return confirm(\"Replace current code with new template?\")'></TD>\n";
      $ret .= "<TD ALIGN='center'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Save as tpl' onClick='main_form.action.value=\"save_as_template\"; return confirm(\"Replace current template?\")'></TD>\n";
      $ret .= "<TD ALIGN='center'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Clear' onClick='if (confirm(\"Do you really want to clear the codearea??\")) {main_form.code.value=\"\"}; return false'></TD>\n";
      $ret .= "<TD ALIGN='right' COLSPAN='2'>\n";
      $ret .= "<SELECT NAME='code_file_name'>\n";

      $data_dir_obj = dir ($this->Conf->Data_dir);
      $selected[$this->Conf->Current_file] = "SELECTED";
      while (false !== ($entry = $data_dir_obj->read())) $my_files[] = $entry;
      sort($my_files);
      while (strlen($file=next($my_files))) {
         if (ereg("^\.{1,2}$", $file)) continue;
         $my_fullname = "{$data_dir_obj->path}/$file";
         $ret .= "<OPTION VALUE='$file' {$selected[$my_fullname]}>$my_fullname</OPTION>\n";
      }
      $data_dir_obj->close();

      $ret .= "</SELECT></TD></TR>\n";
      $ret .= "<TR BGCOLOR='#FFE56A'><TD COLSPAN='5' CLASS='netscapesucks'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Save as' onClick='main_form.action.value=\"save_as\"; main_form.submit()'>\n";
      $ret .= "{$this->Conf->Data_dir}/<INPUT TYPE='text' NAME='save_as_filename' VALUE='{$HTTP_POST_VARS['save_as_filename']}'>\n";
      $ret .= "Replace: <INPUT TYPE='CHECKBOX' NAME='overwrite_ok' VALUE='CHECKED' {$HTTP_POST_VARS['overwrite_ok']}></TD>\n";
      $ret .= "<TD ALIGN='right' COLSPAN='2'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Open' onClick='main_form.action.value=\"open_file\"'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Erase' onClick='main_form.action.value=\"erase_file\"; return confirm(\"Erase: {$this->Conf->Data_dir}/\"+ main_form.code_file_name.value+ \" ?\")'>\n";
      $ret .= "</TD></TR>\n";
      $ret .= "<TR BGCOLOR='#FFE56A'><TD COLSPAN='7'>\n";
      $ret .= "<TEXTAREA COLS='{$this->Conf->Code_cols}' ROWS='{$this->Conf->Code_rows}' NAME='code'>{$this->textarea_safe_code}</TEXTAREA>\n";
      $ret .= "</TD></TR>\n";
      $ret .= "<TR BGCOLOR='#FFE56A'><TD COLSPAN='3' CLASS='netscapesucks'>\n";
      $ret .= "Rows: <INPUT TYPE='text' NAME='Code_rows' VALUE='{$this->Conf->Code_rows}' SIZE='3' MAXLENGTH='3' CLASS='netscapesucks2'>\n";
      $ret .= "Columns: <INPUT TYPE='text' NAME='Code_cols' VALUE='{$this->Conf->Code_cols}' SIZE='3' MAXLENGTH='3' CLASS='netscapesucks2'>\n";
      $ret .= "<INPUT TYPE='submit' VALUE='Save size' onClick='main_form.action.value=\"save_size\"; main_form.submit()'></TD>\n";
      $ret .= "<TD ALIGN='center' COLSPAN='2'><SPAN CLASS='netscapesucks'>Run as:</SPAN> <SELECT NAME='Eval_suffix'>$suffix_select_options</SELECT></TD>\n";
      $ret .= "<TD ALIGN='center'><INPUT TYPE='submit' VALUE='Options' onClick='main_form.action.value=\"options\"; main_form.submit()'></TD>\n";
      $ret .= "<TD ALIGN='right'><INPUT TYPE='submit' VALUE='About' onClick='main_form.action.value=\"about\"; main_form.submit()'>\n";
      $ret .= "</TD></TR>\n";
      $ret .= $this->Out->end_box_table();
      $ret .= "</TD></TR>\n";
      $ret .= $this->Out->end_invisible_table();
      $ret .= "</FORM>\n";
      return ($ret);
   }

   function fancy_view_source() {
      global $HTTP_GET_VARS;
      $row_num_spacer = "&nbsp;&nbsp;";
      $ret = "";
      if ($HTTP_GET_VARS['internal_request'] || (! $this->Conf->Fancy_view_line_numbers)) {
         show_source($HTTP_GET_VARS['file']);
         return;
      }
      else {
         if ($this->Conf->Http_auth_username && $this->Conf->Http_auth_password) {
            $internal_url = "http://{$this->Conf->Http_auth_username}:{$this->Conf->Http_auth_password}@{$_SERVER['HTTP_HOST']}".PAGE_ADDRESS."&action=fancy_view_source&file={$this->Conf->Tmp_file}&internal_request=1";
         }
         else {
            $internal_url = "http://{$_SERVER['HTTP_HOST']}".PAGE_ADDRESS."&action=fancy_view_source&file={$this->Conf->Tmp_file}&internal_request=1";
         }
         if (! $code_array = @file($internal_url)) {
            $ret .= "<H2>An error occured</H2>
                  <P>If you are using password protection for Ide.php, please enter username and password in the 'Options' page.</P>";
         }
         else {
            $fancy_code_str = join("", $code_array);
            $fancy_code_array = split("<(br|BR)[[:space:]]*/*>", $fancy_code_str);
            if (sizeof($fancy_code_array)) {
               $row_num_width = strlen(sizeof($fancy_code_array));
               $ret .= ereg_replace("^<code>", "<code><FONT COLOR='{$this->Conf->Fancy_line_number_color}'>" . sprintf("%0{$row_num_width}d", 1) . "$row_num_spacer</FONT>", ereg_replace("[[:space:]]", "", $fancy_code_array[0]));
               for ($i=1;$i<sizeof($fancy_code_array);$i++) {
                  $row_num = sprintf ("%0{$row_num_width}d", $i+1);
                  $ret .= "\n<BR><FONT COLOR='{$this->Conf->Fancy_line_number_color}'>$row_num$row_num_spacer</FONT>" . trim($fancy_code_array[$i]);
               }
            }
         }
      }
      return ($ret);
   }
   function make_textarea_safe($code)
   {
      $safe_code = eregi_replace("</(TEXTAREA)>", "</ide\\1>", $code);
      if ($this->Conf->Protect_entities)
      $safe_code = eregi_replace("&", "&amp;", $safe_code);
      return $safe_code;
   }
   function js_open_code_window ($url)
   {
      $ret = "";
      $ret .= "<SCRIPT LANGUAGE='JavaScript'>\n";
      $ret .= "var eval_window = window.open('$url','Foo');\n";
      $ret .= "eval_window.focus();\n";
      $ret .= "</SCRIPT>\n";
      return $ret;
   }
   function options_page_save($var_names_array)
   {
      global $HTTP_POST_VARS;
      $this->Conf->Fancy_view_line_numbers = $HTTP_POST_VARS['Fancy_view_line_numbers'] ? 1 : 0;
      $this->Conf->Protect_entities = $HTTP_POST_VARS['Protect_entities'] ? 1 : 0;
      $this->Conf->Eval_executable = $HTTP_POST_VARS['Eval_executable'] ? 1 : 0;
      $this->Conf->Unix_newlines = $HTTP_POST_VARS['Unix_newlines'] ? 1 : 0;
      $this->Conf->save_to_file($var_names_array);
   }

}?>
