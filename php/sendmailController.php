<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");
require_once ("{$self_dir}LoggerOne.php");
require_once ("{$self_dir}registerRequestMailMessage.php");
require_once ("{$self_dir}registerApprovalMailMessage.php");
require_once ("{$self_dir}registerRejectMailMessage.php");
require_once ("{$self_dir}resetRequestMailMessage.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');
if(!isset($log2)) $log2 = new LoggerOne(true, false, LoggerOne::INFO, LoggerOne::INFO);

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	//$userFname="John";$userLname="Smith";$userEmail="john@abc.com";$userPhone="8131112222";$userExt="8543";$serviceType="RegisterRequest";$userPassword="abc123";
	$userFname="Ali";$userLname="Soltani";$userEmail="alisphd@gmail.com";$userPhone="8131112222";$userExt="8101";$partitionname="Alpha Health";$usertype="U";$serviceType="RegisterReject";$userPassword="abc123";
	$log->i( "$self_filename -> (" . __LINE__ . ") in CLI  $userFname  $userLname" );
	$log2->i( "$self_filename -> (" . __LINE__ . ") in CLI  $userFname  $userLname" );
}
else {

	$userFname = ! empty($_POST['userFname']) ? $_POST['userFname'] : NULL;
	$userLname = ! empty($_POST['userLname']) ? $_POST['userLname'] : NULL;
	$userEmail = ! empty($_POST['userEmail']) ? $_POST['userEmail'] : NULL;
	$userPhone = ! empty($_POST['userPhone']) ? $_POST['userPhone'] : NULL;
	$userExt = ! empty($_POST['userExt']) ? $_POST['userExt'] : NULL;
	$partitionname = ! empty($_POST['partitionname']) ? $_POST['partitionname'] : NULL;
	$usertype = ! empty($_POST['usertype']) ? $_POST['usertype'] : NULL;
	$serviceType = ! empty($_POST['serviceType']) ? $_POST['serviceType'] : NULL;
	$userPassword = ! empty($_POST['userPassword']) ? $_POST['userPassword'] : NULL;
}

$log->i( "$self_filename -> (" . __LINE__ . "): userFname={$userFname} userLname={$userLname} userEmail={$userEmail} userPhone={$userPhone} userExt={$userExt} serviceType={$serviceType}" );

switch ( $serviceType )
{
	case "RegisterRequest":
		$log->i( "$self_filename -> (" . __LINE__ . "): RegisterRequest" );
		$fromName = "User"; 
		$fromEmail = $userEmail; //"user@company.com";
		$toEmail = "alirsm@gmail.com";	//$to = "michael@microv.net";
		$subject = "Web Portal Registration Request";
		$userName = $userFname . " " . $userLname;
		$message = get_include_contents("registerRequestMailMessage.php");
		break;
	
	case "ResetRequest":
		$log->i( "$self_filename -> (" . __LINE__ . "): ResetRequest" );
		$fromName = "User";
		$fromEmail = $userEmail; //"user@company.com";
		$toEmail = "alirsm@gmail.com";	//$to = "michael@microv.net";
		$subject = "Web Portal Reset Request";
		$userName = $userFname . " " . $userLname;
		$message = get_include_contents("resetRequestMailMessage.php");
		break;
		
	case "RegisterApproval":
		$log->i( "$self_filename -> (" . __LINE__ . "): RegisterApproval" );
		$fromName = "Microv (Admin)";
		$fromEmail = "admin@cloud.microv.net";
		$toEmail = "alirsm@gmail.com";	//$to = "admin@company.com";
		$subject = "Web Portal Registration Approval";
		$userName = $userFname . " " . $userLname;
		$message = get_include_contents("registerApprovalMailMessage.php");
		break;
		
	case "RegisterReject":
		$log->i( "$self_filename -> (" . __LINE__ . "): RegisterReject" );
		$fromName = "Microv (Admin)";
		$fromEmail = "admin@cloud.microv.net";
		$toEmail = "alirsm@gmail.com";	//$to = "admin@company.com";
		$subject = "Web Portal Registration Reject";
		$userName = $userFname . " " . $userLname;
		$message = get_include_contents("registerRejectMailMessage.php");
		break;
}

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
		    'Reply-To: '.  $fromEmail . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

$ret = mail($toEmail, $subject, $message, $headers);
$log->i( "$self_filename -> (" . __LINE__ . "): sending email: from={$fromEmail} to={$toEmail} subject={$subject} ret={$ret}" );

// $ret bool must be cast to int
print json_encode( array( 'ret' => (int)$ret, 'msg' => $msg ) );


function get_include_contents($filename) 
{	
	global $fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $partitionname, $usertype, $userPassword, $msg;
		
	if (is_file($filename)) 
	{
		ob_start();
		
		switch ( $filename )
		{
			case "registerRequestMailMessage.php":
				echo create_registerRequestMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $partitionname, $usertype);
				$msg = "Register request email has been sent.";
				break;
				
			case "registerRejectMailMessage.php":
				//echo create_registerRejectMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $userPassword);
				echo create_registerRejectMailMessage($fromEmail, $toEmail);
				$msg = "Register request has been rejected.";
				break;
				
			case "registerApprovalMailMessage.php":
				echo create_registerApprovalMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $userPassword);
				$msg = "Register request has been approved.";
				break;
			case "resetRequestMailMessage.php":
				echo create_resetRequestMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $partitionname, $usertype);
				$msg = "Request for password reset has been sent.";
				break;
		}
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	return false;
}

?>