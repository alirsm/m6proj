<!doctype html>
<html lang="en">
<head> 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>jQuery UI Tabs - Default functionality</title>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
<!-- <script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css"/>-->

<!-- <link rel="stylesheet" href="/resources/demos/style.css"/>-->

<!-- DataTables -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> -->
<!-- <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>-->
<link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
<script src="js/jquery.dataTables.min.js"></script>

<!-- jQuery Validator -->
<!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src='https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js'></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>-->


<!-- CSV -->
<script src="js/jquery.tabletoCSV.js" type="text/javascript" charset="utf-8"></script>

<!-- local -->
<link href="style.css" rel="stylesheet" type="text/css"/>
<link href="stylelogin.css" rel="stylesheet" type="text/css"/>
<script src="gui.js" type="text/javascript"></script>

<script>
/*
$( function() {
	$( "#tabs" ).tabs();
	$( "#fromdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#todate" ).datepicker({ dateFormat: 'yy-mm-dd' });
	//$( "#dialog" ).dialog();
} );
*/
</script>

<?php
$self_dir = dirname ( __FILE__ ) . "/";
require_once ("{$self_dir}php/functions.php");
?>
</head>

<body onload="init();">

<!-- <img src="images/logo.png" width=10%/>-->

<!-------  login  ---------------------------------------------->
<div id="login">   
<div id="loginform" class="loginpage"> 
<div class="loginlogo"><img src="images/logo.png"></div>
<div class="login-email"> 
	<label for="user_login">Email Address</label> <input type="email" name="log" id="loginemail" class="logininput" value="" size="20" placeholder="EMAIL ADDRESS"> 
</div>
<div class="login-ext"> 
	<label for="user_login">Extention Number</label> <input type="tel" name="log" id="loginextension" class="logininput" value="" size="20" placeholder="EXTENTION NUMBER"> 
</div>
<div class="login-password"> 
	<label for="user_pass">Password</label> <input type="password" name="pwd" id="loginpassword" class="logininput" value="" size="20" placeholder="PASSWORD" onkeydown="if (event.keyCode == 13) document.getElementById('loginbutton').click()"> 	
</div> 

<!-- first time login  -->
<div id="firsttimelogin" class="hidden">
<div class="login-password"> 
	<label for="user_pass">New Password</label> <input type="password" name="pwd" id="firsttimelogin1" class="logininput" value="" size="20" placeholder="PASSWORD" onkeydown="if (event.keyCode == 13) document.getElementById('loginbutton').click()"> 	
</div> 
<div class="login-password"> 
	<label for="user_pass">Retype New Password</label> <input type="password" name="pwd" id="firsttimelogin2" class="logininput" value="" size="20" placeholder="PASSWORD" onkeydown="if (event.keyCode == 13) document.getElementById('loginbutton').click()"> 	
</div> 
<div class="login-submit"> 
	<button id="firsttimeloginbutton" class="button-primary" onclick="firsttimelogin_onclick()">Login</button>
</div> 
</div>
<!-- /first time login  -->

<div class="login-submit"> 
	<button id="loginbutton" class="button-primary" onclick="login_onclick()">Login</button>
</div> 
<div class="bottom-link">		
	<a href="javascript:void(0)" class="box1" onclick="showRegisterRequest_onclick()">Register Extension</a>
	<div class="forgetpassword"><a href="javascript:void(0)" onclick="showForgetPassword_onclick()">Forget Password?</a></div>	
</div> 	
</div>
</div>
<!-------  /login ---------------------------------------------->


<!-------  register  ------------------------------------------->
<div id="register" class="hidden"> 
<div id="loginform" class="loginpage"> 
<div class="loginlogo"><img src="images/logo.png">
	<br><br>
	<div class="forget">REGISTER YOUR EXTENTION</div>
</div>
<div class="login-email"> 
	<label for="user_login">Your Name</label> <input type="text" name="log" id="registerfname" class="logininput" value="" size="20" placeholder="FIRST NAME"> 
</div>
<div class="login-email"> 
	<label for="user_login">Your Name</label> <input type="text" name="log" id="registerlname" class="logininput" value="" size="20" placeholder="LAST NAME"> 
</div>
<div class="login-email"> 
	<label for="user_login">Email Address</label> <input type="email" name="log" id="registeremail" class="logininput" value="" size="20" placeholder="EMAIL ADDRESS"> 
