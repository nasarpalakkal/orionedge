<?php
//--------------------------------------------------------------------------------------
function GetAccountBalance($type,$accid,$fromdate,$todate,$classid,$currentFiscalYear)
{
	global $link;
				$fromdate=date('Y-m-d',strtotime($fromdate));
				$todate=date('Y-m-d',strtotime($todate));								
					
				if($type==0)
				{					
								if($classid==1)
								{
									$qry_OpeningBalance=mysqli_query($link,"select OpeningBalance from ac_chart_balance where account_code='$accid' and fiscal_year_id='$currentFiscalYear'");
									$obj_OpeningBalance=mysqli_fetch_array($qry_OpeningBalance);
									$OpeningbalanceDr=$obj_OpeningBalance['OpeningBalance'];
									$OpeningbalanceCr=0;
								}
								else
								{
									$qry_OpeningBalance=mysqli_query($link,"select OpeningBalance from ac_chart_balance where account_code='$accid' and fiscal_year_id='$currentFiscalYear'");
									$obj_OpeningBalance=mysqli_fetch_array($qry_OpeningBalance);
									$OpeningbalanceCr=$obj_OpeningBalance['OpeningBalance'];
									$OpeningbalanceDr=0;
								}
				$query_account = mysqli_query($link,"select sum(dr),sum(cr) from ac_gl_trans where account='$accid' and tran_date between '$fromdate' and '$todate'");
      			$obj_account=mysqli_fetch_array($query_account);
				
									$DrValue=$obj_account[0]+$OpeningbalanceDr;
									$CrValue=$obj_account[1]+$OpeningbalanceCr;
											if($DrValue>$CrValue)
											{
											$value=$DrValue-$CrValue;
											}
											else
											{
											$value=$CrValue-$DrValue;
											}				
				}
				else
				{
								if($classid==1)
								{
									$qry_OpeningBalance=mysqli_query($link,"select OpeningBalance from ac_chart_balance where account_type='$accid' and fiscal_year_id='$currentFiscalYear'");
									$obj_OpeningBalance=mysqli_fetch_array($qry_OpeningBalance);
									$OpeningbalanceDr=$obj_OpeningBalance['OpeningBalance'];
									$OpeningbalanceCr=0;
								}
								else
								{
									$qry_OpeningBalance=mysqli_query($link,"select OpeningBalance from ac_chart_balance where account_type='$accid' and fiscal_year_id='$currentFiscalYear'");
									$obj_OpeningBalance=mysqli_fetch_array($qry_OpeningBalance);
									$OpeningbalanceCr=$obj_OpeningBalance['OpeningBalance'];
									$OpeningbalanceDr=0;
								}
				$query_account = mysqli_query($link,"select sum(dr),sum(cr) from ac_gl_trans where ac_chart_group_id like '$accid%' and tran_date between '$fromdate' and '$todate'");
      			$obj_account=mysqli_fetch_array($query_account);
									
									$DrValue=$obj_account[0]+$OpeningbalanceDr;
									$CrValue=$obj_account[1]+$OpeningbalanceCr;
											if($DrValue>$CrValue)
											{
											$value=$DrValue-$CrValue;
											}
											else
											{
											$value=$CrValue-$DrValue;
											}						
				}

	return $value;
}
?>