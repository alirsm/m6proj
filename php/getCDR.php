<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$CLI = PHP_SAPI === 'cli';
$cli = php_sapi_name();

if ( $CLI ) {
	$partition="Alpha Health";$user="9737317868";$fromdate="2016-01-01";$todate="2016-12-31";$number="17132956741";
	$callTypeSelected="'INTL', 'INTERSTATE'";$callDirectionSelected="O";
	//$partition="All";$user="All";$fromdate="";$todate="";$number="";
}
else {
	$partition = $_POST['partition'];
	$user = $_POST['user'];
	$fromdate = $_POST['fromdate'];
	$todate = $_POST['todate'];
	$number = $_POST['number'];
	$callTypeSelected = ! empty($_POST['callTypeSelected']) ? $_POST['callTypeSelected'] : NULL;
	$callDirectionSelected = ! empty($_POST['callDirectionSelected']) ? $_POST['callDirectionSelected'] : NULL;
}

$log->i( "$self_filename -> (". __LINE__ ."): $partition,$user,$fromdate,$todate,$number,$callTypeSelected,$callDirectionSelected" );

//file_put_contents("/tmp/log", $partition, FILE_APPEND | LOCK_EX);

$db = new Db();
$sql = "select TimeStart,TimeEnd,SrcCallerId,DstCallerId,CEILING(Duration/60),CallDirection,CallType 
from M6isam
where RecordType = 0";
if ( $callDirectionSelected != "" ) { $sql .= " and CallDirection = '$callDirectionSelected'"; }
if ( $partition != "All" ) 
{
	if ( $callDirectionSelected == "O") { $sql .= " and SrcPartition = 'OcPartition: $partition'"; }
	else { $sql .= " and DstPartition = 'OcPartition: $partition'"; }
}
if ( $user != "" and  $user != "All" ) 
{ 
	//if ( $callDirectionSelected == "O") { $sql .= " and SrcCallerId='$user'"; }
	//else { $sql .= " and DstCallerId='$user'"; }
	if ( $callDirectionSelected == "O") { $sql .= " and SrcEndName like '%$user%'"; }
	else { $sql .= " and DstEndName like '%$user%'"; }
}
if ( $callTypeSelected != "" ) { $sql .= " and CallType in ($callTypeSelected)"; }
if ( $fromdate != "" ) { $sql .= " and TimeStart >= '$fromdate 00:00:00'"; }
if ( $todate != "" ) { $sql .= " and TimeStart <= '$todate 23:59:59'"; }
if ( $number != "" ) { $sql .= " and (SrcCallerId like '%$number%' or DstCallerId like '%$number%')"; }
$sql .= " order by TimeStart desc";

$log->i("sql = $sql");
ini_set('memory_limit', '-1');
$rows = $db -> select($sql);
$count = count($rows);
$log->i("cdr total record extracted:" . $count);
if ( $count > 5000 ) {
	$rows = array_slice($rows, 0, 5000);
}

$field_list = array("TimeStart","TimeEnd","SrcCallerId","DstCallerId","Duration(min)","CallDirection","CallType");

$result = "<table id='cdrtable' class='display'>";
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
//$log->i($result);

echo json_encode(array("data" => $result, "count" => $count));