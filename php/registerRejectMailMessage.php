<?php 

function create_registerRejectMailMessage($fromEmail, $toEmail) 
{	
	$head = <<<HEAD
<!doctype html>
<html lang="en">
<head>
<style>
html *
{
	font-family: arial;
}
.redText
{
    color: red;
}
.underline{
	-moz-text-decoration-color: red; /* Code for Firefox */
    text-decoration-color: red;
}
</style>
</head>
HEAD;
	
	echo $head;
	echo "<body>";
	
	echo "<table><tr><td><img src='http://4.34.99.241/images/logo_flag.png' width=100%/></td><td width=80%>";
	echo date("F j, Y, g:i a");
	echo "<h2>Web Portal Registration</h2>";
	echo "<h4>Web Portal Registration</h4></td></tr></table>";
	
	echo "<table><tr><td width='20%'>From:</td><td>";
	echo $fromEmail;
	
	echo "</td></tr><tr><td>To:</td><td>";
	echo $toEmail;
	
	echo "</td></tr></table><br>";
	
	echo "Attention User,<br><br>Your Ext registration request has been <a class='underline' href='#'><span class='redText'>REJECTED</span></a> by your company admin.<br><br>";
	
	echo "<a class='underline' href='#'><span class='redText'>Please contact support at 212-201-0799 and or email us at support@microv.net for assistance.</span></a><br><br>";
	
	echo "<br><br><br><br>This message and any attachments are solely for the intended recipient and may contain confidential or privileged  information. if you are not the intended recipient,any disclosure, copying, use, or distribution of the information included in this message and any attachments is prohibited. If you have received this communication in error, please notify us by reply e-mail and immediately and permanently delete this message and any attachments.";

}
	
// example to call
//create_registerRejectMailMessage("admin@cloud.microv.net", "user@gmail.com");
