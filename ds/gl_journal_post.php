<?php
session_start();
include("../conn.php");
//mysqli_set_charset($link,"utf8");
$admid=$_SESSION['ADUSER'];$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
include("db/gl_db.php");
include("db/Address_db.php");
$eid=$_REQUEST['id'];
$PostDate=date('Y-m-d',strtotime($_REQUEST['PD']));
date_default_timezone_set('Asia/Riyadh');
$currentFiscalYear=GetCurrentFiscalYear();	

	/*$qry=mysqli_query($link,"select * from ac_journal where trans_no='$eid' and posting=0");
	$obj=mysqli_fetch_array($qry);
	$tran_date=$obj['tran_date'];*/
	echo $openPeriod=CheckOpenPeriod($PostDate);

	if($openPeriod==1) /////////////// check period is open
	{
	header("location:gl_journal.php?id=$eid&&perror");
	}
	else if($openPeriod=="")
	{
		header("location:gl_journal.php?id=$eid&&perror1");	
	}
	else
	{
		$qry_ret_sum=mysqli_query($link,"select ROUND(sum(dr),2),ROUND(sum(cr),2) from ac_journal_list where trans_no='$eid'");
		$obj_ret_sum=mysqli_fetch_array($qry_ret_sum);
		$net_amt_dr=roundvalues($obj_ret_sum[0]);
		$net_amt_cr=roundvalues($obj_ret_sum[1]);
		
		if($net_amt_dr!=$net_amt_cr) //////// check Dr Cr is equal or not
		{
		header("location:gl_journal.php?id=$eid&&Berror");
		}
		else
		{
			
			
			
			$qry_journal_list=mysqli_query($link,"select * from ac_journal_list where trans_no='$eid'");
			while($obj_journal_list=mysqli_fetch_array($qry_journal_list))
			{
				
				$type=$obj_journal_list['type'];
				$trans_no=$obj_journal_list['trans_no'];
				$tran_date=$obj_journal_list['tran_date'];
				$Debit=roundvalues($obj_journal_list['dr']);
				$Credit=roundvalues($obj_journal_list['cr']);
				$account=$obj_journal_list['account'];
				$Dimension1=$obj_journal_list['dimension_id'];
				$Dimension2=$obj_journal_list['dimension2_id'];
				$Dimension3=$obj_journal_list['dimension3_id'];

				$query_account = mysqli_query($link,"select account_type from ac_chart_master where account_code='$account'");
				$obj_account=mysqli_fetch_array($query_account);
				$account_type=$obj_account['account_type'];

				$query_account1 = mysqli_query($link,"select class_id from ac_chart_types where id='$account_type'");
				$obj_account1=mysqli_fetch_array($query_account1);
				$parent=$obj_account1['class_id'];

				//$fisical_year=$currentFiscalYear;
											//$query_period = mysqli_query($link,"select id,fisical_year from ac_period where fisical_year='$fisical_year' and  '$tran_date' BETWEEN begin and end ");
											$query_period = mysqli_query($link,"select id,fisical_year from ac_period where '$tran_date' BETWEEN begin and end ");
											$obj_period=mysqli_fetch_array($query_period);
											$period_id=$obj_period['id'];
											$fisical_year=$obj_period['fisical_year'];
			
			mysqli_query($link,"insert into ac_gl_trans(type,type_no,tran_date,post_date,account,ac_chart_class_cid,ac_chart_group_id,dr,cr,dimension_id,dimension2_id,dimension3_id,period_id,fiscal_year,uid,cdate) values('$type','$trans_no','$tran_date','$PostDate',".(($account=='')?"NULL":("'".$account."'")) . ",'$parent',".(($account_type=='')?"NULL":("'".$account_type."'")) . ",".(($Debit=='')?"0":("'".$Debit."'")) . ",".(($Credit=='')?"0":("'".$Credit."'")) . ",".(($Dimension1=='')?"0":("'".$Dimension1."'")) . ",".(($Dimension2=='')?"0":("'".$Dimension2."'")) . ",".(($Dimension3=='')?"0":("'".$Dimension3."'")) . ",'$period_id','$fisical_year','$admid',CURRENT_DATE())");						
			}
			
			mysqli_query($link,"update ac_journal set posting='1',posting_date='$PostDate',posting_by='$admid' where trans_no='$eid'");
			
			header("location:gl_journal.php?id=$eid&&Psuccess");
		}
	}
?>