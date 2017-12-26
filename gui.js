/***************************************************************
Detail  :       init()
***************************************************************/
function init() {
	//jQuery("#firsttimelogin").hide();
	
	//jQuery("#firsttimelogin1").hide();
	//jQuery("#firsttimelogin2").hide();
	//jQuery("#firsttimeloginbutton").hide();
	
	jQuery("#cdrtablecsvdiv").hide();
	
	jQuery("#login").show();
	jQuery("#tabs-5").addClass('hidden');
	clearAll();
	getState();
	
	jQuery("#fromdatecdr").datepicker({ dateFormat: 'yy-mm-dd' });
	jQuery("#todatecdr").datepicker({ dateFormat: 'yy-mm-dd' });	
	jQuery("#fromdatectm").datepicker({ dateFormat: 'yy-mm-dd' });
	jQuery("#todatectm").datepicker({ dateFormat: 'yy-mm-dd' });
	
	jQuery("#totalmin").prop('checked', true); ctmbitwiseA = 1;
	jQuery("#totalmincalltype").prop('checked', true); ctmbitwiseB = 1;
	jQuery("#totalminusernumber").prop('checked', false); ctmbitwiseC = 0;
	jQuery("#totalminpartition").prop('checked', false); ctmbitwiseD = 0;
	
	jQuery("#tabs").tabs({ active:0 });
	
	//jQuery("#tabs").tabs();
	// calling a function when a tab is selected
	jQuery("#tabs").tabs({
	    activate: function(event, ui) {
	   	 //reset partition select
	       if (ui.newPanel.selector == "#tabs-5") {
	      	 //$('#partition').val(1);
	      	 jQuery("#partitionspan").hide();
	      	 jQuery("#partition").hide();
	      	 jQuery("#user").hide();
	       }
	       else {
	      	 jQuery("#partitionspan").show();
	      	 jQuery("#partition").show();
	      	 jQuery("#user").show();
	       }
	    }
	});
	
	//Update User tab (tab 5)
	jQuery("#usertyperadioA").prop('checked', true);
	jQuery("#usertyperadioA").prop('disabled', true);
	jQuery("#usertyperadioU").prop('disabled', true);
	jQuery("#adduserpartition").prop('disabled', true);
	jQuery("#adduserbtn").button({ disabled: true });
	jQuery("#adduserbtn2").button({ disabled: true });
	jQuery("#passwordResetApproveButton").button({ disabled: true });
	jQuery("#passwordResetRejectButton").button({ disabled: true });
}

/*
function init2() {
	userType = "S"; partition = "All";
	getPartition(userType, partition);
	jQuery("#login").addClass('hidden');
	clearAll();
	getState();
	jQuery("#main").removeClass('hidden');
	
	$('#tabs').addTab("Add User");

}
*/

/***************************************************************
Detail  :       clear DOM
***************************************************************/
function clearAll(){
	//jQuery("#login input").val("");
	//jQuery("#login span").val("");
	//jQuery("#login span").remove();
	//jQuery("#register input").val("");
	jQuery("#tabs-1 input").val("");
	jQuery("#tabs-2 input").val("");
	jQuery("#tabs-3 input").val("");
	jQuery("#tabs-4 input").val("");
	jQuery("#tabs-5 input").val("");
	jQuery("#partition").empty();
	jQuery("#user").empty();
}

/***************************************************************
Detail  :       Logout
***************************************************************/
function logout_onclick(){
	//jQuery("#main").addClass('hidden');
	//init();
	location.reload();
}

/***************************************************************
Detail  :       login 
***************************************************************/
function login_onclick() {
	console.info(arguments.callee.name + " --> ");
	
	var email = jQuery("#loginemail").val();
	var ext = jQuery("#loginextension").val();
	var password = jQuery("#loginpassword").val();
	
	console.log("%s;%s;%s", email,ext,password);
	console.log("calling login.php");
	
	jQuery.ajax({
		url: "php/login.php",
		data: { email: email, ext: ext, password: password },
		type: "post",
		dataType: 'json',
		success: function(result, textStatus) {
			//console.log("result=" + result);
			console.log("ret=" + result.ret);
			
			if ( result.ret == 1 ) {			
				jQuery("#login").hide();
				name = result.fname + " " + result.lname;
				jQuery("#welcome").html(name);
				jQuery("#main").removeClass('hidden');
								
				if ( result.usertype == "U" ) {	
					// User
					var partition = result.partitionname;
					var user = result.ext;
					$("<option value='" + partition + "'>" + partition + "</option>").appendTo("#partition");
					$("<option value='" + user + "'>" + user + "</option>").appendTo("#user");		
					getPartitionInfo(partition);
					getPartitionAdminInfo(partition);
				}
				else if ( result.usertype == "S" ){
					// SAdmin user
					jQuery("#partition").append("<option value=''>Please choose</option>");
					jQuery("#partition").append("<option value='All'>All</option>");
					getPartition(result.usertype, result.partitionname);
					jQuery("#user").append("<option>Please choose</option>");
					jQuery("#user").prop("disabled", true);
					$('#tabs').addTab("Update User");
					// button disable until SAdmin select a partition
					jQuery("#createcdr").button({ disabled: true });
					jQuery("#createctm").button({ disabled: true });
				}			
				else {
					// Admin user
					var partition = result.partitionname;
					var user = result.ext;
					getPartitionInfo(partition); 
					getPartitionAdminInfo(partition); 
					$("<option value='" + partition + "'>" + partition + "</option>").appendTo("#partition");
					jQuery("#user").append("<option value=''>Please choose</option>");
					jQuery("#user").append("<option value='All'>All</option>");
					getTN(partition);
				}			
			}
			// first time login
			else if ( result.ret == 2 ) {
				jQuery("#loginbutton").hide();
				//jQuery("#firsttimelogin").show();
				jQuery("#firsttimelogin").removeClass('hidden');
				
				
				//jQuery("#firsttimelogin1").show();
				//jQuery("#firsttimelogin2").show();
				//jQuery("#firsttimeloginbutton").show();
			}
			else if ( result.ret == -1 ) {
				errdialog("Error message", "Email address not exist !!!");
			}
			else if ( result.ret == -2 ) {
				errdialog("Error message", "Password is incorrect !!!");
			}
			else if ( result.ret == -3 ) {
				errdialog("Error message", "Extension is incorrect !!!");
			}
			else if ( result.ret == -4 ) {
				errdialog("Error message", "Account is not active !!!");
			}
			else if ( result.ret == -5 ) {
				errdialog("Error message", "User T  ype is incorrect !!!");
			}
			else {
				alert("Unknow ERROR!!!")
			}
		},
		error: function() {
			alert('Not OKay');
		}
	});
}

