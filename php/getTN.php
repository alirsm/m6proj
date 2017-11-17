<?php

//$choice = "OcPartition: Alpha Health";
$choice = $_POST['choice'];

include "ClassDb.php";
$db = new Db();
$data = array();
$rows = $db -> select("select tn from M6PartitionTN where name='$choice'");
//print_r($rows);
foreach ($rows as $row)
{
	$data[] = trim($row['tn']);
}
//print_r($data);
echo json_encode($data, true);