</div>
<div class="login-ext"> 
	<label for="user_login">Extention Number</label> <input type="tel" name="log" id="registerext" class="logininput" value="" size="20" placeholder="EXTENTION NUMBER"> 
</div>
<p>Enter your email address and extension, your company admin will approve your request. You will then be sent your password after approval. <p>
<br>
<div class="login-submit"> 
	<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Send" onclick="registerRequest_onclick()">
</div> 
<div class="login-return">
	<a class="login-returnlink" href="javascript:void(0)" onclick="returntoLogin()">Return to Log in</a>
</div>	
</div>
</div>
<!-------  /register ------------------------------------------->


<!-------  forgetpassword--------------------------------------->
<div id="forgetpassword" class="hidden"> 
<div id="loginform" class="loginpage">
<div class="loginlogo"><img src="images/logo.png">
	<br><br>
	<div class="forget">Forget Password?</div>
</div>		  
<div class="login-email"> 
	<label for="user_login">Your Name</label> <input type="text" name="log" id="forgetfname" class="logininput" value="" size="20" placeholder="FIRST NAME">
</div>
<div class="login-email"> 
	<label for="user_login">Your Name</label> <input type="text" name="log" id="forgetlname" class="logininput" value="" size="20" placeholder="LAST NAME">
</div>
<div class="login-email"> 
	<label for="user_login">Email Address</label> <input type="email" name="log" id="forgetemail" class="logininput" value="" size="20" placeholder="EMAIL ADDRESS"> 
</div>
<div class="login-ext"> 
	<label for="user_login">Extention Number</label> <input type="tel" name="log" id="forgetext" class="logininput" value="" size="20" placeholder="EXTENTION NUMBER"> 
</div>
<p>Enter your email address and extension, your company admin will approve your request. You will then be sent your password after approval. <p>
<br>
<div class="login-submit"> 
	<button id="forgetbutton" class="button-primary" onclick="forgetpassword_onclick()">Send</button>
</div> 
<div class="login-return">
	<a class="login-returnlink" href="javascript:void(0)" onclick="returntoLogin()">Return to Log in</a>
</div>
</div>	
</div> 
<!-------  /forgetpassword ------------------------------------->


<!-------  main  ----------------------------------------------->
<div id="main" class="hidden">

<h2><span id="welcome2">Welcome </span><span id="welcome" style="color:blue">blue</span></h2>

<button id="logout" class="ui-button ui-widget ui-corner-all" style="float: right;" onclick="logout_onclick()">Log Out</button>

<span id="partitionspan">Partition: </span><select id="partition" name="partition" onchange="partition_onchange($(this).val());">
<!-- <option selected>Please choose</option>-->
<!-- <option>Please choose</option>-->
</select>

<select id="user">
	<!-- <option value ="">Please choose</option>-->
</select>

<br><br>


<!-------  tabs  ----------------------------------------------->
<div id="tabs">

<ul>
<li><a href="#tabs-1">Partition Profile</a></li>
<li><a href="#tabs-2">Partition Directory</a></li>
<li><a href="#tabs-3">Call Details</a></li>
<li><a href="#tabs-4">Call Total Min.</a></li>
<!-- <li><a href="#tabs-5">Add User</a></li>-->
</ul>


<!-------  tab 1  ---------------------------------------------->
<div id="tabs-1">
<p>Partition Profile</p>
<fieldset>
<legend>Partition Information</legend>
<label class="box1">Partition ID: </label><label id="partitionid"></label><br>
<label class="box1">Name: </label><input type="text" id="partitionname" name="partitionname" size="45"><br>
<label class="box1">Address: </label><input type="text" id="partitionaddress" name="partitionaddress" size="45"><br>
<label class="box1">Address2: </label><input type="text" id="partitionaddress2" name="partitionaddress2" size="45"><br>
<label class="box1">City: </label><input type="text" id="partitioncity" name="partitioncity" size="45"><br>
<label class="box1">State/Province: </label>
<select name="partitionstate" id="partitionstate">
	<option value="">Select</option>
