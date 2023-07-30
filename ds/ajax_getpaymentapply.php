<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
include("../conn.php");
date_default_timezone_set('Asia/Riyadh');
require_once 'multilanguage.php';																		

$so=$_POST['sno'];
$amt=$_POST['amt'];
$givenamt=$_POST['givenamt'];
			
			
			if($givenamt>$amt)
			{
				$a=$amt;
				$b=0;
				$c=$givenamt-$amt;
			}
			else
			{
				$a=$givenamt;
				$b=$amt-$givenamt;
				$c=0;
			}
			
$users_arr = array();
$users_arr[] = array("givenvalue" =>number_format($a,2,'.',''),"balancevalue" =>number_format($b,2,'.',''),"finalbalance" =>number_format($c,2,'.',''));	
echo json_encode($users_arr);	
?>	
  
  