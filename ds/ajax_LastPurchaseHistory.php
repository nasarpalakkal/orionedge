<?php
include('../conn.php');
$itemid=$_POST["itemid"];
    $a="<table border=\"1\" width=\"100%\" id=\"purchase-item\"><thead><tr>
    <th> PO Number </th>
    <th> Date & Time</th>
    <th> Qty Recieved</th>
    <th> Supplier </th>
    <th> Unit Price </th>
    <th> Sub Total </th>
    <th> Tax </th>
    <th> Total </th>
</tr></thead><tbody>";
	$query = mysqli_query($link,"SELECT A.PONumber,A.unit_price,A.qty,A.createdDate,S.supplier_name,A.unit_price,A.total_price,A.tax_amt,B.cdatetime FROM purchase_list as A left JOIN purchase as B on A.PONumber=B.PONumber left join supplier_details as S on B.SupplierName=S.id where A.item_no='$itemid' ORDER BY A.createdDate DESC LIMIT 5");	
	while($obj=mysqli_fetch_array($query))
    {
   
$a=$a."<tr>
            <td>".$obj['PONumber']."</td>
            <td>".date('d-m-Y H:i',strtotime($obj['cdatetime']))."</td>
            <td>".$obj['qty']."</td>
            <td>".$obj['supplier_name']."</td>
            <td>".$obj['unit_price']."</td>
            <td>".$obj['total_price']."</td>
            <td>".$obj['tax_amt']."</td>
            <td>".number_format($obj['tax_amt']+$obj['total_price'])."</td>
            <td></td>
        </tr>";
    }	
    $a=$a."</tbody></table>";	
$users_arr = array();
$users_arr[] = array("PurchaseList" => $a);		
echo json_encode($users_arr);
?>