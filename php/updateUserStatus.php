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
	$email="ali@abc.com";
	$ext="8101";
	$newstatus = "X";
	$log->i( "$self_filename (". __LINE__ ."): $email $newstatus" );
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
	$newstatus = $val3;
	
	//$status = "A";
	//$fname = ! empty($_REQUEST['fname']) ? $_REQUEST['fname'] : NULL;
	//$lname = ! empty($_REQUEST['lname']) ? $_REQUEST['lname'] : NULL;
	//$phone = ! empty($_REQUEST['phone']) ? $_REQUEST['phone'] : NULL;
}

$log->i( "$self_filename (". __LINE__ ."): $email $ext $newstatus" );

/*
// Register request rejected
if ( $newstatus == "X" ) {
	$ret = sendmailControllerFunc (null, null, $email, null, null, null, null, null, "RegisterReject");
	if ( $ret == 1 ) {
		
		echo "A rejection email has been sent to email: {$email}";
	}
	else {
		echo "Error: Unable to send the rejection email.<br>";
		echo "Template approval email not exists.<br>";
		echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
	}
	//echo "Email ({$email}) with extension ({$ext}) has been rejected.<br>";
	exit(0);
}
*/

if ( $newstatus == "A"  || $newstatus == "X" ) {

	date_default_timezone_set('US/Eastern');
	$now = date("Y-m-d H:i:s");

	$db = new Db();
	$sql = "select fname,lname,phone,password,partitionname,usertype,status from M6User where email='$email' and ext='$ext'";
	$log->i( $sql );
	$rows = $db -> select($sql);
	$count = count($rows);

	if ( $count == 1 ) {
		$fname = $rows[0]['fname'];
		$lname = $rows[0]['lname'];
		$phone = $rows[0]['phone'];
		$password = $rows[0]['password'];
		$partitionname = $rows[0]['partitionname'];
		$usertype = $rows[0]['usertype'];
		$status = $rows[0]['status'];
	
		
		if ( $status == "R" || $status == "X" || $status == "A" ) {
			$sql = "update M6User set status='$newstatus',register_time='$now' where email='$email' and ext='$ext'";
			
			//$log->i( $sql );
	
			if ( $db -> query($sql) ) {
				$log->i( "$self_filename (" . __LINE__ . "): email={$email} ext={$ext} with status={$newstatus} updated" );
				
				if ( $newstatus == "A" ) {
				
					echo "Email: {$email} with extension: {$ext} has been approved.<br>";
					$log->i( "$self_filename (" . __LINE__ . "): $fname,$lname,$email,$phone,$ext,$password,$partitionname,$usertype" );			
					$ret = sendmailControllerFunc ($fname,$lname,$email,$phone,$ext,$partitionname,$usertype,$password,"RegisterApproval");
					
					if ( $ret == 1 ) {
						echo "An approval email has been sent to ({$email}).";
					}
					else {
						echo "Error: Unable to send the approval email.<br>";
						echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
					}
				}
				
				if ( $newstatus == "X" ) {
				
					echo "Email: {$email} with extension: {$ext} has been rejected.<br>";
					$log->i( "$self_filename (" . __LINE__ . "): $email" );
					$ret = sendmailControllerFunc (null, null, $email, null, null, null, null, null, "RegisterReject");
						
					if ( $ret == 1 ) {
						echo "A rejection email has been sent to ({$email}).";
					}
					else {
						echo "Error: Unable to send the rejection email.<br>";
						echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
					}
				}
				
			}
			else {
				$log->w( "$self_filename (" . __LINE__ . "): unable to approve email={$email} ext={$ext} : sql query error: sql={$sql}" );
				echo "Error: Unable to approve email ({$email}) with extension ({$ext}).<br>";
				echo "Updating database faild.<br>";
				echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
				exit(0);
				
			}
		}
		
		/*
		else if ( $status == "A" ) {
			$log->w( "$self_filename (" . __LINE__ . "): email={$email} ext={$ext} has already been registered and activated" );
			echo "Email: {$email} with extension: {$ext} has already been registered and approved.<br>";
			echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
			exit(0);
		}
		*/
		
		
		else {
			$log->w( "$self_filename (" . __LINE__ . "): unable to approve email={$email} ext={$ext} - incorrect existing value in database status={$status}" );
			echo "Error: Unable to approve email: {$email} with extension: {$ext}.<br>";
			echo "The exististing status ($status) for Email: {$email} with Extension: {$ext} is invalid.<br>";
			echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
			exit(0);
		}
		
	}
	else {
		$log->w( "$self_filename (" . __LINE__ . "): unable to approve email={$email} ext={$ext} - The email address not exists in the database." );
		echo "Error: Unable to approve/reject email: {$email} with extension: {$ext}.<br>";
		echo "The email address not exists in the database.<br>";
		echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
	}
	
}
else {
	$log->w( "$self_filename (" . __LINE__ . "): unable to approve email={$email} ext={$ext} - An invalid status value has been submitted ({$newstatus})" );
	echo "Error: Unable to approve/reject email: {$email} with extension: {$ext}.<br>";
	echo "An invalid status value has been submitted.<br>";
	echo "Please contact support at 212-201-0799 and/or email us at support@microv.net for assistance.";
}

?>
