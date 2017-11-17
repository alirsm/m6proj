<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$email="john@abc.com"; $ext="123"; $password="abc123";
}
else {
	$email = ! empty($_POST['email']) ? $_POST['email'] : NULL;
	$ext = ! empty($_POST['ext']) ? $_POST['ext'] : NULL;
	$password = ! empty($_POST['password']) ? $_POST['password'] : NULL;
	//$email = $_POST['email'];
	//$ext = $_POST['ext'];
	//$password = $_POST['password'];
}

$log->i( "$self_filename -> (" . __LINE__ . "): email={$email} password={$password}" );

$db = new Db();
$sql = "select fname,lname,email,ext,status,password,usertype,partitionname,lastlogin_time from M6User where email='$email'";
$log->i("sql = $sql");

$rows = $db -> select($sql);
$count = count($rows);

// email does not exists
if ( $count != 1 ) 
{	
	$ret = -1;
	print json_encode( array( 'ret' => $ret ) );
}
else 
{
	foreach ($rows as $row)
	{
		$hashpassword = $rows[0]['password'];
		$readext = $rows[0]['ext'];
		$status = $rows[0]['status'];
		$usertype = $rows[0]['usertype'];
		
		//if ( password_verify($password, $hashpassword) && $ext == $readext )
		if ( $password == $hashpassword )
		{
			if ( $ext == $readext )
			{
				if ( $status == "A" || $status == "R" )
				{
					if ( $usertype == "S" || $usertype == "A" || $usertype == "U" ) 
					{
						// first time login
						if ( $status == "R" && is_null ($rows[0]['lastlogin_time']) )
						{
							$ret = 2;
						}
						// normal login
						else 
						{
							$ret = 1;
						}
						// remove some fields from return data
						unset($rows[0]['password']);
						unset($rows[0]['lastlogin_time']);
						// add ret value to retune data
						$rows[0]['ret'] = $ret;
						print json_encode($rows[0]);	
					}
					// not valid user type
					else 
					{
						$ret = -5;
						print json_encode( array( 'ret' => $ret ) );
					}
				}
				// not active
				else 
				{
					$ret = -4;
					print json_encode( array( 'ret' => $ret ) );
				}
			}
			// ext not matched
			else 
			{
				$ret = -3;
				print json_encode( array( 'ret' => $ret ) );
			}
		}
		// password not matched
		else
		{
			$ret = -2;
			print json_encode( array( 'ret' => $ret ) );
		}
		
	}
	
	/*
	$hashpassword = $rows[0]['password'];
	// password matched
	//if ( password_verify ( $password, $hashpassword)) {
	if ( $password == $hashpassword )
	{
		// first time login
		if ( is_null ($rows[0]['lastlogin_time']) )
		{
			$ret = 2;
		}
		// normal login
		else
		{
			$ret = 1;
		}
		// remove some fields from return data
		unset($rows[0]['password']);
		unset($rows[0]['lastlogin_time']);
		// add ret value to retune data
		$rows[0]['ret'] = $ret;
		print json_encode($rows[0]);
		
	}
	// password/ext doesn't matched
	else 
	{
		$ret = -2;
		print json_encode( array( 'ret' => $ret ) );
	}
	*/
	
	
}