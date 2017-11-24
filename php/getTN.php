<?php

$choice = "Bcs Inc";
//$choice = $_POST['choice'];

include "ClassDb.php";
$db = new Db();
$data = array();
$rows = $db -> select("select user, tn from M6PartitionTN where name='$choice' order by user, tn");
//$rows = $db -> select("select user, tn from M6PartitionTN where name='$choice' order by user, tn");
//print_r($rows);
foreach ($rows as $row)
{
	//$data[] = trim($row['tn']);
	//$data[] = $row['tn'];
	$data[] = array ($row["user"], $row["tn"]);
	//$data[] = array ("user" => $row["user"], "tn" => $row["tn"]);
}
//print_r($data);
echo json_encode($data, true);


