<?php
function mysqlconnect($onlyconn = false)
{
   global $db_host, $db_name, $db_user, $db_pass, $HTTP_SERVER_VARS, $onlyconn;
   if (!mysql_connect($db_host, $db_user, $db_pass))
   {
      switch (mysql_errno())
      {
         case 1040:
         case 2002:
            error("The server load is very high at the moment. Please press the Refresh button in your browser to retry.", "MySQL Error (".mysql_errno().")", __FILE__, __LINE__, false);
         default:
            error(mysql_error(), "MySQL Error (".mysql_errno().")", __FILE__, __LINE__, false, "mysql_connect({$db_host}, {$db_user}, password)");
      }
   }
   mysql_select_db($db_name) or error(mysql_error(), "MySQL Error (".mysql_errno().")", __FILE__, __LINE__, false, "mysql_select_db({$db_name})");
   sql_query("SET NAMES '".SQLNAMES."'", __FILE__, __LINE__);
   //sql_query("SET `time_zone` = \"CET\"", __FILE__, __LINE__);
   loadconfig();
   if (!$onlyconn)
   {
      full_cleanup();
      global $config;
   }
}
function sql_query($query, $file, $line, $prnt = false)
{
   global $queries, $SHOWSQLQUERIES;
   $queries++;
   if ($SHOWSQLQUERIES || $prnt)
   {
      global $querytime;
      $mtime = explode(" ", microtime());
      $mtime = $mtime[1] + $mtime[0];
      $querytime_before = $mtime;
      $a = mysql_query($query) or error(mysql_error(), "MySQL Error (".mysql_errno().")", $file, $line, true, $query);
      $mtime = explode(" ", microtime());
      $mtime = $mtime[1] + $mtime[0];
      $querytime += $mtime - $querytime_before;
      print ("<font color=green><b>{$query}</b></font> <i>(".($mtime - $querytime_before).")</i><br>");
   }
   else $a = mysql_query($query) or error(mysql_error(), "MySQL Error (".mysql_errno().")", $file, $line, true, $query);
   return $a;
}
function sql_array($query, $file, $line, $prnt = false)
{
   $res = sql_query($query, $file, $line, $prnt);
   $ret = array();
   while ($val = mysql_fetch_row($res))
   {
      array_push($ret, $val[0]);
   }
   return $ret;
}
function sql_data($query, $file, $line, $prnt = false, $assoc = true)
{
   $res = sql_query($query, $file, $line, $prnt);
   if ($assoc) return mysql_fetch_assoc($res);
   else return mysql_fetch_array($res);
}
function sql_get($query, $file, $line, $prnt = false)
{
   $ret = sql_data($query, $file, $line, $prnt, false);
   return $ret[0];
}
// Optimize the database's tables.
function OptimizeTables()
{
	global $mysql_database, $context;
	// Get a list of tables, as well as how many there are.
	$result = sql_query("SHOW TABLE STATUS FROM `{$mysql_database}`");
	$tables = array();
	if (!$result)
	{
		$result = sql_query("SHOW TABLES FROM `{$mysql_database}`");
		while ($table = mysql_fetch_row($result))
			$tables[] = array('table_name' => $table[0]);
		mysql_free_result($result);
	}
	else
	{
		$i = 0;
		while ($table = mysql_fetch_assoc($result))
			$tables[] = $table + array('table_name' => mysql_tablename($result, $i++));
		mysql_free_result($result);
	}
	// If there aren't any tables then I believe that would mean the world has exploded...
   $context['num_tables'] = count($tables);
	$context['optimized_tables'] = array();
	foreach ($tables as $table)
	{
		// Optimize the table!  We use backticks here because it might be a custom table.
		$result = sql_query("OPTIMIZE TABLE `$table[table_name]`");
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		if (!isset($row['Msg_text']) || strpos($row['Msg_text'], 'already') === false || !isset($table['Data_free']) || $table['Data_free'] != 0)
			$context['optimized_tables'][] = array(
				'name' => $table['table_name'],
				'data_freed' => isset($table['Data_free']) ? $table['Data_free'] / 1024 : '<i>??</i>',
			);
	}
	// Number of tables, etc....
	$context['num_tables_optimized'] = count($context['optimized_tables']);
}

