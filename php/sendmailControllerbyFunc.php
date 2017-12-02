<?php

function sendmailControllerFunc ($userFname, $userLname, $userEmail, $userPhone, $userExt, $partitionname, $usertype, $userPassword, $serviceType)
{
	$self_dir = dirname ( __FILE__ ) . "/";
	$self_filename = basename ( __FILE__ );
	require_once ("{$self_dir}ClassDb.php");
	require_once ("{$self_dir}Logger.php");
	require_once ("{$self_dir}registerRequestMailMessage.php");
	require_once ("{$self_dir}registerApprovalMailMessage.php");
	require_once ("{$self_dir}registerRejectMailMessage.php");
	require_once ("{$self_dir}resetApproveMailMessage.php");
	require_once ("{$self_dir}resetRejectMailMessage.php");
	
	if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');
	
	$log->i( "$self_filename (" . __LINE__ . "): $userFname,$userLname,$userEmail,$userPhone,$userExt,$partitionname,$usertype,$userPassword" );
	
	switch ( $serviceType )
	{
		
		case "RegisterRequest":
			$log->i( "$self_filename -> (" . __LINE__ . "): RegisterRequest" );
			$fromName = "User";
			$fromEmail = $userEmail; //"user@company.com";
			$toEmail = "alirsm@gmail.com";	//$to = "michael@microv.net";
			$subject = "Web Portal Registration Request";
			$userName = $userFname . " " . $userLname;
			$filename = "registerRequestMailMessage.php";
			$filename = "registerRequestMailMessageWithButtons.php";
			if (is_file($filename))
			{
				ob_start();
				echo create_registerRequestMailMessage($fromEmail,$toEmail,$subject,$userName,$userEmail,null,$userExt,null,null);	
				$msg = "Register request has been sent out.";
				$message = ob_get_contents();
				ob_end_clean();
			}
			else
			{
				$log->e( "$self_filename (" . __LINE__ . "): file {$filename} not exists." );
				return -1;
			}
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
					'Reply-To: '.  $fromEmail . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				
			$ret = mail($toEmail, $subject, $message, $headers);
			$log->i( "$self_filename (" . __LINE__ . "): sending email: to={$toEmail} subject={$subject} ret={$ret}" );
			break;	
			
			
		case "RegisterApproval":
			$log->i( "$self_filename -> (" . __LINE__ . "): RegisterApproval" );
			$fromName = "Microv (Admin)";
			$fromEmail = "admin@cloud.microv.net";
			$toEmail = "alirsm@gmail.com";	//$to = $userEmail;
			$subject = "Web Portal Registration Approval";
			$userName = $userFname . " " . $userLname;
			$log->i( "$self_filename (" . __LINE__ . "): $fromEmail" );
			$filename = "registerApprovalMailMessage.php";
			if (is_file($filename))
			{
				ob_start();
				echo create_registerApprovalMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $userPassword);
				$msg = "Register request has been approved.";
				$message = ob_get_contents();
				ob_end_clean();
			}
			else
			{
				$log->e( "$self_filename (" . __LINE__ . "): file {$filename} not exists." );
				return -1;
			}
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
					'Reply-To: '.  $fromEmail . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
			
			$ret = mail($toEmail, $subject, $message, $headers);
			$log->i( "$self_filename (" . __LINE__ . "): sending email: to={$toEmail} subject={$subject} ret={$ret}" );
			break;
			
			case "RegisterReject":
				$log->i( "$self_filename -> (" . __LINE__ . "): RegisterReject" );
				$fromName = "Microv (Admin)";
				$fromEmail = "admin@cloud.microv.net";
				$toEmail = "alirsm@gmail.com";	//$to = $userEmail;
				$subject = "Web Portal Registration Reject";
				$userName = $userFname . " " . $userLname;
				$log->i( "$self_filename (" . __LINE__ . "): $fromEmail" );
				$filename = "registerRejectMailMessage.php";
				if (is_file($filename))
				{
					ob_start();
					echo create_registerRejectMailMessage($fromEmail, $toEmail);
					$msg = "Register request has been rejected.";
					$message = ob_get_contents();
					ob_end_clean();
				}
				else
				{
					$log->e( "$self_filename (" . __LINE__ . "): file {$filename} not exists." );
					return -1;
				}
					
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
						'Reply-To: '.  $fromEmail . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					
				$ret = mail($toEmail, $subject, $message, $headers);
				$log->i( "$self_filename (" . __LINE__ . "): sending email: to={$toEmail} subject={$subject} ret={$ret}" );	
				break;			
				
				case "ResetApprove":
					$log->i( "$self_filename -> (" . __LINE__ . "): ResetApprove" );
					$fromName = "Microv (Admin)";
					$fromEmail = "admin@cloud.microv.net";
					$toEmail = "alirsm@gmail.com";	//$to = $userEmail;
					$subject = "Web Portal Reset Approval";
					$userName = $userFname . " " . $userLname;
					$log->i( "$self_filename (" . __LINE__ . "): $fromEmail" );
					$filename = "resetApproveMailMessage.php";
					if (is_file($filename))
					{
						ob_start();
						echo create_resetApproveMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $userPassword);
						$msg = "Reset request has been approved.";
						$message = ob_get_contents();
						ob_end_clean();
					}
					else
					{
						$log->e( "$self_filename (" . __LINE__ . "): file {$filename} not exists." );
						return -1;
					}
						
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
							'Reply-To: '.  $fromEmail . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						
					$ret = mail($toEmail, $subject, $message, $headers);
					$log->i( "$self_filename (" . __LINE__ . "): sending email: to={$toEmail} subject={$subject} ret={$ret}" );
					break;
						
				case "ResetReject":
					$log->i( "$self_filename -> (" . __LINE__ . "): ResetReject" );
					$fromName = "Microv (Admin)";
					$fromEmail = "admin@cloud.microv.net";
					$toEmail = "alirsm@gmail.com";	//$to = $userEmail;
					$subject = "Web Portal Reset Reject";
					$userName = $userFname . " " . $userLname;
					$log->i( "$self_filename (" . __LINE__ . "): $fromEmail" );
					$filename = "resetRejectMailMessage.php";
					if (is_file($filename))
					{
						ob_start();
						echo create_resetRejectMailMessage($fromEmail, $toEmail);
						$msg = "Reset request has been rejected.";
						$message = ob_get_contents();
						ob_end_clean();
					}
					else
					{
						$log->e( "$self_filename (" . __LINE__ . "): file {$filename} not exists." );
						return -1;
					}
						
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
							'Reply-To: '.  $fromEmail . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						
					$ret = mail($toEmail, $subject, $message, $headers);
					$log->i( "$self_filename (" . __LINE__ . "): sending email: to={$toEmail} subject={$subject} ret={$ret}" );
					break;
	}
	
	return $ret;
		
	// $ret bool must be cast to int
	//print json_encode( array( 'ret' => (int)$ret, 'msg' => $msg ) );
	
}

//sendmailControllerFunc("Ali","Soltani","ali@abc.com","8131112222","8101","Alpha Health","U","abc123","registerApproval");
//sendmailControllerFunc(null, null, "john@abc.com", null, null, null, null, null, "ResetReject");
//sendmailControllerFunc ("test2f","test2l","test2@abc.com",null,"123",null,null,"abc123","RegisterRequest");

?>