/***************************************************************
Detail  :       first time login 
***************************************************************/
function firsttimelogin_onclick(email) {
	// login for first time with new password
	var email = jQuery("#loginemail").val();
	var password1 = jQuery("#firsttimelogin1").val();
	var password2 = jQuery("#firsttimelogin2").val();
	if ( password1 == password2 ){
		jQuery.ajax({
			url: "php/savePassword.php",
			data: { email: email,
					password: password1},
			type: "post",
			dataType: "json",
			success: function(result, textStatus) {
				console.log(result);
				if ( result.ret == 1 ) {
					infodialog("Info message", result.msg);
					jQuery("#login").hide();
					name = result.fname + " " + result.lname;
					jQuery("#welcome").html(name);
					jQuery("#main").removeClass('hidden');
					
					// User
					if ( result.usertype == "U" ) {	
						var partition = result.partitionname;
						var user = result.ext;
						$("<option value='" + partition + "'>" + partition + "</option>").appendTo("#partition");
						$("<option value='" + user + "'>" + user + "</option>").appendTo("#user");	
						getPartitionInfo(partition);
						getPartitionAdminInfo(partition);	
					}
					// SAdmin user
					else if ( result.usertype == "S" ) {
						jQuery("#partition").append("<option>Please choose</option>");
						jQuery("#partition").append("<option value='All'>All</option>");
						getPartition(result.usertype, result.partitionname);
						jQuery("#user").append("<option>Please choose</option>");
						jQuery("#user").prop("disabled", true);
						$('#tabs').addTab("Update User");
					}
					// Admin user
					else {
						var partition = result.partitionname;
						var user = result.ext;
						getPartitionInfo(partition); 
						getPartitionAdminInfo(partition); 
						$("<option value='" + partition + "'>" + partition + "</option>").appendTo("#partition");
						jQuery("#user").append("<option value=''>Please choose</option>");
						jQuery("#user").append("<option value='All'>All</option>");
						getTN(partition);
					}
				}
				else {
					errdialog("Error message", result.msg);
					return false;
				}
			},
			error: function() {
				alert('Not OKay');
			}
		});	
	}
	else {
		jQuery("#firsttimelogin1").val('');
		jQuery("#firsttimelogin2").val('');
		alertdialog("Alert", "Passwords do not matched.");
		return false;
	}	
	//if ( jQuery("#firsttimelogin1").is(":visible") ) {
}

/***************************************************************
Detail  :       Show Register div
***************************************************************/
function showRegisterRequest_onclick() {
	console.log(arguments.callee.name + " --> ");
	
	//jQuery("#login").addClass('hidden');
	jQuery("#login").hide();
	jQuery("#register").removeClass('hidden');
	//get list of partition names for regsiter request
	//getPartitionForRegister();
}

/***************************************************************
Detail  :       Register Request
***************************************************************/
function registerRequest_onclick() {	
	console.log(arguments.callee.name + " --> ");
	
	/*
   $("input").each(function() {
   	console.log("each input called");
   	if(!$(this).val()){
           alert('Some fields are empty');
           //errdialog("Error message", "Some fields are empty !!!");
          return false;
       }
   });
   */
	
	//getPartitionForRegister();
	
	var fname = jQuery("#registerfname").val();
	var lname = jQuery("#registerlname").val();
	var email = jQuery("#registeremail").val();
	var ext = jQuery("#registerext").val();
	var status = "R";
	
	/*
	var partitionname = jQuery("#registerpartition").val();
	var usertype;
	if ( $("#registerradioA").prop("checked") )  {
		usertype = "A"
	}
	else {
		usertype = "U"
	}
	*/
	
	console.log(arguments.callee.name + " --> " + fname + lname + email + ext + status );
	
// add user with status=R
	jQuery.ajax({
		url: "php/registerRequest.php",
		data: { fname: fname,
				  lname: lname,
				  email: email,
				  ext: ext,
				  status: status},
		type: "post",
		dataType: "json",
		success: function(result, status) {
			console.log(result);
			if ( result.ret == 1 ) {
				infodialog("Info message", result.msg);
			}
			else if ( result.ret == 0 ){
				alertdialog("Alert message", result.msg);
			}
			else {
				errdialog("Error message", result.msg);
			}

			
			
			/*
			// if user added then send email
			if ( result.ret == 1 ) {
				jQuery.ajax({
					url: "php/sendmailController.php",
					data: { userFname: fname,
							  userLname: lname,
							  userEmail: email,
							  userExt: ext,
							  serviceType: "RegisterRequest"},
					type: "post",
					dataType: "json",
					success: function(result, status) {
						//console.log(result);
						infodialog("Info message", result.msg);
					},
					error: function() {
						alert("Not OKay status:" +  status);
					}
				});
			}
			else if ( result.ret == 0 ){
				//alertdialog("Alert message", "Unable tooo register user.");
				alertdialog("Alert message", result.msg);
			}
			else {
				errdialog("Error message", result.msg);
			}
			*/
			
		},
		error: function() {
			alert("Not OKay status:" +  status);
		}
	});
	
}


// validate input notempty for register request form
/*
$(document).ready(function() {
	$( "#registerbtn" ).button({
		 disabled: true
	});

	//$( "#registerfname" ).keyup(function() {
	$( "#registerfname, #registerlname , #registeremail, #registerphone, #registerext" ).blur(function() {
		console.log(this.id + " " + $(this).val() );
		if(allFilled()) {
			$( "#registerbtn" ).button({
				disabled: false
			});	
		}
		else {
			$( "#registerbtn" ).button({
				disabled: true
			});	
		}
	});
});

function allFilled() {
	//console.log(arguments.callee.name + " --> ");
   var filled = true;
   $('#OLDregister input').each(function() {
   	//console.log($(this).val());
   	if($(this).val() == '') {	 
   		filled = false;
   		console.log(arguments.callee.name + " --> " + filled + " " + $(this).val() );
   	}
   });
   return filled;
}
*/


