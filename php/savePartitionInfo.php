<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

//$partitionid = "OcPartition: Alpha Health";
$partitionid = $_POST['partitionid'];
$name = $_POST['name'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zipcode = $_POST['zipcode'];

$padminfname = $_POST['padminfname'];
$padminlname = $_POST['padminlname'];
$padminphone = $_POST['padminphone'];
$padminemail = $_POST['padminemail'];
$padminemail2 = $_POST['padminemail2'];

$log->i( "$self_filename -> (" . __LINE__ . "): partitionid={$partitionid}" );

$log->i("name = $name");

echo $partitionid;
$db = new Db();

$sql = "update PartitionInfo set name = '$name',
								 			address = '$address',
								 			address2 = '$address2',
								 			city = '$city',
								 			state = '$state',
								 			zipcode = '$zipcode',
								 			adminfname = '$padminfname',
								 			adminlname = '$padminlname',
								 			adminphone = '$padminphone',
								 			adminemail = '$padminemail',
								 			adminemail2 = '$padminemail2'			
        where partitionid = '$partitionid'";

$log->i("sql = $sql");

if ( !$db -> query($sql) ) {
	$log->i("query fail");
}



//$rows = $db -> select($sql);

//print json_encode($rows);

//return json_encode($rows);


//foreach ($rows as $row)
//{
	//echo "<option value='" . $row['SrcPartition'] . "'>" . $row['SrcPartition'] . "</option>";
//	echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
//}