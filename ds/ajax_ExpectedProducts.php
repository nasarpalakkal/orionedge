<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
include("../conn.php");
date_default_timezone_set('Asia/Riyadh'); 
$strID=$_POST['strID'];
$BOM=$_POST['BOM'];
$sno=$_POST['sno'];
$qty=$_POST['qty'];
$unit=$_POST['unit'];

        $item=mysqli_query($link,"select A.item_no,A.unit,A.qty,A.unitsno,U.descr UnitDescr,C.factor_val from billofmaterial_list as A left join inventory_uom as C on A.item_no=C.item_no and A.unit=C.unit left join tb_units as U on A.unit=U.id where A.PONumber='$BOM' and A.sno='$sno'");
        $objitem=mysqli_fetch_array($item);
        $qtyBill=$objitem['qty']*$objitem['factor_val'];    //////// from Bill of material        
        $item_id=$objitem['item_no'];
        $FactorVal1=$objitem['factor_val'];
        

        $item1=mysqli_query($link,"select A.factor_val from inventory_uom as A where A.item_no='$item_id' and A.unit='$unit'");
        $objitem1=mysqli_fetch_array($item1);
        $FactorVal=$objitem1['factor_val'];

        $TotalUnit=$qty*$FactorVal;
        $EachValue=$TotalUnit/$qtyBill;
		
$users_arr = array();
$users_arr[] = array("TotalQty" => $EachValue);	
echo json_encode($users_arr);
?>	
  