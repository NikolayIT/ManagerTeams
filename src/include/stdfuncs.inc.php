<?php
// stdfuncs.inc.php
/*
 * Copyright (C) 2004-2007 Manuel K?gi, kaegi(at)gmx(dot)ch
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

global $VERSION;
$VERSION['lib'][__FILE__]['date']    = "2007-12-04";
$VERSION['lib'][__FILE__]['version'] = "2.1";
$VERSION['lib'][__FILE__]['author']  = "kaegi &co";
$VERSION['lib'][__FILE__]['description']  = "Standard-Funktionen";

// ****** Change-Log **********************************************************
// * 04.12.07 enhanced debug::get Andres Obrero
// * 04.09.06 phpValue2js: Zahlenwert 0 als '0' zur?ckgeben
// * 28.09.04 trans::hashExplode(...) erweitert (tylmann)
// **


class std {

	/**
	*	getMicroTime
	*
	*	@returns the actual tim in seconds
	*/

	function getMicroTime(){
		list($usec, $sec) = explode(" ",microtime());
		return ($usec + $sec);
	}

	/**
	*	getRequest
	*
	*	merges HTTP_GET, HTTP_POST and HTPP-FILE requests togehter so that you have
	*	afterwards an all in one array
	*
	*	@param array $post: The HTTP_POST request ($_POST)
	*	@param array $get: The HTTP-GET request ($_GET)
	*	@param array $files : The HTTP-FILE request, if a form uploaded Files ($_FILES)
	*
	*	@uses arrayfunc::recursiveMerge
	*
	*	@returns array, a recursively merged Array
	*/
	function getRequest($post, $get, $files) {

	// Setzt verschiedene $_REQUEST-Daten sinnvoll zusammen
		$request = array();
		$files = std::rearrangeFiles($files);
		//debug::show($files);
		$request = arrayfunc::recursiveMerge($files, $get);
		//debug::show($request);
		$request = arrayfunc::recursiveMerge($request, $post);
		//debug::show($request);
		return $request;
	}

	/**
	*	rearrangeFiles - PRIVATE
	*
	*	re-arrranges the HTTP-FILE-REQUEST array so that its
	*	organized like the POST and GET array afterwards
	*
	*	@param the raw FILE-Array
	*
	*	@returns a POST/GET-Like structured Array
	*/
	private function rearrangeFiles($files) {
	// Hilfsfunktion f?r std::getRequest() (rekursiv)
		$retArr = array();
		foreach($files as $key => $value) {
			if (arrayfunc::isOneDimensional($value)) {
				$retArr[$key] = $value;
			} else {
				$tmpArr = array();
				foreach($value as $httpKey => $subValue) {
					foreach($subValue as $k => $v) {
						$tmpArr[$k][$httpKey] = $v;
					}
				}
				$retArr[$key] = std::rearrangeFiles($tmpArr);
			}
		}
		return $retArr;
	}

	/**
	*	removeEmptyLines
	*
	*	removes Empty Lines from a Text
	*
	*	@param string $text, the Text to remove the empty Lines From
	*	@param string $newLineChar - OPTIONAL - the New-Line indicator, default is "\n"
	*
	*	@returns the text without blank lines
	*/
	function removeEmptyLines($text, $newLineChar="\n") {
		$lines = split($newLineChar, $text);
		foreach ($lines as $idx => $line) {
			if (trim($line) == "") unset($lines[$idx]);
		}
		return join($newLineChar, $lines);
	}
}

class debug {

	/**
	*	Will get a globally defined JS-Function
	*	-  change the name if it messes up with your own JavaScript functions
	*	-  ..or set it to the empty-String to disable value-toggling
	*/
	static $toggleFunctionName = "tns";
	static $colorScheme = array();
	static $colorSchemeInited = array();
	static $toggleScriptInited = false;

