<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$email="ali@abc.com";
	$ext="8101";
	$newstatus = "A";
	$log->i( "$self_filename (". __LINE__ ."): $email,$ext,$newstatus" );
}
else {
	$val1 = ! empty($_POST['val1']) ? $_POST['val1'] : NULL;
	//$val2 = ! empty($_POST['val2']) ? $_POST['val2'] : NULL;
	//$val3 = ! empty($_POST['val3']) ? $_POST['val3'] : NULL;
	$log->i( "$self_filename (". __LINE__ ."):$val1" );
	//$log->i( "$self_filename (". __LINE__ ."):$val1 $val2 $val3" );

}

//$log->i( "$self_filename (". __LINE__ ."): $email,$ext,$newstatus" );


?>