</select><br>
<label class="box1">Zip/Postal Code: </label><input type="text" id="partitionzip" class="zipcode" name="partitionzip" onblur="validate2(this)" value=""><span id="err-partitionzip" class="errmsg"></span><br>
</fieldset>
<br>
<fieldset>
<legend>Partition Administrator</legend>
<label class="box1">First Name: </label><input type="text" id="padminfname" name="padminfname" size="45"><br>
<label class="box1">Last Name: </label><input type="text" id="padminlname" name="padminlname" size="45"><br>
<label class="box1">Phone: </label><input type="text" id="padminphone" name="padminphone" class="phone" onblur="validate2(this)" size="45"><span id="err-padminphone" class="errmsg"></span><br>
<label class="box1">Email: </label><input type="email" id="padminemail" class="email" name="padminemail" size="45" onblur="validate2(this)"><span id="err-padminemail" class="errmsg"></span><br>
<label class="box1">Support Email: </label><input type="email" id="padminemail2" class="email" name="padminemail2" size="45" onblur="validate2(this)"><span id="err-padminemail2" class="errmsg"></span><br>
</fieldset>
<br>
<button id="savepartitionprofile" class="ui-button ui-widget ui-corner-all" onclick="savePartitionProfile_onclick()">Save</button>
</div>
<!-------  /tab 1  --------------------------------------------->


<!-------  tab 2  ----------------------------------------------> 
<div id="tabs-2">
<p>Partition Directory</p>
<select>
	<option value="LastName">Last Name</option>
  	<option value="FirstName">First Name</option>
  	<option value="PhoneNumber">Phone Number</option>
</select>
<select>
	<option value="StartsWith">Starts With</option>
  	<option value="Contains">Contains</option>
  	<option value="EqualTo">Equal To</option>
</select>
<input type='text' name='PartitionDirectory' value=''>
<input type='submit' name='PartitionDirectory' value='Search'>
</div>
<!-------  /tab 2  ---------------------------------------------> 


<!-------  tab 3  ---------------------------------------------->
<div id="tabs-3">
<fieldset>
<legend>Report</legend>
<label class="box1">Datge range: </label> From: <input type="text" id="fromdatecdr" name="fromdatecdr"> To: <input type="text" id="todatecdr" name="todatecdr"><br>
<label class="box1">Include: </label>
<input type="checkbox" name="cdrcheckbox" id="cdrcheckboxinterstate" value="interstate" checked="checked"> Interstate calls
<input type="checkbox" name="cdrcheckbox" id="cdrcheckboxintrastate" value="intrastate"> Intrastate calls
<input type="checkbox" name="cdrcheckbox" id="cdrcheckboxtollfree" value="tollfree"> Toll Free calls	 
<input type="checkbox" name="cdrcheckbox" id="cdrcheckboxintl" value="intl"> INTL calls
<br>
<label class="box1"></label>
<input type="radio" name="cdrradiobtn" id="cdrradiobtnoutbound" value="O" checked="checked"/> Outbound calls
<input type="radio" name="cdrradiobtn" id="cdrradiobtninbound" value="I"/> Inbound calls
<!-- <input type="radio" name="cdrradiobtn" id="cdrradiobtnboth" value="B"/> Both -->
<br><br>
<label class="box1">Search by number: </label><input id="searchnumber" type="text"><br>
<br>
<button id="createcdr" class="ui-button ui-widget ui-corner-all" onclick="createCDR_onclick()">Create Report</button>
</fieldset>
<br>
<fieldset>
<legend>Export</legend>
<!-- Email to: <input type="text"><button id="exportreportemail" onclick="exportreportemail_onclick()">Submit</button>--> <br>
Export report to CSV: <button id="exportreportcsv" onclick="exportreportcsv_onclick()">Submit</button><br>
<!-- Export report to PDF: <button id="exportreportpdf" onclick="exportreportpdf_onclick()">Submit</button><br> -->
</fieldset>
<br>
<div id="messagediv" style="color:#0000FF"></div>
<br>
<div id="cdrtablediv"></div>
<div id="cdrtablecsvdiv"></div>
</div>
<!-------  /tab 3  --------------------------------------------->


<!-------  tab 4  ---------------------------------------------->
<div id="tabs-4">
<fieldset>
<legend>Report</legend>
<label class="box1">Datge range: </label> From: <input type="text" id="fromdatectm" name="fromdatectm"> To: <input type="text" id="todatectm" name="todatectm"><br>
<label class="box1">Include: </label>

<!-- 
<input id="totalmin" type="checkbox" name="call" value="totalmin" checked> Total Min
<input id="totalmincalltype" type="checkbox" name="call" value="totalmincalltype" checked> Total Min Per Call Type
<input id="totalminusernumber" type="checkbox" name="call" value="totalminusernumber" checked> Total Min Per User Number (Outbound)
<input id="totalminpartition" type="checkbox" name="call" value="totalminpartition" style="display:none;"> <span id="totalminpartitionspan" style="display:none;">Total Min Per Partition</span>
-->