	/**
	*	initializes the colorScheme
	*	would be a Constructors task if the debug would be instantated, since in debug all methods are static,
	*	call this method before the first time you need color scheme
	*
	*	The meaning of the keys:
	*		- vc = ValueColor, the background of shown Values
	*		- akc = ArrayKeyColor, the background of array-Keys
	*		- okc = ObjectKeyColor, the background of shown "object keys" i.e. the names of public accessible object/class-Variables
	*		- tc = ValueColor, the background of the (optional) Title
	*		- gc = ValueColor, the color of the structuring-grid, i.e. the background of the table
	*/
	private function initColorScheme() {
		debug::$colorScheme[] = array('vc'=>"#d6f2ff", 'akc'=>"#aec4cf", 'okc'=>"#44809c", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#f2bb94", 'akc'=>"#cc9e7c", 'okc'=>"#a68065", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#faea37", 'akc'=>"#d4c62f", 'okc'=>"#ada226", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#4bf8d0", 'akc'=>"#3fd1af", 'okc'=>"#33ab8f", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#7a7a7a", 'akc'=>"#a1a1a1", 'okc'=>"#c7c7c7", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#0099cc", 'akc'=>"#009999", 'okc'=>"#00ff00", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#cfc", 'akc'=>"#cf6", 'okc'=>"#cf0", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#ffc", 'akc'=>"#ff6", 'okc'=>"#ff0", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
		debug::$colorScheme[] = array('vc'=>"#f96", 'akc'=>"#c66", 'okc'=>"#966", 'tc'=>'#CCCCCC', 'gc'=>'#AAAAFF');
	}

	/**
	*	Creates and returns the JavaScript-Snippet used to toggle values by klicking on the keys
	*	returns the code only once, i.e. the first time, print it to the standard output if you get the snippet
	*/
	private function getToggleScript() {
		$script = "";
		if (debug::$toggleScriptInited == false && debug::$toggleFunctionName != "") {
			$script = "<script>\n";
			$script.= "	function ".debug::$toggleFunctionName."(event) {\n";
			$script.= "		var evtSource;\n";
			$script.= "		if (window.event) evtSource = window.event.srcElement;\n";
			$script.= "		else evtSource = event.target;\n";
			$script.= "		while (evtSource.nextSibling == null) { evtSource = evtSource.parentNode;  }\n";
			$script.= "		var tNode = evtSource.nextSibling;\n";
			$script.= "		while (tNode.nodeType != 1) { tNode = tNode.nextSibling; }\n";
			$script.= '		tNode.style.display = (tNode.style.display != "none") ? "none" : "block";'."\n";
			$script.= "	}\n";
			$script.= "</script>\n";
			debug::$toggleScriptInited = true;
		}
		return $script;
	}

	/**
	*	Creates the style information for a color schema if needed
	*	i.e. this schema is used the first time, print it to the standard output if you need it
	*
	*	@param int $schema the index of the desired color schema
	*	@return style-code if needed
	*/
	private function setStylesForScheme($schema) {
		$style = "";
		if (count(debug::$colorScheme) == 0) debug::initColorScheme();
		if (! isset(debug::$colorSchemeInited[$schema]))  {
			$style = "<style>\n";
			$style.= "	div.debug_$schema .value {  background-color:".debug::$colorScheme[$schema]['vc'].";  }\n";
			$style.= "	div.debug_$schema .a_key {  background-color:".debug::$colorScheme[$schema]['akc'].";  }\n";
			$style.= "	div.debug_$schema .o_key {  background-color:".debug::$colorScheme[$schema]['okc'].";  }\n";
			$style.= "	div.debug_$schema .title {  background-color:".debug::$colorScheme[$schema]['tc'].";  }\n";
			$style.= "	div.debug_$schema .grid  {  font-family:arial; background-color:".debug::$colorScheme[$schema]['gc']."; vertical-align:top;  }\n";
			$style.= "</style>\n";

			debug::$colorSchemeInited[$schema] = true;
			//print($style);
		}
		return $style;

	}

	/** *************************
	 *	Craetes and returns a HTML-Code that shows nicely
	 *	the Structure and Value(s) of any PHP-Varible, the given Value can be from a simple Integer
	 *	to a complex object-structure. This function works recursively.
	 *
	 *	@param mixed $arr : the PHP-Varible to look in
	 *	@param string $start: a title for the created structure-table, if numeric passed as $height
	 *	@param int $height optional height of scrollzone div, 	will be interpreted as the color schema if $start is interpreted as height
	 * 	@param int $schema optional 0 - 9 colorscheme, 		will be ignored if $start is interpreted as height
	 *
	 *	@return a HTML-Code Snippet (e.g. to be Viewed in a Browser)
	 ** *************************
	 */
	function get($arr, $start=true, $height=false, $schema=0) {
		$str = "";
		$name = "";
		if (is_numeric($start)) {	// All Arguments "move" 1 to the left
			$colorindex = $height;
			$height = $start;
			$start = true;
		}
		if (is_string($start)) {	// Indicates that we are on "root"-Level
			$name = $start;
			$start = true;
		}

		if (is_array($arr) || is_object($arr))  {

			if ($start == true) {	// the "root" run
				$neededScriptCode = debug::getToggleScript();
				$neededStyleCode = debug::setStylesForScheme($schema);

				$styling = "";
				if (is_numeric($height)) $styling = "style='max-height:{$height}px; overflow:auto;'";
				$str = $neededScriptCode."<div class='debug_$schema' $styling>\n".$neededStyleCode;
			}

			$emptyWhat = "empty-array";
			$keyClass = 'a_key';
			if (is_object($arr)) {
				$keyClass = 'o_key';
				$emptyWhat = "empty-object";
			}
			if (debug::isOneDimensional($arr) && !$start) {
				if (count($arr) == 0) {
					$str.= "<span class='$keyClass'>$emptyWhat</span><br>\n";
				}
				foreach($arr as $key => $value) {
					$str.= "<span class='$keyClass'>".debug::decorateValue($key)."</span>\n";
					$str.= "<span class='value'>".debug::decorateValue($value)."</span>\n";
				}
			}
			else {
				$onClick = "";
				if (debug::$toggleFunctionName != "") $onClick = "onclick='".debug::$toggleFunctionName."(event)'";
				$str.= "<table class='grid'>\n";
				if ($name != "") {
					$str.= "<thead $onClick><tr><th colspan='2' class='title'>$name</th></tr></thead>\n";
				}
				$str.= "<tbody>\n";
				if (count($arr) == 0) {
					$str.= "   <tr><td colspan='2' class='$keyClass'>$emptyWhat</td></tr>\n";
				}
				foreach($arr as $key => $value) {
					$str.= "   <tr>\n";
					$str.= "      <td class='$keyClass' $onClick>".debug::decorateValue($key)."</td>\n";
					$str.= "      <td class='value'>".debug::get($value, false)."</td>\n";
					$str.= "   </tr>\n";
				}
				$str.= "</tbody></table>\n";
			}

			if ($start == true) {	// the top-Level run
				$str.= "</div>\n";
			}
		}
		else {		// the "leave"-run
			$str = debug::decorateValue($arr);
			if ($name != "") $str = "$name = $str<br>\n";
			flush();
		}
		return $str;
	}

	/**
	*	Checks if an array is one-dimensional, i.e. if no one of the values is an array or abject again
	*	The public version of this function is in arrayfunc, this here is just to use by debug::get
	*	To avoid that the basic methods debug::get and debug::show have dependencies of other classes
	*
	*	@param array $arr: the array to check
	*
	*	@return boolean if it is one-dimensional
	*/
	private function isOneDimensional($arr) {
		if (! is_array($arr) && ! is_object($arr)) return false;
		foreach ($arr as $val) {
			if (is_array($val) || is_object($val)) return false;
		}
		return true;
	}

	/**
	*	Same as debug::get, but prints the created HTML-Code directly to the Standard Output.
	*	NOTE: This is the one and only debuging Tool!!
	*
	*	@param mixed $arr : the PHP-Varible to look in
	*	@param string $start: a title for the created structure-table
	*/
	function show($arr, $title=false) {
		print(debug::get($arr, $title));
		flush();
	}

	static $messungen;
	static $lastTime;

	/**
	*	Starts a time measurement, deprecated, use starMessung and stopMessung instead.
	*
	*	@param string $name: The name for this time measurement.
	*		the measurement stops if this function is called again with another name.
	*		measurements with the same name will be summated.
	*
	*/
	function messung($name)	{

		$nowTime = std::getMicroTime();
		if (!isset(debug::$messungen[$name])) debug::$messungen[$name] = 0;
		if (isset(debug::$lastTime)) {
			debug::$messungen[$name]+= (($nowTime - debug::$lastTime)*1000);
		}
		debug::$lastTime = $nowTime;
	}

	/**
	*	Shows the result of the measurements
	*	Prints them HTML-Encoded to the Standard-Output (i.e. Browser)
	*
	*	@uses debug::show()
	*/
	function showTime() {
		debug::show(debug::$messungen, "Zeitmessungen");
	}

	/**
	*	Returns the results of the measurements, HTML-Encoded
	*
	*	@uses debug::get()
	*	@returns the HTML-Code
	*/
	function getTime() {
		return debug::get(debug::$messungen, "Zeitmessungen");
	}

	/**
	*	Shows the Stacktrace
	*
	*	@uses debug::show()
	*/
	function showTrace() {
		$stack = debug_backtrace();
		$niceStack = array();
		for ($n = 1; $n < count($stack); $n++) {
			$key = "{$stack[$n]['file']} ({$stack[$n]['line']})";
			$argv = array();
			foreach ($stack[$n]['args'] as $arg) {
				$argv[] = var_export($arg, true);
			}
			$arglist = join(', ', $argv);
			$niceStack[$key] = $stack[$n]['class'].$stack[$n]['type'].$stack[$n]['function']."(".$arglist.")";
		}
		debug::show($niceStack, "{$stack[0]['file']} ({$stack[0]['line']})");
	}

	static $laufzeit = array();
	static $laufzeitStack = array();
	static $stackName = array();

	/**
	*	Starts a new (time-)measurement with the given Name
	*	Measurements can be Started within other measurements
	*	Measurements with the same can be started and stopted multiple times, they will be counted ans summated.
	*	e.g. within once quicksort, 100 times "HD-read" and 40 times "HD-write"
	*	- inner Measurements MUST be stoped before the outer measurment stops!
	*	- all Measurements MUST be stopped before showing the Results!
	*
	*	@param string $name The Name for this measurement (e.g. "Quicksearch")
	*		the measurement stops stops if stopMessung is Called and the given name is identical
	*
	*/
	function startMessung($name) {
		if (count(debug::$laufzeitStack) == 0) {
			$prtLaufzeit = &debug::$laufzeit;
		}
		else {
			$prtLaufzeit = &debug::$laufzeitStack[count(debug::$laufzeitStack)-1];
		}
		if (!isset($prtLaufzeit['childs'][$name])) {
			$prtLaufzeit['childs'][$name] = array('count' => 0, 'time' => 0);
		}
		$prtLaufzeit['childs'][$name]['start'] = std::getMicroTime();
		debug::$laufzeitStack[count(debug::$laufzeitStack)] = &$prtLaufzeit['childs'][$name];
		debug::$stackName[count(debug::$stackName)] = $name;
	}

	/**
	*	stops the Measurrement with teh given Name
	*
	*	@param string $name the Name of the Measurement to stop
	*/
	function stopMessung($name) {
		$tiefe = 0;
		for ($n = count(debug::$laufzeitStack) - 1; $n >= 0; $n--) {
			if (debug::$stackName[$n] == $name) $tiefe = max($tiefe, $n);
		}

		$cnt = count(debug::$laufzeitStack);
		for ($n = $tiefe; $n < $cnt; $n++) {
			$aktLz = &debug::$laufzeitStack[$n];
			if ($n == $tiefe) {
				$aktLz['time']+= std::getMicroTime() - $aktLz['start'];
				$aktLz['count']++;
			}
			else {
				$aktLz['time']+= std::getMicroTime() - $aktLz['start'];
				$aktLz['count'] = "Error, Measurement ".debug::$stackName[$n]." not closed";
			}
			unset(debug::$laufzeitStack[$n]);
			unset(debug::$stackName[$n]);
		}

	}

	/**
	*	PRIVATE - This function works recursively.
	*
	*	Rearaanges the results from all Measurements so that they will get human-understandable
	*
	*	@param array $laufzeit, the raw-array produced by calling start- and stopMessung
	*
	*	@returns a humen-understandable Version
	*/
	private function createLaufzeitResult($laufzeit) {
		$result = "";
		if (isset($laufzeit['childs'])) {
			$childTime = 0;
			if (isset($laufzeit['time'])) $result['Calls'] = $laufzeit['count'];
			foreach($laufzeit['childs'] as $name => $child) {
				$result[$name] = debug::createLaufzeitResult($child);
				$childTime+= $child['time'];
			}
			if (isset($laufzeit['time'])) {
				$result['Difference'] = ($laufzeit['time'] - $childTime) * 1000;
				$result['TOTAL'] = $laufzeit['time'] * 1000;
			}

		}
		else {
			$result = array('Calls' => $laufzeit['count'], 'time' => $laufzeit['time'] * 1000);
		}
		return $result;
	}

	/**
	*	Showns the mesurements results (they from start- and stopMessung)
	*	prints HTML-Encoded Results directly to the Standard-Output
	*
	*	@uses debug::show()
	**/
	function showLaufzeit() {
		$lz_nice = debug::createLaufzeitResult(debug::$laufzeit);
		debug::show($lz_nice, "Time measurements in ms");
	}

	/**
	*	Returns the mesurements results HTML-Encoded(they from start- and stopMessung).
	*
	*	@uses debug::get()
	*	@return the HTML-Code
	**/
	function getLaufzeit() {
		$lz_nice = debug::createLaufzeitResult(debug::$laufzeit);
		// $str =  debug::get(debug::$laufzeit, "Zeitmessungen in s");
		return debug::get($lz_nice, "Time measurements in ms");
	}

	/**
	*	Prepares Values to be used in debug::show / debug::get used to indicate a values type
	*	- Strings will be
	*		- in double-qutes if they are empty (to see something)
	*		- Normal if not empty
	*		- < and > will be rplaced by "&lt;", "&gt;" to avoid tag-Interpretation by a Browser
	*	- booleans and all numbers will be bold.
	*	- the NULL-Value will be bold and italic
	*
	*	@param mixed $value: the Value to HMTL-Encode
	*	@returns the HTML-Encoded Value
	*/
	private function decorateValue($value) {
		if (is_string($value)) {
			if (trim($value) == "") $decValue = "\"$value\"";
			else $decValue = str_replace(array("<", ">"), array("&lt;", "&gt;"), $value);
		}
		else if (is_bool($value)) {
			if ($value) $decValue = "true";
			else $decValue = "false";
			$decValue = "<b>$decValue</b>";
		}
		else if (is_null($value)) {
			$decValue = "<b><i>null</i></b>";
		}
		else {
			$decValue = "<b>$value</b>";
		}
		return $decValue;
	}

}

class trans {

