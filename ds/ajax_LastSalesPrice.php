<?php
include('../conn.php');
$itemid=$_POST["itemid"];
$CustomerName=$_POST["CustomerName"];
	$query = mysqli_query($link,"SELECT A.unit_price,C.descr as UnitDescr,A.TaxPer FROM sales_list as A left JOIN sales as B on A.PONumber=B.PONumber left join tb_units as C on A.unit=C.id where A.item_no='$itemid' and B.CustomerName='$CustomerName' ORDER BY A.createdDate DESC LIMIT 1");	
	$obj=mysqli_fetch_array($query);
			
			$Taxvalue=number_format(($obj['unit_price']*($obj['TaxPer']/100)),2,'.','');
			$unitprice=number_format(($obj['unit_price']+$Taxvalue),2,'.','');
			$unit_price=$unitprice." per ".$obj['UnitDescr'];	
$users_arr = array();
$users_arr[] = array("SalesPrice" => $unit_price);		
echo json_encode($users_arr);
?>