/***************************************************************
Detail  :       Forget Password
***************************************************************/
function forgetpassword_onclick() {	
	console.log(arguments.callee.name + " --> ");
	
	var fname = jQuery("#forgetfname").val();
	var lname = jQuery("#forgetlname").val();
	var email = jQuery("#forgetemail").val();
	var ext = jQuery("#forgetext").val();
		
	console.log(arguments.callee.name + " --> " + "%s;%s;%s;%s", forgetfname,forgetlname,forgetemail,forgetext);

	
	jQuery.ajax({
		url: "php/resetPasswordRequest.php",
		data: { fname: fname,
				  lname: lname,
				  email: email,
				  ext: ext},
		type: "post",
		dataType: "json",
		success: function(result, status) {
			console.log(result);
			//infodialog("Info message", result.msg);
							
			// if user added then send email
			if ( result.ret == 1 ) {
	
	
				jQuery.ajax({
					url: "php/sendmailController.php",
					data: { userFname: fname,
							  userLname: lname,
							  userEmail: email,
							  userExt: ext,
							  serviceType: "ResetRequest"},
					type: "post",
					dataType: "json",
					success: function(result, status) {
						//console.log(result);
						infodialog("Info message", result.msg);	
					},
					error: function() {
						alert("Not OKay status:" +  status);
					}
				});
	
			}
			else if ( result.ret == 0 ){
				//alertdialog("Alert message", "Unable tooo register user.");
				alertdialog("Alert message", result.msg);
			}
			else {
				errdialog("Error message", result.msg);
			}
			
			
		},
		error: function() {
			alert("Not OKay status:" +  status);
		}
	});
	
	
	
	
}

/***************************************************************
Detail  :       Get Partition for Register Request
***************************************************************/
function getPartitionForRegister() {
	console.log(arguments.callee.name + " --> ");

	// need to get full list of partition names
	userType = "S";
	partition = "All";

	jQuery.ajax({
		url: "php/getPartition.php",
		data: { userType: userType, partition: partition },
		type: "post",
		success: function(result, textStatus) {
			//console.log(result);	
			jQuery("#registerpartition").append(result);	
		},
		error: function() {
			alert('Not OKay');
		}
	});
}

/***************************************************************
Detail  :       Send Register Request
***************************************************************/
function returntoLogin() {
	jQuery("#register").addClass('hidden');
	jQuery("#OLDregister").addClass('hidden');
	jQuery("#forgetpassword").addClass('hidden');
	//jQuery("#login").removeClass('hidden');
	jQuery("#login").show();
}


/***************************************************************
Detail  :       Load User will be remove
***************************************************************/
function loaduser() {
	console.log(arguments.callee.name + " --> ");
	
	var email = jQuery("#adduseremail").val();
	var ext = jQuery("#adduserext").val();
	
	//jQuery("#adduseremail").val("");
	jQuery("#adduserext").val("");
	jQuery("#adduserfname").val("");
	jQuery("#adduserlname").val("");
	jQuery("#adduserphone").val("");
	jQuery("#adduserstatus").val("");
	jQuery("#addusertype").val("");
	jQuery("#adduserpartition").val($("#adduserpartition option:first").val());	
	jQuery("#usertyperadioA").prop('checked', true);
	jQuery("#userstatusradioA").prop('checked', true);	
	
	if ( ! validateEmail(email) ) {
		alertdialog("Alert", "Incorrect Email Address.");
		return false;
	}
	
	/*
	if ( ! validateExtension(ext) ) {
		alertdialog("Alert", "Incorrect Extension Number.");
		return false;
	}
	*/
	
	jQuery.ajax({
		url: "php/loaduser.php",
		data: { email: email},
		dataType: 'json',
		type: "post",
		success: function(result, textStatus) {
			if ( result.ret == 1 ) {
				console.log(result.data);
				jQuery("#adduserext").val(result.data.ext);
				jQuery("#adduserfname").val(result.data.fname);
				jQuery("#adduserlname").val(result.data.lname);
				jQuery("#adduserphone").val(result.data.phone);	
		
				jQuery("#usertyperadioA").prop('disabled', false);
				jQuery("#usertyperadioU").prop('disabled', false);
				jQuery("#adduserpartition").prop('disabled', false);
				jQuery("#adduserbtn").button({ disabled: false });
				jQuery("#adduserbtn2").button({ disabled: false });
				
				if ( result.data.reset == "R" )  {
				jQuery("#passwordResetApproveButton").button({ disabled: false });
				jQuery("#passwordResetRejectButton").button({ disabled: false });
				}
				
				if (result.data.status == "R") { jQuery("#adduserstatus").val("Requested"); }
				else if (result.data.status == "A") { jQuery("#adduserstatus").val("Approved"); }
				else if (result.data.status == "J") { jQuery("#adduserstatus").val("Rejected"); }
				else if (result.data.status == "X") { jQuery("#adduserstatus").val("Disabled"); }
				
				if (result.data.reset == "R") { jQuery("#adduserreset").val("Requested"); }
				else if (result.data.reset == "A") { jQuery("#adduserreset").val("Approved"); }
				else if (result.data.reset == "J") { jQuery("#adduserreset").val("Rejected"); }
				
				if (result.data.usertype == "S") { jQuery("#addusertype").val("Super"); }
				else if (result.data.usertype == "A") { jQuery("#addusertype").val("Admin"); }
				else if (result.data.usertype == "U") { jQuery("#addusertype").val("User"); }
				
				//jQuery("#adduserstatus").val(result.data.status);
				//jQuery("#addusertype").val(result.data.usertype);
			}
			else if ( result.ret == -1 ){
				alertdialog("Alert message", result.msg);
				jQuery("#usertyperadioA").prop('disabled', true);
				jQuery("#usertyperadioU").prop('disabled', true);
				jQuery("#adduserpartition").prop('disabled', true);
				jQuery("#adduserbtn").button({ disabled: true });
				jQuery("#adduserbtn2").button({ disabled: true });
				jQuery("#passwordResetApproveButton").button({ disabled: true });
				jQuery("#passwordResetRejectButton").button({ disabled: true });
			}
			else {
				errdialog("Error message", result.msg);
				jQuery("#usertyperadioA").prop('disabled', true);
				jQuery("#usertyperadioU").prop('disabled', true);
				jQuery("#adduserpartition").prop('disabled', true);
				jQuery("#adduserbtn").button({ disabled: false });
				jQuery("#adduserbtn2").button({ disabled: false });
				jQuery("#passwordResetApproveButton").button({ disabled: true });
				jQuery("#passwordResetRejectButton").button({ disabled: true });
			}
		},
		error: function() {
			alert('Not OKay');
		}
	});
	
	
}

