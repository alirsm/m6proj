<?php 

function create_registerRequestMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $partitionname, $usertype) 
{	
	//$cipher = 'AES-256-CBC';
	//$key = 'THIS IS A TEST';
	//$iv = "TESTTESTTEST1234";
	
	//$val1 = openssl_encrypt($userEmail, $cipher, $key, 0, $iv);
	//$val2 = openssl_encrypt($userExt, $cipher, $key, 0, $iv);
	
	$val1 = $userEmail;
	$val2 = $userExt;
	
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
	
	//echo "<table><tr><td><img src='http://baymerchandise.com/cdr/proj/images/logo_flag.jpg' width=100%/></td><td width=80%>";
	//echo "<table><tr><td><img src='http://68.200.25.190/m6ui_orig/images/logo_flag.png' width=100%/></td><td width=80%>";
	echo "<table><tr><td><img src='http://4.34.99.241/images/logo_flag.png' width=100%/></td><td width=80%>";
	echo date("F j, Y, g:i a");
	echo "<h2>Web Portal Registration</h2>";
	echo "<h4>Web Portal Registration Request</h4></td></tr></table>";

	echo "<table><tr><td width='20%'>From:</td><td>";
	
	echo $fromEmail;
	echo "</td></tr><tr><td>To:</td><td>";
	
	echo $toEmail;
	echo "</td></tr></table><br>";
	
	echo "Attention Admin,<br><br>At <b><span class='redText'>";
	echo date("F j, Y, g:i a");
	echo "</b></span> the following user has registered their extention with the following information.<br><br>";
	
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
	</tr>";
	
	/*
	<tr>
	<td style='font-size:25px;'>Partition:</td>
	<td style='font-size:25px;'>"; echo $partitionname; echo "</td>
	</tr>
			
	<tr class='imageunder'>
	<td style='font-size:25px;'>Type:</td>
	<td style='font-size:25px;'>"; echo $usertype; echo "</td>
	</tr>";
	*/	
	
	echo "</table>";
	
	/*
	//$val3 = openssl_encrypt("A", $cipher, $key, 0, $iv);
	$val3 = "A";
	echo "<a href='http://68.200.25.190/m6ui/php/updateUserStatus.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'><img src='http://4.34.99.241/images/approved.png'></a>";
	//echo "<a href='http://4.34.99.241/php/updateUserStatus.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'><img src='http://4.34.99.241/images/approved.png'></a>";
	//echo "<a href='http://baymerchandise.com/cdr/m6ui/php/updateUserStatus.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'><img src='http://4.34.99.241/images/approved.png'></a>";
	echo " ";
	//$val3 = openssl_encrypt("X", $cipher, $key, 0, $iv);
	$val3 = "X";
	echo "<a href='http://68.200.25.190/m6ui/php/updateUserStatus.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'><img src='http://4.34.99.241/images/rejected.png' style='margin-left:10%;'></a>";
	//echo "<a href='http://4.34.99.241/php/updateUserStatus.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'><img src='http://4.34.99.241/images/rejected.png' style='margin-left:10%;'></a>";
	//echo "<a href='http://baymerchandise.com/cdr/m6ui/php/updateUserStatus.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'><img src='http://4.34.99.241/images/rejected.png' style='margin-left:10%;'></a>";
	*/
	
	echo "
	<p>
	<br>
	
	This message and any attachments are solely for the intended recipient and may contain confidential or privileged  information. if you are not the intended recipient,any disclosure, copying, use, or distribution of the information included in this message and any attachments is prohibited. If you have received this communication in error, please notify us by reply e-mail and immediately and permanently delete this message and any attachments.
	</p>
	</body></html>";
}

/*
create_registerRequestMailMessage("ali@abc.com",
											 "alirsm@gmail.com",
											 "subject not used",
											 "Jone Due",
											 "test@abc.com",
											 "8131112222",
											 "8101",
											 "Alpha Health",
											 "U");
*/