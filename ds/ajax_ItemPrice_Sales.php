<?php
include('../conn.php');
$Pid=$_POST["Pid"];
$unitid=$_POST["unitid"];
$salestype=$_POST["salestype"];	  
//$Packing_Setup=$_POST["Packing_Setup"];	
	
		if($Packing_Setup=="")
		{
	$query = mysqli_query($link,"SELECT A.w_price,A.retail_price,B.Type,B.TaxType,C.perc,A.factor_val,Q.qty FROM inventory_uom as A left join inventory as B on A.item_no=B.item_no left join tax_type as C on B.TaxType=C.id left join inventory_qty as Q on A.item_no=Q.item_no and Q.warehouse_id=1 where A.item_no='$Pid' and A.sno='$unitid'");
		}
		else
		{	
		 
	$query = mysqli_query($link,"SELECT A.w_price,A.retail_price,B.Type,B.TaxType,C.perc,A.factor_val,Q.qty FROM inventory_uom as A left join inventory as B on A.item_no=B.item_no left join tax_type as C on B.TaxType=C.id where A.item_no='$Pid' left join inventory_qty as Q on A.item_no=Q.item_no and Q.warehouse_id=1 and A.sno='$unitid'");	
		}
	$obj=mysqli_fetch_array($query);
	if($salestype==1){ $UnitAmount=$obj['retail_price'];  } else{ $UnitAmount=$obj['w_price']; }
	$vat=($obj['perc']/100);
	$vatAmount=number_format($UnitAmount*$vat,2,'.','');	
	$TaxPerc=$obj['perc'];
	$ItemType=$obj['Type'];
	$retail_price=$obj['retail_price'];
	$w_price=$obj['w_price'];
	$factor_val=$obj['factor_val'];
	if($obj['qty']!="") { $qtyonhand=round($obj['qty']/$factor_val); } else {$qtyonhand=0;}

$users_arr = array();
$users_arr[] = array("UnitAmount" => $UnitAmount,"TaxAmt" => $vatAmount,"TaxPerc" => $TaxPerc,"ItemType" => $ItemType,"retail_price" => $retail_price,"w_price" => $w_price,"factor_val" => $factor_val,"qtyonhand" => $qtyonhand);		
echo json_encode($users_arr);
?>