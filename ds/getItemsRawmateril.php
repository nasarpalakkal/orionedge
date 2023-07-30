<?php
include "../conn.php";

$search = $_POST['search'];
$salestype= $_POST['salestype'];
$whid= $_POST['store'];
																		

	if($search=="")
	{
	$query = "SELECT A.item_no,A.item_descr,A.item_descr_ar,A.AvgCost,A.StandardCost,A.BaseLastCost,B.barcode,B.factor_val,D.descr as UnitDescr,B.sno UnitID,B.Packing_Setup,B.w_price as WholesalePrice,B.retail_price as RetailPrice,A.TaxType,C.perc,C.division_val,A.Type,Q.qty as QtyOnHand,A.IncludeTax FROM inventory as A left join inventory_uom as B on A.item_no=B.item_no left join tb_units as D on B.unit=D.id  left join tax_type as C on A.TaxType=C.id left join inventory_qty as Q on A.item_no=Q.item_no and Q.warehouse_id='".$whid."' WHERE A.Type=5" ;
	}
	else
	{
	$query = "SELECT A.item_no,A.item_descr,A.item_descr_ar,A.AvgCost,A.StandardCost,A.BaseLastCost,B.barcode,B.factor_val,D.descr as UnitDescr,B.sno UnitID,B.Packing_Setup,B.w_price as WholesalePrice,B.retail_price as RetailPrice,A.TaxType,C.perc,C.division_val,A.Type,Q.qty as QtyOnHand,A.IncludeTax FROM inventory as A left join inventory_uom as B on A.item_no=B.item_no left join tb_units as D on B.unit=D.id  left join tax_type as C on A.TaxType=C.id left join inventory_qty as Q on A.item_no=Q.item_no and Q.warehouse_id='".$whid."' WHERE  A.Type=5 and (A.item_no like'%".$search."%' or A.item_descr like '%".$search."%' or A.item_descr_ar like '%".$search."%' or B.barcode LIKE '%".$search."%')" ;
	}
 $result = mysqli_query($link,$query);
 
 while($row = mysqli_fetch_array($result) ){
 			$division_val=$row['division_val'];
			$IncludeTax=$row['IncludeTax'];
			$IncludeTax=$row['IncludeTax'];
			$taxperc=$row["perc"];
			
									
 	
	if($row['Packing_Setup']==""){$UnitDescr=$row['UnitDescr']; } else {$UnitDescr=$row['UnitDescr'].'-'.$row['Packing_Setup']; }
	if($row['QtyOnHand']!="") { $qtyonhand=round($row['QtyOnHand']/$row['factor_val']); } else {$qtyonhand=0;}
						
							if($IncludeTax==1)
							{
	if($salestype==1){ $UnitAmount=number_format($row['RetailPrice']/$division_val,2,'.','');  } else{ $UnitAmount=number_format($row['WholesalePrice']/$division_val,2,'.',''); }
	if($salestype==1){ $vatAmount=number_format($row['RetailPrice']-$UnitAmount,2,'.','');  } else{ $vatAmount=number_format($row['WholesalePrice']-$UnitAmount,2,'.',''); }
	if($salestype==1){ $AmountwithTax=number_format($row['RetailPrice'],2,'.','');  } else{ $AmountwithTax=number_format($row['WholesalePrice'],2,'.',''); }
							}
							else
							{					
	if($salestype==1){ $UnitAmount=number_format($row['RetailPrice'],2,'.','');  } else{ $UnitAmount=number_format($row['WholesalePrice'],2,'.',''); }
	if($salestype==1){ $vatAmount=number_format(($row['RetailPrice']*($taxperc/100)),2,'.','');  } else{ $vatAmount=number_format(($row['WholesalePrice']*($taxperc/100)),2,'.',''); }
	if($salestype==1){ $AmountwithTax=number_format($UnitAmount+$vatAmount,2,'.','');  } else{ $AmountwithTax=number_format($UnitAmount+$vatAmount,2,'.',''); }
							}
	
			
  $response[] = array("value"=>$row['item_no'],"label"=>$row['item_no']."-".$row['item_descr']."-".$row['item_descr_ar']."-".$row['barcode']."-".$UnitDescr."-".$row['WholesalePrice'],"labelAr"=>$row['item_no']."-".$row['item_descr_ar']."-".$row['barcode'],"AvgCost"=>$row['AvgCost'],"BaseLastCost"=>$row['BaseLastCost'],"factor_val"=>$row['factor_val'],"StandardCost"=>$row['StandardCost'],"UnitDescr"=>$UnitDescr,"UnitID"=>$row['UnitID'],"TaxPerc" => $row['perc'],"ItemType" => $row['Type'],"WholesalePrice" => $row['WholesalePrice'],"RetailPrice" => $row['RetailPrice'],"salestype" =>$salestype,"UnitAmount" => $UnitAmount,"TaxAmt" => $vatAmount,"AmountwithTax" => $AmountwithTax,"division_val"=> $division_val,"qtyonhand"=>$row['QtyOnHand'],"IncludeTax"=>$row['IncludeTax']);
 }
 // encoding array to json format
 echo json_encode($response);
 exit;
?>