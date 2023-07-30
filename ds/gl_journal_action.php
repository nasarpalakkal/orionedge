<?php
session_start();
include("../conn.php");
$admid=$_SESSION['ADUSER'];$RoleID=$_SESSION['RoleID'];
include("db/gl_db.php");
include("db/Address_db.php");
$eid=$_REQUEST['eid'];
$JournalDate=date("Y-m-d", strtotime($_REQUEST['JournalDate']));
if($eid!="")
{
$trans_no=$eid;
}
else
{
$qrymax=mysqli_query($link,"select max(trans_no)from ac_journal");
$objmax=mysqli_fetch_array($qrymax);
$trans_no=$objmax[0]+1;

$OrderNumberArrayJL=GETNextNumber(15,$JournalDate,'ac_journal');			
$JLOrderNumber=$OrderNumberArrayJL['NextNumber'];
$JLOrderNumberNN=$OrderNumberArrayJL['NN'];
UPDATENextNumber(15,$JLOrderNumberNN,$JournalDate);
}
if($_REQUEST['DocumentDate']==""){$DocumentDate="";}else{ $DocumentDate=date("Y-m-d", strtotime($_REQUEST['DocumentDate'])); }
if($_REQUEST['EventDate']==""){$EventDate="";}else{ $EventDate=date("Y-m-d", strtotime($_REQUEST['EventDate'])); }
$Sourceref=$_REQUEST['Sourceref'];
$Reference=$_REQUEST['Reference'];
$Currency=$_REQUEST['Currency'];
$exchangeRate=1;

if($_REQUEST['gross_amt_debit']==$_REQUEST['gross_amt_credit'])
{
$gross_amt=$_REQUEST['gross_amt_debit'];
}
else
{
$gross_amt='';
}
$MainMemo=mysqli_real_escape_string($link,$_REQUEST['MainMemo']);

if($eid=="")
{
mysqli_query($link,"insert into ac_journal(type,trans_no,tran_date,reference,source_ref,event_date,doc_date,currency,amount,rate,memo,uid,cdate,PONumber,next_number,entry_type) values('0','$trans_no',".(($JournalDate=='')?"NULL":("'".$JournalDate."'")) . ",".(($Reference=='')?"NULL":("'".$Reference."'")) . ",".(($Sourceref=='')?"NULL":("'".$Sourceref."'")) . ",".(($EventDate=='')?"NULL":("'".$EventDate."'")) . ",".(($DocumentDate=='')?"NULL":("'".$DocumentDate."'")) . ",".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'1')");
}
else
{
mysqli_query($link,"update ac_journal set tran_date=".(($JournalDate=='')?"NULL":("'".$JournalDate."'")) . ",reference=".(($Reference=='')?"NULL":("'".$Reference."'")) . ",source_ref=".(($Sourceref=='')?"NULL":("'".$Sourceref."'")) . ",event_date=".(($EventDate=='')?"NULL":("'".$EventDate."'")) . ",doc_date=".(($DocumentDate=='')?"NULL":("'".$DocumentDate."'")) . ",currency=".(($Currency=='')?"NULL":("'".$Currency."'")) . ",amount=".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",rate=".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",memo=".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . " where trans_no='$eid'");
mysqli_query($link,"delete from ac_journal_list where trans_no='$eid'");
}

$Countnumbmer=$_REQUEST['Countnumbmer'];
for($i=0;$i<=$Countnumbmer;$i++)
{
	$SalesAccount=$_REQUEST['SalesAccount'][$i];
	$linememo=mysqli_real_escape_string($link,$_REQUEST['linememo'][$i]);
	$Dimension=$_REQUEST['Dimension'][$i];
	$Debit=$_REQUEST['Debit'][$i];
	$Credit=$_REQUEST['Credit'][$i];
	$Dimension1=$_REQUEST['Business_Unit'][$i]; /// Business Unit
	$Dimension2=$_REQUEST['Cost_Center'][$i]; /// Cost Center
	$Dimension3=$_REQUEST['Project'][$i]; /// Project 
		if($SalesAccount!="")
		{
		mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr,dimension_id,dimension2_id,dimension3_id,linememo) values('0','$trans_no',".(($JournalDate=='')?"NULL":("'".$JournalDate."'")) . ",".(($SalesAccount=='')?"NULL":("'".$SalesAccount."'")) . ",".(($Debit=='')?"0":("'".$Debit."'")) . ",".(($Credit=='')?"0":("'".$Credit."'")) . ",".(($Dimension1=='')?"0":("'".$Dimension1."'")) . ",".(($Dimension2=='')?"0":("'".$Dimension2."'")) . ",".(($Dimension3=='')?"0":("'".$Dimension3."'")) . ",".(($linememo=='')?"NULL":("'".$linememo."'")) . ")");
		}
}

if($eid=="")
{
header("location:gl_journal.php?save");
}
else
{
header("location:gl_journal.php?id=$trans_no&&updated");
}
?>