<input type="radio" name="ctmradiobtn" id="ctmradiobtn1" value="1" checked="checked"/> Total Min
<input type="radio" name="ctmradiobtn" id="ctmradiobtn2" value="2"/> Total Min Per Call Type
<input type="radio" name="ctmradiobtn" id="ctmradiobtn3" value="3"/> Total Min Per User Number (Outbound)
<input type="radio" name="ctmradiobtn" id="ctmradiobtn4" value="4"/> <span id="ctmradiobtn4span">Total Min Per Partition (Outbound)</span>


<br>
<label class="box1">Search by number: </label><input id="searchnumberctm" type="text"><br>
<br>
<button id="createctm" class="ui-button ui-widget ui-corner-all" onclick="createCTM_onclick()">Create Report</button>
</fieldset>
<br>
<!--
<fieldset>
<legend>Export</legend>
Email to: <input type="text"><button id="exportreportemail2" onclick="exportreportemail2_onclick()">Submit</button><br>
Export report to CSV: <button id="exportreportcsv2" onclick="exportreportcsv2_onclick()">Submit</button><br>
Export report to PDF: <button id="exportreportpdf2" onclick="exportreportpdf2_onclick()">Submit</button><br>
</fieldset>
<br>
-->
<div id="ctmmessagediv"></div>

<div id="ctmtablediv" style="display:none"></div>
<br>
<div id="ctmtablediv2" style="display:none"></div>
<br>
<div id="ctmtablediv3" style="display:none"></div>
<br>
<div id="ctmtablediv4" style="display:none;"></div>
</div>
<!-------  /tab 4  --------------------------------------------->


<!-------  tab 5  Update User ------------------------------------->
<div id="tabs-5">
<p style="color:blue;"><b>(1) Search User by typing Email Address and press Enter.</b></p>
<p style="color:blue;"><b>(2) Register or Reset Password for the User.</b></p>
<br>
<fieldset>
<legend>Update User</legend>
<br>
<label class="box1">Email Address: </label><input type="email" id="adduseremail" name="adduseremail" size="45" onblur="loaduser()" onkeydown="if (event.keyCode == 13) loaduser()"/><br>
<label class="box1">Extension Number: </label><input style="border:none; font-weight: bold;" type="text" id="adduserext" name="adduserext" readonly/><br>
<label class="box1">First Name: </label><input style="border:none; font-weight: bold;" type="text" id="adduserfname" name="adduserfname" size="45" readonly/><br>
<label class="box1">Last Name: </label><input style="border:none; font-weight: bold;" type="text" id="adduserlname" name="adduserlname" size="45" readonly/><br>
<label class="box1">Phone Number: </label><input style="border:none; font-weight: bold;" id="adduserphone" name="adduserphone" size="45" readonly/><br>
<label class="box1">Register Status: </label><input style="border:none; font-weight: bold;" id="adduserstatus" name="adduserstatus" size="45" readonly/><br>
<label class="box1">Reset Status: </label><input style="border:none; font-weight: bold;" id="adduserreset" name="adduserreset" size="45" readonly/><br>
<label class="box1">User Type: </label><input style="border:none; font-weight: bold;"id="addusertype" name="addusertype" size="45" readonly/><br>
<br>
<!-- <button id="rejectRegisterButton" class="ui-button ui-widget ui-corner-all" onclick="adduser_reset_onclick()">Reset</button>-->

<fieldset>
<legend>Register</legend>
<br>
<label class="box1">Partition: </label><select id="adduserpartition" name="partition"></select><br>
<label class="box1">User Type:</label>
<input type="radio" id="usertyperadioA" name="usertype" value="A" checked="checked"/> Admin
<input type="radio" id="usertyperadioU" name="usertype" value="U"/> User<br>
<!-- <label class="box1">User Status:</label>
<input type="radio" id="userstatusradioA" name="userstatus" value="A" checked="checked"/> Approve
<input type="radio" id="userstatusradioJ" name="userstatus" value="J"/> Reject-->
<br>
<button id="adduserbtn" class="ui-button ui-widget ui-corner-all" onclick="register_onclick('A')">Approve</button>
<button id="adduserbtn2" class="ui-button ui-widget ui-corner-all" onclick="register_onclick('J')">Reject</button>
<button id="rejectRegisterButton" class="ui-button ui-widget ui-corner-all" onclick="adduser_reset_onclick()">Reset</button>
</fieldset>
<br><br>
<fieldset>
<legend>Reset Password</legend>
<br>
<button id="passwordResetApproveButton" class="ui-button ui-widget ui-corner-all" onclick="resetPassword_onclick('Y')">Approve</button>
<button id="passwordResetRejectButton" class="ui-button ui-widget ui-corner-all" onclick="resetPassword_onclick('N')">Reject</button>
</fieldset>

