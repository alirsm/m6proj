<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$fname="Ali"; $lname="Soltani"; $email="test@abc.com"; $ext="";
}
else {	
	$fname = ! empty($_POST['fname']) ? $_POST['fname'] : NULL;
	$lname = ! empty($_POST['lname']) ? $_POST['lname'] : NULL;
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	$ext = ! empty($_POST['ext']) ? $_POST['ext'] : NULL;
}

$log->i( "$self_filename -> (". __LINE__ ."): $fname,$lname,$email,$ext" );

date_default_timezone_set('US/Eastern');
$now = date("Y-m-d H:i:s");

$db = new Db();
$sql = "select email, ext from M6User where email='$email'";
$log->i( $sql );
$rows = $db -> select($sql);
if ( $rows !== false) {
	$count = count($rows);
	$log->i( "$self_filename -> (" . __LINE__ . "): count={$count}" );
	
	if ( $count == 1 ) {

		$sql = "update M6User set reset='R' where email='$email'";
		
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename (" . __LINE__ . "): reset updated reset=R" );
			$msg = "Request for password reset has been sent.";
			$ret = 1;
				
			/*
			$ret = sendmailControllerFunc ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$hashPassword,"ResetApproval");
				
			if ( $ret == 1 ) {
				$log->i( "$self_filename (" . __LINE__ . "): An approval email has been sent." );
				$msg = "Password has been reset. An approval email has been sent.";
				$ret = 1;
			}
			else {
				$log->i( "$self_filename (" . __LINE__ . "): Unbale to send an approval email" );
				$msg = "Password has been reset. Unbale to send an approval email.";
				$ret = 0;
			}
			*/
			
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): sql query error: sql={$sql}" );
			$msg = "Error: Request for password reset failed.";
			$ret = -1;
		}
	}
	else {
		$log->w( "$self_filename (" . __LINE__ . "): email={$email} not exist" );
		$msg = "Email {$email} does not exist.";
		$ret = 0;
	}
	
}
else {
	$log->i( "$self_filename -> (" . __LINE__ . "): sql query error: sql={$sql}" );
	$msg = "Request for password reset failed.";
	$ret = -1;
}

print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
