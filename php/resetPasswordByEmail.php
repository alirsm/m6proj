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
	//$fname="Ali";$lname="Soltani";$email="ali@abc.com";$ext="8101";$partitionname="OcPartition: Alpha Health";$usertype="U";
	$email="john@abc.com";
	$ext = "1234";
	$reset = "N";
	$log->i( "$self_filename (". __LINE__ ."): $email $ext $reset" );
}
else {
	$val1 = ! empty($_REQUEST['val1']) ? $_REQUEST['val1'] : NULL;
	$val2 = ! empty($_REQUEST['val2']) ? $_REQUEST['val2'] : NULL;
	$val3 = ! empty($_REQUEST['val3']) ? $_REQUEST['val3'] : NULL;
	$log->i( "$self_filename (". __LINE__ ."):$val1 $val2 $val3" );
	
	$cipher = 'AES-256-CBC';
	$key = 'ABADAN is the best';
	$iv = "ABADANBAMAN1343!";
	
	//$email = openssl_decrypt($val1, $cipher, $key, 0, $iv);
	//$ext = openssl_decrypt($val2, $cipher, $key, 0, $iv);
	//$newstatus = openssl_decrypt($val3, $cipher, $key, 0, $iv);
	
	$email = $val1;
	$ext = $val2;
	$reset = $val3;
	
	//$status = "A";
	//$fname = ! empty($_REQUEST['fname']) ? $_REQUEST['fname'] : NULL;
	//$lname = ! empty($_REQUEST['lname']) ? $_REQUEST['lname'] : NULL;
	//$phone = ! empty($_REQUEST['phone']) ? $_REQUEST['phone'] : NULL;
}

$log->i( "$self_filename (". __LINE__ ."): $email $ext $reset" );

if ( $reset == "Y"  || $reset == "N" ) {

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
			//$hashPassword = "abc125";
			$hashPassword = $password;
			$sql = "update M6User set password='$hashPassword' where email='$email' and ext='$ext'";
			
			if ( $db -> query($sql) ) {
				$log->i( "$self_filename (" . __LINE__ . "): password has been reset for email={$email} ext={$ext}" );
				echo "The password for email: {$email} with extension: {$ext} has been reset.<br>";
			
				$ret = sendmailControllerFunc ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$hashPassword,"ResetApprove");
				//$ret = sendmailControllerFunc (null, null, $email, null, null, null, null, null, "ResetApprove");
									
				if ( $ret == 1 ) {
					echo "An approval email has been sent to ({$email}).";
				}
				else {
					echo "Error: Unable to send the approvall email.<br>";
					echo "Please contact support at 212-201-0799 and/or email us at microv.net for assistance.";
				}
			
			}
			else {
				$log->w( "$self_filename (" . __LINE__ . "): unable to reset password for email={$email} ext={$ext} : sql query error: sql={$sql}" );
				echo "Error: Unable to reset password for email ({$email}) with extension ({$ext}).<br>";
				echo "Updating database faild.<br>";
				echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
				exit(0);
			}
		}
			
		if ( $reset == "N" ) {	
			//$ret = sendmailControllerApprovalFun ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$password);
			$ret = sendmailControllerFunc (null, null, $email, null, null , null, null, null, "ResetReject");	

			if ( $ret == 1 ) {
				echo "A rejection email has been sent to ({$email}).";
			}
			else {
				echo "Error: Unable to send the rejection email.<br>";
				echo "Please contact support at 212-201-0799 and/or email us at microv.net for assistance.";
			}	
		}
		
			
	}
	else {
		$log->w( "$self_filename (" . __LINE__ . "): unable to reset password for email={$email} ext={$ext} - The email address not exists in the database." );
		echo "Error: Unable to reset password for email: {$email} with extension: {$ext}.<br>";
		echo "The email address not exists in the database.<br>";
		echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
	}

}
else {
	$log->w( "$self_filename (" . __LINE__ . "): unable to reset password for email={$email} ext={$ext} - An invalid value has been submitted ({$newstatus})" );
	echo "Error: Unable to reset password for email: {$email} with extension: {$ext}.<br>";
	echo "An invalid value has been submitted.<br>";
	echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
}



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