// MySQL DUMP
// Dumps the database to a file.
function DumpDatabase($db_name, $struct, $data, $compress)
{
	global $db_prefix, $scripturl, $context, $modSettings, $crlf;
   minclass(UC_ADMIN);
	// You can't dump nothing!
	if (!$struct && !$data)
	{
	   $struct = true;
	   $data = true;
	}
	// Attempt to stop from dying...
	@set_time_limit(600);
	@ini_set('memory_limit', '128M');
	// Start saving the output... (don't do it otherwise for memory reasons.)
	if ($compress)
	{
		// Send faked headers so it will just save the compressed output as a gzip.
		header('Content-Type: application/x-gzip');
		header('Accept-Ranges: bytes');
		header('Content-Encoding: none');
		// Gecko browsers... don't like this. (Mozilla, Firefox, etc.)
		$extension = '.sql.gz';
	}
	else
	{
		// Tell the client to save this file, even though it's text.
		header('Content-Type: application/octetstream');
		header('Content-Encoding: none');
		// This time the extension should just be .sql.
		$extension = '.sql';
	}
	// This should turn off the session URL parser.
	$scripturl = '';
	// Send the proper headers to let them download this file.
	header('Content-Disposition: filename="' . $db_name . '-' . (empty($_GET['struct']) ? 'data' : (empty($_GET['data']) ? 'structure' : 'complete')) . '_' . str_replace(" ", "_", get_date_time()) . $extension . '"');
	header('Cache-Control: private');
	header('Connection: close');
	// This makes things simpler when using it so very very often.
	$crlf = "\r\n";
	// SQL Dump Header.
	echo
		'-- ----------------------------------------------------------', $crlf,
		'--', $crlf,
		'-- Database dump of tables in `', $db_name, '`', $crlf,
		'-- Time: ', get_date_time(true), $crlf,
		'--', $crlf,
		'-- ----------------------------------------------------------', $crlf,
		$crlf;
	// Get all tables in the database....
	$queryTables = sql_query("SHOW TABLES FROM $db_name");
	// Dump each table.
	while ($tableName = mysql_fetch_row($queryTables))
	{
	   //echo ("Table: $tableName$crlf");
		if (function_exists('apache_reset_timeout'))	apache_reset_timeout();
		// Are we dumping the structures?
		if ($struct)
		{
			echo
				$crlf,
				'--', $crlf,
				'-- Table structure for table `', $tableName[0], '`', $crlf,
				'--', $crlf,
				$crlf,
				'DROP TABLE IF EXISTS `', $tableName[0], '`;', $crlf,
				$crlf,
				getTableSQLData($tableName[0]), ';', $crlf;
		}
		// Are there any rows in this table?
		if ($data) $get_rows = getTableContent($tableName[0]);
		// No rows to get - skip it.
		if ($get_rows)
		{
		   echo
			   $crlf,
			   '--', $crlf,
			   '-- Dumping data in `', $tableName[0], '`', $crlf,
			   '--', $crlf,
			   $crlf,
			   $get_rows,
			   '-- --------------------------------------------------------', $crlf;
		}
	}
	mysql_free_result($queryTables);
	echo
		$crlf,
		'-- Done', $crlf;
	exit;
}

