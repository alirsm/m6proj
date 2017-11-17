<?php

function sendemail($fromName, $fromEmail, $toEmail, $subject, $message)
{
	$self_dir = dirname ( __FILE__ ) . "/";
	require_once ("{$self_dir}ClassDb.php");
	require_once ("{$self_dir}Logger.php");
	
	if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');
	
	$log->i("inside sendmail");
	
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
			'Reply-To: '.  $fromEmail . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
	
	$res = mail($to,$subject,$message,$headers);
	
	$log->i( "$self_filename -> (" . __LINE__ . "): sending email to {$toEmail} res={$res}" );
}
?>