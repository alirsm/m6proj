<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$partition="Alpha Health";$user="9737317868";$fromdate="2016-01-01"; $todate="2016-12-31"; 
	$ctmbitwise = 4;
	$ctmreport = 4;  // 4 with $partition="All"
	$searchnumber="";
}
else {
	$partition = $_POST['partition'];
	$user = $_POST['user'];
	$fromdate = ! empty($_POST['fromdate']) ? $_POST['fromdate'] : NULL;
	$todate = ! empty($_POST['todate']) ? $_POST['todate'] : NULL;
	$searchnumber = $_POST['searchnumber'];
	$ctmbitwise = $_POST['ctmbitwise'];
	$ctmreport = $_POST['ctmreport'];
}

$log->i("partition={$partition} user={$user} ctmbitwise={$ctmbitwise} $fromdate $todate $searchnumber");

$reportA = $ctmbitwise&1;
$reportB = ($ctmbitwise&2) >>1;
$reportC = ($ctmbitwise&4) >>2;
$reportD = ($ctmbitwise&8) >>3;

$log->i("$reportA $reportB $reportC $reportD");

$result = $result2 = $result3 = $result4 = "";

$db = new Db();

if ( $fromdate ===  NULL ){
	$sql = "select TimeStart from M6 order by TimeStart asc limit 1";
	$rows = $db -> select($sql);
	$fromdate = $rows[0]['TimeStart'];
}

if ( $todate ===  NULL ){
	$sql = "select TimeStart from M6 order by TimeStart desc limit 1";
	$rows = $db -> select($sql);
	$todate = $rows[0]['TimeStart'];
}
//$log->i("fromdate = $fromdate");
//$log->i("todate = $todate");


/****************** ReportA  Table for Total Min ******************/
//if ( $reportA )
if ( $ctmreport == 1 )
{
	$log->i("================== report A ==================");
	$sql = "(select CallDirection, round(sum(Duration)/60) TM
	from M6
	where trim(RecordType) = '0'
	and trim(TerminationReason) = '0'
	and CallDirection = 'O'";
	if ( $partition != "All" ) { $sql .= " and SrcPartition = 'OcPartition: $partition'"; }
	if ( $user != "" and  $user != "All" ) { $sql .= " and SrcCallerId='$user'"; }
	if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
	if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
	if ( $searchnumber != "" ) { $sql .= " and (SrcCallerId like '%$searchnumber%' or DstCallerId like '%$searchnumber%')"; }
	$sql .= ") union (";
	$sql .= "select CallDirection, round(sum(Duration)/60) TM
	from M6
	where trim(RecordType) = '0'
	and trim(TerminationReason) = '0'
	and CallDirection = 'I'";
	if ( $partition != "All" ) { $sql .= " and DstPartition = 'OcPartition: $partition'"; }
	if ( $user != "" and  $user != "All" ) { $sql .= " and DstCallerId='$user'"; }
	if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
	if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
	if ( $searchnumber != "" ) { $sql .= " and (SrcCallerId like '%$searchnumber%' or DstCallerId like '%$searchnumber%')"; }
	$sql .= ")";
	
	/*
	if ( $partition != "All" ) { $sql .= " and (SrcPartition = 'OcPartition: $partition' or DstPartition = 'OcPartition: $partition')"; }
	if ( $user != "" and  $user != "All" ) { $sql .= " and (SrcCallerId='$user' or DstCallerId='$user')"; }
	if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
	if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
	if ( $searchnumber != "" ) { $sql .= " and (SrcCallerId like '%$searchnumber%' or DstCallerId like '%$searchnumber%')"; }
	$sql .= " group by CallDirection
	order by CallDirection";
	*/
	
	
	
	$log->i("sql1 = $sql");
	$rows = $db -> select($sql);
	$count = count($rows);
	$log->i("ctm total record extracted for Total Min:" . $count);
	$field_list = array("Call Direction","Total Min");
	
	$result = "<table id='ctmtable' class='display'>";
	$result .="<caption><p2><b>Total Minute Report for partition: $partition</b></p2></caption>";
	$result .= "<thead align='left'><tr>";
	foreach ($field_list as $field)
	{
		$result .= "<th>{$field}</th>";
	}
	$result .= "</tr></thead>";
	$result .= "<tbody>";
	foreach ($rows as $row)
	{
		if ( $row["CallDirection"] == "I" ) { $row["CallDirection"] = "INBOUND"; }
		if ( $row["CallDirection"] == "O" ) { $row["CallDirection"] = "OUTBOUND"; }
		
		$result .= "<tr>\n";
		foreach ($row as $cell)
			$result .= "<td>$cell</td>";
	
			$result .= "</tr>\n";
	}
	$result .= "</tbody>";
	$result .= "</table>";
}

