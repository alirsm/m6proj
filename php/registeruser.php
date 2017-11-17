<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$fname="Ali"; $lname="Soltani"; $email="ali2@abc.com"; $ext="105"; $status="R";
}
else {	
	$status = ! empty($_POST['status']) ? $_POST['status'] : NULL;
	$fname = ! empty($_POST['fname']) ? $_POST['fname'] : NULL;
	$lname = ! empty($_POST['lname']) ? $_POST['lname'] : NULL;
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	//$phone = ! empty($_POST['phone']) ? $_POST['phone'] : NULL;
	$ext = ! empty($_POST['ext']) ? $_POST['ext'] : NULL;
	//$partitionname = ! empty($_POST['partitionname']) ? $_POST['partitionname'] : NULL;
	//$usertype = ! empty($_POST['usertype']) ? $_POST['usertype'] : NULL;
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
$sql = "select email, ext, status from M6User where email='$email'";
$log->i( $sql );
$rows = $db -> select($sql);
$count = count($rows);

if ( $count == 1 ) {
	$mystatus = $rows[0]['status'];
	if ( $mystatus == "R" ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already registered" );
		$msg = "The email address has already registered.";
		$ret = -1;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
	else if ( $mystatus == "A" ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already registered and approved" );
		$msg = "The email addrss has already registered and approved.";
		$ret = -1;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
	else if ( $mystatus == "J" ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} already registered and rejected" );
		$msg = "The email addrss has already registered and rejected.";
		$ret = -1;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
	else if ( $mystatus == "X" ) {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} is disabled" );
		$msg = "The email addrss has disabled.";
		$ret = -1;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
	else {
		$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} exists with status={$status}" );
		$msg = "The email address already existed with status = {$status}.";
		$ret = -1;
		print json_encode( array( 'ret' => $ret, 'msg' => $msg ) );
		exit(0);
	}
}
else {
	//add user 
	$sql = "insert into M6User (fname,lname,email,ext,status,password,register_time) 
	        values ('$fname','$lname','$email','$ext','$status','$hashPassword','$now')";
	$log->i( $sql );
}

if ( $db -> query($sql) ) { 
	$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} password={$password} registered" );
	$ret = 1;
	$msg = "User has been registered.";
}
else {
	$log->e( "$self_filename -> (" . __LINE__ . "): unable to register email={$email}" );
	$ret = -2;
	$msg = "Unable to register user.";
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