	static $jsSpecialChars = array("\\", '"',"\n", "\r");
	static $jsReplaceTo = array("\\\\", '\"', '\n', '\r');


	/**
	*	Creates a JavaScript-Code that initializes a JavaScript Variable that contains
	*	the value of the given PHP-Variable
	*
	*	The PHP-Variable can be fromn a simple integer to a complex object / array
	*
	*	@param string $jsVarName, the name the Varible should have in the JavaScript
	*	@param mixed $phpvar, the Value the JS-Variable should be initialized with
	*	@uses trans::phpValue2js()
	*
	*	@return string, the JavaScriptCode
	*/
	function php2js($jsVarName, $phpvar) {
		$jsCode = "var $jsVarName = ".trans::phpValue2js($phpvar).";\n";
		return $jsCode;
	}

	/**
	*	Converts a PHP-Value into a JSON-String
	*	This function works recursively
	*
	*	@param nixed $phpValue: the Value to transform into JSON
	*	@return string the JSON String
	*/
	function phpValue2js($phpValue) {
		$jsCode = false;
		if (is_long($phpValue)) $jsCode = $phpValue;
		if (is_float($phpValue)) $jsCode = $phpValue;
		if (is_integer($phpValue)) $jsCode = $phpValue;
		if (is_int($phpValue)) $jsCode = $phpValue;
		if (is_double($phpValue)) $jsCode = $phpValue;
		if (!$jsCode && $jsCode !== false) $jsCode = '0';

		if (is_bool($phpValue)) {
			if ($phpValue) $jsCode = "true";
			else $jsCode = "false";
		}
		if (is_string($phpValue)) $jsCode = '"'.str_replace(trans::$jsSpecialChars, trans::$jsReplaceTo, $phpValue).'"';
		if (is_null($phpValue)) $jsCode = "null";

		if (is_array($phpValue)) {
			$elems = array();
			$numeric = arrayfunc::is_numeric_array($phpValue);
			foreach($phpValue as $key => $seg){
				$part = array();
				if ($numeric == false) $part[] = '"'.str_replace(trans::$jsSpecialChars, trans::$jsReplaceTo, $key).'"';
				$part[] = trans::phpValue2js($seg);
				$elems[] = join(':', $part);
			}
			if ($numeric) $jsCode = "[". join(",", $elems) ."]";
			else $jsCode = "{". join(",", $elems) ."}";
		}
		if ($jsCode == "") $jsCode = '"Type not supported yet"';
		return $jsCode;

		/*

		   If the JSON-Extention is available this would (hopefully) run much faster!


		   $f = $r = array();
		   foreach(array_merge(range(0, 7), array(11), range(14, 31)) as $v) {
		       $f[] = chr($v);
		       $r[] = "\\u00".sprintf("%02x", $v);
		   }
		   return str_replace($f, $r, json_encode($phpValue));
		  */
	}
/*
	function jsEscape($val){
		return str_replace(trans::$jsSpecialChars, trans::$jsReplaceTo, $val);
	}
*/

