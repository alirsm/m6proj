<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$email="ali@abc.com";
}
else {	
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	//$ext = ! empty($_POST['ext']) ? $_POST['ext'] : NULL;
}

$log->i( "$self_filename -> (". __LINE__ ."): $email" );

$db = new Db();
$sql = "select ext,fname,lname,phone,status,usertype,partitionname,password from M6User where email='$email'";
$rows = $db -> select($sql);
$count = count($rows);
if ( $count != 1 ) {
	$log->i( "$self_filename -> (" . __LINE__ . "): Email {$email} not exists" );
	$ret = -1;
	$msg = "Email {$email} not exist.";
	print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
}
else {
	$ret = 1;
	$log->i( "$self_filename -> (" . __LINE__ . "): record found" );
	echo json_encode(array("data" => $rows[0], "ret" => $ret));
}