/******************ReportB  Table for Total Min per Call Type ******************/
//if ( $reportB )
elseif ( $ctmreport == 2 )
{
	$log->i("================== report B ==================");
	$sql = "select CallDirection, callType, round(sum(Duration)/60) TM
	from M6
	where trim(RecordType) = '0'
	and trim(TerminationReason) = '0'";
	if ( $partition != "All" ) { $sql .= " and (SrcPartition = 'OcPartition: $partition' or DstPartition = 'OcPartition: $partition')"; }
	if ( $user != "" and  $user != "All" ) { $sql .= " and (SrcCallerId='$user' or DstCallerId='$user')"; }
	if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
	if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
	if ( $searchnumber != "" ) { $sql .= " and (SrcCallerId like '%$searchnumber%' or DstCallerId like '%$searchnumber%')"; }
	$sql .= " and callType in ('INTERSTATE','INTL','INTRASTATE','TOLLFREE','WZ1')";
	$sql .= " group by CallDirection, callType
	order by CallDirection, callType";
	$log->i("sql2 = $sql");
	$rows = $db -> select($sql);
	$count = count($rows);
	$log->i("ctm total record extracted for Total Min per Call Type:" . $count);
	$field_list = array("Call Direction","Call Type","Total Min");
	$result2 = "<table id='ctmtable2' class='display'>";
	$result2 .="<caption><p2><b>Total Minute per Call Type Report for partition: $partition</b></p2></caption>";
	$result2 .= "<thead align='left'><tr>";
	foreach ($field_list as $field)
	{
		$result2 .= "<th>{$field}</th>";
	}
	$result2 .= "</tr></thead>";
	$result2 .= "<tbody>";
	foreach ($rows as $row)
	{
		if ( $row["CallDirection"] == "I" ) { $row["CallDirection"] = "INBOUND"; }
		if ( $row["CallDirection"] == "O" ) { $row["CallDirection"] = "OUTBOUND"; }
		
		$result2 .= "<tr>\n";
		foreach ($row as $cell)
			$result2 .= "<td>$cell</td>";
	
			$result2 .= "</tr>\n";
	}
	$result2 .= "</tbody>";
	$result2 .= "</table>";
	//$log->i($result);
}

/******************ReportC  Table for Total Min per User Number ******************/
//if ( $reportC )
elseif ( $ctmreport == 3 )
{
	// only for Outbound
	$log->i("================== report C ==================");
	$sql = "select CallDirection, SrcCallerId, substr(SrcPartition,14), round(sum(Duration)/60) TM
	from M6
	where trim(RecordType) = '0'
	and trim(TerminationReason) = '0'";
	if ( $partition != "All" ) { $sql .= " and substr(SrcPartition,14) = '$partition'"; }
	//if ( $partition != "All" ) { $sql .= " and (SrcPartition = 'OcPartition: $partition' or DstPartition = 'OcPartition: $partition')"; }
	//if ( $user != "" and  $user != "All" ) { $sql .= " and (SrcCallerId='$user' or DstCallerId='$user')"; }
	if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
	if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
	if ( $searchnumber != "" ) { $sql .= " and (SrcCallerId like '%$searchnumber%' or DstCallerId like '%$searchnumber%')"; }
	$sql .= " and callType in ('INTERSTATE','INTL','INTRASTATE','TOLLFREE','WZ1')";
	$sql .= " and CallDirection = 'O'";
	$sql .= " group by SrcCallerId, SrcPartition
	order by TM desc";
	$log->i("sql3 = $sql");
	$rows = $db -> select($sql);
	$count = count($rows);
	$log->i("ctm total record extracted for Total Min per User Number:" . $count);
	$field_list = array("Call Direction","User Number", "Source Partition", "Total Min");
	$result3 = "<table id='ctmtable3' class='display'>";
	$result3 .="<caption><p2><b>Total Outbound Minute per User Number Report for partition: $partition</b></p2></caption>";
	$result3 .= "<thead align='left'><tr>";
	foreach ($field_list as $field)
	{
		$result3 .= "<th>{$field}</th>";
	}
	$result3 .= "</tr></thead>";
	$result3 .= "<tbody>";
	foreach ($rows as $row)
	{
		if ( $row["CallDirection"] == "I" ) { $row["CallDirection"] = "INBOUND"; }
		if ( $row["CallDirection"] == "O" ) { $row["CallDirection"] = "OUTBOUND"; }
	
		$result3 .= "<tr>\n";
		foreach ($row as $cell)
			$result3 .= "<td>$cell</td>";
	
			$result3 .= "</tr>\n";
	}
	$result3 .= "</tbody>";
	$result3 .= "</table>";
	//$log->i($result);
}