	/**
	*	Creates a PHP-Code that initializes a PHP Variable that contains
	*	the value of the given PHP-Variable, this function works recursively.
	*
	*	The PHP-Variable can be fromn a simple integer to a complex object / array
	*
	*	@param string $phpVarName, the name the Varible should have
	*	@param mixed $phpvar, the Value the New-Variable should be initialized with
	*	@param string $firstDimSpacer - OPTIONAL - Just to butify the Output
	*
	*	@return string, the PHP-Code
	*/
	function php2phpCode($phpVarName, $phpvar, $firstDimSpacer="") {
		$phpCode = ""; $pre = '$';

		if (is_numeric($phpvar)) $phpCode = "$pre$phpVarName = $phpvar;\n";
		if (is_bool($phpvar)) {
			if ($phpvar) $phpCode = "$pre$phpVarName = true;\n";
			else $phpCode = "$pre$phpVarName = false;\n";
		}
		if (is_string($phpvar) && !is_numeric($phpvar)) $phpCode = "$pre$phpVarName = \"".trans::replaceChars($phpvar)."\";\n";
		if (is_null($phpvar)) $phpCode = "$pre$phpVarName = null;\n";

		if (is_array($phpvar)) {
			if (arrayfunc::isOneDimensional($phpvar)) {
				$phpCode = "$pre$phpVarName = array(";
				foreach ($phpvar as $key => $value) {
					if (! is_numeric($value)) $value = trans::replaceChars($value);
					if (is_int($key) && $key >= 0) $phpCode.= "$key => \"$value\", ";
					else $phpCode.= "\"$key\" => \"$value\", ";
				}
				if (count($phpvar) > 0) {
					$phpCode = substr($phpCode, 0, -2);
				}
				$phpCode.= ");\n";

			}
			else {
				$phpCode = "$pre$phpVarName = array();\n";
				foreach ($phpvar as $key => $value) {
					if (is_int($key) && $key >= 0) $name = $phpVarName."[".$key."]";
					else $name = $phpVarName."['".$key."']";
					$phpCode.= trans::php2phpCode($name, $value).$firstDimSpacer;
				}
			}
		}
		if ($phpCode == "") $phpCode = "$pre$phpVarName = \"Type $type not supported yet\";\n";
		return $phpCode;
	}

	/**
	*	replaces Charachters which would cause Problems in PHP-Code
	*
	*	@param string $str: the raw String
	*	@return string the escaped String
	*/
	function replaceChars($str){
		$patterns = array("\"", "\n", "\t", "\r", '$');
		$replacements = array("\\\"", "\\\n", "\\\t", "\\\r", "\\\\$");
		return str_replace($patterns, $replacements, $str);
	}

	/**
	*	converts a HTML-Enncoded String into Plaintext
	*	- all tags will be removed
	*   	- some special tabs will be replaced  by a newline char
	*	- the <td>-Tag will be replaced ba a tabulator Char
	*
	*	@param string $htmlstr the HTML-Encoded String
	*	@param string $newLineChar - OPTIONAL - the Character to be used as newline.Char, default: "\n"
	*	@param string $tabChar - OPTIONAL - the Character to be used as tabulator.Char, default: "\t"
	*
	*	@return the plaintext
	*/
	function html2plaintext($htmlstr, $newLineChar = "\n", $tabChar = "\t") {
		$DEF_TAGLIST_LINEBREAK = array("br", "h1", "h2", "h3", "h4", "h5", "h6", "p", "div", "tr");
  		$DEF_TAGLIST_TAB = array("td");

		$htmlstr = str_replace("\n", "", $htmlstr);
		$htmlstr = str_replace("\r", "", $htmlstr);

		$htmlstr = str_replace("<br>", $newLineChar, $htmlstr);

		foreach ($DEF_TAGLIST_LINEBREAK as $tag) {
			$htmlstr = str_replace("</$tag>", $newLineChar, $htmlstr);
			$htmlstr = str_replace("<$tag />", $newLineChar, $htmlstr);
			$htmlstr = str_replace("<$tag/>", $newLineChar, $htmlstr);
		}
		foreach ($DEF_TAGLIST_TAB as $tag) {
			$htmlstr = str_replace("</$tag>", $tabChar, $htmlstr);
			$htmlstr = str_replace("<$tag />", $tabChar, $htmlstr);
			$htmlstr = str_replace("<$tag/>", $tabChar, $htmlstr);
		}

		$str = trans::decodeHTML($htmlstr);
		return strip_tags($str);
	}

	/**
	*
	*	NOT USED ANYMORE - HOPEFULLY
	*
	*/
/*
	function conv($str, $what) {
		$what = strtoupper($what);
		switch ($what) {
			case 'IV':
				$str = str_replace("'", "&#039;", $str);
				$str = str_replace('"', "&quot", $str);
				break;
			case 'DBV':

				break;
			case 'URL':

				break;
			case 'JS':
				$str = addcslashes(addslashes($str), "\n,\r,\t");
				break;
			case 'SAMP':

				break;

		}
		return $str;
	}
*/

	/**
	*	Explodes a string into a Hashtable
	*
	*	@param string $delim1, the delemiter that seperates the entries
	*	@param string $delim2, the delimiter that seperates the key from the Value
	*	@param string $string the String to explode
	*
	*	Example : 	String: "width: 100px; height:50px"
	*			delim1: ";"
	*			delim2: ":"
	*		==> array('width' => '100px', 'height':'50px');
	*
	*	@return the created hashtable
	*/
	function hashExplode($delim1, $delim2, $string) {
		if ($string == "") return array();
		$arr = explode($delim1, $string);
		$hashy = array();
		foreach ($arr as $zuw) {
			$arr2 = explode($delim2, $zuw);
			if (count($arr2) == 1) $hashy[$arr2[0]] = $arr2[0]; // 040928 hinzugef?gt (tylmann)
			if (count($arr2) == 2) $hashy[$arr2[0]] = $arr2[1];
			if (count($arr2) > 2)  $hashy[array_shift($arr2)] = implode($delim2, $arr2); // 040928 hinzugef?gt (tylmann)
		}
		return $hashy;

	}

	/**
	*	Joins a Hashtable to a String
	*
	*	Just the opposite of trans::hashExplode
	*
	*	@param string $delim1, the delemiter that seperates the entries
	*	@param string $delim2, the delimiter that seperates the key from the Value
	*	@param string $hashy the Hashtable to join
	*
	*	@return the created string
	*/
	function hashJoin($delim1, $delim2, $hashy) {
		$arr = array();
		foreach ($hashy as $key => $val) {
			$arr[] = $key.$delim2.$val;
		}
		return join($delim1, $arr);
	}

