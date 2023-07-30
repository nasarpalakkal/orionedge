<?php
include '../conn.php';
	$fetchData = mysqli_query($link,"SELECT A.PONumber,A.item_no,B.item_descr as itemDescription,B.item_descr_ar as itemDescriptionAr FROM billofmaterial as A left JOIN inventory as B on A.item_no=B.item_no"); 
	
$data = array();
$data[] = array("id"=>'', "text"=>'Select Product Details');
while ($row = mysqli_fetch_array($fetchData)) {
    $data[] = array("id"=>$row['PONumber'], "text"=>$row['item_no']."-".$row['itemDescription']."-".$row['itemDescriptionAr']);
}

echo json_encode($data);