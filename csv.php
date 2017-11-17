<!DOCTYPE html>
<html>
<head>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript" charset="utf-8"></script>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>


<script src="js/jquery.tabletoCSV.js" type="text/javascript" charset="utf-8"></script>
<script>
$(function(){
	$("#export").click(function(){
		$("#export_table").tableToCSV();
	});
});
	</script>
	</head>
	<body>
	<table id="export_table">
	<caption>asdsa</caption>
	<tr>
	<th>blah</th>
	<th>blahs</th>
	<th>blahss</th>
	</tr>
	<tr>
	<td>blah1</td>
	<td>blah1</td>
	<td>blah1</td>
	</tr>
	<tr>
	<td>blah2</td>
	<td>blah2</td>
	<td>blah2</td>
	</tr>
	</table>
	<button id="export" data-export="export">Export</button>
	</body>
	</html>