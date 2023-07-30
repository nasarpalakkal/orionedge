<?php
include('../conn.php');
$Pid=$_POST["Pid"];
$unitid=$_POST["unitid"];
$salestype=$_POST["salestype"];
	$query = mysqli_query($link,"SELECT TaxType,perc,Type,perc,unit FROM inventory_v  left join tax_type on inventory_v.TaxType=tax_type.id where item_no='$Pid'");
	$obj=mysqli_fetch_array($query);	
		
	$TaxPerc=$obj['perc'];
	$ItemType=$obj['Type'];
	
	
	$query1 = mysqli_query($link,"SELECT w_price FROM inventory_v  where item_no='$Pid' and unit='$unitid'");
	$no1=mysqli_num_rows($query1);
		if($no1==0)
		{
	$UnitAmount=0;
		}
		else
		{
	$obj1=mysqli_fetch_array($query1);
	$UnitAmount=$obj1['w_price'];
		}
	$vat=($obj['perc']/100);
	$vatAmount=$UnitAmount*$vat;

$users_arr = array();
$users_arr[] = array("UnitAmount" => $UnitAmount,"TaxAmt" => $vatAmount,"TaxPerc" => $TaxPerc,"ItemType" => $ItemType);		
echo json_encode($users_arr);
?>