/*
function loadPartitionInfo(result) {
	var obj = jQuery.parseJSON(result);
	//console.log(obj[0].state);
	//console.log(obj[0].name);
	jQuery("#partitionid").text(obj[0].partitionid);
	jQuery("#partitionname").val(obj[0].name);
	jQuery("#partitionaddress").val(obj[0].address);
	jQuery("#partitionaddress2").val(obj[0].address2);
	jQuery("#partitioncity").val(obj[0].city);
	jQuery("#partitionstate").val(obj[0].state);
	jQuery("#partitionzip").val(obj[0].zipcode);
	
	jQuery("#padminfname").val(obj[0].adminfname);
	jQuery("#padminlname").val(obj[0].adminlname);
	jQuery("#padminphone").val(obj[0].adminphone);
	jQuery("#padminemail").val(obj[0].adminemail);
	jQuery("#padminemail2").val(obj[0].adminemail2);
}
*/

/***************************************************************
Detail  :       Register User
***************************************************************/
function register_onclick(userstatus) {	
	console.log(arguments.callee.name + " --> " + "%s", userstatus);
	
	var email = jQuery("#adduseremail").val();
	var ext = jQuery("#adduserext").val();
	var fname = jQuery("#adduserfname").val();
	var lname = jQuery("#adduserlname").val();
	var partitionname = jQuery("#adduserpartition").val();
	
	var usertype;
	if ( $("#usertyperadioA").prop("checked") )  {
		usertype = "A"
	}
	else {
		usertype = "U"
	}
	
	/*
	var userstatus;
	if ( $("#userstatusradioA").prop("checked") )  {
		userstatus = "A"
	}
	else {
		userstatus = "J"
	}
	*/
	
	//var usertype = $('input:radio[name=usertype]:checked').val();
	//var usertype = $('input[name=usertype]:radio:checked').val();
	
	console.log(arguments.callee.name + " --> " + email + " " + partitionname + " " + usertype + " " + userstatus + " " + fname + " " + lname + " " + ext);

	/*
	if (email == "") {
		//alert("Please fill all required fields.");
		alertdialog("Alert", "Please fill all required fields.");
		return false;
	}
	*/
	
	if ( userstatus == "J" ) {
		msg = "Are you sure you want to Reject register request?"
	} else if ( userstatus == "A" ) {
		msg = "Are you sure you want to Approve register request?"
	}
	//msg = "Are you sure about your selection?"
	
	$('#div-dialog-confirm').html( "<p><span class='ui-icon ui-icon-circle-check' style='float:left; margin:0 7px 20px 0;'></span>" + msg + "</p>" );
	$("#div-dialog-confirm").dialog({
		modal: true,
		resizable: false,
      //height: "auto",
      //width: 400,
		title: "Confirmation",
		dialogClass: 'alertTitleClass',
		buttons: {
			"Yes": function() {
				ret = 1;
				console.log("Close selected ret =" + ret);
				$(this).dialog("close");
				
				jQuery.ajax({
					url: "php/register.php",
					data: { status: userstatus,
							email: email,
							partitionname: partitionname,
							usertype: usertype},
					dataType: 'json',
					type: "post",
					success: function(result, textStatus) {
						console.log(result);
						if ( result.ret == 1 ) {
							//password = result.password;
							infodialog("Info message", result.msg);
							loaduser();
							//sendmailController(fname,lname,email,ext,password,userstatus)
							console.log("infomessage");
						}
						else if ( result.ret == -1 ){
							alertdialog("Alert message", result.msg);
						}
						else {
							errdialog("Error message", result.msg);
						}
						//var obj = jQuery.parseJSON(result);
						//console.log(obj[0].zipcode);
						//loadPartitionInfo(result);
					},
					error: function() {
						alert('Not OKay');
					}
				}); 	
				
			},
			"Cancel": function() {
				ret = 0;
				console.log("Cancel selected ret =" + ret);
				$(this).dialog("close");
			},
		}	
		
	});
	
}

/***************************************************************
Detail  :       Reset Add User
***************************************************************/
function adduser_reset_onclick() {	
	console.log(arguments.callee.name + " --> ");
	
	/*
	jQuery("#adduseremail").val("");
	jQuery("#adduserext").val("");
	jQuery("#adduserfname").val("");
	jQuery("#adduserlname").val("");
	jQuery("#adduserphone").val("");
	jQuery("#adduserstatus").val("");
	jQuery("#addusertype").val("");
	*/
	jQuery("#adduseremail").val("");
	jQuery("#tabs-5").find("input:text").val("");
	
	jQuery("#adduserpartition").val($("#adduserpartition option:first").val());	
	jQuery("#usertyperadioA").prop('checked', true);
	jQuery("#userstatusradioA").prop('checked', true);	
}


/***************************************************************
Detail  :       Reset Password
***************************************************************/
function resetPassword_onclick(reset) {	
	console.log(arguments.callee.name + " --> " + "%s", reset);
	
	var email = jQuery("#adduseremail").val();
	var ext = jQuery("#adduserext").val();
	var fname = jQuery("#adduserfname").val();
	var lname = jQuery("#adduserlname").val();
	var userstatus = jQuery("#adduserstatus").val();

	if ( reset == "N" ) {
		msg = "Are you sure you want to Reject reset password request?"
	} else if ( reset == "Y" ) {
		msg = "Are you sure you want to Approve reset password request?"
	}
	//msg = "Are you sure about your selection?"
	
	$('#div-dialog-confirm').html( "<p><span class='ui-icon ui-icon-circle-check' style='float:left; margin:0 7px 20px 0;'></span>" + msg + "</p>" );
	$("#div-dialog-confirm").dialog({
		modal: true,
		resizable: false,
      //height: "auto",
      //width: 400,
		title: "Confirmation",
		dialogClass: 'alertTitleClass',
		buttons: {
			"Yes": function() {
				ret = 1;
				console.log("Close selected ret =" + ret);
				$(this).dialog("close");
				
				jQuery.ajax({
					url: "php/resetPassword.php",
					data: { email: email,
							ext: ext,
							reset: reset },
					dataType: 'json',
					type: "post",
					success: function(result, textStatus) {
						console.log(result);
						if ( result.ret == 1 ) {
							password = result.password;
							infodialog("Info message", result.msg);
							loaduser();
							//sendmailController(fname,lname,email,ext,password,userstatus)
							console.log("infomessage");
						}
						else if ( result.ret == 0 ){
							alertdialog("Alert message", result.msg);
						}
						else {
							errdialog("Error message", result.msg);
						}
						//var obj = jQuery.parseJSON(result);
						//console.log(obj[0].zipcode);
						//loadPartitionInfo(result);
					},
					error: function() {
						alert('Not OKay');
					}
				}); 	
				
			},
			"Cancel": function() {
				ret = 0;
				console.log("Cancel selected ret =" + ret);
				$(this).dialog("close");
			},
		}	
		
	});	
	
}


