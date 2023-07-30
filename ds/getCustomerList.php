<?php
include "../conn.php";
$RevenueType= $_POST['RevenueType'];
$Store=$_POST['Store'];
       
	if (empty($_POST['search']) || ctype_space($_POST['search'])) {
			$search="";	
        }
		else
		{
			$search=$_POST['search'];	
		}		

	if($search=="")
	{
			if($RevenueType==2)
			{
				$query = "SELECT * FROM customer_details where RevenueType=2" ;
			}
			else if($RevenueType==3)
			{
				$query = "SELECT * FROM customer_details where RevenueType=3" ;
			}
			else if($RevenueType==1)
			{
				$query = "SELECT * FROM customer_details where RevenueType=1" ;
			}
			else
			{
				$query = "SELECT * FROM customer_details" ;
			}
	
	}
	else
	{
        if ($RevenueType==2) {
			$query = "SELECT id,code,customer_name,CustomerAccount,customer_name_ar,CustomerType,customer_contact1,payment_terms,RevenueType FROM customer_details WHERE RevenueType=2  and store_id='$Store' and (code like'%".$search."%' or customer_name like '%".$search."%' or customer_name_ar like '%".$search."%' or customer_contact1 like '%".$search."%' or CustomerAccount like '%".$search."%')" ;
        }
		else if ($RevenueType==3) {
			$query = "SELECT id,code,customer_name,CustomerAccount,customer_name_ar,CustomerType,customer_contact1,payment_terms,RevenueType FROM customer_details WHERE RevenueType=3  and store_id='$Store' and (code like'%".$search."%' or customer_name like '%".$search."%' or customer_name_ar like '%".$search."%' or customer_contact1 like '%".$search."%' or CustomerAccount like '%".$search."%')" ;
        }
		else if ($RevenueType==1) {
			$query = "SELECT id,code,customer_name,CustomerAccount,customer_name_ar,CustomerType,customer_contact1,payment_terms,RevenueType FROM customer_details WHERE RevenueType=1  and store_id='$Store' and (code like'%".$search."%' or customer_name like '%".$search."%' or customer_name_ar like '%".$search."%' or customer_contact1 like '%".$search."%' or CustomerAccount like '%".$search."%')" ;
        }
			else{
				$query = "SELECT id,code,customer_name,CustomerAccount,customer_name_ar,CustomerType,customer_contact1,payment_terms,RevenueType FROM customer_details WHERE   store_id='$Store' and (code like'%".$search."%' or customer_name like '%".$search."%' or customer_name_ar like '%".$search."%' or customer_contact1 like '%".$search."%' or CustomerAccount like '%".$search."%')" ;
			}
	
	}
 $result = mysqli_query($link,$query);
 
 while($row = mysqli_fetch_array($result) ){
	$payment_terms=$row['payment_terms'];

						$querypt = mysqli_query($link,"SELECT payment_duedays FROM payment_terms_master_details WHERE  payment_id='$payment_terms' limit 1");	
						$rowpt = mysqli_fetch_array($querypt);
						$payment_duedays=$rowpt['payment_duedays'];
						if($payment_duedays>0)
						{
								$duedateext=date('Y-m-d', strtotime('Y-m-d +'.$payment_duedays.' day'));
						}
						else
						{
								$duedateext=$cdate;
						}
				   

 				if($row['CustomerType']==""){$CustomerType=1;}else {$CustomerType=$row['CustomerType'];}
  $response[] = array("value"=>$row['id'],"label"=>$row['code']."-".$row['customer_name']." ".$row['customer_name_ar']."-".$row['customer_contact1'],"CustomerType"=>$CustomerType,"payment_terms"=>$row['payment_terms'],"duedate"=>$duedateext,"RevenueType"=>$row['RevenueType']);
 }

 // encoding array to json format
 echo json_encode($response);
 exit;
?>