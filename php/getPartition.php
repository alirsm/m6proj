<?php

$self_dir = dirname ( __FILE__ ) . "/";
$self_filename = basename ( __FILE__ );
require_once ("{$self_dir}ClassDb.php");
require_once ("{$self_dir}Logger.php");

if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

$userType = $_POST['userType'];
$partition = $_POST['partition'];
$log->i("userType=$userType   partition=$partition");
$db = new Db();

if ( $userType == "S" )
{
	$sql = "select distinct name from M6PartitionTN order by name";
}
elseif ( $userType == "A" )
{
	$sql = "select distinct name from M6PartitionTN where name = '$partition'";
}
/*
elseif ( $userType == "user" )
{
	$sql = "select distinct name, tn from PartitionTN where tn = '$searchValue' order by name, tn";
}
*/
else 
{
	$log->e("incorrect userType");
}
$log->i("sql = $sql");

$rows = $db -> select($sql);

/*
echo "<option value='1'>Please choose</option>";

if ( $userType == "S" ) {
	echo "<option value='All'>All</option>";
}
*/

foreach ($rows as $row)
{
	echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
}