/***************************************************************
Detail  :       Send Register Approval
***************************************************************/
function sendmailController(fname,lname,email,ext,password,userstatus) {
	console.log(arguments.callee.name + " --> " + fname+lname+email+ext+password+userstatus);
	
	if ( userstatus == "A" ) {
		serviceType = "RegisterApproval";
	}
	else if ( userstatus == "J" ) {
		serviceType = "RegisterReject";
	}
	
	jQuery.ajax({
		url: "php/sendmailController.php",
		data: { userFname: fname,
				  userLname: lname,
				  userEmail: email,
				  userExt: ext,
				  userPassword: password,
				  serviceType: serviceType},
		type: "post",
		dataType: "json",
		success: function(result, status) {
			//console.log(result);
			infodialog("Info message", result.msg);
		},
		error: function() {
			alert("Not OKay status:" +  status);
		}
	});
}

/***************************************************************
Detail  :       Reject User
***************************************************************/
function rejectRegister_onclick() {
	alert("in process");
}

/***************************************************************
Detail  :       Forget Password
***************************************************************/
function showForgetPassword_onclick() {
	console.log(arguments.callee.name + " --> ");
	
	//jQuery("#login").addClass('hidden');
	jQuery("#login").hide();
	jQuery("#forgetpassword").removeClass('hidden');
}

/***************************************************************
Detail  :       Get Partitions
***************************************************************/
function getPartition(userType, partition) {
	console.log(arguments.callee.name + " --> ");
	//console.log(arguments.callee.name + " --> " + "userType=" + userType + " partition=" + partition);
	
	//if ( userType == "S") {
	//	$('#tabs').addTab("Add User");
	//}

	jQuery.ajax({
		url: "php/getPartition.php",
		data: { userType: userType, partition: partition },
		type: "post",
		success: function(result, textStatus) {
			//console.log(result);	
			jQuery("#partition").append(result);
			jQuery("#adduserpartition").append(result);
			jQuery("#registerpartition").append(result);
			
			//jQuery("#user").append("<option>Please choose</option>");
			//jQuery("#user").prop("disabled", true);			
		},
		error: function() {
			alert('Not OKay');
		}
	});
}

/***************************************************************
Detail  :       Extract TNs for selected partition
***************************************************************/
function partition_onchange(partition) {
	// only SAdmin user calls this function
	console.log(arguments.callee.name + " --> " + "partition=" + partition);
	
	getPartitionInfo(partition); 
	getPartitionAdminInfo(partition); 
	
	if ( partition == "" ) {
		//jQuery("#createcdr").prop("disabled", true);
		jQuery("#createcdr").button({ disabled: true });
		jQuery("#createctm").button({ disabled: true });
	}
	else {
		//jQuery("#createcdr").prop("disabled", false);
		jQuery("#createcdr").button({ disabled: false });
		jQuery("#createctm").button({ disabled: false });
	}
	
	if ( partition != "All" ) {
		jQuery("#user").prop("disabled", false);
		$("#user").find("option").remove();
		jQuery("#user").append("<option value='All'>All</option>");
		//jQuery("#user").val('1');
		
		// if partition selected, uncheck check box and hide
		jQuery("#totalminpartition").attr('checked', false);
		jQuery("#totalminpartition").hide();
		jQuery("#totalminpartitionspan").hide();
		jQuery("#ctmradiobtn4").attr('checked', false);
		jQuery("#ctmradiobtn4span").hide();
		jQuery("#ctmradiobtn4").hide();
		
	}
	else {
		$("#user").find("option").remove();
		jQuery("#user").append("<option value=''>Please choose</option>");
		jQuery("#user").prop("disabled", true);
		
		jQuery("#totalminpartition").show();
		jQuery("#totalminpartitionspan").show();
		jQuery("#ctmradiobtn4").show();
		jQuery("#ctmradiobtn4span").show();
	}	
	getTN(partition);
}

/***************************************************************
Detail  :       Get TNs for selected partition
***************************************************************/
function getTN(partition) {
	console.log(arguments.callee.name + " --> ");
	//console.log(arguments.callee.name + " --> " + "partition=" + partition);	
	
	jQuery.ajax({
		url: "php/getTN.php",
		data: { choice: partition },
		type: "post",
		success: function(result, textStatus) {
			//console.log(result);
			jQuery.each( $.parseJSON(result), function( key, value ) {
				//$("<option value='" + value + "'>" + value + "</option>").appendTo("#user");
				$("<option value='" + value[0] + "'>" + value[0] + " (" + value[1] + ")" + "</option>").appendTo("#user");
			});
		},
		error: function() {
			alert('Not OKay');
		}
	});
}
/***************************************************************
Detail  :       Extract users for selected partition
***************************************************************/
/*
$(document).ready(function(){
	$("#partition").change(function(){
		console.log( "select value ==> " +  $("#partition").val() );
		
		if ( $("#partition").val() == "" || $("#partition").val() == "All" ) {
			jQuery("#user").prop("disabled", true);
		}
		else {
			jQuery("#user").prop("disabled", false);
			
			$.post("php/getTN.php", {choice: $("#partition").val()}, function(data){
				//alert("received: "+data);
			    $("#user").find("option").remove();
			    //jQuery("#user").append("<option>Please choose</option>");
			    jQuery("#user").append("<option value='All'>All</option>");
			    $.each($.parseJSON(data), function(key,value){
			    	//alert(value);
			    	$("<option value='" + value + "'>" + value + "</option>").appendTo("#user");
			    });
			});
			
			
		}
		
	});
});
*/