	/**
	*	Like trans::hashJoin except the the Values will be quoted with the giver quote Charachter
	*
	*	Useful to build-up attribute-lists for HTML Tags
	*
	*	@param string $hashy the Hashtable to join
	*	@param string $delim, the delemiter that seperates the entries, typically ' '
	*	@param string $quote, The Charachter used to quote the Values, typically '"'
	*	@param string $equiv, the delimiter that seperates the key from the Value, typically '='
	*
	*	@return the created string
	*/
	function joinToAttrList($hashy, $delim, $quote, $equiv) {
		$arr = array();
		foreach ($hashy as $key => $val) {
			$val = htmlentities($val);
			$arr[] = $key.$equiv.$quote.$val.$quote;
		}
		return join($delim, $arr);
	}

	/**
	*	Creates a Mail-Header String from the given hashtable
	*
	*	@param string $header the Hashtable to use
	*
	*	a shortcut for trans::hashJoin("\r\n", ": ", $header);
	*
	*	@return the created string
	*/
	function mailHeaderFromHash($header) {
		$headers = "";
		foreach ($header as $arg => $value) {
			$headers.= "$arg: $value\r\n";
		}
		return $headers;
	}

	/**
	*	Builds up the quiry-part of an URL that represents the given data
	*	data can be from a simple integer up to a complax object / array
	*	This function works recursively
	*	If a complex array/object is given the keys will be the Variablenames in the query
	*
	*	NOTE: Due to an URL-Query must by a String this function is NOT type-safe
	*
	*	@param mixed $data, the data that should be contained in the Query
	*	@param string $prefix, the name of the Variable in the query, optional if the given Data is an array/object
	*
	*	@return the URL-Query, values will be URL-Encoded
	*/
	function http_build_query($data, $prefix="") {
		if (! is_array($data) && ! is_object($data)) {
			return $prefix."=".urlencode($data);
		}
		$cmds = array();
		foreach ($data as $key => $value) {
			if ($prefix != "") $cmds[] = trans::http_build_query($value, $prefix."[".$key."]");
			else $cmds[] = trans::http_build_query($value, $key);
		}
		foreach ($cmds as $idx => $cmd) {
			if ($cmd == "") unset($cmds[$idx]);		// avoid empty-arrays
		}
		return join("&", $cmds);
	}

	/**
	*	Builds up an array from an XML-Encoded String
	*
	*	NOTE: On error a HTML-formattted Error Message will be sent to the standard output.
	*		This is to simplify debugging, remove this in production releases or use ob_start() and ob_clean()
	*
	*	@param string $xml, the XML-String to interprete
	*	@return the built up array representing the XML-String, false if it fails
	*/
	function xml2array($xml) {
		$xp = xml_parser_create();
		xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, false);
		xml_parser_set_option($xp, XML_OPTION_SKIP_WHITE, true);
		xml_parse_into_struct($xp, trim($xml), $vals, $index);
		$err = xml_get_error_code($xp);

		$result = false;
		if ($err != 0) {
			$error['Code'] = $err;
			$error['Text'] = xml_error_string($err);
			$error['Zeile'] = xml_get_current_line_number($xp);
			$error['Spalte'] = xml_get_current_column_number($xp);
			$error['Byte'] = xml_get_current_byte_index($xp);
			debug::show($error, "XML-Fehler");
		}
		else {
			$data = array();
			$parentList = array(&$data);

			foreach($vals as $value) {
				$parent = &$parentList[count($parentList)-1];
				if ($value['value'] == null) $value['value'] = "";

				switch ($value['type']) {
					case 'open':
						$node = array('tag' => $value['tag'], 'attributes' => $value['attributes'], 'value' => $value['value'], 'childs' => array());
						$parent['childs'][] = $node;
						$parentList[count($parentList)] = &$parent['childs'][count($parent['childs']) - 1];
						break;

					case 'complete':
						$node = array('tag' => $value['tag'], 'attributes' => $value['attributes'], 'value' => $value['value'], 'childs' => array());
						$parent['childs'][] = $node;
						break;

					case 'close':
						unset($parentList[count($parentList) - 1]);
						break;

				}
			}
			$result = $data['childs'][0];
		}
		xml_parser_free($xp);
		return $result;
	}

	/**
	*	Decodes HTML-Entities (&gt;, &auml; &nbsp;, etc..)
	*
	*	@param string $string, the string to decode
	*	@return string, the String Where the HTML Entities are replaced by "normal" Charachters
	*/
	private function decodeHTML($string) {
		 $string = strtr($string, array_flip(get_html_translation_table(HTML_ENTITIES)));
		 $string = preg_replace("/&#([0-9]+);/me", "chr('\\1')", $string);
		 return $string;
	}


}

class arrayfunc {

	var $EQUAL = "=";
	var $LIKE = "LIKE";
	var $SMALLER = "<";
	var $GREATER = ">";

	/**
	*	Sorts a 2-Dimensional Array, the intention is to sort arrays like Tables in SQL (by "Column")
	*	So the $data should be an array of "rows" (represented as Hashtable where the Key will be the
	*	"column-name" and the Value the Velu od the actual Cell), the keys in each row should be the same
	*
	*	@param array $data, the data to sort, an array of rows
	*	@param mixed $criteria, the "columnname" to sort, can be comma-separated e.g. "date,time";
	*				can also be an array e.g. array('date', 'time');
	*	@param mixed $direction the sort Direction ASC or DESC, OPTIONAL, ASC is default
	*				can also be comma-seperated or array, MUST have the same "length" as $criteria
	*	@param boolean $holdIndizes - OPTIONAL, default: false, if the indizes of the "rows" should stay at their
	*				rows. This is useful if the row-indizes represent e.g.
	*					- the Primary keys of the according Database-Entries
	*					- Filenames
	*					- URL's, etc...
	*
	*
	*	@return array the sorted array
	*
	*	NOTE: 	- To test what happens with the different options use debug::show() before and after sorting
	*		- Runtime: O(n * ln(n))	if one criteria is given, O(m * (n * ln(n)) if m criterias given (worst case!!)
	*/
	function tableSort($data, $criteria, $direction="ASC", $holdIndizes=false) {
		debug::startMessung("tableSort");
		$sortArr = array();
		$retArr = array();
		if (!is_array($criteria)) $criteria = explode(",", $criteria);
		if (!is_array($direction)) $direction = explode(",", $direction);

		$thisCrit = $criteria[0];
		$thisDir = $direction[0];
		unset ($criteria[0]);
		unset ($direction[0]);

		foreach ($data as $idx => $row) {
			$sortArr[$row[$thisCrit]][$idx] = $row;
		}

		if (count($criteria) > 0) {
			if (count($direction) == 0) $direction = array();
			foreach ($sortArr as $critValue => $matches) {
				$sortArr[$critValue] = arrayfunc::tableSort($matches, join(",",$criteria), join(",",$direction), $holdIndizes);
			}
		}

		if (strtolower($thisDir) == "desc") krsort($sortArr);
		else ksort($sortArr);

		foreach ($sortArr as $key => $subArr) {
			foreach ($subArr as $idx => $row) {
				if ($holdIndizes) $retArr[$idx] = $row;
				else $retArr[] = $row;
			}
		}
		debug::stopMessung("tableSort");
		return $retArr;
	}

