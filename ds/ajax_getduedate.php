<?php
include "../conn.php";

	$itemext = $_POST['itemext'];
    $cdate = date('Y-m-d',strtotime($_POST['cdate']));
																		
															
	$query = mysqli_query($link,"SELECT payment_duedays FROM payment_terms_master_details WHERE  payment_id='$itemext' limit 1");	
 	$row = mysqli_fetch_array($query);
 	$payment_duedays=$row['payment_duedays'];            
		
            if($payment_duedays>0)
            {
                    $duedateext=date('Y-m-d', strtotime($cdate . ' +'.$payment_duedays.' day'));
            }
            else
            {
                    $duedateext=$cdate;
            }
            			
			
	
			
 $response[] = array("value"=>$duedateext);
 // encoding array to json format
 echo json_encode($response);
 exit;
?>