/***************************************************************
Detail  :       Get Partition Info
***************************************************************/
function getPartitionInfo(partition) {
	console.log(arguments.callee.name + " --> ");
	//console.log(arguments.callee.name + " --> " + "partition=" + partition);
	
	jQuery.ajax({
		url: "php/getPartitionInfo.php",
		data: { partition: partition },
		type: "post",
		success: function(result, textStatus) {
			//console.log(result);
			//var obj = jQuery.parseJSON(result);
			//console.log(obj[0].zipcode);
			loadPartitionInfo(result);
		},
		error: function() {
			alert('Not OKay');
		}
	});
	//jQuery('#tabs-1 div').html('');
}

function loadPartitionInfo(result) {
	var obj = jQuery.parseJSON(result);
	//console.log(obj[0].state);
	//console.log(obj[0].name);
	jQuery("#partitionid").text(obj[0].partitionid);
	jQuery("#partitionname").val(obj[0].name);
	jQuery("#partitionaddress").val(obj[0].address);
	jQuery("#partitionaddress2").val(obj[0].address2);
	jQuery("#partitioncity").val(obj[0].city);
	jQuery("#partitionstate").val(obj[0].state);
	jQuery("#partitionzip").val(obj[0].zipcode);
	
	jQuery("#padminfname").val(obj[0].adminfname);
	jQuery("#padminlname").val(obj[0].adminlname);
	jQuery("#padminphone").val(obj[0].adminphone);
	jQuery("#padminemail").val(obj[0].adminemail);
	jQuery("#padminemail2").val(obj[0].adminemail2);
}



function getPartitionAdminInfo(partition) {
	console.log(arguments.callee.name + " --> ");
	//console.log("getPartitionAdminInfo " + partition);
	
	jQuery.ajax({
		url: "php/getPartitionAdminInfo.php",
		data: { partitionname: partition },
		type: "post",
		success: function(result, textStatus) {
			//console.log(result);
			loadPartitionAdminInfo(result);
		},
		error: function() {
			alert('Not OKay');
		}
	});
}

function loadPartitionAdminInfo(result) {
	var obj = jQuery.parseJSON(result);
	//console.log(obj[0].state);
	//console.log(obj[0].name);
	jQuery("#padminfname").val(obj[0].fname);
	jQuery("#padminlname").val(obj[0].lname);
	jQuery("#padminphone").val(obj[0].phone);
	jQuery("#padminemail").val(obj[0].email);
	jQuery("#padminemail2").val(obj[0].email2);
}

/***************************************************************
Detail  :       Save Partition Info
***************************************************************/
function savePartitionProfile_onclick() {
	console.log(arguments.callee.name + " --> ");
	
	var partitionid = jQuery("#partitionid").text();
	var name = jQuery("#partitionname").val();
	var address = jQuery("#partitionaddress").val();
	var address2 = jQuery("#partitionaddress2").val();
	var city = jQuery("#partitioncity").val();
	var state = jQuery("#partitionstate").val();
	var zipcode = jQuery("#partitionzip").val();
	
	var padminfname = jQuery("#padminfname").val();
	var padminlname = jQuery("#padminlname").val();
	var padminphone = jQuery("#padminphone").val();
	var padminemail = jQuery("#padminemail").val();
	var padminemail2 = jQuery("#padminemail2").val();
	
	console.log(zipcode);
	
	console.log("calling savePartitionInfo.php");
	
	jQuery.ajax({
		url: "php/savePartitionInfo.php",
		data: { partitionid: partitionid,
				  name: name,
				  address: address,
				  address2: address2,
				  city: city,
				  state: state,
				  zipcode: zipcode,
				  padminfname: padminfname,
				  padminlname: padminlname,
				  padminphone: padminphone,
				  padminemail: padminemail,
				  padminemail2: padminemail2 },
		type: "post",
		success: function(result, textStatus) {
			console.log("here");
			//var obj = jQuery.parseJSON(result);
			//console.log(obj[0].zipcode);
			//loadPartitionInfo(result);
		},
		error: function() {
			alert('Not OKay');
		}
	});	
	
}

/***************************************************************
Detail  :       Extract States
***************************************************************/
function getState() {
	//alert("state");
	jQuery.ajax({
		url: "php/getState.php",
		data: {},
		type: "post",
		success: function(result, textStatus) {
			//console.log(result);	
			jQuery("#partitionstate").append(result);
		},
		error: function() {
			alert('Not OKay');
		}
	});
}

/***************************************************************
Detail  :       Create report
***************************************************************/
function createCDR_onclick() {
	console.log(arguments.callee.name + " --> ");
	
	//console.log(jQuery("#partition").val());

	var arr = [];  

	if ( $("#cdrcheckboxintl").prop("checked") )  {
		arr.push("INTL");
	}
	if ( $("#cdrcheckboxinterstate").prop("checked") )  {
		arr.push("INTERSTATE");
	}
	if ( $("#cdrcheckboxintrastate").prop("checked") )  {
		arr.push("INTRASTATE");
	}
	if ( $("#cdrcheckboxtollfree").prop("checked") )  {
		arr.push("TOLLFREE");
	}
	var callTypeSelected = arr.map(function(id) { return "'" + id + "'"; }).join(", ");
	
	
	var callDirectionSelected;
	if ( $("#cdrradiobtnoutbound").prop("checked") )  {
		callDirectionSelected = "O"
	}
	else if ( $("#cdrradiobtninbound").prop("checked") )  {
		callDirectionSelected = "I"
	}
	else {
		callDirectionSelected  = "";
	}
	
	//var callDirectionSelected = arr.map(function(id) { return "'" + id + "'"; }).join(", ");
	// need to check why not working !!!!
	var callDirectionSelected2 = $('input:radio[name=cdrradiobtn]:checked').val();
		
	console.log(arguments.callee.name + " --> " + "callTypeSelected=" + callTypeSelected);
	console.log(arguments.callee.name + " --> " + "callDirectionSelected=" + callDirectionSelected);
   
	jQuery("body").addClass("waiting");
	
	$partition = jQuery("#partition").val();
	$user = jQuery("#user").val();
	//console.log(arguments.callee.name + " --> " + "partition=" + $partition + " user=" + $user);
	$number = jQuery("#searchnumber").val();
	$fromdate = jQuery("#fromdatecdr").val();
	$todate = jQuery("#todatecdr").val();
	jQuery.ajax({
		url: "php/getCDR.php",
		data: { partition: $partition,
			    user: $user,
			    fromdate: $fromdate,
			    todate: $todate,
			    number: $number,
			    callTypeSelected: callTypeSelected,
			    callDirectionSelected: callDirectionSelected
			  },
		type: "post",
		dataType: 'json',
		success: function(result, textStatus) {
			jQuery("body").removeClass("waiting");
			tablestring = "<table id='cdrtable' class='display'>";
			tablestring = tablestring + result.data;
			console.log(tablestring);
			csvstring = "<table id='cdrtablecsv'>";
			csvstring = csvstring + result.data;
			//console.log(result.data);	
			//console.log(result.count);
			jQuery("#messagediv").text("");
			//jQuery("#searchnumber").val("");
			if ( result.count > 5000 ) {
				jQuery("#messagediv").text("Total count: " + result.count + ", Display only 5000 records");
			}
			//$('html, body').css("cursor", "default");  
			//jQuery("#cdrtablediv").html(result.data);
			jQuery("#cdrtablediv").html(tablestring);
			jQuery("#cdrtablecsvdiv").html(csvstring);
			//$("#cdrtable").tableToCSV();
	    	jQuery('#cdrtable').DataTable({ 
	    		"paging": true,
	    		"bPaginate": true,
	    		"bProcessing": true,
	    		"order": [[ 0, "desc" ]]
	    	});
		},
		error: function() {
			alert('Not OKay');
		}
	});
}

