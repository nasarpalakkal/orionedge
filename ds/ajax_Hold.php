<?php
session_start();
include("../conn.php");
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
$ComputerID=$_SESSION['ComputerID'];
$COMPort=$_SESSION['COMPort'];
$Whid=$_SESSION['WHID'];


date_default_timezone_set('Asia/Riyadh');
if(!empty($_POST["Inv_number"])) 
{
			$uid=$_POST["uid"];
			$cdate=$_POST["salesdate"];
			$Inv_number=$_POST["Inv_number"];
			$TableSelect=$_POST["TableSelect"];
			mysqli_query($link,"insert into tbl_temp_sales_list_hold(invoice_no,sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc,stype,pstatus) select '$Inv_number',sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc,stype,pstatus from tbl_temp_sales where uid='$uid' and invoice_no='$Inv_number'");
			
			
			$productByCode = mysqli_query($link,"SELECT count(sno),sum(total_price),sum(tax_amt),datetime FROM  tbl_temp_sales  where uid='$uid' and invoice_no='$Inv_number' ");
			$objByCode=mysqli_fetch_array($productByCode);
			$total_items=$objByCode[0];
			$TotalAmt=$objByCode[1];
			$nextTaxTotal=$objByCode[2];
			$cdatetime=$objByCode[3];
			
			mysqli_query($link,"insert into tbl_temp_sales_hold(invoice_no,total_items,TotalAmt,tax_amt,uid,date,wh_id,TableID) values('$Inv_number',".(($total_items=='')?"NULL":("'".$total_items."'")) . ",".(($TotalAmt=='')?"NULL":("'".$TotalAmt."'")) . ",".(($nextTaxTotal=='')?"NULL":("'".$nextTaxTotal."'")) . ",'$uid','$cdatetime',".(($Whid=='')?"NULL":("'".$Whid."'")) . ",".(($TableSelect=='')?"NULL":("'".$TableSelect."'")) . ")");
						mysqli_query($link,"delete from tbl_temp_sales where uid='$uid' and invoice_no='$Inv_number'");
			
            if ($COMPort!='') {
                exec("copy /b Hex1.txt $COMPort");
                exec("copy /b Hex3.txt $COMPort");	/////// company name////
            }

			$qryext=mysqli_query($link,"select * from tbl_temp_sales_list_hold where pstatus=0 and invoice_no='$Inv_number'");
			$noext=mysqli_num_rows($qryext);
	
}
else{
	$noext=0;
}
$users_arr = array();
$users_arr[] = array("valueext" => $noext);		
echo json_encode($users_arr);	
?>	
  