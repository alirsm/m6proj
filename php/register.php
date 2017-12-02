<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");
require_once ("{$self_dir}sendmailControllerbyFunc.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$status = "J"; $email="test2@abc.com"; $partitionname="OcPartition: Alpha Health"; $usertype="A";
}
else {	
	$status = ! empty($_POST['status']) ? $_POST['status'] : NULL;
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	$partitionname = ! empty($_POST['partitionname']) ? $_POST['partitionname'] : NULL;
	$usertype = ! empty($_POST['usertype']) ? $_POST['usertype'] : NULL;
}

$log->i( "$self_filename -> (". __LINE__ ."): $status,$email,$partitionname,$usertype" );

date_default_timezone_set('US/Eastern');
$now = date("Y-m-d H:i:s");

$db = new Db();
//$sql = "select email, ext, status, password from M6User where email='$email'";
$sql = "select password, ext, status, usertype, fname, lname, phone from M6User where email='$email'";
$log->e( $sql );
$rows = $db -> select($sql);
$count = count($rows);

if ( $count == 1 ) {
	$fname = $rows[0]['fname'];
	$lname = $rows[0]['lname'];
	$phone = $rows[0]['phone'];
	$ext = $rows[0]['ext'];
	$password = $rows[0]['password'];
	$mystatus = $rows[0]['status'];
	
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

	if ( $status == "A" ) {
		$sql = "update M6User set status='$status',partitionname='$partitionname',usertype='$usertype',register_time='$now' where email='$email'";
		
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename (" . __LINE__ . "): user has been approved for email={$email} ext={$ext}" );
				
			$ret = sendmailControllerFunc ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$password,"RegisterApproval");
				
			if ( $ret == 1 ) {
				$log->i( "$self_filename (" . __LINE__ . "): An approval email has been sent." );
				$msg = "Registration has been approved. An approval email has been sent out.";
				$ret = 1;
			}
			else {
				$log->i( "$self_filename (" . __LINE__ . "): Unbale to send an approval email" );
				$msg = "Registration has been approved. Unbale to send out an approval email.";
				$ret = 0;
			}
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): sql query error: sql={$sql}" );
			$msg = "Error: Registration approval failed.";
			$ret = -1;
		}
	}
	
	else if ( $status == "J" ) {
		$sql = "update M6User set status='$status',partitionname='$partitionname',usertype='$usertype',register_time='$now' where email='$email'";
	
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename (" . __LINE__ . "): user has been rejected for email={$email} ext={$ext}" );
	
			$ret = sendmailControllerFunc ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$password,"RegisterReject");
	
			if ( $ret == 1 ) {
				$log->i( "$self_filename (" . __LINE__ . "): A rejection email has been sent." );
				$msg = "Registration has been rejected. A rejection email has been sent out.";
				$ret = 1;
			}
			else {
				$log->i( "$self_filename (" . __LINE__ . "): Unbale to send an approval email" );
				$msg = "Registration has been rejected. Unbale to send out a rejection email.";
				$ret = 0;
			}
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): sql query error: sql={$sql}" );
			$msg = "Error: Registration rejection failed.";
			$ret = -1;
		}
	}
	
	else {
		$log->w( "$self_filename (" . __LINE__ . "): unable to approve user for email={$email} ext={$ext} reset={$reset} - An invalid value has been submitted ({$newstatus})" );
		$msg = "Error: Invalid value submitted. Registration failed.";
		$ret = -1;
	}
	
}
else {
	$log->e( "$self_filename -> (" . __LINE__ . "): Email {$email} not exist., unablr to register user." );
	$msg = "Email {$email} does not exist.";
	$ret = 0;
}

print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );


