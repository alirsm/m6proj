<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");

$db = new Db();

function partition_option()
{
	global $db;
	$rows = $db -> select("select distinct name from PartitionTN order by name;");
	foreach ($rows as $row)
	{
		//echo "<option value='" . $row['SrcPartition'] . "'>" . $row['SrcPartition'] . "</option>";
		echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
	}
}
//partition_option();

function get_cdr($partition)
{
	global $db;
	//$rows = $db -> select("select TimeStart,TimeEnd,SrcCallerId,DstCallerId,Duration,CallType,BilledSrcDID,CallDirection,SrcCallerInfo,DstCallerInfo from M6b where SrcPartition='$partition' order by TimeStart desc limit 3;");
	//$field_list = array("TimeStart","TimeEnd","SrcCallerId","DstCallerId","Duration","CallType","BilledSrcDID","CallDirection","SrcCallerInfo","DstCallerInfo");
	$rows = $db -> select("select TimeStart,TimeEnd,SrcCallerId,DstCallerId from M6 where SrcPartition='$partition' order by TimeStart desc;");
	$field_list = array("TimeStart","TimeEnd","SrcCallerId","DstCallerId");
	
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
}
//get_cdr("OcPartition: Indie Lee and Co");