</fieldset>
</div>
<!-------  /tab 5  Update User ------------------------------------>


</div>
<!-------  /tabs  ---------------------------------------------->


</div>
<!------- /main  ----------------------------------------------->

  
<!------- dialogs  --------------------------------------------->

<div id="div-dialog-confirm"></div>

<div id="div-dialog-info"></div>

<div id="div-dialog-error"></div>

<div id="div-dialog-alert"></div>

</body>
</html>


<!-------  OLD login  ---------------------------------------------->
<!-- 
<div id="login" class="hidden">
<h2>Login</h2>
<fieldset>
<legend>Login</legend>
<br>
<label class="box1">Email Address: </label><input type="email" id="loginemail2" class="email" name="loginemail2" size="45" onblur="validate2(this)"><span id="err-loginemail" class="errmsg"></span><br>
<label class="box1">Extension Number: </label><input type="text" id="loginextension2" name="loginextension2" size="45"><br>
<label class="box1">Password: </label><input type="password" id="loginpassword2" name="loginextension2" size="45" onkeydown="if (event.keyCode == 13) document.getElementById('loginbutton').click()"><br>
<br>
<button id="loginbutton" class="ui-button ui-widget ui-corner-all" onclick="login_onclick()">Login</button><br>
<br>
<a href="javascript:void(0)" class="box1" onclick="showRegisterRequest_onclick()">Register Extension</a>
<a href="javascript:void(0)" onclick="showForgetPassword_onclick()">Forget Password</a>
-->
<!-- first time login  -->
<!-- 
<div id="firsttimelogin">
<label class="box1">New Password: </label><input type="password" id="firsttimelogin1" name="firsttimelogin1" size="45"><br>
<label class="box1">Retype New Password: </label><input type="password" id="firsttimelogin2" name="firsttimelogin2" size="45"><br>
<br>
<button id="firsttimeloginbutton" class="ui-button ui-widget ui-corner-all" onclick="firsttimelogin_onclick()">Login</button><br>
</div>
</fieldset>
</div>
-->
<!-------  /OLD login ---------------------------------------------->


<!-------  OLD register  ------------------------------------------->

<!-- 
<div id="OLDregister" class="hidden">
<h2>Register Extension</h2>
<fieldset>
<legend>Register</legend>
<br>
<label class="box1">First Name: </label><input type="text" id="registerfname" class="nonempty" name="registerfname" size="45" onblur="validate2(this)"><span class="errmsg">*</span><span id="err-registerfname" class="errmsg"></span><br>
<label class="box1">Last Name: </label><input type="text" id="registerlname" class="nonempty" name="registerlname" size="45" onblur="validate2(this)"><span class="errmsg">*</span><span id="err-registerlname" class="errmsg"></span><br>
<label class="box1">Email Address: </label><input type="email" id="registeremail" class="email" name="registeremail" size="45" onblur="validate2(this)"><span class="errmsg">*</span><span id="err-registeremail" class="errmsg"></span><br>
<label class="box1">Phone Number: </label><input type="text" id="registerphone" class="phone" name="registerphone" size="45" onblur="validate2(this)"><span class="errmsg">*</span><span id="err-registerphone" class="errmsg"></span><br>
<label class="box1">Extension Number: </label><input type="text" id="registerext" class="extnumber" name="registerext" size="45" onblur="validate2(this)"><span class="errmsg">*</span><span id="err-registerext" class="errmsg"></span><br>

<label class="box1">Partition: </label><select id="registerpartition" name="partition"></select><br>
<label class="box1">Login Type:</label>
<input type="radio" id="registerradioA" name="register" value="A"/> Partition Admin
<input type="radio" id="registerradioU" name="register" value="U" checked="checked"/> User

<br>
<button id="registerbtn" class="ui-button ui-widget ui-corner-all" onclick="registerRequest_onclick()">Send Request</button>
<br><br>
<a href="javascript:void(0)" onclick="returntoLogin()">Return to Log in</a>
</fieldset>
</div>
-->
<!-------  /OLD register ---------------------------------------------->

