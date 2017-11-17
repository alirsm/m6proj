<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$name = "Alpha Health";
}
else {
	$name = $_POST['partition'];
}

$log->i("partition name = $name");
$db = new Db();

$sql = "select * from M6Partition where name = '$name';";
$rows = $db -> select($sql);

print json_encode($rows);

//return json_encode($rows);

return 1;


//foreach ($rows as $row)
//{
	//echo "<option value='" . $row['SrcPartition'] . "'>" . $row['SrcPartition'] . "</option>";
//	echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
//}