<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$db = new Db();

$rows = $db -> select("select distinct state from LERG3 where country='USA' order by state;");
foreach ($rows as $row)
{
	echo "<option value='" . $row['state'] . "'>" . $row['state'] . "</option>";
}

?>