		/*------------	 HOWTO  -------------------------

		$data : 2 Dimensionales Array, keys des 2. Dimension kommen als Kriterium in Frage
		$criteria  : String oder 1-Dimsionales Array von kriterien, meherere Krinerien k?nnen auch
				komma-getrennt als String ?bergeben werden

		--------------------------------------------------*/

	/**
	*	Groups a 2-Dimensional Array. Like in tableSort the imagination is that we Group a Table
	*	The data must be an Arrray of "rows", row must be Hashtables (colname => cellValue)
	*
	*	This function groups all rows toghether where the "cellValue" of the given "column" is the same
	*	One can also say seperates the given Array n Parts where n is the numer of different entries in the
	*	given "column"
	*
	*	@param array $data, the data to Group
	*	@param mixed $criteria, the Column to use for the seperation, can also be a comma-seperated string or
	*				an array
	*	@param boolean $holdIndizes - OPTIONAL, default: false, if the indizes of the "rows" should stay at their
	*				rows. This is useful if the row-indizes represent e.g.
	*					- the Primary keys of the according Database-Entries
	*					- Filenames
	*					- URL's, etc...
	*	@return array the grouped data
	*
	*	NOTE: 	- To test what happens with the different options use debug::show() before and after grouping
	*		- Runtime: O(n)	if one criteria is given O(m * n) if m criterias given
	*/
	function tableGroup($data, $criteria, $holdIndizes=false) {
		debug::startMessung("tableGroup");
		$groupedArr = array();
		if (!is_array($criteria)) $criteria = explode(",", $criteria);

		$thisCrit = trim($criteria[0]);
		unset ($criteria[0]);

		foreach ($data as $idx => $row) {
			if ($holdIndizes) $groupedArr[$row[$thisCrit]][$idx] = $row;
			else $groupedArr[$row[$thisCrit]][] = $row;
		}

		if (count($criteria) > 0) {
			foreach ($groupedArr as $critValue => $matches) {
				$groupedArr[$critValue] = arrayfunc::tableGroup($matches, join(",",$criteria));
			}
		}

		debug::stopMessung("tableGroup");
		return $groupedArr;
	}

	/**
	*	A Bit like tableGroup, but assumes that the values in the grouping-Column(s) are unique
	*	=> so not an Array of rows will be contained in each section of the grouped array but only
	*	that single row where the grouping-Column has this value!
	*
	*	@param array $data, the data to Group
	*	@param mixed $criteria, the Column to use for the seperation, can also be a comma-seperated string or
	*				an array
	*	$param string $field - OPTIONAL -  if you are not intrested in the whole Row in each section but only one
	*				"cell-Value" u can specify from which column this value should be taken.
	*
	*	@return array the grouped data
	*
	*	NOTE: 	- To test what happens with the different options use debug::show() before and after grouping
	*		- Runtime: O(n)	if one criteria is given O(m * n) if m criterias given
	*		- YOU as user of this function are responsible that the values in the grouping "Column" are unique
	*			!! IF NOT YOU WILL LOOSE DATA WITHOUT WARNING !!
	*/
	function uniqueTableGroup($data, $criteria, $field=false) {
		debug::startMessung("uniqueTableGroup");
		$groupedArr = array();
		if (!is_array($criteria)) $criteria = explode(",", $criteria);

		$thisCrit = trim($criteria[0]);

		foreach ($data as $idx => $row) {
			if (count($criteria) == 1) {
				if ($field == false) $groupedArr[$row[$thisCrit]] = $row;
				else $groupedArr[$row[$thisCrit]] = $row[$field];
			}
			else {
				$groupedArr[$row[$thisCrit]][] = $row;
			}
		}

		unset ($criteria[0]);
		if (count($criteria) > 0) {
			foreach ($groupedArr as $critValue => $matches) {
				$groupedArr[$critValue] = arrayfunc::uniqueTableGroup($matches, join(",",$criteria), $field);
			}
		}

		debug::stopMessung("uniqueTableGroup");
		return $groupedArr;
	}

	/**
	*	Used for an SQL-Like Descriptive search for "records"
	*	Like at the other tableXXX functions the imagination is to have an Array
	*	of Hashtables representing the rows of a table. The hashkeys will specify the column.
	*
	*	Runs through the given dataArray and returns all rows in a new array which macht
	*	with the given filter. A Filter must at leadt consit out of an "column"-Name (the hashkey
	*	of the "Rows" to use) an operator and a value. More than one filter can be given.
	*
	*	@param array $data, tha data to filter.
	*	@param mixed filter the Filter tu use.
	*
	*	Filterformats:
	*	- 1. as String : 1a [Column] [operator] [value]: "name = Hans", the spaces must be set!
	*		or	 1b [Column] [operator] [value] AND [Column] [operator] [value]: "name = Hans AND firstname = Andi"
	*				- Only "AND" is possible
	*	- 2. As Array : 2a: array(Column, operator, value) : array('name', '=', 'Hans');
	*			2b: an array of Strings formatted as shown in 1a: array("name = Hans", "firstname = Andi");
	*			2c: an array of arrays formatted as shown in 2a
	*	Possible operators: "=", "LIKE", "<", ">"
	*
	*	@return the filtered rows
	*/
	function tableFilter($data, $filter) {
		$operatorOrder = array("=", "LIKE", "<", ">");
		$filter = arrayfunc::parseFilter($filter, $operatorOrder);

		$filters = arrayfunc::tableGroup($filter, 1);
		foreach ($operatorOrder as $operator) {
			if (isset($filters[$operator])) {
				foreach ($filters[$operator] as $actualFilter) {
					$data = arrayfunc::primitiveFilter($data, $actualFilter[0], $actualFilter[1], $actualFilter[2]);
				}
			}
		}
		return $data;
	}

	/**
	*	Summates all values of a particular column
	*
	*	@param array $data: an Array of "tableRows", tablerows as a Hashtable colName => value
	*	@param strign $field: the column to summate
	*
	*	@return number, the sum of the spec. column,
	*
	*	@example : arrayfunc::tableSum($employees, "salary");
	*/
	function tableSum($data, $field) {
		$sum = 0;
		foreach($data as $row)  {
			$sum+= $row[$field];
		}
		return $sum;
	}

	/**
	*	Checks if an array is a Hashtable (i.e. it is NOT a numeric array)
	*
	*	@param array $var: the array to check
	*
	*	@return boolean if it is associative
	*/
	function is_assoc_array($var) {
		if (! is_array($var)) return false;
		$n = 0;
		foreach ($var as $key => $value) {
			if ($key != $n || !is_int($key)) return true;
			$n++;
		}
		return false;
	}

	/**
	*	Checks if an array is numeric, i.e. if all keys are integer and
	*	and countin up form 0 to the (length - 1) of the array.
	*
	*	@param array $var: the array to check
	*
	*	@return boolean if it is numeric
	*/
	function is_numeric_array($var) {
		if (! is_array($var)) return false;
		$n = 0;
		foreach ($var as $key => $value) {
			if (! ($key === $n)) return false;
			$n++;
		}
		return true;
	}

