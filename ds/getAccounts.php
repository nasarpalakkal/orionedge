<?php
include "../conn.php";

$search = $_POST['search'];
$type=$_POST['type'];																

	if($search=="")
	{
	$query = "select * from ac_chart_master where cid=5" ;
	}
	else
	{
    $query = "select * from ac_chart_master where cid=5 and (account_name like'%".$search."%' or account_name_ar like '%".$search."%' or account_code like '%".$search."%')";    
	}
 $result = mysqli_query($link,$query);
 
 while($row = mysqli_fetch_array($result) ){
 			$account_code=$row['account_code'];
			$account_name=$row['account_name'];
			$account_name_ar=$row['account_name_ar'];
			
  $response[] = array("value"=>$account_code,"label"=>$account_code."-".$account_name."-".$account_name_ar);
 }
 // encoding array to json format
 echo json_encode($response);
 exit;
?>