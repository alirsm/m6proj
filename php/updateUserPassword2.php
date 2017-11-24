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
	$email="john@abcc.com";
	$ext = "1234";
	$reset = "N";
}
else {
	$email = ! empty($_REQUEST['email']) ? $_REQUEST['email'] : NULL;
	$ext = ! empty($_REQUEST['ext']) ? $_REQUEST['ext'] : NULL;
	$reset = ! empty($_REQUEST['rest']) ? $_REQUEST['reset'] : NULL;
	
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
//$sql = "select fname,lname,email,ext,status,lastlogin_time from M6User where email='$email'";
$sql = "select fname,lname,phone,partitionname,usertype,status from M6User where email='$email' and ext='$ext'";
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
		$sql = "update M6User set password='$hashPassword' where email='$email' and ext='$ext'";
		
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename (" . __LINE__ . "): password has been reset for email={$email} ext={$ext}" );
					
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
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): unable to reset password for email={$email} ext={$ext} : sql query error: sql={$sql}" );
			$msg = "Error: Unable to reset password.";
			$ret = -1;
		}
	}
	
	else if ( $reset == "N" ) {
		$ret = sendmailControllerFunc (null, null, $email, null, null , null, null, null, "ResetReject");
	
		if ( $ret == 1 ) {
			$log->w( "$self_filename (" . __LINE__ . "): Reset password has been rejected. A Rejection email has been sent." );
			$msg = "Reset password has been rejected. A Rejection email has been sent.";
			$ret = 1;
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): Reset password has been rejected. Unable to send a Rejection email." );
			$msg = "Reset password has been rejected. Unable to send a Rejection email.";
			$ret = 0;
		}
	}
	
	else {
		$log->w( "$self_filename (" . __LINE__ . "): unable to reset password for email={$email} ext={$ext} reset={$reset} - An invalid value has been submitted ({$newstatus})" );
		$msg = "Error: Unable to reset password. invalid value submitted.";
		$ret = -1;
	}

}

else {
	$log->i( "$self_filename (" . __LINE__ . "): email={$email} not exists, unable to reset passowrd" );
	$msg = "Email {$email} not exists. Unable to reset password.";
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
