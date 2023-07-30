<?php
include('../conn.php');
$Pid=$_POST["Pid"];
$Wid=$_POST["Wid"];
$unit=$_POST["unit"];
//$query = $db->query("SELECT qty FROM inventory_qty where item_no='$Pid' and warehouse_id='$Wid'");
 
$query = mysqli_query($link,"SELECT A.factor_val,Q.qty FROM inventory_uom as A left join inventory_qty as Q on A.item_no=Q.item_no and Q.warehouse_id='$Wid' where A.item_no='$Pid' and A.sno='$unit'");
    $rowCount = mysqli_num_rows($query);
    
    //Display cities list
    if($rowCount > 0){       
        $obj = mysqli_fetch_array($query);
         	 $factor_val=$obj['factor_val'];
			if($obj['qty']!="") { $qtyonhand=round($obj['qty']/$factor_val); } else {$qtyonhand=0;}
        }
		else
		{
		$qty="0";
		}
$users_arr = array();
$users_arr[] = array("Allresult" => $qtyonhand);	
echo json_encode($users_arr);			
?>