//if ( $reportD )
elseif ( $ctmreport == 4 )
{
	/******************ReportD  Table for Total Min per Partition ******************/
	$log->i("================== report D ==================");
	if ( $partition == "All" ) {
		$sql = "select substr(SrcPartition,14), CallDirection, round(sum(Duration)/60) TM
		from M6
		where trim(RecordType) = '0'
		and trim(TerminationReason) = '0'
		and SrcPartition != ''";
		if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
		if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
		if ( $searchnumber != "" ) { $sql .= " and SrcCallerId like '%$searchnumber%'"; }
		//$sql .= " and callType in ('INTERSTATE','INTL','INTRASTATE','TOLLFREE','WZ1')";
		$sql .= " and CallDirection = 'O'";
		$sql .= " group by SrcPartition, CallDirection
		union
		select substr(DstPartition,14), CallDirection, round(sum(Duration)/60) TM
		from M6
		where trim(RecordType) = '0'
		and trim(TerminationReason) = '0'
		and DstPartition != ''";
		if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate'"; }
		if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate'"; }
		if ( $searchnumber != "" ) { $sql .= " and DstCallerId like '%$searchnumber%'"; }
		//$sql .= " and callType in ('INTERSTATE','INTL','INTRASTATE','TOLLFREE','WZ1')";
		$sql .= " and CallDirection = 'I'";
		$sql .= " group by DstPartition, CallDirection";
		$log->i("sql4 = $sql");
		
		$rows = $db -> select($sql);
		$count = count($rows);
		$log->i("ctm total record extracted for Total Min per Partition:" . $count);
		$field_list = array("Partition", "Call Direction", "Total Min");
		$result4 = "";
		$result4 = "<table id='ctmtable4' class='display'>";
		$result4 .="<caption><p2><b>Total Minute per Partition Report</b></p2></caption>";
		$result4 .= "<thead align='left'><tr>";
		foreach ($field_list as $field)
		{
			$result4 .= "<th>{$field}</th>";
		}
		$result4 .= "</tr></thead>";
		$result4 .= "<tbody>";
		foreach ($rows as $row)
		{
			if ( $row["CallDirection"] == "I" ) { $row["CallDirection"] = "INBOUND"; }
			if ( $row["CallDirection"] == "O" ) { $row["CallDirection"] = "OUTBOUND"; }
			
			$result4 .= "<tr>\n";
			foreach ($row as $cell)
				$result4 .= "<td>$cell</td>";
		
				$result4 .= "</tr>\n";
		}
		$result4 .= "</tbody>";
		$result4 .= "</table>";
		//$log->i($result);
	}
	else {
		$result4 = "";
	}
}

echo json_encode(array("data" => $result, "data2" => $result2, "data3" => $result3, "data4" => $result4, "count" => $count, "fromdate" => $fromdate, "todate" => $todate));

	
	
	
	
	
	
/*
echo "<table id='cdrtable' class='display'>";
echo "<thead><tr>";
foreach ($field_list as $field)
{
	echo "<th>{$field}</th>";
}
echo "</tr></thead>";
echo "<tbody>";
foreach ($rows as $row)
{
	echo "<tr>\n";
	foreach ($row as $cell)
		echo "<td>$cell</td>";

		echo "</tr>\n";
}
echo "</tbody>";
echo "</table>";
*/