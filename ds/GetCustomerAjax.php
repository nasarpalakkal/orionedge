<?php
//include 'config.php';
include '../conn.php';

// Number of records fetch
$numberofrecords = 10;

if(!isset($_POST['searchTerm'])){
	$stmt=mysqli_query($link,"select * from customer_details limit 10");

}else{

	$search = $_POST['searchTerm'];// Search text
	$stmt=mysqli_query($link,"select * from customer_details where (customer_name like '%$search%' or code like '%$search%' or customer_name_ar like '%$search%')");
}
	
$response = array();

$response[] = array(
    "id" => "",
    "text" => "-Select Customer-"
 );

while($usersList=mysqli_fetch_array($stmt)){
    $response[] = array(
       "id" => $usersList['id'],
       "text" => $usersList['code']."-".$usersList['customer_name']
    );
 }

echo json_encode($response);
exit();
