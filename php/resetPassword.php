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
	$email="test@abc.com";
	$ext = "1234";
	$reset = "Y";
}
else {
	$email = ! empty($_REQUEST['email']) ? $_REQUEST['email'] : NULL;
	$ext = ! empty($_REQUEST['ext']) ? $_REQUEST['ext'] : NULL;
	$reset = ! empty($_REQUEST['reset']) ? $_REQUEST['reset'] : NULL;
	
	$cipher = 'AES-256-CBC';
	$key = 'ABADAN is the best';
	$iv = "ABADANBAMAN1343!";
	
	//$email = openssl_decrypt($val1, $cipher, $key, 0, $iv);
	//$ext = openssl_decrypt($val2, $cipher, $key, 0, $iv);
	//$newstatus = openssl_decrypt($val3, $cipher, $key, 0, $iv);
}

$log->i( "$self_filename (". __LINE__ ."): $email $ext $reset" );

date_default_timezone_set('US/Eastern');
$now = date("Y-m-d H:i:s");

$db = new Db();
//$sql = "select fname,lname,phone,partitionname,usertype,status from M6User where email='$email' and ext='$ext'";
$sql = "select fname,lname,phone,partitionname,usertype,status from M6User where email='$email'";

$log->i( $sql );
$rows = $db -> select($sql);
$count = count($rows);

if ( $count == 1 ) {
	$fname = $rows[0]['fname'];
	$lname = $rows[0]['lname'];
	$phone = $rows[0]['phone'];
	$partitionname = $rows[0]['partitionname'];
	$usertype = $rows[0]['usertype'];
	$status = $rows[0]['status'];
	
	if ( $reset == "Y" ) {
		$password = randomPassword();
		//$password = "abc125";
		//$hashPassword = password_hash($password, PASSWORD_BCRYPT);
		$hashPassword = $password;
		//$sql = "update M6User set password='$hashPassword', set reset='A' where email='$email' and ext='$ext'";
		$sql = "update M6User set password='$hashPassword', reset='A' where email='$email'";
		
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename (" . __LINE__ . "): password has been reset for email={$email} ext={$ext}" );
					
			$ret = sendmailControllerFunc ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$hashPassword,"ResetApprove");
					
			if ( $ret == 1 ) {
				$log->i( "$self_filename (" . __LINE__ . "): An approval email has been sent." );
				$msg = "Password has been reset. An approval email has been sent out.";
				$ret = 1;
			}
			else {
				$log->i( "$self_filename (" . __LINE__ . "): Unbale to send an approval email" );
				$msg = "Password has been reset. Unbale to send out an approval email.";
				$ret = 0;
			}
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): sql query error: sql={$sql}" );
			$msg = "Error: Password reset failed.";
			$ret = -1;
		}
	}
	
	else if ( $reset == "N" ) {
		$sql = "update M6User set reset='J' where email='$email'";
		
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename (" . __LINE__ . "): reset updated. reset=J" );
				
			$ret = sendmailControllerFunc (null, null, $email, null, null , null, null, null, "ResetReject");
				
			if ( $ret == 1 ) {
				$log->i( "$self_filename (" . __LINE__ . "): A Rejection email has been sent out." );
				$msg = "Reset password has been rejected. A Rejection email has been sent out.";
				$ret = 1;
			}
			else {
				$log->i( "$self_filename (" . __LINE__ . "): Unbale to send out a Rejection email" );
				$msg = "Reset password has been rejected. Unable to send out a Rejection email.";
				$ret = 0;
			}
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): sql query error: sql={$sql}" );
			$msg = "Error: Password reset failed.";
			$ret = -1;
		}		
	}
	
	else {
		$log->w( "$self_filename (" . __LINE__ . "): unable to reset password for email={$email} ext={$ext} reset={$reset} - An invalid value has been submitted ({$newstatus})" );
		$msg = "Error: Invalid value submitted. Password reset failed.";
		$ret = -1;
	}

}

else {
	$log->i( "$self_filename (" . __LINE__ . "): email={$email} not exists, unable to reset passowrd" );
	$msg = "Email {$email} does not exists.";
	$ret = 0;
}

print json_encode( array('ret' => $ret, 'msg' => $msg) );

function randomPassword() {
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

?>
