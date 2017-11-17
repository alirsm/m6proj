<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$partitionname = "Alpha Health";
}
else {
	$partitionname = ! empty($_POST['partitionname']) ? $_POST['partitionname'] : NULL;
}

$log->i( "$self_filename -> (" . __LINE__ . "): partitionname={$partitionname}" );

$db = new Db();

$sql = "select * from M6User where partitionname = '$partitionname' and usertype = 'A'";
$log->i( "$self_filename -> (" . __LINE__ . "): sql={$sql}" );
$rows = $db -> select($sql);

print json_encode($rows);

return json_encode($rows);