/***************************************************************
Detail  :       Export report to CSV file
***************************************************************/
function exportreportcsv_onclick(){
	console.log(arguments.callee.name + " --> ");
	
	$("#cdrtablecsv").tableToCSV();
	
}

/***************************************************************
Detail  :       Export report to PDF file
***************************************************************/
function exportreportpdf_onclick(){
	console.log(arguments.callee.name + " --> ");
	
	var doc = new jsPDF('p', 'pt');
	var elem = document.getElementById("cdrtable");
	var res = doc.autoTableHtmlToJson(elem);
	doc.autoTable(res.columns, res.data);
	doc.save("table.pdf");
}


/***************************************************************
Detail  :       Create CTM report
***************************************************************/
function createCTM_onclick() {
	console.log(arguments.callee.name + " --> ");

	jQuery("body").addClass("waiting");
	
	partition = jQuery("#partition").val();
	user = jQuery("#user").val();
	fromdate = jQuery("#fromdatectm").val();
	todate = jQuery("#todatectm").val();
	searchnumber = jQuery("#searchnumberctm").val();
	
	//bitwise for CDM checkboxes
	ctmbitwise = ctmbitwiseA | (ctmbitwiseB<<1) | (ctmbitwiseC<<2) | (ctmbitwiseD<<3);
	
	var ctmreport;
	if ( $("#ctmradiobtn1").prop("checked") )  {
		ctmreport = 1;
	}
	else if ( $("#ctmradiobtn2").prop("checked") )  {
		ctmreport = 2;
	}
	else if ( $("#ctmradiobtn3").prop("checked") )  {
		ctmreport = 3;
	}
	else {
		ctmreport = 4;
	}
	console.log("crmreport=" + ctmreport);

	console.log(arguments.callee.name + " --> " + partition);
	
	jQuery.ajax({
		url: "php/getCTM.php",
		data: { partition: partition,
			     user: user,
			     fromdate: fromdate,
			     todate: todate,
			     searchnumber: searchnumber,
			     ctmbitwise: ctmbitwise,
			     ctmreport: ctmreport
				},
		type: "post",
		dataType: 'json',
		success: function(result, textStatus) {
			jQuery("body").removeClass("waiting");
			console.log(result.count);
			console.log(result);
			
			//jQuery("#searchnumber").val("");
			
			jQuery("#ctmmessagediv").text("");
			jQuery("#ctmmessagediv").append("From: <span style='color: blue'>" + result.fromdate + "</span>");
			jQuery("#ctmmessagediv").append(" To: <span style='color: blue'>" + result.todate + "</span>");
			
			jQuery("#ctmtablediv").hide();
			jQuery("#ctmtablediv2").hide();
			jQuery("#ctmtablediv3").hide();
			jQuery("#ctmtablediv4").hide();
			
			if ( result.data != "" ) {
			jQuery("#ctmtablediv").show();
			jQuery("#ctmtablediv").html(result.data);
	    	jQuery('#ctmtable').DataTable({ 
	    		"paging": true,
	    		"bPaginate": true,
	    		"bProcessing": true,
	    		"order": [[ 0, "desc" ]]
	    	});
			}
	    	
			if ( result.data2 != "" ) {
			jQuery("#ctmtablediv2").show();
			jQuery("#ctmtablediv2").html(result.data2);
	    	jQuery('#ctmtable2').DataTable({ 
	    		"paging": true,
	    		"bPaginate": true,
	    		"bProcessing": true,
	    		"order": [[ 0, "desc" ]]
	    	});
			}
	    	
			if ( result.data3 != "" ) {
			jQuery("#ctmtablediv3").show();
			jQuery("#ctmtablediv3").html(result.data3);
	    	jQuery('#ctmtable3').DataTable({ 
	    		"paging": true,
	    		"bPaginate": true,
	    		"bProcessing": true,
	    		"order": [[ 0, "desc" ]]
	    	});
			}
			
	    	if ( result.data4 != "" ) {
	    		jQuery("#ctmtablediv4").show();
	    		// only display table if All partition selected
				jQuery("#ctmtablediv4").html(result.data4);
		    	jQuery('#ctmtable4').DataTable({ 
		    		"paging": true,
		    		"bPaginate": true,
		    		"bProcessing": true,
		    		"order": [[ 0, "asc" ]]
		    	});
	    	}
	    	
		},
		error: function() {
			alert('Not OKay');
		}
	});
	
}

/***************************************************************
Detail  :       Modal confirmation
***************************************************************/
function confirmdialog(tit, msg)
{
	var ret;
	
	//$('#div-dialog-info').html( "<p><span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 20px 0;'></span>" + msg + "</p>" );
	$('#div-dialog-confirm').html( "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>" );
	
	$("#div-dialog-confirm").dialog({
		modal: true,
		resizable: false,
      height: "auto",
      width: 400,
		title: tit,
		dialogClass: 'infoTitleClass',
		buttons: {
			"Close": function() {
				ret = 1;
				console.log("Close selected ret =" + ret);
				$(this).dialog("close");
			},
			"Cancel": function() {
				ret = 0;
				console.log("Cancel selected ret =" + ret);
				$(this).dialog("close");
			},
		}
	});
	
	console.log("before return ret=" + ret);
	return ret;
}