	/**
	*	Checks if an array is one-dimensional, i.e. if no one of the values is an array or abject again
	*
	*	@param array $arr: the array to check
	*
	*	@return boolean if it is one-dimensional
	*/
	function isOneDimensional($arr) {
		if (! is_array($arr) && ! is_object($arr)) return false;
		foreach ($arr as $val) {
			if (is_array($val) || is_object($val)) return false;
		}
		return true;
	}

	/**
	*	Parses a filter given to the tableFilter function into an 2-dimensional array that represents
	*	the filter-format 2c described in tableFilter
	*
	*	@param mixed $filter: the filter given to the tableFilter funciton (cam be any Variant, from 1a to 2c)
	*	@param array $operators the known operators
	*
	*	@return the 2c-like fomratted filter array
	*
	*	@see arrayfunc::tableFilter
	*/
	private function parseFilter($filter, $operators) {
		if (is_string($filter)) {
			$filter = explode(" AND ", $filter);
		}
		if (count($filter) == 3 && in_array($filter[1], $operators)) {
			$newFilter = array($filter);
			$filter = $newFilter;
		}
		else {
			foreach($filter as $idx => $fil) {
				if (is_string($fil)) $filter[$idx] = explode(" ", $fil);
			}
		}

		return $filter;
	}

	/**
	*	Filters an array of Hashtbales
	*
	*	@param array $data the data to filter
	*	@param string $field, specifies the hash-key to compare
	*	@param string $operator, specifies the comparison operator to use: one of : "=", "<", ">", "LIKE"
	*	@param string $value, the value the the compared value should have (or should beeing greater..)
	*
	*	@return array an array of these hashtables whith matched
	*
	*	@see arrayfunc::tableFilter, this function is internally user by tableFilter
	*/
	private function primitiveFilter($data, $field, $operator, $value) {
		// show("PrimitiveFiltering: $field, $operator, $value<br>\n");
		$result = array();
		foreach ($data as $idx => $row) {
			if (arrayfunc::compare($row[$field], $operator, $value)) {
				// print("Vergleich {$row[$field]}, $operator, $value, OK<br>\n");
				$result[] = $row;
			}
			else {
				// print("Vergleich {$row[$field]}, $operator, $value, FEHLGESHLAGEN<br>\n");
			}
		}
		return $result;
	}

	/**
	*	Compares 2 Values
	*
	*	@param string $LHS the Left-Hand-Side Value
	*	@param string $operator, specifies the comparison operator to use: one of : "=", "<", ">", "LIKE"
	*	@param string $RHS the Right-Hand-Side Value
	*
	*	@return boolean if the LHS and RHS match (occording to the given operator)
	*/
	private function compare($LHS, $operator, $RHS) {
		if ($operator == '=') return ($LHS == $RHS);
		if ($operator == '<') return ($LHS < $RHS);
		if ($operator == '>') return ($LHS > $RHS);
		if ($operator == 'LIKE') return arrayfunc::likeCompare($LHS, $RHS);
		return false;
	}

	/**
	*	Performs a Database LIKE-Compare, Case-Insesitive
	*	'%' as wild-card is understood, '_' as char for exectly one Character NOT
	*
	*	@param string $value the value to check
	*	@param string $pattern the "target"-Value that my contain the wildcards
	*
	*	@return boolean, if the value mathces the given pattern
	*/
	private function likeCompare($value, $pattern) {
		$pattern = strtolower($pattern);		// Damit das ganze Case-INsensitive  l?uft
		$value = strtolower($value);

		$pttrn = explode("%", $pattern);
		$lastIdx = count($pttrn)-1;

		if ($lastIdx == 0) return ($pattern == $value);

		$failed = false;
		foreach ($pttrn as $idx => $str) {
			if ($str != "" && $failed === false)  {
				$pos = strpos($value, $str);
				if ($pos === false) $failed = true;				// str gar nicht vorhanden => RAUS
				if ($idx == 0 && $pos > 0) $failed = true;			// anfang, pos muss = 0 sein sonst => RAUS
				if ($idx == $lastIdx)	{
					$pos = strrpos($value, $str);
					if ($pos != strlen($value) - strlen($str)) $failed = true; // letzes, pos muss am ende sein, sonst => RAUS
				}
				if ($failed === false) {
					$value = substr($value, $pos + strlen($str));		// Alles vor und mit dem gefundenen Wert abhacken.
				}
			}
		}
		return ($failed === false);
	}

	/**
	*	Uses an array of arrays, retrieves maximum length of the arrays contained in the give array
	*
	*	@param array $arr an array of arrays;
	*
	*	@return int the maximum length;
	*/
	function getMax2ndDimLength($arr) {
		$max = 0;
		foreach ($arr as $key => $value) {
			if (is_array($value)) $max = max($max, count($value));
		}
		return $max;
	}

	/**
	*	Recursively merges two arrays
	*	If two keys are identical the second one will be choosen
	*	If two identical keys are integer both will be taken
	*
	*	@param array $arr1, the first array
	*	@param array $arr2, the second array
	*
	*	@return array the merged array
	*/
	function recursiveMerge($arr1, $arr2) {
		$result = array();
		if (is_array($arr1) && is_array($arr2)) {
			$result = $arr1;
			foreach ($arr2 as $key => $value) {
				if (isset($result[$key])) {
					if (is_integer($key) && ! (is_array($result[$key]) && (is_array($arr2[$key])))) $result[] = $value;
					else $result[$key] = arrayfunc::recursiveMerge($result[$key], $arr2[$key]);
				}
				else {
					$result[$key] = $value;
				}
			}
		}
		else {
			$result = $arr2;
		}
		return $result;
	}

	/**
	*	Generates a list of all values of a given "column" in a array of Hashtables
	*
	*	@param array $data, the array of ahstables to use
	*	@param string $fileName, the hashkey of the value to be taken into the list
	*	@param boolean $holdIndizes - OPTIONAL - if the indizes in the list should be tahe same as in the dataset
	*			default: false => the list will be a numeric array from 0 to length-1
	*				 true => use this option if the indizes of the array represtent e.g. database Primary keys
	*
	*	@return array the newly generated list
	*/
	function getListOfField($data, $fieldName, $holdIndizes=false) {
		$liste = array();
		foreach ($data as $idx => $row) {
			if ($holdIndizes) $liste[$idx] = $row[$fieldName];
			else $liste[] = $row[$fieldName];
		}
		return $liste;

	}


}
class filesys {
//-------------------------------
//$regexp = "/[0-9]{1,}_[0-9]{1,}_[0-9]{4,}/";
//$regexp = "/.jpg/";

	static $loadedConstructParts = array();
	static $loadedConstructFiles = array();

	/**
	*	Reads a part of a construct file
	*
	*	@param string $file, the filename to read
	*	@param string $part, the part to read
	*
	*	@return string the string found in the given part
	*/
	function readConstructFile($file, $part, $useIncluePath = 0) {
		if ($useIncluePath == true) $useIncluePath = 1;

		if (isset(filesys::$loadedConstructParts[$file][$part])) {
			$construct = filesys::$loadedConstructParts[$file][$part];
		}
		else {
			$construct = "";
			if (isset(filesys::$loadedConstructFiles[$file])) {
				$raw = filesys::$loadedConstructFiles[$file];
			}
			else {
				$raw = file_get_contents($file, $useIncluePath);
				filesys::$loadedConstructFiles[$file] = $raw;
			}

			if ($part != "") {
				$beginner = "<!-- BEGIN $part -->";
				$ender = "<!-- END $part -->";
				$begin = strpos($raw, $beginner);
				$end = strpos($raw, $ender);
				if (! ($begin === false) && ! ($end === false)) {
					$begin = $begin + strlen($beginner);
					$construct = substr($raw, $begin, $end - $begin);
					$construct = trim($construct);
				}
				else	{
					print("{$part} NOT FOUND in \"{$file}\"<br>\n");
				}
			}
			else {
				$construct = $raw;
			}
			filesys::$loadedConstructParts[$file][$part] = $construct;
		}
		return $construct;
	}

