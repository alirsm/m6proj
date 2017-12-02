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
	$fname="test2f"; $lname="test2l"; $email="test2@abc.com"; $ext="105"; $status="R";
}
else {	
	$status = ! empty($_POST['status']) ? $_POST['status'] : NULL;
	$fname = ! empty($_POST['fname']) ? $_POST['fname'] : NULL;
	$lname = ! empty($_POST['lname']) ? $_POST['lname'] : NULL;
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	$ext = ! empty($_POST['ext']) ? $_POST['ext'] : NULL;
}

$password = randomPassword();
//$password = "abc125";
//$hashPassword = password_hash($password, PASSWORD_BCRYPT);
//$hashPassword = "abc125";
$hashPassword = $password;

$log->i( "$self_filename -> (". __LINE__ ."): $fname,$lname,$email,$ext,$password,$status" );

date_default_timezone_set('US/Eastern');
$now = date("Y-m-d H:i:s");

$db = new Db();
//$sql = "select email, ext, status from M6User where email='$email'";
$sql = "select password, ext, status, usertype, fname, lname, phone from M6User where email='$email'";
$log->i( $sql );
$rows = $db -> select($sql);
if ( $rows !== false) {
	$count = count($rows);
	$log->i( "$self_filename -> (" . __LINE__ . "): count={$count}" );
	
	if ( $count == 1 ) {
		$mystatus = $rows[0]['status'];
			
		
		if ( $mystatus == "R" ) {
			$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already registered" );
			$msg = "Register request has already submitted.";
			$ret = 0;
			print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
			exit(0);
		}
		else if ( $mystatus == "A" ) {
			$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already registered and approved" );
			$msg = "Register request has already submitted and approved.";
			$ret = 0;
			print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
			exit(0);
		}
		else if ( $mystatus == "J" ) {
			$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already registered and rejected" );
			$msg = "Register request has already submitted and rejected.";
			$ret = 0;
			print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
			exit(0);
		}
		else if ( $mystatus == "X" ) {
			$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} is disabled" );
			$msg = "The email address has disabled.";
			$ret = 0;
			print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
			exit(0);
		}
		else {
			$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} exists with status={$status}" );
			$msg = "Register request failed. Invalid user status: {$status}.";
			$ret = 0;
			print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
			exit(0);
		}
	}
	else {
		//add user 
		$sql = "insert into M6User (fname,lname,email,ext,status,password,register_time) 
		        values ('$fname','$lname','$email','$ext','$status','$hashPassword','$now')";
		//$log->i( $sql );
		
		if ( $db -> query($sql) ) {
			$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} password={$password} registered" );
			
			$ret = sendmailControllerFunc ($fname,$lname,$email,null,$ext,null,null,null,"RegisterRequest");
				
			if ( $ret == 1 ) {
				$log->i( "$self_filename (" . __LINE__ . "): An approval email has been sent." );
				$msg = "Register request email has been sent out.";
				$ret = 1;
			}
			else {
				$log->i( "$self_filename (" . __LINE__ . "): Unbale to send an approval email" );
				$msg = "Registration has been submitted. Unbale to send out a register request email.";
				$ret = 0;
			}
			
			
		}
		else {
			$log->w( "$self_filename (" . __LINE__ . "): sql query error: sql={$sql}" );
			$ret = -1;
			$msg = "Error: Register Request failed.";
		}
	}
	
}
else {
	$log->i( "$self_filename -> (" . __LINE__ . "): unable to run query" );
	$msg = "Register Request failed.";
	$ret = -1;
	print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
	exit(-1);
}

print json_encode( array( 'ret' => $ret, 'msg' => $msg, 'password' => $password ) );


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
