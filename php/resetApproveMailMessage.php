<?php 

function create_resetApproveMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $userPassword) 
{	
	$head = <<<HEAD
<!doctype html>
<html lang="en">
<head>
<style>
html *
{
	font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
}
.redText
{
    color: red;
}
td{
	font-size:15px;
}
</style>
</head>
HEAD;
	
	echo $head;
	echo "<body>";
	
	//echo "<table><tr><td><img src='http://68.200.25.190/m6ui_orig/images/logo_flag.png' width=100%/></td><td width=80%>";
	echo "<table><tr><td><img src='http://4.34.99.241/images/logo_flag.png' width=100%/></td><td width=80%>";
	//echo "<table><tr><td><img src='http://baymerchandise.com/cdr/m6ui/images/logo_flag.png' width=100%/></td><td width=80%>";
	echo date("F j, Y, g:i a");
	echo "<h2>Web Portal Reset</h2>";
	echo "<h4>Web Portal Reset Request</h4></td></tr></table>";
	
	echo "<table><tr><td width='20%'>From:</td><td>";
	
	echo $fromEmail;
	echo "</td></tr><tr><td>To:</td><td>";
	
	echo $toEmail;
	echo "</td></tr></table><br>";
	
	echo "Attention Admin,<br><br>";
	echo "Your password reset request has been approved by your company admin. Please use the following temporary password to login to your account..<br><br>";
	
	echo "<table>
	<tr>
	<td width='25%' style='font-size:25px;'>Name:</td>
	<td style='font-size:25px;'>"; echo $userName; echo "</td>
	</tr>
	<tr>
	<td style='font-size:25px;'>Email:</td>
	<td style='font-size:25px;'>"; echo $userEmail; echo "</td>
	</tr>
	<tr>
	<td style='font-size:25px;'>Ext:</td>
	<td style='font-size:25px;'>"; echo $userExt; echo "</td>
	</tr>		
	<tr class='imageunder'>
	<td style='font-size:25px;'>Password:</td>
	<td style='font-size:25px;'>"; echo $userPassword; echo "</td>
	</tr>";
	echo "</table><br><br>";
	
	echo "
	<p>
	<br><br>
	This message and any attachments are solely for the intended recipient and may contain confidential or privileged  information. if you are not the intended recipient,any disclosure, copying, use, or distribution of the information included in this message and any attachments is prohibited. If you have received this communication in error, please notify us by reply e-mail and immediately and permanently delete this message and any attachments.
	</p>
	</body></html>";
}

/*
create_resetApproveMailMessage("wwebportaladmin@microv.net",
												 "alirsm@gmail.com",
												 "HTML email",
												 "John Smitj",
												 "jsmith@yorcompany.com",
												 "8131112222",
												 "8008",
												 "abc123");
*/

