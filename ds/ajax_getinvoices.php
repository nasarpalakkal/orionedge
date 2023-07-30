<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
include("../conn.php");
date_default_timezone_set('Asia/Riyadh');
require_once 'multilanguage.php';																		
include('db/gl_db.php');

$accountid=$_POST['accountid'];
$type=$_POST['type'];
$currentFiscalYear=GetCurrentFiscalYear();
$query_fiscalyear = mysqli_query($link,"select begin,end from fiscal_year where id='$currentFiscalYear'");
					$object_fiscalyear = mysqli_fetch_array($query_fiscalyear);
					$FromDate=date('Y-m-d',strtotime($object_fiscalyear['begin']));
					$ToDate=date('Y-m-d');

		$a="<table style=\"width:100%;background-color:#DFDFDF\" id=\"myTable\"><thead>
<tr><th><span class=\"ArFont\">".gettext("Invoice Number")."</span><th><span class=\"ArFont\">".gettext("Invoice Date")."</span></th><th><span class=\"ArFont\">".gettext("Invoice Amount")."</span></th><th><span class=\"ArFont\">".gettext("Due Amount")."</span></th><th><span class=\"ArFont\">".gettext("Allocated Amount")."</span></th><th><span class=\"ArFont\">".gettext("Blance Amount")."</span></th><th><span class=\"ArFont\">".gettext("Apply")."</span></th></tr></thead><tbody></table>";	
						
										if($type==1)
										{
											$customer_qry=mysqli_query($link,"SELECT CustomerAccount FROM customer_details WHERE id='$accountid' ");	
											$obj_qry=mysqli_fetch_array($customer_qry);										

											$AccountCode=$obj_qry['CustomerAccount'];
											$AccountName=GetAccountName($AccountCode)['account_name'];
											$AccountCID = GetAccountName($AccountCode)['cid'];
											$qryAccountType = mysqli_query($link, "select AccountType from ac_chart_class where cid='$AccountCID'");
											$objAccountType = mysqli_fetch_array($qryAccountType);
											$AccountTypeMain = $objAccountType['AccountType'];

											
																						///////////////////////   VIBIN   /////////////////////////////////////////
											$qry_fy = mysqli_query($link, "SELECT id, begin FROM fiscal_year where year(begin) <= year(". $FromDate. ") AND closed=1 order by id DESC LIMIT 1");
											$obj_fy = mysqli_fetch_array($qry_fy);

											$qry_verify = mysqli_query($link, "SELECT id, begin FROM fiscal_year ORDER BY begin ASC LIMIT 1");
											$obj_verify = mysqli_fetch_array($qry_verify);

											if ($obj_fy['id']) {												
											$openFiscalYear = $obj_fy['id'];
											$fyStart = $obj_fy['begin'];
											} else {										
											$openFiscalYear = $obj_verify['id'];
											$fyStart = $obj_verify['begin'];
											}
											
											// Check for last opening date so as to calculate previous balance
											if ($FromDate >= $fyStart) {
												$qry_OpeningBalance_othermonths = mysqli_query($link, "select round(sum(dr),2),round(sum(cr),2) from ac_gl_trans where account='$AccountCode' and tran_date < '$FromDate' ");	
												$obj_OpeningBalance_othermonths = mysqli_fetch_array($qry_OpeningBalance_othermonths);
												$sumDr = $obj_OpeningBalance_othermonths[0];
												$sumCr = $obj_OpeningBalance_othermonths[1];
												
												$balance_total_prev = $sumDr - $sumCr;
											} else {
												$balance_total_prev = 0;
											}

											////////////////////////////////////////////////////////////////

											$qry_OpeningBalance = mysqli_query($link, "select OpeningBalance from ac_chart_balance where account_code='$AccountCode' and fiscal_year_id='$openFiscalYear'");
											$obj_OpeningBalance = mysqli_fetch_array($qry_OpeningBalance);

											// Only for the opening balance we check of Credit balance account, then multiply by -1
											// Rest of the caluclation uses (Dr - Cr) to find the difference
											$Openingbalance =  $AccountTypeMain == "CR" ? -$obj_OpeningBalance['OpeningBalance'] : $obj_OpeningBalance['OpeningBalance'];

											$select_query = mysqli_query($link, "SELECT ac_gl_trans.tran_date,ac_gl_trans.dr,ac_gl_trans.cr,ac_journal.memo,ac_journal.reference,ac_gl_trans.account,ac_chart_class.AccountType,s.duedate FROM ac_gl_trans left join ac_journal on ac_gl_trans.type_no=ac_journal.trans_no left join ac_chart_class on ac_gl_trans.ac_chart_class_cid=ac_chart_class.ctype left join sales as s on (ac_journal.document_number=s.PONumber and ac_journal.document_type='sale') left join purchase as p on (ac_journal.document_number=s.PONumber and ac_journal.document_type='purchase')  WHERE ac_gl_trans.account='$AccountCode' and ac_gl_trans.tran_date between '$FromDate' and '$ToDate' ORDER BY ac_gl_trans.tran_date asc");
											$debit_total = 0;
											$credit_total = 0;
											$balance_total = $Openingbalance + $balance_total_prev + $balance_total;

											while ($row = mysqli_fetch_array($select_query)) {
												$tran_date = date('d-m-Y', strtotime($row['tran_date']));
												$drAmt = $row['dr'];
												$crAmt = $row['cr'];
												$debit_total = $debit_total + $drAmt;
												$credit_total = $credit_total + $crAmt;
												$balance_total = $balance_total + $drAmt - $crAmt;

												}
													
		$i=0;
		$b=0;
		$item=mysqli_query($link,"SELECT PONumber,net_amt,cdate FROM sales WHERE CustomerName='$accountid' and CashType='2' and payment_status=0");	
		$nos=mysqli_num_rows($item);	
		while($objitem=mysqli_fetch_array($item))
		{		
    			$invoice_no=$objitem['PONumber'];				
				$net_amt=$objitem['net_amt'];
				$net_amt_display=number_format($objitem['net_amt'],2,'.',',');
				$invoiceDate_display=date('d-m-Y',strtotime($objitem['cdate']));
				$invoiceDate=$objitem['cdate'];
						$item1=mysqli_query($link,"SELECT sum(amount_due) FROM voucher_list WHERE PONumber='$invoice_no'");	
						$object1=mysqli_fetch_array($item1);
						$dueAmount=$net_amt-$object1[0];						
				$a=$a."<tr>	
				<td  ><font size=\"2.2\">$invoice_no  <input type=\"hidden\" value='$invoice_no' id='invoice_no_show$i' name='invoice_no_show[]'></font></td>
				<td ><font size=\"2.2\">$invoiceDate_display <input type=\"hidden\" value='$invoiceDate' id='invoice_date_show$i' name='invoice_date_show[]'></font></td>
				<td><font size=\"2.2\">$net_amt_display  <input type=\"hidden\" value='$dueAmount' id='invoice_amt_show$i' name='invoice_amt_show[]'></font></td>
				<td><font size=\"2.2\">$dueAmount  </font></td>
				<td><input type=\"text\" value='' id='Amt_given$i' name='Amt_given[]' size=10 style=\"text-align:center;\" autocomplete='off' readonly=\"readonly\"></td>
				<td><input type=\"text\" value='' id='Amt_balance$i' name='Amt_balance[]' size=10 style=\"text-align:center;\" readonly=\"readonly\"></td>
				<td><input type=\"checkbox\" id='apply$i' class=\"checkbox\" onclick=\"frmClickApply($i,'$dueAmount')\"> <input type=\"hidden\" name='cBox[]' id='cBox$i' value='0'/></td>
				
				</tr>";		
				
				$b=$b+$dueAmount;		
				$i=$i+1;
		}	
																							$qry_acc=mysqli_query($link,"select CustomerAccount from customer_details where id='$accountid'");
																							$obj_acc=mysqli_fetch_array($qry_acc);																							
																							$CustomerAccount=$obj_acc['CustomerAccount'];
																							
											$qry_advance_amt=mysqli_query($link,"select ROUND(sum(dr),2),ROUND(sum(cr),2) from ac_gl_trans where account='$CustomerAccount'");	
											$obj_advance_amt=mysqli_fetch_array($qry_advance_amt);
											$advance_amt1=$obj_advance_amt[1]-$obj_advance_amt[0];
												if($advance_amt1!="")
													{
													$advance_amt=$advance_amt1;
													}
													else
													{
													$advance_amt=0;
													}
											
																							/*$qry_acc=mysqli_query($link,"select CustomerAdavance from customer_details where id='$accountid'");
																							$obj_acc=mysqli_fetch_array($qry_acc);																							
																							$CustomerAdavance=$obj_acc['CustomerAdavance'];
											$qry_advance_amt=mysqli_query($link,"select ROUND(sum(dr),2),ROUND(sum(cr),2) from ac_gl_trans where account='$CustomerAdavance'");	
											$obj_advance_amt=mysqli_fetch_array($qry_advance_amt);
											$advance_amt=$obj_advance_amt[1]-$obj_advance_amt[0];*/
		
									}
									else
									{

										$customer_qry=mysqli_query($link,"SELECT SupplierAccount FROM supplier_details WHERE id='$accountid' ");	
											$obj_qry=mysqli_fetch_array($customer_qry);										

											$AccountCode=$obj_qry['SupplierAccount'];
											$AccountName=GetAccountName($AccountCode)['account_name'];
											$AccountCID = GetAccountName($AccountCode)['cid'];
											$qryAccountType = mysqli_query($link, "select AccountType from ac_chart_class where cid='$AccountCID'");
											$objAccountType = mysqli_fetch_array($qryAccountType);
											$AccountTypeMain = $objAccountType['AccountType'];

											
																						///////////////////////   VIBIN   /////////////////////////////////////////
											$qry_fy = mysqli_query($link, "SELECT id, begin FROM fiscal_year where year(begin) <= year(". $FromDate. ") AND closed=1 order by id DESC LIMIT 1");
											$obj_fy = mysqli_fetch_array($qry_fy);

											$qry_verify = mysqli_query($link, "SELECT id, begin FROM fiscal_year ORDER BY begin ASC LIMIT 1");
											$obj_verify = mysqli_fetch_array($qry_verify);

											if ($obj_fy['id']) {												
											$openFiscalYear = $obj_fy['id'];
											$fyStart = $obj_fy['begin'];
											} else {										
											$openFiscalYear = $obj_verify['id'];
											$fyStart = $obj_verify['begin'];
											}
											
											// Check for last opening date so as to calculate previous balance
											if ($FromDate >= $fyStart) {
												$qry_OpeningBalance_othermonths = mysqli_query($link, "select round(sum(dr),2),round(sum(cr),2) from ac_gl_trans where account='$AccountCode' and tran_date < '$FromDate' ");	
												$obj_OpeningBalance_othermonths = mysqli_fetch_array($qry_OpeningBalance_othermonths);
												$sumDr = $obj_OpeningBalance_othermonths[0];
												$sumCr = $obj_OpeningBalance_othermonths[1];
												
												$balance_total_prev = $sumDr - $sumCr;
											} else {
												$balance_total_prev = 0;
											}

											////////////////////////////////////////////////////////////////

											$qry_OpeningBalance = mysqli_query($link, "select OpeningBalance from ac_chart_balance where account_code='$AccountCode' and fiscal_year_id='$openFiscalYear'");
											$obj_OpeningBalance = mysqli_fetch_array($qry_OpeningBalance);

											// Only for the opening balance we check of Credit balance account, then multiply by -1
											// Rest of the caluclation uses (Dr - Cr) to find the difference
											$Openingbalance =  $AccountTypeMain == "CR" ? -$obj_OpeningBalance['OpeningBalance'] : $obj_OpeningBalance['OpeningBalance'];

											$select_query = mysqli_query($link, "SELECT ac_gl_trans.tran_date,ac_gl_trans.dr,ac_gl_trans.cr,ac_journal.memo,ac_journal.reference,ac_gl_trans.account,ac_chart_class.AccountType,s.duedate FROM ac_gl_trans left join ac_journal on ac_gl_trans.type_no=ac_journal.trans_no left join ac_chart_class on ac_gl_trans.ac_chart_class_cid=ac_chart_class.ctype left join sales as s on (ac_journal.document_number=s.PONumber and ac_journal.document_type='sale') left join purchase as p on (ac_journal.document_number=s.PONumber and ac_journal.document_type='purchase')  WHERE ac_gl_trans.account='$AccountCode' and ac_gl_trans.tran_date between '$FromDate' and '$ToDate' ORDER BY ac_gl_trans.tran_date asc");
											$debit_total = 0;
											$credit_total = 0;
											$balance_total = $Openingbalance + $balance_total_prev + $balance_total;

											while ($row = mysqli_fetch_array($select_query)) {
												$tran_date = date('d-m-Y', strtotime($row['tran_date']));
												$drAmt = $row['dr'];
												$crAmt = $row['cr'];
												$debit_total = $debit_total + $drAmt;
												$credit_total = $credit_total + $crAmt;
												$balance_total = $balance_total + $drAmt - $crAmt;

												}

		$i=0;
		$b=0;
		$item=mysqli_query($link,"SELECT PONumber,net_amt,cdate FROM purchase WHERE SupplierName='$accountid' and PurchaseType='2' and payment_status=0");	
		$nos=mysqli_num_rows($item);	
		while($objitem=mysqli_fetch_array($item))
		{		
    			$invoice_no=$objitem['PONumber'];				
				$net_amt=$objitem['net_amt'];
				$net_amt_display=number_format($objitem['net_amt'],2,'.',',');
				$invoiceDate_display=date('d-m-Y',strtotime($objitem['cdate']));
				$invoiceDate=$objitem['cdate'];
						$item1=mysqli_query($link,"SELECT sum(amount_due) FROM voucher_list WHERE PONumber='$invoice_no'");	
						$object1=mysqli_fetch_array($item1);
						$dueAmount=$net_amt-$object1[0];
				$a=$a."<tr>	
				<td  ><font size=\"2.2\">$invoice_no  <input type=\"hidden\" value='$invoice_no' id='invoice_no_show$i' name='invoice_no_show[]'></font></td>
				<td ><font size=\"2.2\">$invoiceDate_display <input type=\"hidden\" value='$invoiceDate' id='invoice_date_show$i' name='invoice_date_show[]'></font></td>
				<td><font size=\"2.2\">$net_amt_display  <input type=\"hidden\" value='$dueAmount' id='invoice_amt_show$i' name='invoice_amt_show[]'></font></td>
				<td><font size=\"2.2\">$dueAmount  </font></td>
				<td><input type=\"text\" value='' id='Amt_given$i' name='Amt_given[]' size=10 style=\"text-align:center;\" autocomplete='off' readonly=\"readonly\"></td>
				<td><input type=\"text\" value='' id='Amt_balance$i' name='Amt_balance[]' size=10 style=\"text-align:center;\" readonly=\"readonly\"></td>
				<td><input type=\"checkbox\" id='apply$i' class=\"checkbox\" onclick=\"frmClickApply($i,'$dueAmount')\"> <input type=\"hidden\" name='cBox[]' id='cBox$i' value='0'/></td>									
				</tr>";		
				
				$b=$b+$dueAmount;		
				$i=$i+1;
		}
								
																							$qry_acc=mysqli_query($link,"select SupplierAccount from supplier_details where id='$accountid'");
																							$obj_acc=mysqli_fetch_array($qry_acc);																							
																							$CustomerAccount=$obj_acc['SupplierAccount'];
																							
											$qry_advance_amt=mysqli_query($link,"select ROUND(sum(dr),2),ROUND(sum(cr),2) from ac_gl_trans where account='$CustomerAccount'");	
											$obj_advance_amt=mysqli_fetch_array($qry_advance_amt);
											$advance_amt1=$obj_advance_amt[1]-$obj_advance_amt[0];
												if($advance_amt1!="")
													{
													$advance_amt=$advance_amt1;
													}
													else
													{
													$advance_amt=0;
													}
		
									}
		
$users_arr = array();
$users_arr[] = array("Allresult" =>$a,"BalnceAmount" =>$b,"TotalInvoiceNo" =>$nos,"AdvanceAmount" =>$balance_total);	
echo json_encode($users_arr);	
?>	
  
  