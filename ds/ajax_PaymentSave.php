<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$Whid=$_SESSION['WHID'];
$SalesDate=$_SESSION['SalesDate'];
$SalesOpeningAmt=$_SESSION['SalesOpeningAmt'];
$ComputerID=$_SESSION['ComputerID'];
date_default_timezone_set('Asia/Riyadh'); 
$cdate=date('Y-m-d',strtotime($SalesDate));
$ctime=date('Y-m-d',strtotime($SalesDate))." ".date('H:i:s');
$sysdatetime=date('Y-m-d H:i:s');									
include("../conn.php"); 
include("db/StockUpdate.php");
include("db/auto_sales_journal.php");
include("db/Address_db.php");
if(!empty($_POST["TotalAmt"]))
{
$TotalAmt=floatval(preg_replace('/[^\d.]/', '', $_POST["TotalAmt"]));
$TotalItems=$_POST["TotalItems"];
$PaidAmount1=floatval(preg_replace('/[^\d.]/', '', $_POST["PaidAmount1"]));
$PaidAmount2=floatval(preg_replace('/[^\d.]/', '', $_POST["PaidAmount2"]));
$BalanceAmount=floatval(preg_replace('/[^\d.]/', '', $_POST["BalanceAmount"]));
$PayName=$_POST["PayName"];
$PaymentType=$_POST["PaymentType"];
$Inv_number=$_POST["Inv_number"];
$OrderType=$_POST['OrderType'];
$OrderTypeTable=$_POST['OrderTypeTable'];
$CardType=$_POST['CardType'];
$DiscountPerc=floatval(preg_replace('/[^\d.]/', '', $_POST["DiscountPerc"]));
$DiscountAmt=floatval(preg_replace('/[^\d.]/', '', $_POST["DiscountAmt"]));

if($DiscountAmt>0){$DiscountType=$_POST['DiscountType'];}else{$DiscountType="";}

$TotalTax=floatval(preg_replace('/[^\d.]/', '', $_POST["TotalTax"]));
$OriginalTotal=floatval(preg_replace('/[^\d.]/', '', $_POST["OriginalTotal"]));

$SalesType=floatval(preg_replace('/[^\d.]/', '', $_POST["SalesType"])); /////////// Sales or Return

$TotalAmt_afterdiscount=($OriginalTotal-$DiscountAmt)+$TotalTax;

$CashType=$_POST["CustomerType"]??1;
$SalesType=$_POST["SalesType"]??2; // 1- retail 2- wholesale
$vat_include=$_POST['vat_include'];
		
            if ($CashType==1) {
            					if ($PaidAmount1>0 && $PaidAmount2>0) {
            						$paymentType=3;
            						$payment_status=1;
            					} elseif ($PaidAmount1>0 && $PaidAmount2==0) {
            						$paymentType=1;
            						$payment_status=1;
            					} elseif ($PaidAmount1==0 && $PaidAmount2>0) {
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
            								
								
								
								
								$TotalItems=$TotalItems;
								$TotalAmt=$TotalAmt;	
								
								if($vat_include==1)
								{
									$qry_nextnumber=mysqli_query($link,"SELECT next_number FROM next_numbering where type='sales_tax_hd'");	
									$obj_nextnumber=mysqli_fetch_array($qry_nextnumber);
									$nextnumber=$obj_nextnumber['next_number'];

									$nextnumberNext=str_pad(intval($nextnumber) + 1, strlen($nextnumber), '0', STR_PAD_LEFT);
													
												//	$nextnumberNext=$nextnumber//+1;
													mysqli_query($link,"update next_numbering set next_number='$nextnumberNext' where type='sales_tax_hd'");
													$Ebill=1;
													$nextnumber="2807".$nextnumber;
								}
								else
								{
									$qry_nextnumber=mysqli_query($link,"SELECT next_number FROM next_numbering where type='sales'");	
									$obj_nextnumber=mysqli_fetch_array($qry_nextnumber);
									$nextnumber=$obj_nextnumber['next_number'];
									$nextnumberNext=str_pad(intval($nextnumber) + 1, strlen($nextnumber), '0', STR_PAD_LEFT);
													
												//	$nextnumberNext=$nextnumber//+1;
													mysqli_query($link,"update next_numbering set next_number='$nextnumberNext' where type='sales'");
													$Ebill=0;
								}
													
													
													

																		
									

									$finalAvgCost=0;
									$sales_profit=0;
										$qry22=mysqli_query($link,"select invoice_no,sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc FROM tbl_temp_sales where uid='$admid'");
										while($obj22=mysqli_fetch_array($qry22))
													{
														$invoice_no22=$obj22['invoice_no'];
														$sno22=$obj22['sno']-1;
														$code22=$obj22['code'];
														$unit22=$obj22['unit'];
														$qty22=$obj22['qty'];
														$unit_price22=$obj22['unit_price']??0;
														$tax_amt22=$obj22['tax_amt']??0;
														$total_price22=$obj22['total_price'];
														$uid22=$obj22['uid'];
														$datetime22=$obj22['datetime'];
														$taxperc22=$obj22['taxperc'];
														
														$qryInventory=mysqli_query($link,"select BaseLastCost,AvgCost,SalesPrice FROM inventory WHERE item_no='$code22'");	
														$objInventory=mysqli_fetch_array($qryInventory);														
														$LastCost=$objInventory['BaseLastCost']??0;	
														$AvgCost=$objInventory['AvgCost']??0;
														$unit_price33=$objInventory['SalesPrice']??0;
														
														$finalAvgCost=roundvalues($finalAvgCost+(($qty22*$factor_val)*$AvgCost))??0;		

														$qryfactor=mysqli_query($link,"select factor_val,sno FROM inventory_uom WHERE item_no='$code22' and unit='$unit22'");	
														$objfactor=mysqli_fetch_array($qryfactor);	
														$factor_val=$objfactor['factor_val'];
														$unitsno22=$objfactor['sno'];

														$item_profit=(($unit_price33*($qty22*$factor_val))-($LastCost*($qty22*$factor_val)))??0;
														$sales_profit=$sales_profit+$item_profit;
														$unit_price23=$unit_price22+$tax_amt22;
														
												mysqli_query($link,"insert into sales_list(PONumber,sno,item_no,unit,unit_price,qty,total_price,tax_amt,created_by,createdDate,item_type,TaxPer,unitsno,unitprice_withtax,BaseLastCost,item_profit) values('$nextnumber','$sno22','$code22','$unit22','$unit_price22','$qty22','$total_price22',".(($tax_amt22=='')?"NULL":("'".$tax_amt22."'")) . ",'$uid22',".(($datetime22=='')?"NULL":("'".$datetime22."'")) . ",0,".(($taxperc22=='')?"NULL":("'".$taxperc22."'")) . ",'$unitsno22','$unit_price23','$LastCost','$item_profit')");


												$varibaletxt="insert into sales_list(PONumber,sno,item_no,unit,unit_price,qty,total_price,tax_amt,created_by,createdDate,item_type,TaxPer,unitsno,unitprice_withtax,BaseLastCost,item_profit) values('$nextnumber','$sno22','$code22','$unit22','$unit_price22','$qty22','$total_price22',".(($tax_amt22=='')?"NULL":("'".$tax_amt22."'")) . ",'$uid22',".(($datetime22=='')?"NULL":("'".$datetime22."'")) . ",0,".(($taxperc22=='')?"NULL":("'".$taxperc22."'")) . ",'$unitsno22','$unit_price23','$LastCost','$item_profit')"."\n";
				
														$myfile = fopen("sales_list".$cdate.".txt", "a");
														fwrite($myfile, $varibaletxt);
														fclose($myfile);	
												}

													
$isssuccess=mysqli_query($link,"insert into sales(PONumber,CustomerName,tax_amt,sub_total,net_discount,discount_per_amt,net_amt,cdate,created_by,update_stoke,journal_id,cdatetime,discount_type,status,CashType,SalesType,location,payment_status,payment_method,payment_amt_cash,payment_amt_card,sales_profit,Ebill) values('$nextnumber','$PayName',".(($TotalTax=='')?"0":("'".$TotalTax."'")) . ",".(($OriginalTotal=='')?"NULL":("'".$OriginalTotal."'")) . ",".(($DiscountAmt=='')?"0":("'".$DiscountAmt."'")) . ",".(($DiscountPerc=='')?"0":("'".$DiscountPerc."'")) . ",".(($TotalAmt_afterdiscount=='')?"NULL":("'".$TotalAmt_afterdiscount."'")) . ",".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($admid=='')?"NULL":("'".$admid."'")) . ",'1','0',".(($sysdatetime=='')?"NULL":("'".$sysdatetime."'")) . ",".(($DiscountType=='')?"NULL":("'".$DiscountType."'")) . ",'1','$CashType',".(($SalesType=='')?"0":("'".$SalesType."'")) . ",".(($Whid=='')?"NULL":("'".$Whid."'")) . ",".(($payment_status=='')?"0":("'".$payment_status."'")) . ",".(($paymentType=='')?"0":("'".$paymentType."'")) . ",".(($PaidAmount1=='')?"NULL":("'".$PaidAmount1."'")) . ",".(($PaidAmount2=='')?"NULL":("'".$PaidAmount2."'")) . ",".(($sales_profit=='')?"NULL":("'".$sales_profit."'")) . ",'$Ebill')");
															
															$varibaletxtHeader="insert into sales(PONumber,CustomerName,tax_amt,sub_total,net_discount,discount_per_amt,net_amt,cdate,created_by,update_stoke,journal_id,cdatetime,discount_type,status,CashType,SalesType,location,payment_status,payment_method,payment_amt_cash,payment_amt_card) values('$nextnumber','$PayName',".(($TotalTax=='')?"0":("'".$TotalTax."'")) . ",".(($OriginalTotal=='')?"NULL":("'".$OriginalTotal."'")) . ",".(($DiscountAmt=='')?"0":("'".$DiscountAmt."'")) . ",".(($DiscountPerc=='')?"0":("'".$DiscountPerc."'")) . ",".(($TotalAmt_afterdiscount=='')?"NULL":("'".$TotalAmt_afterdiscount."'")) . ",".(($cdate=='')?"NULL":("'".$cdate."'")) . ",".(($admid=='')?"NULL":("'".$admid."'")) . ",'0','0',".(($sysdatetime=='')?"NULL":("'".$sysdatetime."'")) . ",".(($DiscountType=='')?"NULL":("'".$DiscountType."'")) . ",'1','$CashType',".(($SalesType=='')?"0":("'".$SalesType."'")) . ",".(($Whid=='')?"NULL":("'".$Whid."'")) . ",".(($payment_status=='')?"0":("'".$payment_status."'")) . ",".(($paymentType=='')?"0":("'".$paymentType."'")) . ",".(($PaidAmount1=='')?"NULL":("'".$PaidAmount1."'")) . ",".(($PaidAmount2=='')?"NULL":("'".$PaidAmount2."'")) . ")"."\n";
															
															$myfile1 = fopen("pos_sales_header".$cdate.".txt", "a");
															fwrite($myfile1, $varibaletxtHeader);
															fclose($myfile1);	
															
															
				
												//////////////////// updating the stock //////////////////
											$stockupdate=StockUpdate(1,$Inv_number,$salesInvoiceID=$nextnumber,$whid=23);
											
												$qry_initial=mysqli_query($link,"select journal_id from sales where PONumber='$nextnumber'");
												$obj_initial=mysqli_num_rows($qry_initial);
												$trans_no_old=$obj_initial['journal_id'];

												if($trans_no_old==0)
												{
												$qrymax=mysqli_query($link,"select max(trans_no) from ac_journal");
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
												
											

											$createJournal=StoreJournal($PayName,$salesInvoiceID=$nextnumber,$trans_no,$OriginalTotal,$DiscountAmt,$TotalTax,$TotalAmt_afterdiscount,$paymentType,$PaidAmount1,$PaidAmount2,$finalAvgCost,$admid,$JLOrderNumber,$JLOrderNumberNN);
													
								
									
				$qry33=mysqli_query($link,"select * FROM sales_list where PONumber='$nextnumber'");		
				$nos33=mysqli_num_rows($qry33);			
				$qry44=mysqli_query($link,"select * FROM sales where PONumber='$nextnumber'");		
				$nos44=mysqli_num_rows($qry44);		
					
					if( ($nos33>0) && ($nos44>0))
					{
						mysqli_query($link,"delete from tbl_temp_sales where uid='$admid'");		
						echo $nextnumber;	
									
					}
					else
					{
					mysqli_query($link,"delete from sales where PONumber='$nextnumber'");
					mysqli_query($link,"delete from sales_list where PONumber='$nextnumber'");
						echo 0;	
					}
}
?>	
  