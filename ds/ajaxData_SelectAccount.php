<?php
//Include database configuration file
include('../conn.php');
if(isset($_POST["category"])){
$category=$_POST["category"];

				if($category==1)
				{
					$query=mysqli_query($link,"select A.SalesDiscountAccount as AccountID,B.account_name as descr from ac_general_gl_setup as A LEFT JOIN ac_chart_master as B on A.SalesDiscountAccount=B.account_code ");										
				}
				else if($category==2)
				{
					$query =mysqli_query($link,"select account_code as AccountID,account_name as descr from ac_chart_master where account_type=51");
				}
				else if($category==3)
				{
					$query=mysqli_query($link,"select A.Sales_Return as AccountID,B.account_name as descr from ac_general_gl_setup as A LEFT JOIN ac_chart_master as B on A.Sales_Return=B.account_code ");											
				}              /////////////// purchase
				if($category==4)
				{
					$query=mysqli_query($link,"select A.PurchaseDiscountAccount as AccountID,B.account_name as descr from ac_general_gl_setup as A LEFT JOIN ac_chart_master as B on A.PurchaseDiscountAccount=B.account_code ");										
				}
				else if($category==5)
				{
					$query =mysqli_query($link,"select account_code as AccountID,account_name as descr from ac_chart_master where account_type=51");
				}
				else if($category==6)
				{
					$query=mysqli_query($link,"select A.PurchaseReturn as AccountID,B.account_name as descr from ac_general_gl_setup as A LEFT JOIN ac_chart_master as B on A.PurchaseReturn=B.account_code ");											
				}

				
				$rowCount = mysqli_num_rows($query);
				if($rowCount > 0){
   				 $users_arr = array();
						 while($row = mysqli_fetch_array($query))
						 {
						 $id=$row['AccountID'];
						 $name=$row['descr'];						 
						 $users_arr[] = array("id" => $id,"name" => $name);
						 }
						        }
echo json_encode($users_arr);
}
?>