	/**
	*	Finds all Filenames in a directory matching the given regular expression
	*
	*	@param string $dir, the Directory to search
	*	@param string $regexp, the reqular expression to use
	*
	*	@return array, all fileNames found and matching the $regexp
	*/
	function findMatchFiles($dir, $regexp){
		$result_array = array();

		$dir = dir($dir);
		if ($regexp != null) {
			while ($file = $dir->read()) {
				if (preg_match($regexp, $file)) {
					$result_array[] = $file;
				}
			}
		}
		$dir->close();
		return $result_array;
	}

	/**
	*	returns all directories contained in the given directory
	*
	*	@param string $odir, the Directory to search
	*
	*	@return an array of all dirs found
	*/
	function findDirs($odir){
		print("odir=".$odir);
		$result_array = array();
		$dir = dir($odir);
		while ($file = $dir->read()) {
			if ($file != "." && $file != "..") {
				if (is_dir($odir.$file)) {
					$result_array[] = $file;
				}
			}
		}
		$dir->close();
		return $result_array;
	}

	/**
	*	shrinks a path down to the really needed path
	*	"/var/www/../php" will get "/var/php";
	*
	*	@param string $path, the path to shrink
	*
	*	@param the shrinked path
	*/
	function shrinkPath($path) {
		$hasEndSlash = false;
		if (substr($path, -1) == '/') $hasEndSlash = true;
		do {
			$changesDone = false;
			$folders = explode("/", $path);

			foreach ($folders as $idx => $folder) {
				if (($idx > 0 && $folder == "") || $folder == ".") {
					unset($folders[$idx]);
					$changesDone = true;
				}
				if ($folder == ".." && isset($folders[$idx-1]) && $folders[$idx-1] != "..") {
					unset($folders[$idx]);
					unset($folders[$idx-1]);
					$changesDone = true;
				}
			}
			$path = join("/", $folders);
		} while ($changesDone);

		if ($hasEndSlash) $path.= '/';
		return $path;
	}

	/**
	*	checks if the give directory exists
	*	tries to create it if not
	*
	*	@param string $dir, the directory to check
	*/
	function checkDir($dir) {
		if(is_dir($dir)) return $dir;
		exec("mkdir -p -m 01777 \"$dir\"", $r, $s);
	}

	/**
	*	Checks if a file is php-includable (i.e. it can be found in one of the include-paths)
	*
	*	@param string file, the file to check (inclusive path if it should be in a subfolder of an include-path)
	*	@return boolean if the file could be included
	*/
	function includable($file) {
		$paths = explode(":", ini_get("include_path"));
		foreach($paths as $val)
			if(file_exists($val . "/" . $file)) return true;
		return false;
	}

	/**
	*	Checks if in a directory a fil can be created and written
	*
	*	@param string $dir, the directory to check
	*	@return boolean if a file could be created and opend for writing
	*/
	function checkRights($dir){
		@$fp = fopen($dir."dummyXfileForWriteTest.txt", "w+");
		if($fp === false){
			return false;
		}else{
			fwrite($fp, "writeEnabled");
			fclose($fp);
			if(is_array(file($dir."dummyXfileForWriteTest.txt")) ){
				unlink($dir."dummyXfileForWriteTest.txt");
				return $dir;
			}
		}
	}

}

class datefunc {

	/**
	*	Adds some months and some days to a SQL-formatted Date (YYYY-MM-DD)
	*
	*	@param string $date, the date to start from
	*	@param string $months, how many months to add, migtht be negative
	*	@param string $days, how many days to add, migtht be negative
	*	@return string the new date (SQL-Formatted)
	*/
	function addDate($date, $months, $days) {
		$d1 = datefunc::addDays($date, $days);
		return datefunc::addMonths($d1, $months);
	}

	/**
	*	Adds some days to a SQL-formatted Date (YYYY-MM-DD)
	*
	*	@param string $date, the date to start from
	*	@param string $days, how many days to add, migtht be negative
	*	@return string the new date (SQL-Formatted)
	*/
	function addDays($date, $days) {
		$utc = strtotime($date);
		$utc+= (24 * 3600) * $days;
		return date("Y-m-d", $utc);
	}

	/**
	*	Adds some months to a SQL-formatted Date (YYYY-MM-DD)
	*
	*	@param string $date, the date to start from
	*	@param string $months, how many months to add, migtht be negative
	*	@return string the new date (SQL-Formatted)
	*/
	function addMonths($date, $months) {
		$d = explode("-", $date);
		$d[1] = $d[1] + $months;
		$years = floor(($d[1]-1) / 12);
		$d[0]+= $years;
		$d[1]-= $years * 12;
		if (strlen($d[1]) == 1) $d[1] = "0".$d[1];
		return join("-", $d);
	}

	/**
	*	Transformes a SQL-Date into a european-style formatted Date (DD.MM.YYYY)
	*
	*	@param string $date, the date to start from
	*	@return the european-style formatted date
	*/
	function sqlDate2euroDate($sqlDate) {
		$dt = explode('-', $sqlDate);
		return $dt[2].".".$dt[1].".".$dt[0];
	}
}

/*********************************************************************************************************

	END OF DOCUMENTATION
	FOLLOWING FUNCTIONS SHOULD NOT BE PART OF stdfuncs.inc.php
	DO NOT USE THEM, DEPRECATED.

**********************************************************************************************************/

class cookie {
	/* Sends a cookie to the client.
	 * @param	$method	int	Use one of several methods to set a cookie:
	 *                              1 - cookie with a preset timeout ($default_cookies_timeout)
	 *                              2 - session cookie (lifetime = 0)
	 */
	function send_cookie($key, $val, $dauer=null ) {

		$default_cookies_timeout = time() + 60 * 60 * 24 * 7 * 52; //ein jahr g?ltig

		if($dauer === null)
			$dauer = $default_cookies_timeout;
		$path = '/';
		setcookie($key, $val, $dauer, $path);
	}

	function destroy_cookie($key) {
		$path = '/';
		setcookie($key, '', time() - 2592000, $path);
	}
}

class ziplib {

	function extract($sourceFile, $targetDir) {
		filesys::checkDir($targetDir);
		exec("rm {$targetDir}* -r; cd {$targetDir}; unzip {$sourceFile}");
	}

	function compress($sourceDir, $targetFile) {
//		debug::show($sourceDir, $targetFile);
		$cmd = "cd {$sourceDir}; zip -r {$targetFile} *";
//		debug::show($cmd);
		exec($cmd, $output, $retVal);
//		debug::show($output, $retVal);
	}
}

?>