// Get the content (INSERTs) for a table.
function getTableContent($tableName)
{
	global $crlf;

	// Get everything from the table.
	$result = sql_query("
		SELECT /*!40001 SQL_NO_CACHE */ *
		FROM `$tableName`", false, false);

	// The number of rows, just for record keeping and breaking INSERTs up.
	$num_rows = @mysql_num_rows($result);
	$current_row = 0;

	if ($num_rows == 0)
		return '';

	$fields = array_keys(mysql_fetch_assoc($result));
	mysql_data_seek($result, 0);

	// Start it off with the basic INSERT INTO.
	$data = 'INSERT INTO `' . $tableName . '`' . $crlf . "\t(`" . implode('`, `', $fields) . '`)' . $crlf . 'VALUES ';

	// Loop through each row.
	while ($row = mysql_fetch_row($result))
	{
		$current_row++;

		// Get the fields in this row...
		$field_list = array();
		for ($j = 0; $j < mysql_num_fields($result); $j++)
		{
			// Try to figure out the type of each field. (NULL, number, or 'string'.)
			if (!isset($row[$j]))
				$field_list[] = 'NULL';
			elseif (is_numeric($row[$j]))
				$field_list[] = $row[$j];
			else
				$field_list[] = "'" . mysql_escape_string($row[$j]) . "'";
		}

		// 'Insert' the data.
		$data .= '(' . implode(', ', $field_list) . ')';

		// Start a new INSERT statement after every 250....
		if ($current_row > 249 && $current_row % 250 == 0)
			$data .= ';' . $crlf . 'INSERT INTO `' . $tableName . '`' . $crlf . "\t(`" . implode('`, `', $fields) . '`)' . $crlf . 'VALUES ';
		// All done!
		elseif ($current_row == $num_rows)
			$data .= ';' . $crlf;
		// Otherwise, go to the next line.
		else
			$data .= ',' . $crlf . "\t";
	}
	mysql_free_result($result);

	// Return an empty string if there were no rows.
	return $num_rows == 0 ? '' : $data;
}

// Get the schema (CREATE) for a table.
function getTableSQLData($tableName)
{
	global $crlf;

	// Start the create table...
	$schema_create = 'CREATE TABLE `' . $tableName . '` (' . $crlf;

	// Find all the fields.
	$result = sql_query("
		SHOW FIELDS
		FROM `$tableName`", false, false);
	while ($row = @mysql_fetch_assoc($result))
	{
		// Make the CREATE for this column.
		$schema_create .= '  ' . $row['Field'] . ' ' . $row['Type'] . ($row['Null'] != 'YES' ? ' NOT NULL' : '');

		// Add a default...?
		if (isset($row['Default']))
		{
			// Make a special case of auto-timestamp.
			if ($row['Default'] == 'CURRENT_TIMESTAMP')
				$schema_create .= ' /*!40102 NOT NULL default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP */';
			else
				$schema_create .= ' default ' . (is_numeric($row['Default']) ? $row['Default'] : "'" . mysql_escape_string($row['Default']) . "'");
		}

		// And now any extra information. (such as auto_increment.)
		$schema_create .= ($row['Extra'] != '' ? ' ' . $row['Extra'] : '') . ',' . $crlf;
	}
	@mysql_free_result($result);

	// Take off the last comma.
	$schema_create = substr($schema_create, 0, -strlen($crlf) - 1);

	// Find the keys.
	$result = sql_query("
		SHOW KEYS
		FROM `$tableName`", false, false);
	$indexes = array();
	while ($row = @mysql_fetch_assoc($result))
	{
		// IS this a primary key, unique index, or regular index?
		$row['Key_name'] = $row['Key_name'] == 'PRIMARY' ? 'PRIMARY KEY' : (empty($row['Non_unique']) ? 'UNIQUE ' : ($row['Comment'] == 'FULLTEXT' || (isset($row['Index_type']) && $row['Index_type'] == 'FULLTEXT') ? 'FULLTEXT ' : 'KEY ')) . $row['Key_name'];

		// Is this the first column in the index?
		if (empty($indexes[$row['Key_name']]))
			$indexes[$row['Key_name']] = array();

		// A sub part, like only indexing 15 characters of a varchar.
		if (!empty($row['Sub_part']))
			$indexes[$row['Key_name']][$row['Seq_in_index']] = $row['Column_name'] . '(' . $row['Sub_part'] . ')';
		else
			$indexes[$row['Key_name']][$row['Seq_in_index']] = $row['Column_name'];
	}
	@mysql_free_result($result);

	// Build the CREATEs for the keys.
	foreach ($indexes as $keyname => $columns)
	{
		// Ensure the columns are in proper order.
		ksort($columns);

		$schema_create .= ',' . $crlf . '  ' . $keyname . ' (' . implode($columns, ', ') . ')';
	}

	// Now just get the comment and type... (MyISAM, etc.)
	$result = sql_query("
		SHOW TABLE STATUS
		LIKE '" . strtr($tableName, array('_' => '\\_', '%' => '\\%')) . "'", false, false);
	$row = @mysql_fetch_assoc($result);
	@mysql_free_result($result);

	// Probably MyISAM.... and it might have a comment.
	$schema_create .= $crlf . ') TYPE=' . (isset($row['Type']) ? $row['Type'] : $row['Engine']) . ($row['Comment'] != '' ? ' COMMENT="' . $row['Comment'] . '"' : '');

	return $schema_create;
}
?>