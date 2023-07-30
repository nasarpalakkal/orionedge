<?php
include('../conn.php');
if(isset($_POST["code"]) && !empty($_POST["code"])){
$code=$_POST["code"];
$fname=mysqli_real_escape_string($link,strtoupper($_POST["fname"]));
$sname=mysqli_real_escape_string($link,$_POST["sname"]);
$aname=mysqli_real_escape_string($link,$_POST["aname"]);

	 $query = mysqli_query($link,"SELECT * from customer_details where code='$code'");		
	 $no=mysqli_num_rows($query);
	 if($no==0)
	 {
	 
				     $qry1=mysqli_query($link,"select next_number from next_numbering where type='customer_acc'");
					$obj1=mysqli_fetch_array($qry1);
					$CustomerAccount=$obj1['next_number'];
					$nextAccountNumber=$obj1['next_number']+1;
					
					$account_type1='1121';
					$class_id1='1';
					
	 mysqli_query($link,"insert into  customer_details(code,customer_name,customer_name_ar,customer_address,CustomerAccount,Type) values('$code',".(($fname=='')?"NULL":("'".$fname."'")) . ",".(($sname=='')?"NULL":("'".$sname."'")) . ",".(($aname=='')?"NULL":("'".$aname."'")) . ",".(($CustomerAccount=='')?"NULL":("'".$CustomerAccount."'")) . ",'1')");	
	 
	 mysqli_query($link,"insert into ac_chart_master(account_code,account_name,account_name_ar,account_type,cid,inactive) values('$CustomerAccount','$fname',".(($sname=='')?"NULL":("'".$sname."'")) . ",".(($account_type1=='')?"NULL":("'".$account_type1."'")) . ",'$class_id1','0')");
	 
	 $nextupdatenumber=$code+1;
	 mysqli_query($link,"update next_numbering set next_number='$nextAccountNumber' where type='customer_acc'");	
	 mysqli_query($link,"update next_numbering set next_number='$nextupdatenumber' where type='customer'");		
	 }	 
}
$query1 = mysqli_query($link,"SELECT  id,code,customer_name  from customer_details ");
		//Count total number of rows
    $rowCount =mysqli_num_rows($query1);
     //Display cities list
    if($rowCount > 0){	       
		echo '<option value="">Select Customer</option>';
		while($row = mysqli_fetch_array($query1)){             
						if($row['code']==$code)
						{
			echo '<option value="'.$row['id'].'" selected="selected">'.$row['customer_name'].'</option>';
						}
						else
						{
			echo '<option value="'.$row['id'].'" >'.$row['customer_name'].'</option>';
						}
			
        }
    }else{
        echo '<option value=""></option>';
    }
?>