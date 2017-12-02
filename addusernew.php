<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>jQuery UI Tabs - Default functionality</title>

<!-- jquery -->
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>-->
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css"/>

<!-- <link rel="stylesheet" href="/resources/demos/style.css"/>-->

<!-- DataTables -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> -->
<!-- <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>-->
<link rel="stylesheet" href="css/jquery.dataTables.min.css"/>
<script src="js/jquery.dataTables.min.js"></script>

<!-- jQuery Validator -->
<!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>-->
<script src='https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js'></script>

<!-- CSV -->
<script src="js/jquery.tabletoCSV.js" type="text/javascript" charset="utf-8"></script>

<link href="stylenew.css" rel="stylesheet" type="text/css"/>
<script src="gui.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
   
   $('input').on('blur keyup', function() {
       if ($("#test").valid()) {
           $('#adduserbtn').prop('disabled', false);  
       } 
       else {
           $('#adduserbtn').prop('disabled', 'disabled');
       }
   });
   
   $("#test").validate({
       rules: {
      	 name: {
               required: true,
               minlength: 3
           },
           email: {
               required: true,
               email: true
           }
       }
   });
});
</script>

<?php
//include "php/functions.php";
$self_dir = dirname ( __FILE__ ) . "/";
require_once ("{$self_dir}php/functions.php");
?>

</head>
<body>
<form id="test">
<div class="tab5">

<label class="box1">Simple label</label><input type="text" id="name" name="name" /><br/>
<label class="mainlabel">Simple label</label><input type="email" id="email" name="email" /><br/>
<input type="submit" id="submit" disabled="disabled" />

<!-- 
<div class="block">
    <label>Simple label</label><input id="in1" type="text" />
</div>
<div class="block">
    <label>Label with more text</label><input id="in2" type="email" />
</div>
<div class="block">
    <label>Short</label><input type="text" />
</div>

<button id="adduserbtn" class="ui-button ui-widget ui-corner-all" onclick="register_onclick()">Add User</button>
-->

</div>
</form>
</body>
</html>