/***************************************************************
Detail  :       Modal info message
***************************************************************/
function infodialog(tit, msg)
{
	$('#div-dialog-info').html( "<p><span class='ui-icon ui-icon-info' style='float:left; margin:0 7px 20px 0;'></span>" + msg + "</p>" );
	$("#div-dialog-info").dialog({
		modal: true,
		resizable: false,
		title: tit,
		dialogClass: 'infoTitleClass',
		buttons: {
			"Close": function() {
				$(this).dialog("close");
			},
		}
	});
}

/***************************************************************
Detail  :       Modal error message
***************************************************************/
function errdialog(tit, msg)
{
	$('#div-dialog-error').html( "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + msg + "</p>" );
	$("#div-dialog-error").dialog({
		modal: true,
		resizable: false,
		title: tit,
		dialogClass: 'errorTitleClass',
		buttons: {
			"Close": function() {
				$(this).dialog("close");
			},
		}
	});
}

/***************************************************************
Detail  :       Modal alert message
***************************************************************/
function alertdialog(tit, msg)
{
	$('#div-dialog-alert').html( "<p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>" + msg + "</p>" );
	$("#div-dialog-alert").dialog({
		modal: true,
		resizable: false,
		title: tit,
		dialogClass: 'alertTitleClass',
		buttons: {
			"Close": function() {
				$(this).dialog("close");
			},
		}
	});
}

/***************************************************************
Detail  :       Validation
***************************************************************/
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatePhone(phonenumber) {
	  var re = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
	  return re.test(phonenumber)
}

function validateExtension(ext) {
	var re = /^[0-9]{1,10}$/;		// 1 to 10 digit number
	  return re.test(ext)
}

function validate(id, type, value, nonempty) {
	//console.log(arguments.callee.name + " --> " + "id=" + id + " type=" + type + " value=" + value);
	switch (type) {
		case "phone":
			var re = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;	
			if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html("!! invalid phone number");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
			
		case "email":
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if ( value == "" && nonempty == 1 ) {
				jQuery("#err-" + id).html("!! email address can not be empty");
				return false;
			} 
			else if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html("!! invalid email address");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
			
		case "zipcode":
			var re = /^([0-9]{5})(?:[-\s]*([0-9]{4}))?$/;
			if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html("!! invalid zip code");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
	
	}
}


function validate2(e) {
	//console.log(arguments.callee.name + " --> " + " class=" + e.className );
	//console.log(arguments.callee.name + " --> " + " id=" + $(element).closest("div").attr("id") );
	//console.log(e.id);
	//console.log(e.value);
	//console.log(e.className);
	
	id = e.id
	type = e.className;
	value = e.value;
	//console.log(arguments.callee.name + " --> " + id + " " + type + " " + value );
	switch (type) {		
		case "nonempty":
			if ( value == "" ) {
				jQuery("#err-" + id).html(" value can not be empty");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
			
		case "email":
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html(" invalid email address");
				jQuery("#" + id ).val("");
				//jQuery("#registeremail").focus();
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
			
		case "phone":
			var re = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;	
			if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html(" invalid phone number");
				jQuery("#" + id ).val("");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
			
		case "extnumber":
			var re = /^[0-9]{1,10}$/;
			if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html(" invalid ext number");
				jQuery("#" + id ).val("");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
			
		case "zipcode":
			var re = /^([0-9]{5})(?:[-\s]*([0-9]{4}))?$/;
			if ( ! re.test(value) ) { 
				jQuery("#err-" + id).html(" invalid zip code");
				return false;
			}
			jQuery("#err-" + id).html("");
			break;
	}
}

/***************************************************************
Detail  :       Add new tab
***************************************************************/
$.fn.addTab2 = function (name) {
   $('ul', this).append('<li><a href="#tab-' + name + '">' + name + '</a></li>');
   $(this).append("<div id='tab-" + name + "'></div>");
   $(this).tabs("refresh");
};

$.fn.addTab = function () {
   $('ul', this).append('<li><a href="#tabs-5">Update User</a></li>');
   $(this).append("<div id='tabs-5'></div>");
   $(this).tabs("refresh");
   jQuery("#tabs-5").removeClass('hidden');
};

/***************************************************************
Detail  :       Call Back for CTM checkbox
***************************************************************/
// call back functions for tabs-4 checkbox
$(function () {
	
	$('#totalmin').on('click', function(){
	   if(this.checked){
	   	jQuery("#ctmtablediv").show();
	   	ctmbitwiseA = 1;
	   }else{
	   	jQuery("#ctmtablediv").hide();
	   	ctmbitwiseA = 0;
	   }
	}) 
	
	$('#totalmincalltype').on('click', function(){
	   if(this.checked){
	   	ctmbitwiseB = 1;
	   	jQuery("#ctmtablediv2").show();
	   }else{
	   	jQuery("#ctmtablediv2").hide();
	   	ctmbitwiseB = 0;
	   }
	}) 
	
	$('#totalminusernumber').on('click', function(){
	   if(this.checked){
	   	ctmbitwiseC = 1;
	   	jQuery("#ctmtablediv3").show();
	   }else{
	   	jQuery("#ctmtablediv3").hide();
	   	ctmbitwiseC = 0;
	   }
	}) 
	
	$('#totalminpartition').on('click', function(){
	   if(this.checked){
	   	ctmbitwiseD = 1;
	   	jQuery("#ctmtablediv4").show();
	   }else{
	   	jQuery("#ctmtablediv4").hide();
	   	ctmbitwiseD = 0;
	   }
	}) 

});


/*
// jquery validate
$(document).ready(function() {
   
   $('input').on('blur', function() {
       if ($("#tabs5form").valid()) {
           $('#adduserbtn').prop('disabled', false);  
       } 
       else {
           $('#adduserbtn').prop('disabled', 'disabled');
       }
   });
   
   $("#tabs5form").validate({
   		onkeyup: false,
   	   onclick: false,
   	   onsubmit: true,
   	error: function(label) {
         $(this).addClass("error");
       },
   	rules: {
      	 adduserext: {
               required: true,
               digits: true
           },
           adduseremail: {
         	  required: true,
         	  email: true
           },
           adduserfname: {
         	  required: true,
         	  minlength: 3
           },
           adduserlname: {
         	  required: true,
         	  minlength: 3
           },
           adduserphone: {
         	  required: true,
         	  phoneUS: true
           }

       }
   });
});
*/