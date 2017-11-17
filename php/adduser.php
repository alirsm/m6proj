<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$status = "A"; $email="john@abc.com"; $ext="105"; $partitionname="OcPartition: Alpha Health"; $usertype="A";
}
else {	
	$status = ! empty($_POST['status']) ? $_POST['status'] : NULL;
	//$fname = ! empty($_POST['fname']) ? $_POST['fname'] : NULL;
	//$lname = ! empty($_POST['lname']) ? $_POST['lname'] : NULL;
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	//$phone = ! empty($_POST['phone']) ? $_POST['phone'] : NULL;
	//$ext = ! empty($_POST['ext']) ? $_POST['ext'] : NULL;
	$partitionname = ! empty($_POST['partitionname']) ? $_POST['partitionname'] : NULL;
	$usertype = ! empty($_POST['usertype']) ? $_POST['usertype'] : NULL;
}


$log->i( "$self_filename -> (". __LINE__ ."): $status,$email,$partitionname,$usertype" );

date_default_timezone_set('US/Eastern');
$now = date("Y-m-d H:i:s");

$db = new Db();
$sql = "select email, ext, status, password from M6User where email='$email'";
$log->e( $sql );
$rows = $db -> select($sql);
$count = count($rows);

if ( $count == 1 ) {
	$password = $rows[0]['password'];
	$log->i( "$self_filename -> (" . __LINE__ . "): password={$password}" );
	/*
	$mystatus = $rows[0]['status'];
	if ( $mystatus == "A" ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already been approved." );
		$msg = "The email has already been approved.";
		$ret = -1;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
	*/
	
	/*
	else if ( $mystatus == "R" ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): A request has already submitted for email={$email}" );
		$msg = "A request has already submitted.";
		$ret = -2;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
	*/

	$sql = "update M6User set status='$status',partitionname='$partitionname',usertype='$usertype',register_time='$now' where email='$email'";
	$log->e( $sql );
	if ( $db -> query($sql) ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} usertype={$usertype} partition={$partitionname} status={$status} updated" );
		$ret = 1;
		$msg = "User status has been updated.";
	}
	else {
		$log->e( "$self_filename -> (" . __LINE__ . "): unable to update email={$email}" );
		$ret = -2;
		$msg = "Unable to update user status";
	}
}
else {
	$log->e( "$self_filename -> (" . __LINE__ . "): Email {$email} not exist." );
	$ret = -2;
	$msg = "Email {$email} not exist.";
}

print json_encode( array( 'ret' => $ret, 'password' => $password, 'msg' => $msg ) );


