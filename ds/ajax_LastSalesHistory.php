<?php
include('../conn.php');
$itemid=$_POST["itemid"];
$CustomerName=$_POST["CustomerName"];
    $a="<table border=\"1\" width=\"100%\" id=\"sales-item\"><thead><tr>
    <th> PO Number </th>
    <th> Date & Time</th>
    <th> Qty Recieved</th>
    <th> Supplier </th>
    <th> Unit Price </th>
    <th> Sub Total </th>
    <th> Tax </th>
    <th> Total </th>
</tr></thead><tbody>";
	$query = mysqli_query($link,"SELECT A.PONumber,A.unit_price,A.qty,A.createdDate,S.customer_name,A.unit_price,A.total_price,A.tax_amt,B.cdatetime FROM sales_list as A left JOIN sales as B on A.PONumber=B.PONumber left join customer_details as S on B.CustomerName=S.id where A.item_no='$itemid' and B.CustomerName='$CustomerName' ORDER BY A.createdDate DESC LIMIT 5");	    
	while($obj=mysqli_fetch_array($query))
    {
   
$a=$a."<tr>
            <td>".$obj['PONumber']."</td>
            <td>".date('d-m-Y H:i',strtotime($obj['cdatetime']))."</td>
            <td>".$obj['qty']."</td>
            <td>".$obj['customer_name']."</td>
            <td>".$obj['unit_price']."</td>
            <td>".$obj['total_price']."</td>
            <td>".$obj['tax_amt']."</td>
            <td>".number_format($obj['tax_amt']+$obj['total_price'])."</td>
            <td></td>
        </tr>";
    }	
    $a=$a."</tbody></table>";
    
    

    $b="<table border=\"1\" width=\"100%\" id=\"sales-item1\"><thead><tr>
    <th> PO Number </th>
    <th> Date & Time</th>
    <th> Qty Recieved</th>
    <th> Supplier </th>
    <th> Unit Price </th>
    <th> Sub Total </th>
    <th> Tax </th>
    <th> Total </th>
</tr></thead><tbody>";
	$query = mysqli_query($link,"SELECT A.PONumber,A.unit_price,A.qty,A.createdDate,S.customer_name,A.unit_price,A.total_price,A.tax_amt,B.cdatetime FROM sales_list as A left JOIN sales as B on A.PONumber=B.PONumber left join customer_details as S on B.CustomerName=S.id where A.item_no='$itemid' ORDER BY A.createdDate DESC LIMIT 5");	
	while($obj=mysqli_fetch_array($query))
    {
   
$b=$b."<tr>
            <td>".$obj['PONumber']."</td>
            <td>".date('d-m-Y H:i',strtotime($obj['cdatetime']))."</td>
            <td>".$obj['qty']."</td>
            <td>".$obj['customer_name']."</td>
            <td>".$obj['unit_price']."</td>
            <td>".$obj['total_price']."</td>
            <td>".$obj['tax_amt']."</td>
            <td>".number_format($obj['tax_amt']+$obj['total_price'])."</td>
            <td></td>
        </tr>";
    }	
    $b=$b."</tbody></table>";

$users_arr = array();
$users_arr[] = array("PurchaseList" => $a,"SalesList" => $b);		
echo json_encode($users_arr);
?>