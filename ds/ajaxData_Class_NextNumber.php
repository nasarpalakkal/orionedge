<?php
//Include database configuration file
include('../conn.php');
$Department=$_POST["Department"];

     $query =mysqli_query($link,"SELECT * FROM ac_chart_types WHERE  id='$Department'");    
    //Count total number of rows
   $rowCount = mysqli_num_rows($query);
     //Display cities list
    if($rowCount > 0){
        $row = mysqli_fetch_array($query);
		$cid=$row['class_id'];
        
    }
	$Test="SELECT max(account_code) FROM ac_chart_master WHERE  account_type='$Department'";
	$queryFindNextAccNumber =mysqli_query($link,"SELECT max(account_code) FROM ac_chart_master WHERE  account_type='$Department'");    
	$objectFindNextAccNumber = mysqli_fetch_array($queryFindNextAccNumber);
	$MaxAccID=$objectFindNextAccNumber[0]+1;
						$strlen=strlen($MaxAccID);
						if($strlen==1)
						{
							if(strlen($Department)==1)
							{
						$NewValue=$Department."00000".$MaxAccID;
							}
							else if(strlen($Department)==2)
							{
						$NewValue=$Department."00000".$MaxAccID;
							}
							else if(strlen($Department)==3)
							{
						$NewValue=$Department."0000".$MaxAccID;
							}
							else if(strlen($Department)==4)
							{
						$NewValue=$Department."000".$MaxAccID;
							}
							else
							{
						$NewValue=$Department."0000".$MaxAccID;	
							}
						}
						else if($strlen==8)
						{
						$NewValue=$MaxAccID;						
						}
						/*else if($strlen==2)
						{
						$NewValue=$Department."0000".$MaxAccID;
						}
						else if($strlen==3)
						{
						$NewValue=$Department."000".$MaxAccID;
						}
						else if($strlen==4)
						{
						$NewValue=$Department."00".$MaxAccID;
						}
						else if($strlen==5)
						{
						$NewValue=$Department."0".$MaxAccID;
						}
						else if($strlen==6)
						{
						$NewValue=$Department.$MaxAccID;
						}
						else if($strlen==7)
						{
						$NewValue=$Department.$MaxAccID;
						}*/						
						
						
					

$users_arr = array();
$users_arr[] = array("Allresult" => $cid,"NextAccountNumber" => $NewValue);	
echo json_encode($users_arr);
?>