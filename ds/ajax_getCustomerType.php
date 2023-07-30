<?php
include('../conn.php');
$Pid=$_POST["Pid"];
$type=$_POST["type"];
		 	if($type==1)
			{
	$query = mysqli_query($link,"SELECT CustomerType FROM customer_details where id='$Pid'");	
	$obj=mysqli_fetch_array($query);
	if($obj['CustomerType']==""){$val=1;}else {$val=$obj['CustomerType'];}
	if($val==2){$SalesType=3;}else { $SalesType=$val;}
			}
			else
			{
	$query = mysqli_query($link,"SELECT SupplierType FROM supplier_details where id='$Pid'");	
	$obj=mysqli_fetch_array($query);
	if($obj['SupplierType']==""){$val=1;}else {$val=$obj['SupplierType'];}
	if($val==2){$SalesType=3;}else { $SalesType=$val;}	
			}
$users_arr = array();
$users_arr[] = array("CustomerType" => $SalesType);		
echo json_encode($users_arr);
?>