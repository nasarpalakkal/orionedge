<?php
include "../conn.php";

$pid = $_POST['Pid'];
$unitid = $_POST['unitid'];

$a="SELECT factor_val FROM inventory_uom  WHERE  item_no='$pid' and unit='$unitid'";

	$query =mysqli_query($link,"SELECT factor_val FROM inventory_uom  WHERE  item_no='$pid' and unit='$unitid'");	
 	$row = mysqli_fetch_array($query);
	$factor_val=$row['factor_val'];
    $response[] = array("factor_val"=>$factor_val);


 // encoding array to json format
 echo json_encode($response);
 exit;
?>