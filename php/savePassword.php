<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

$log->i( "$self_filename -> (" . __LINE__ . "): CLI={$CLI}" );

if ( $CLI ) {
	$email="john@abc.com";$password="abc";
}
else {	
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	$password = ! empty($_POST['password']) ? $_POST['password'] : NULL;;
}

//$password = randomPassword();
//$password = "abc123";
//$hashPassword = password_hash($password, PASSWORD_BCRYPT);
$hashPassword = $password;

date_default_timezone_set('US/Eastern');
$now = date("Y-m-d H:i:s");

$db = new Db();
$sql = "update M6User set password='$password', lastlogin_time='$now' where email='$email'";
$log->i( "$self_filename -> (" . __LINE__ . "): sql={$sql}" );
if ( $db -> query($sql) ) {
	$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} password={$password} saved" );
	$msg = "New password saved.";
	$ret = 1;
}
else {
	$log->e( "$self_filename -> (" . __LINE__ . "): unable to save password: email={$email} password={$password}" );
	$ret = 1001;
	$msg = "Unable to save password.";
	print json_encode( array('ret' => $ret, 'msg' => $msg) );
	exit(-1);
}

$sql = "select fname,lname,email,ext,usertype,partitionname,lastlogin_time from M6User where email='$email'";
$rows = $db -> select($sql);
$count = count($rows);
// $count must be 1
if ( $count != 1 ) {
	$msg = "email does not exists.";
	$ret = -1;
	print json_encode( array('ret' => $ret, 'msg' => $msg) );
	exit(-1);
}
else {
	$rows[0]['ret'] = $ret;
	$rows[0]['msg'] = $msg;
}

print json_encode($rows[0]);
//print json_encode( array('ret' => $ret, 'msg' => $msg) );

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