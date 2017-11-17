<?php 

function create_registerRequestMailMessage($fromEmail, $toEmail, $subject, $userName, $userEmail, $userPhone, $userExt, $partitionname, $usertype) 
{	
	/*
	$plaintext1 = 'bob@abc.com';
	$plaintext2 = '1234';
	$cipher = 'AES-256-CBC';
	$key = 'ABADAN is the best';
	$iv = "ABADANBAMAN1343!";
	$val1 = openssl_encrypt($userEmail, $cipher, $key, 0, $iv);
	$val2 = openssl_encrypt($userExt, $cipher, $key, 0, $iv);
	*/
	
	/* OLD
	//$val1 = substr($val1, 0, -2);
	//$val2 = substr($val2, 0, -2);
	//$val1 = $userEmail;
	//$val2 = $userExt;
	 */
	
	echo "<!doctype html><html lang='en'>";
	echo "<head>";
	echo "<style> html * {font-family: arial;} .redText {color: red;} </style>";
	echo "</head>";

	echo "<body>";
	
	echo "<table><tr><td><img src='http://baymerchandise.com/cdr/proj/images/logo.jpg' width=100%/></td><td width=80%>";
	echo date("F j, Y, g:i a");
	echo "<h2>Web Portal Registration</h2>";
	echo "<h4>Web Portal Registration Request</h4></td></tr></table>";

	echo "<table><tr><td width='20%'>From:</td><td>";
	
	echo $fromEmail;
	echo "</td></tr><tr><td>To:</td><td>";
	
	echo $toEmail;
	echo "</td></tr></table><br>";
	
	echo "Attention Admin,<br><br>At <span class='redText'>";
	echo date("F j, Y, g:i a");
	echo "</span> the following user has registered their extention with the following information.<br><br>";
	
	echo "<table><tr><td width='25%'>Name:</td><td>";
	echo $userName;
	echo "</td></tr><tr><td>Email:</td><td>";
	echo $userEmail;
	echo "</td></tr><tr><td>Phone:</td><td>";
	echo $userPhone;
	echo "</td></tr><tr><td>Ext:</td><td>";
	echo $userExt;
	echo "</td></tr><tr><td>Partition:</td><td>";
	echo $partitionname;
	echo "</td></tr><tr><td>User type:</td><td>";
	echo $usertype;
	echo "</td></tr></table><br>";
	
	//echo "<table><tr><td width='60%'><a href='http://68.200.25.190/proj/php/adduser2.php?email=" . $userEmail . "&ext=" . $userExt ."'>Approved</a></td>";
	////echo "<table><tr><td width='60%'><a href='http://68.200.25.190/proj/php/adduser2.php?email=" . $userEmail . "&ext=" . $userExt .  "&fname=" . $userFname .  "&lname=" . $userLname .  "&phone=" . $userPhone . "'>Approved</a></td>";
	//echo "<td><a href='http://www.missouri.edu'>Rejected</a></td></tr></table>";
	//echo "<a href='http://68.200.25.190/m6ui/php/adduser2.php?val0=" . $val0 . "&val1=" . $val1 . "&val2=" . urlencode($val2) . "&val3=A'>Approve</a>";
	//echo "<a href='http://68.200.25.190/m6ui/php/adduser2.php?val0=" . $val0 . "&val1=" . $val1 . "&val2=" . urlencode($val2) . "&val3=A'>Approve</a>";
	
	
	//$val3 = openssl_encrypt("A", $cipher, $key, 0, $iv);
	$val1 = "ali@abc.com"; $val2 = "8101"; $val3 = "A";
	
	echo "<form action='http://baymerchandise.com/cdr/m6ui/php/test2.php' method='post'>";
	echo "<input type='hidden' value='" . $val1 . "' name='val1' />"; 
	echo "<input type='submit' value='Submit'/>";
	echo "</form>";
	
	//echo "<a href='http://68.200.25.190/m6ui/php/adduser2.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'>Approve</a>";
	//echo " ";
	//$val3 = openssl_encrypt("X", $cipher, $key, 0, $iv);
	//$val1 = "ali@abc.com"; $val2 = "8101"; $val3 = "X";
	//echo "<a href='http://68.200.25.190/m6ui/php/adduser2.php?val1=" . $val1 . "&val2=" . $val2 . "&val3=" . $val3. "'>Reject</a>";	
	
	echo "</body>";
	echo "</html>";
}

/*
create_registerRequestMailMessage("ali@abc.com",
											 "alirsm@gmail.com",
											 "subject not used",
											 "Ali Soltani",
											 "ali@abc.com",
											 "8131112222",
											 "8101",
											 "Alpha Health",
											 "U");
*/

