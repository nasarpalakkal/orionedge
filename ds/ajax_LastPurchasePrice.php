<?php
include('../conn.php');
$itemid=$_POST["itemid"];
$CustomerName=$_POST["CustomerName"];
	$query = mysqli_query($link,"SELECT AvgCost,BaseLastCost,StandardCost FROM inventory where item_no='$itemid'");	
	$obj=mysqli_fetch_array($query);			
$users_arr = array();
$AvgCost=$obj['AvgCost'];
$BaseLastCost=$obj['BaseLastCost'];
$StandardCost=$obj['StandardCost'];
$users_arr[] = array("AvgCost" => $AvgCost,"BaseLastCost" => $BaseLastCost,"StandardCost" => $StandardCost);		
echo json_encode($users_arr);
?>