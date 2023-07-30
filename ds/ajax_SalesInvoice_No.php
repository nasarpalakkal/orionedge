<?php
include('../conn.php');
$type=$_POST["type"];
	if($type==1)
	{
	$query = mysqli_query($link,"SELECT  next_number FROM next_numbering  where type='sales'");
	$obj=mysqli_fetch_array($query);		
	$next_number=$obj['next_number'];
	}
	else
	{
	//$query = mysqli_query($link,"SELECT  next_number FROM next_numbering  where type='sales_return'");
	$query = mysqli_query($link,"SELECT  next_number FROM next_numbering  where type='sales'");
	$obj=mysqli_fetch_array($query);		
	$next_number=$obj['next_number'];
	}
$users_arr = array();
$users_arr[] = array("next_number" => $next_number);		
echo json_encode($users_arr);
?>