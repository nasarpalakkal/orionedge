<?php
session_start();
include("../conn.php");
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
$RoleMaster=$_SESSION['RoleMaster'];
if($admid=="")
{
include("logout.php");
}
include("db/Address_db.php");	
date_default_timezone_set('Asia/Riyadh');
GETNextNumberGoTo:			
				$eid=$_REQUEST['eid'];				
				
				$holdval=$_REQUEST['holdval'];
			    $printval=$_REQUEST['printval'];
					if($holdval==1)
					{
				$SalesCat=3; //// Hold
					}
					else
					{
				$SalesCat=$_REQUEST['SalesCat']; //// sale or return	
					}
				$cdate=date('Y-m-d',strtotime($_REQUEST['FDate']));
				$duedate=date('Y-m-d',strtotime($_REQUEST['DueDate']));
				$Ebill=$_REQUEST['Ebill'];

				if($_REQUEST['OrderNumber']=="")
				{	
					if($SalesCat==2)
					{
						if($Ebill==1)
						{
							$OrderNumberArray=GETNextNumber(22,$cdate,'sales');			
							$OrderNumber=$OrderNumberArray['NextNumber'];
							$OrderNumberNN=$OrderNumberArray['NN'];
							$Test=$OrderNumberArray['test'];
							UPDATENextNumber(22,$OrderNumberNN,$cdate);

								$qry_ext=mysqli_query($link,"select * from sales where PONumber='$OrderNumber'");
								$nos_ext=mysqli_num_rows($qry_ext);
								if($nos_ext>0)
								{
									goto GETNextNumberGoTo;
								}  

						}
						else
						{
							$OrderNumberArray=GETNextNumber(21,$cdate,'sales');			
							$OrderNumber=$OrderNumberArray['NextNumber'];
							$OrderNumberNN=$OrderNumberArray['NN'];
							$Test=$OrderNumberArray['test'];
							UPDATENextNumber(21,$OrderNumberNN,$cdate);

								$qry_ext=mysqli_query($link,"select * from sales where PONumber='$OrderNumber'");
								$nos_ext=mysqli_num_rows($qry_ext);
								if($nos_ext>0)
								{
									goto GETNextNumberGoTo;
								}  
						}	
					}
					else
					{
						if($Ebill==1)
						{
							$OrderNumberArray=GETNextNumber(16,$cdate,'sales');			
							$OrderNumber=$OrderNumberArray['NextNumber'];
							$OrderNumberNN=$OrderNumberArray['NN'];
							$Test=$OrderNumberArray['test'];
							UPDATENextNumber(16,$OrderNumberNN,$cdate);

								$qry_ext=mysqli_query($link,"select * from sales where PONumber='$OrderNumber'");
								$nos_ext=mysqli_num_rows($qry_ext);
								if($nos_ext>0)
								{
									goto GETNextNumberGoTo;
								}  
						}
						else
						{
							$OrderNumberArray=GETNextNumber(1,$cdate,'sales');			
							$OrderNumber=$OrderNumberArray['NextNumber'];
							$OrderNumberNN=$OrderNumberArray['NN'];
							$Test=$OrderNumberArray['test'];
							UPDATENextNumber(1,$OrderNumberNN,$cdate);

								$qry_ext=mysqli_query($link,"select * from sales where PONumber='$OrderNumber'");
								$nos_ext=mysqli_num_rows($qry_ext);
								if($nos_ext>0)
								{
									goto GETNextNumberGoTo;
								}  
						}	
					}
											
															
				}
				else
				{
		$OrderNumber=$_REQUEST['OrderNumber'];
				}					
				$CustomerName=$_REQUEST['CustomerName'];
				$CustomerBranchID=$_REQUEST['CustomerBranchID'];
				$CustomerCode=CustomerDetails($CustomerName)['code'];
				$SupplierNameValue=mysqli_real_escape_string($link,CustomerDetails($CustomerName)['customer_name']);
				$BillNumber=mysqli_real_escape_string($link,$_REQUEST['ReferenceDetails']);
				$Totalrow=$_REQUEST['Countnumbmer'];
				$SalesType=$_REQUEST['SalesType'];
					
				$CashType=$_REQUEST['CashType'];
				$ReasonForReturn=mysqli_real_escape_string($link,$_REQUEST['ReasonForReturn']);
				$PaymentTerm=$_REQUEST['PaymentTerm'];
				$originalinvoicenumber=$_REQUEST['originalinvoicenumber'];
				$PurchaseNumber=$_REQUEST['PurchaseNumber'];
				
				
				$cdatetime=date('Y-m-d H:i:s');
				$cashAmt=$_REQUEST['cashAmt'];
				$cardAmt=$_REQUEST['cardAmt'];
				
						if($CashType==1){$paymentType=$_REQUEST['cash']; } else {$paymentType=0;}
				
				$radio=$_REQUEST['radio'];
				if($radio==1){$percenatage_val="";} else {$percenatage_val=$_REQUEST['percenatage_val'];}
				
				$sub_total=number_format(preg_replace('/[^\d.]/', '', $_REQUEST['sub_total']),2,'.','');
				$discount_amt=number_format(preg_replace('/[^\d.]/', '', $_REQUEST['discount_amt']),2,'.','');
				$Tax_amt=number_format(preg_replace('/[^\d.]/', '', $_REQUEST['Tax_amt']),2,'.','');
				$gross_amt=number_format(preg_replace('/[^\d.]/', '', $_REQUEST['gross_amt']),2,'.','');
				
				if ($CashType==1) {
					if ($cashAmt>0 && $cardAmt>0) {
						$paymentType=3;
						$payment_status=1;
					} elseif ($cashAmt>0 && $cardAmt==0) {
						$paymentType=1;
						$payment_status=1;
					} elseif ($cashAmt==0 && $cardAmt>0) {
						{
							$payment_status=1;	
							$paymentType=2;
							}
					} else {
						$payment_status=0;
						$paymentType=0;
					}
				}
				else {
					$payment_status=0;
					$paymentType=0;
				}	

				$trans_no=0;
				$whid=$_REQUEST['Store'];																		
					
				
										
				
				
				$qry_estimate=mysqli_query($link,"select A.item_no,A.unit,A.qty,B.factor_val from sales_list as A left join inventory_uom as B on A.item_no=B.item_no and A.unit=B.unit where PONumber='$OrderNumber' ");
				$nos_estimate=mysqli_num_rows($qry_estimate);
				if($nos_estimate>0)
				{
				mysqli_query($link,"delete from sales_list where PONumber='$OrderNumber'");
				}													
				
				
				$TotalOrderQty=0;
				$TotalRecQty=0;
				$finalAvgCost=0;
				$sales_profit=0;
				for($i=0;$i<=$Totalrow;$i++)
				{
				$ItemMasterID=$_REQUEST['ItemMasterID'][$i];	
				$Itemdetails=mysqli_real_escape_string($link,$_REQUEST['details'][$i]);				
				$sno=$_REQUEST['unit'][$i];
				$qry_inv2=mysqli_query($link,"select unit,factor_val from inventory_uom where item_no='$ItemMasterID' and sno='$sno'");																
				$obj_inv2=mysqli_fetch_array($qry_inv2);
				$unit=$obj_inv2['unit'];
				$factor_val=$obj_inv2['factor_val']??0;
				
				$qry_inv3=mysqli_query($link,"select BaseLastCost,AvgCost,SalesPrice FROM inventory WHERE item_no='$ItemMasterID'");				
				$obj_inv3=mysqli_fetch_array($qry_inv3);
				$unit_price33=$obj_inv3['SalesPrice']??0;
				
				$unit_price=$_REQUEST['sprice'][$i]??0;				
				$qty=$_REQUEST['qty'][$i]??1;
				$TotalPrice=$_REQUEST['amount'][$i];
				$TaxAmt=$_REQUEST['taxamt_unit'][$i];						
				$TaxPerc=$_REQUEST['TaxPerc'][$i];						
				$ItemType=$_REQUEST['ItemType'][$i];
				$AmountwithTax=$_REQUEST['AmountwithTax'][$i];	
				$LastCost=$_REQUEST['LastCost'][$i]??0;			
				$item_profit=((doubleval($unit_price33)*doubleval($qty0*$factor_val))-(doubleval($LastCost)*doubleval($qty??0*$factor_val??0)))??0;	
				$sales_profit=doubleval($sales_profit)+doubleval($item_profit);
									if($ItemMasterID!='')
									{													
				mysqli_query($link,"insert into sales_list(PONumber,sno,item_no,details,unit,unit_price,qty,total_price,tax_amt,created_by,createdDate,item_type,TaxPer,unitsno,unitprice_withtax,item_profit) values('$OrderNumber','$i','$ItemMasterID',".(($Itemdetails=='')?"NULL":("'".$Itemdetails."'")) . ",".(($unit=='')?"NULL":("'".$unit."'")) . ",".(($unit_price=='')?"NULL":("'".$unit_price."'")) . ",".(($qty=='')?"NULL":("'".$qty."'")) . ",".(($TotalPrice=='')?"NULL":("'".$TotalPrice."'")) . ",".(($TaxAmt=='')?"NULL":("'".$TaxAmt."'")) . ",'$admid','$cdatetime',".(($ItemType=='')?"NULL":("'".$ItemType."'")) . ",".(($TaxPerc=='')?"NULL":("'".$TaxPerc."'")) . ",'$sno',".(($AmountwithTax=='')?"NULL":("'".$AmountwithTax."'")) . ",".(($item_profit=='')?"0":("'".$item_profit."'")) . ")");
									 $varibaletxt="insert into sales_list(PONumber,sno,item_no,details,unit,unit_price,qty,total_price,tax_amt,created_by,createdDate,item_type,TaxPer,unitsno,unitprice_withtax) values('$OrderNumber','$i','$ItemMasterID',".(($Itemdetails=='')?"NULL":("'".$Itemdetails."'")) . ",".(($unit=='')?"NULL":("'".$unit."'")) . ",".(($unit_price=='')?"NULL":("'".$unit_price."'")) . ",".(($qty=='')?"NULL":("'".$qty."'")) . ",".(($TotalPrice=='')?"NULL":("'".$TotalPrice."'")) . ",".(($TaxAmt=='')?"NULL":("'".$TaxAmt."'")) . ",'$admid','$cdatetime',".(($ItemType=='')?"NULL":("'".$ItemType."'")) . ",".(($TaxPerc=='')?"NULL":("'".$TaxPerc."'")) . ",'$sno',".(($AmountwithTax=='')?"NULL":("'".$AmountwithTax."'")) . ",".(($item_profit=='')?"0":("'".$item_profit."'")) . ")"."---Line Items"."\n";
											$myfile = fopen("sales_log.txt", "a");
											fwrite($myfile, $varibaletxt);
											fclose($myfile);												
										
										$AvgCost=$_REQUEST['AvgCost'][$i]??0;
										$factor_val=$_REQUEST['factor_val'][$i]??1;
										$finalAvgCost=roundvalues(doubleval($finalAvgCost??0)+((doubleval($qty)*doubleval($factor_val))*doubleval($AvgCost)));								
																
									}				
				 }
											
											$qry_initial=mysqli_query($link,"select * from sales where PONumber='$OrderNumber'");
											$nos_initial=mysqli_num_rows($qry_initial);
											////////// For Accounts ///////////////////
																	
											if($gross_amt>0)
											{
																if($SalesCat==1) /////// Sales
																{																							
											$MainMemo="Sales from ".$CustomerCode.'-'.$SupplierNameValue."-Sales Order Number-".$OrderNumber;
												if($nos_initial>0)
												{
														$trans_no_old=$_REQUEST['journal_id'];
														if($trans_no_old==0)
														{
														$qrymax=mysqli_query($link,"select max(trans_no)from ac_journal");
														$objmax=mysqli_fetch_array($qrymax);
														$trans_no=$objmax[0]+1;
														
														$OrderNumberArrayJL=GETNextNumber(4,$cdate,'ac_journal');			
														$JLOrderNumber=$OrderNumberArrayJL['NextNumber'];
														$JLOrderNumberNN=$OrderNumberArrayJL['NN'];
														UPDATENextNumber(4,$JLOrderNumberNN,$cdate);
														}
														else
														{
														$trans_no=$trans_no_old;
														}
																												
											mysqli_query($link,"delete from ac_journal_list  where trans_no='$trans_no' ");
												}
												else
												{																		
											$qrymax=mysqli_query($link,"select max(trans_no)from ac_journal");
											$objmax=mysqli_fetch_array($qrymax);
											$trans_no=$objmax[0]+1;

														$OrderNumberArrayJL=GETNextNumber(4,$cdate,'ac_journal');			
														$JLOrderNumber=$OrderNumberArrayJL['NextNumber'];
														$JLOrderNumberNN=$OrderNumberArrayJL['NN'];
														UPDATENextNumber(4,$JLOrderNumberNN,$cdate);

												}
											$Currency="SAR";
											$exchangeRate=1;
											
										$qry_acc_cr=mysqli_query($link,"select SalesAccount,COGSAccount,InventoryAccount from ac_general_gl_setup");
										$obj_acc_cr=mysqli_fetch_array($qry_acc_cr);
										$account_code_cr=$obj_acc_cr['SalesAccount'];
										$COGSAccount=$obj_acc_cr['COGSAccount'];
										$InventoryAccount=$obj_acc_cr['InventoryAccount'];														
										
												
												
										
										$reference="Sales Invoice Number-".$OrderNumber;
										$source_ref="Sales Invoice Number-".$OrderNumber;
														/////// journal header///////
														if($nos_initial>0)
															{
																if($trans_no_old==0)
																{
																mysqli_query($link,"insert into ac_journal(type,trans_no,tran_date,reference,source_ref,doc_date,currency,amount,rate,memo,uid,cdate,order_amt,PONumber,next_number,document_number,document_type) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",'$reference',".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",'$cdate',".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'$OrderNumber','sale')");
																}
																else
																{
																mysqli_query($link,"update ac_journal set reference='$reference',source_ref=".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",doc_date='$cdate',amount=".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",memo=".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",uid='$admid',order_amt=".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",tran_date=".(($cdate=='')?"NULL":("'".$cdate."'")) . " where trans_no='$trans_no'");
																}											
															}
															else
															{
					mysqli_query($link,"insert into ac_journal(type,trans_no,tran_date,reference,source_ref,doc_date,currency,amount,rate,memo,uid,cdate,order_amt,PONumber,next_number,document_number,document_type) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",'$reference',".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",'$cdate',".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'$OrderNumber','sale')");

					$varibaletxt1="insert into ac_journal(type,trans_no,tran_date,reference,source_ref,doc_date,currency,amount,rate,memo,uid,cdate,order_amt,PONumber,next_number,document_number,document_type) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",'$reference',".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",'$cdate',".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'$OrderNumber','sale')";

					$myfile = fopen("acjournal_log.txt", "a");
					fwrite($myfile, $varibaletxt1);
					fclose($myfile);

															}										
														
															/////////////Debit Account////
															if($paymentType==0)
															{
																		$qry_acc_dr=mysqli_query($link,"select CustomerAccount from customer_details where id='$CustomerName'");
																		$obj_acc_dr=mysqli_fetch_array($qry_acc_dr);
																		$account_code_dr=$obj_acc_dr['CustomerAccount'];
																		mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($account_code_dr=='')?"NULL":("'".$account_code_dr."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",'0')");
															} 	
															else
															{
																if($cashAmt>0)
																{
																	$qry_acc_dr=mysqli_query($link,"select CashAccount from ac_general_gl_setup");
																	$obj_acc_dr=mysqli_fetch_array($qry_acc_dr);
																	$account_code_dr=$obj_acc_dr['CashAccount'];

																	mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($account_code_dr=='')?"NULL":("'".$account_code_dr."'")) . ",".(($cashAmt=='')?"0":("'".$cashAmt."'")) . ",'0')");
																}
																if($cardAmt>0)
																{
																	$qry_acc_dr=mysqli_query($link,"select BankAccount from ac_general_gl_setup");
																	$obj_acc_dr=mysqli_fetch_array($qry_acc_dr);
																	$account_code_dr=$obj_acc_dr['BankAccount'];

																	mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($account_code_dr=='')?"NULL":("'".$account_code_dr."'")) . ",".(($cardAmt=='')?"0":("'".$cardAmt."'")) . ",'0')");
																}
															}
															
																											
															
															

											/////////////Credit Account////																	
					mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($account_code_cr=='')?"NULL":("'".$account_code_cr."'")) . ",'0',".(($sub_total=='')?"0":("'".$sub_total."'")) . ")");
											
														///////////////////////// Tax ////////////
												if($Tax_amt>0)
												{
												$qry_Tax_acc=mysqli_query($link,"select SalesAccount from tax_type where perc='5' ");
												$obj_Tax_acc=mysqli_fetch_array($qry_Tax_acc);
												$SalesTaxAccount=$obj_Tax_acc['SalesAccount'];
												
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($SalesTaxAccount=='')?"NULL":("'".$SalesTaxAccount."'")) . ",'0',".(($Tax_amt=='')?"0":("'".$Tax_amt."'")) . ")");
												}
														///////////////////////// Discount ////////////
												if($discount_amt>0)
												{
												$qry_discount_acc=mysqli_query($link,"select SalesDiscountAccount from ac_general_gl_setup ");
												$obj_discount_acc=mysqli_fetch_array($qry_discount_acc);
												$SalesDiscountAccount=$obj_discount_acc['SalesDiscountAccount'];
												
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($SalesDiscountAccount=='')?"NULL":("'".$SalesDiscountAccount."'")) . ",".(($discount_amt=='')?"0":("'".$discount_amt."'")) . ",'0')");
												}
												
												//////////////////////// update inventory Account
												if($finalAvgCost>0)
												{
																//// COGS
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($COGSAccount=='')?"NULL":("'".$COGSAccount."'")) . ",".(($finalAvgCost=='')?"0":("'".$finalAvgCost."'")) . ",'0')");
																///Inventory
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($InventoryAccount=='')?"NULL":("'".$InventoryAccount."'")) . ",'0',".(($finalAvgCost=='')?"0":("'".$finalAvgCost."'")) . ")");
												}

																		/////////////////// post journal //////////////////
																		$PostDate=$cdate;
																		$qry_journal_list=mysqli_query($link,"select * from ac_journal_list where trans_no='$trans_no'");
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

																			// $query_period = mysqli_query($link,"select id,fisical_year from ac_period where '$tran_date' BETWEEN begin and end ");
																			// 							$obj_period=mysqli_fetch_array($query_period);
																			// 							$period_id=$obj_period['id'];
																			// 							$fisical_year=$obj_period['fisical_year'];
																		
																			$period_id=1;
																			$fisical_year=2;

																		mysqli_query($link,"insert into ac_gl_trans(type,type_no,tran_date,post_date,account,ac_chart_class_cid,ac_chart_group_id,dr,cr,dimension_id,dimension2_id,dimension3_id,period_id,fiscal_year,uid,cdate) values('$type','$trans_no','$tran_date','$PostDate',".(($account=='')?"NULL":("'".$account."'")) . ",'$parent',".(($account_type=='')?"NULL":("'".$account_type."'")) . ",".(($Debit=='')?"0":("'".$Debit."'")) . ",".(($Credit=='')?"0":("'".$Credit."'")) . ",".(($Dimension1=='')?"0":("'".$Dimension1."'")) . ",".(($Dimension2=='')?"0":("'".$Dimension2."'")) . ",".(($Dimension3=='')?"0":("'".$Dimension3."'")) . ",'$period_id','$fisical_year','$admid',CURRENT_DATE())");		
																							
																		}
																		
																		mysqli_query($link,"update ac_journal set posting='1',posting_date='$PostDate',posting_by='$admid' where trans_no='$trans_no'");
												
												
																		}///////Sales end
																		else if($SalesCat==2)//// Sales return
																		{
																		$MainMemo="Sales Return to ".$CustomerCode.'-'.$SupplierNameValue."-Sales Order Number-".$OrderNumber;
																		if($nos_initial>0)
																		{
																	$trans_no=$_REQUEST['journal_id'];
																	mysqli_query($link,"delete from ac_journal_list  where trans_no='$trans_no' ");
																		}
																		else
																		{																		
																	$qrymax=mysqli_query($link,"select max(trans_no)from ac_journal");
																	$objmax=mysqli_fetch_array($qrymax);
																	$trans_no=$objmax[0]+1;

																	$OrderNumberArrayJL2=GETNextNumber(4,$cdate,'ac_journal');			
																	$JLOrderNumber=$OrderNumberArrayJL2['NextNumber'];
																	$JLOrderNumberNN=$OrderNumberArrayJL2['NN'];
																	UPDATENextNumber(4,$JLOrderNumberNN,$cdate);
																	

																		}
																	$Currency="SAR";
																	$exchangeRate=1;
																		
																		$qry_acc_dr=mysqli_query($link,"select Sales_Return,COGSAccount,InventoryAccount from ac_general_gl_setup");
																		$obj_acc_dr=mysqli_fetch_array($qry_acc_dr);
																		$account_code_dr=$obj_acc_dr['Sales_Return'];
																		$COGSAccount=$obj_acc_dr['COGSAccount'];
																		$InventoryAccount=$obj_acc_dr['InventoryAccount'];
																		
																				if($CashType==1)
																				{
																		$qry_acc_cr=mysqli_query($link,"select CashAccount from ac_general_gl_setup");
																		$obj_acc_cr=mysqli_fetch_array($qry_acc_cr);
																		$account_code_cr=$obj_acc_cr['CashAccount'];
																		$payment_status=1;
																				}																				
																				else
																				{
																		$qry_acc_cr=mysqli_query($link,"select CustomerAccount from customer_details where id='$CustomerName'");
																		$obj_acc_cr=mysqli_fetch_array($qry_acc_cr);
																		$account_code_cr=$obj_acc_cr['CustomerAccount'];	
																		$payment_status=0;
																				}
																		
																		$reference="Sales Order Number-".$OrderNumber;
																		$source_ref="Sales Order Number-".$OrderNumber;
																		
																		/////// journal header///////
														if($nos_initial>0)
															{
					mysqli_query($link,"update ac_journal set reference='$reference',source_ref=".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",doc_date='$cdate',amount=".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",memo=".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",uid='$admid',order_amt=".(($gross_amt=='')?"0":("'".$gross_amt."'")) . " where trans_no='$trans_no'");
															}
															else
															{
					mysqli_query($link,"insert into ac_journal(type,trans_no,tran_date,reference,source_ref,doc_date,currency,amount,rate,memo,uid,cdate,order_amt,PONumber,next_number,document_number,document_type) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",'$reference',".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",'$cdate',".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'$OrderNumber','sale')");
															}
															/////////////Debit Account//// 																	
					mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($account_code_dr=='')?"NULL":("'".$account_code_dr."'")) . ",".(($sub_total=='')?"0":("'".$sub_total."'")) . ",'0')");
					
											/////////////Credit Account////																	
					mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($account_code_cr=='')?"NULL":("'".$account_code_cr."'")) . ",'0',".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ")");
					
												
														///////////////////////// Tax ////////////
												if($Tax_amt>0)
												{
												$qry_Tax_acc=mysqli_query($link,"select SalesAccount from tax_type where perc='5' ");
												$obj_Tax_acc=mysqli_fetch_array($qry_Tax_acc);
												$SalesTaxAccount=$obj_Tax_acc['SalesAccount'];
												
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($SalesTaxAccount=='')?"NULL":("'".$SalesTaxAccount."'")) . ",".(($Tax_amt=='')?"0":("'".$Tax_amt."'")) . ",'0')");
												}
														///////////////////////// Discount ////////////
												if($discount_amt>0)
												{
												$qry_discount_acc=mysqli_query($link,"select SalesDiscountAccount from ac_general_gl_setup ");
												$obj_discount_acc=mysqli_fetch_array($qry_discount_acc);
												$SalesDiscountAccount=$obj_discount_acc['SalesDiscountAccount'];
												
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($SalesDiscountAccount=='')?"NULL":("'".$SalesDiscountAccount."'")) . ",'0',".(($discount_amt=='')?"0":("'".$discount_amt."'")) . ")");
												}
												
												//////////////////////// update inventory Account
												if($finalAvgCost>0)
												{
																//// COGS
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($COGSAccount=='')?"NULL":("'".$COGSAccount."'")) . ",'0',".(($finalAvgCost=='')?"0":("'".$finalAvgCost."'")) . ")");
																///Inventory
												mysqli_query($link,"insert into ac_journal_list(type,trans_no,tran_date,account,dr,cr) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($InventoryAccount=='')?"NULL":("'".$InventoryAccount."'")) . ",".(($finalAvgCost=='')?"0":("'".$finalAvgCost."'")) . ",'0')");
												}						
																		



																/////////////////// post journal //////////////////
																$PostDate=$cdate;
																$qry_journal_list=mysqli_query($link,"select * from ac_journal_list where trans_no='$trans_no'");
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

																	// $query_period = mysqli_query($link,"select id,fisical_year from ac_period where '$tran_date' BETWEEN begin and end ");
																	// 							$obj_period=mysqli_fetch_array($query_period);
																	// 							$period_id=$obj_period['id'];
																	// 							$fisical_year=$obj_period['fisical_year'];
																
																	$period_id=1;
																	$fisical_year=2;

																mysqli_query($link,"insert into ac_gl_trans(type,type_no,tran_date,post_date,account,ac_chart_class_cid,ac_chart_group_id,dr,cr,dimension_id,dimension2_id,dimension3_id,period_id,fiscal_year,uid,cdate) values('$type','$trans_no','$tran_date','$PostDate',".(($account=='')?"NULL":("'".$account."'")) . ",'$parent',".(($account_type=='')?"NULL":("'".$account_type."'")) . ",".(($Debit=='')?"0":("'".$Debit."'")) . ",".(($Credit=='')?"0":("'".$Credit."'")) . ",".(($Dimension1=='')?"0":("'".$Dimension1."'")) . ",".(($Dimension2=='')?"0":("'".$Dimension2."'")) . ",".(($Dimension3=='')?"0":("'".$Dimension3."'")) . ",'$period_id','$fisical_year','$admid',CURRENT_DATE())");		
																					
																}
												
												mysqli_query($link,"update ac_journal set posting='1',posting_date='$PostDate',posting_by='$admid' where trans_no='$trans_no'");

																		
																		}//// end Sales return
																		else if($SalesCat==3)
																		{
																			if($CashType==1)
																				{																								
																		$payment_status=0;
																				}
																				else
																				{
																		$payment_status=1;
																				}																										
																		$trans_no=0;
																		}
																		
												
												
											
											}
											else
											{
											$trans_no=0;
											}
											

				// 														/////////////// Sales Header /////////////	
											if($nos_initial>0)
											{
											mysqli_query($link,"update sales set CustomerName='$CustomerName',CustomerBranchID=".(($CustomerBranchID=='')?"NULL":("'".$CustomerBranchID."'")) . ",bill_no=".(($BillNumber=='')?"NULL":("'".$BillNumber."'")) . ",tax_amt=".(($Tax_amt=='')?"NULL":("'".$Tax_amt."'")) . ",sub_total=".(($sub_total=='')?"NULL":("'".$sub_total."'")) . ",net_discount=".(($discount_amt=='')?"NULL":("'".$discount_amt."'")) . ",net_amt=".(($gross_amt=='')?"NULL":("'".$gross_amt."'")) . ",cdate='$cdate',cdatetime='$cdatetime',discount_type=".(($radio=='')?"NULL":("'".$radio."'")) . ",discount_per_amt=".(($percenatage_val=='')?"NULL":("'".$percenatage_val."'")) . ",update_stoke='0',status='$SalesCat',CashType='$CashType',journal_id='$trans_no',SalesType='$SalesType',location='$whid',payment_method='$paymentType',payment_terms=".(($PaymentTerm=='')?"NULL":("'".$PaymentTerm."'")) . ",ReasonForReturn=".(($ReasonForReturn=='')?"NULL":("'".$ReasonForReturn."'")) . ",payment_amt_cash=".(($cashAmt=='')?"0":("'".$cashAmt."'")) . ",payment_amt_card=".(($cardAmt=='')?"0":("'".$cardAmt."'")) . ",payment_status='$payment_status',Ebill=".(($Ebill=='')?"0":("'".$Ebill."'")) . ",PONumberret=".(($originalinvoicenumber=='')?"NULL":("'".$originalinvoicenumber."'")) . ",duedate=".(($duedate=='')?"NULL":("'".$duedate."'")) . ",sales_profit=".(($sales_profit=='')?"0":("'".$sales_profit."'")) . " where  PONumber='$OrderNumber'");	
											
											$varibaletxt="update sales set CustomerName='$CustomerName',CustomerBranchID=".(($CustomerBranchID=='')?"NULL":("'".$CustomerBranchID."'")) . ",bill_no=".(($BillNumber=='')?"NULL":("'".$BillNumber."'")) . ",tax_amt=".(($Tax_amt=='')?"NULL":("'".$Tax_amt."'")) . ",sub_total=".(($sub_total=='')?"NULL":("'".$sub_total."'")) . ",net_discount=".(($discount_amt=='')?"NULL":("'".$discount_amt."'")) . ",net_amt=".(($gross_amt=='')?"NULL":("'".$gross_amt."'")) . ",cdate='$cdate',cdatetime='$cdatetime',discount_type=".(($radio=='')?"NULL":("'".$radio."'")) . ",discount_per_amt=".(($percenatage_val=='')?"NULL":("'".$percenatage_val."'")) . ",update_stoke='0',status='$SalesCat',CashType='$CashType',journal_id='$trans_no',SalesType='$SalesType',location='$whid',payment_method='$paymentType',payment_terms=".(($PaymentTerm=='')?"NULL":("'".$PaymentTerm."'")) . ",ReasonForReturn=".(($ReasonForReturn=='')?"NULL":("'".$ReasonForReturn."'")) . ",payment_amt_cash=".(($cashAmt=='')?"0":("'".$cashAmt."'")) . ",payment_amt_card=".(($cardAmt=='')?"0":("'".$cardAmt."'")) . ",payment_status='$payment_status',Ebill=".(($Ebill=='')?"0":("'".$Ebill."'")) . ",PONumberret=".(($originalinvoicenumber=='')?"NULL":("'".$originalinvoicenumber."'")) . ",duedate=".(($duedate=='')?"NULL":("'".$duedate."'")) . ",sales_profit=".(($sales_profit=='')?"0":("'".$sales_profit."'")) . " where  PONumber='$OrderNumber'"."\n";
											
											$myfile = fopen("sales_log.txt", "a");
											fwrite($myfile, $varibaletxt);
											fclose($myfile);
											
											}
											else
											{
											mysqli_query($link,"insert into sales(PONumber,CustomerName,CustomerBranchID,bill_no,tax_amt,sub_total,net_discount,net_amt,cdate,created_by,update_stoke,journal_id,cdatetime,discount_type,status,discount_per_amt,CashType,SalesType,location,sales_transfer,payment_status,payment_method,payment_terms,ReasonForReturn,payment_amt_cash,payment_amt_card,Ebill,next_number,PONumberret,duedate,PurchaseNumber,sales_profit) values('$OrderNumber','$CustomerName',".(($CustomerBranchID=='')?"NULL":("'".$CustomerBranchID."'")) . ",".(($BillNumber=='')?"NULL":("'".$BillNumber."'")) . ",".(($Tax_amt=='')?"NULL":("'".$Tax_amt."'")) . ",".(($sub_total=='')?"NULL":("'".$sub_total."'")) . ",".(($discount_amt=='')?"NULL":("'".$discount_amt."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",'$cdate','$admid','0','$trans_no','$cdatetime',".(($radio=='')?"NULL":("'".$radio."'")) . ",'$SalesCat',".(($percenatage_val=='')?"NULL":("'".$percenatage_val."'")) . ",'$CashType','$SalesType','$whid','0','$payment_status','$paymentType',".(($PaymentTerm=='')?"NULL":("'".$PaymentTerm."'")) . ",".(($ReasonForReturn=='')?"NULL":("'".$ReasonForReturn."'")) . ",".(($cashAmt=='')?"0":("'".$cashAmt."'")) . ",".(($cardAmt=='')?"0":("'".$cardAmt."'")) . ",".(($Ebill=='')?"0":("'".$Ebill."'")) . ",'$OrderNumberNN',".(($originalinvoicenumber=='')?"NULL":("'".$originalinvoicenumber."'")) . ",".(($duedate=='')?"NULL":("'".$duedate."'")) . ",".(($PurchaseNumber=='')?"NULL":("'".$PurchaseNumber."'")) . ",".(($sales_profit=='')?"0":("'".$sales_profit."'")) . ")");		
											
											$varibaletxt="insert into sales(PONumber,CustomerName,CustomerBranchID,bill_no,tax_amt,sub_total,net_discount,net_amt,cdate,created_by,update_stoke,journal_id,cdatetime,discount_type,status,discount_per_amt,CashType,SalesType,location,sales_transfer,payment_status,payment_method,payment_terms,ReasonForReturn,payment_amt_cash,payment_amt_card,Ebill,next_number,PONumberret,duedate,PurchaseNumber) values('$OrderNumber','$CustomerName',".(($CustomerBranchID=='')?"NULL":("'".$CustomerBranchID."'")) . ",".(($BillNumber=='')?"NULL":("'".$BillNumber."'")) . ",".(($Tax_amt=='')?"NULL":("'".$Tax_amt."'")) . ",".(($sub_total=='')?"NULL":("'".$sub_total."'")) . ",".(($discount_amt=='')?"NULL":("'".$discount_amt."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",'$cdate','$admid','0','$trans_no','$cdatetime',".(($radio=='')?"NULL":("'".$radio."'")) . ",'$SalesCat',".(($percenatage_val=='')?"NULL":("'".$percenatage_val."'")) . ",'$CashType','$SalesType','$whid','0','$payment_status','$paymentType',".(($PaymentTerm=='')?"NULL":("'".$PaymentTerm."'")) . ",".(($ReasonForReturn=='')?"NULL":("'".$ReasonForReturn."'")) . ",".(($cashAmt=='')?"0":("'".$cashAmt."'")) . ",".(($cardAmt=='')?"0":("'".$cardAmt."'")) . ",".(($Ebill=='')?"0":("'".$Ebill."'")) . ",'$OrderNumberNN',".(($originalinvoicenumber=='')?"NULL":("'".$originalinvoicenumber."'")) . ",".(($duedate=='')?"NULL":("'".$duedate."'")) . ",".(($PurchaseNumber=='')?"NULL":("'".$PurchaseNumber."'")) . ",".(($sales_profit=='')?"0":("'".$sales_profit."'")) . ")"."\n";	
											
											$myfile = fopen("sales_log.txt", "a");
											fwrite($myfile, $varibaletxt);
											fclose($myfile);							
											}
											
											
											
				// 							////////////////////// Update Stock /////////////////											

											$qry_initial1=mysqli_query($link,"select * from sales where PONumber='$OrderNumber' and update_stoke='0'");
											$nos_initial1=mysqli_num_rows($qry_initial1);
											if($nos_initial1>0)
											{
															for($i=0;$i<=$Totalrow;$i++)
															{
															$ItemMasterID=$_REQUEST['ItemMasterID'][$i];
															echo $sno=$_REQUEST['unit'][$i];
																$qry_inv2=mysqli_query($link,"select unit from inventory_uom where item_no='$ItemMasterID' and sno='$sno'");																
																$obj_inv2=mysqli_fetch_array($qry_inv2);
																$unit=$obj_inv2['unit'];
															$rqty=$_REQUEST['qty'][$i];																														
															if($_REQUEST['qty_old'][$i]>0){$previousqty=$_REQUEST['qty_old'][$i];} else {$previousqty=0;} /// only in update section
															$unit_price=$_REQUEST['sprice'][$i];
															
																			if($ItemMasterID!="")
																			{
																$qry_uom=mysqli_query($link,"select factor_val from inventory_uom where item_no='$ItemMasterID' and unit='$unit'");
																//print_r("select factor_val from inventory_uom where item_no='$ItemMasterID' and unit='$unit'");
																$obj_uom=mysqli_fetch_array($qry_uom);
																if($obj_uom[0]>0){$factor_val=$obj_uom[0];} else {$factor_val=1;}
																
																$qty=$factor_val*$rqty;	
																
																$prevqty=$factor_val*$previousqty;	/// only in update section
																
																$finalqty=$qty-$prevqty;

																$dt1=date("Y-m-d");
																$dt2=date("Y-m-d H:i:s");
																
																
																$qry_inv1=mysqli_query($link,"select qty from inventory_qty where item_no='$ItemMasterID' and warehouse_id='$whid'");
																$nos_inv1=mysqli_num_rows($qry_inv1);
																if($nos_inv1==0)
																{
																	$firstqty=$finalqty*-1;
																mysqli_query($link,"insert into inventory_qty(item_no,qty,warehouse_id) values('$ItemMasterID','$firstqty','$whid')");

																mysqli_query($link,"insert into stock_log(item_no,warehouse_id,orderid,qty,type,cdate,cdatetime) values('$ItemMasterID','$whid','$OrderNumber','$finalqty','sales','$dt1','$dt2')");

																}
																else
																{
																$obj_inv1=mysqli_fetch_array($qry_inv1);
																$old_qty=$obj_inv1[0];
																
																							if($SalesCat==1)    ///// sales
																							{
																$newqty=$old_qty-$finalqty;	
																mysqli_query($link,"update inventory_qty set qty='$newqty' where item_no='$ItemMasterID' and warehouse_id='$whid'");
																mysqli_query($link,"insert into stock_log(item_no,warehouse_id,orderid,qty,type,cdate,cdatetime) values('$ItemMasterID','$whid','$OrderNumber','$finalqty','sales','$dt1','$dt2')");
																							}
																							else if($SalesCat==2)                ///// sales return
																							{
															    $newqty=$old_qty+$qty;								
																mysqli_query($link,"update inventory_qty set qty='$newqty' where item_no='$ItemMasterID' and warehouse_id='$whid'");
																mysqli_query($link,"insert into stock_log(item_no,warehouse_id,orderid,qty,type,cdate,cdatetime) values('$ItemMasterID','$whid','$OrderNumber','$qty','sales return','$dt1','$dt2')");
																
																							}																						
																
																
																}		
																			}										
															
															}
												
															if($SalesCat==1 || $SalesCat==2)    ///// sales and return
															{
											mysqli_query($link,"update sales set update_stoke='1' where  PONumber='$OrderNumber'");
															}
											}		
										
										
 if($nos_initial>0)
 {
		if($printval==1)
		{
			if($RoleMaster==5)
			{
				echo '<script>window.open("print_bill.php?id='.$OrderNumber.'");</script>';
			}
			else{
					if($Ebill==1)
					{
						echo '<script>window.open("print_bill.php?id='.$OrderNumber.'");</script>';
					}
					else{
						echo '<script>window.open("sales_print.php?id='.$OrderNumber.'");</script>';
					}
				
			}

		}
echo '<script>window.location.href = "Sales.php?id='.$OrderNumber.'&&update";</script>';
			
}
else
{
	if($SalesCat==1 || $SalesCat==2)    ///// sales
	{
		
		if($printval==1)
		{
			if($RoleMaster==5)
			{
				echo '<script>window.open("print_bill.php?id='.$OrderNumber.'");</script>';
			}
			else{
					if($Ebill==1)
					{
						echo '<script>window.open("print_bill.php?id='.$OrderNumber.'");</script>';
					}
					else{
						echo '<script>window.open("sales_print.php?id='.$OrderNumber.'");</script>';
					}
			}
				}
		else
		{
echo '<script>window.location.href = "Sales.php?save";</script>';
		}
	}
echo '<script>window.location.href = "Sales.php?save";</script>';
}
?>