<?php
function PaymentTerms($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT descr FROM payment_terms_master WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function SupplierDetails($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT code,supplier_name,supplier_address,supplier_contact1,SupplierAccount,supplier_name_ar,VatNumber FROM supplier_details WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function CustomerDetails($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT code,customer_name,customer_name_ar,customer_address,customer_contact1,VatNumber,country,region,additional_no,other_id,building_no,street,district,city,postalcode FROM customer_details WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function CustomerBranchDetails($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT code,descr_en FROM customer_details_branch WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function BankMaster($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT descr FROM bank WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function UserDetails($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT displayname,username,ContactNumber FROM user WHERE  uid='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function CompanyDetails()
{
	global $link;
	$query = mysqli_query($link,"SELECT cnameEn,cnameAr,address,phone,TIN,CRNumber,VATNumber,TaxReturnType,financial_year,address_ar,VATNumberAr,logo,country,region,additional_no,other_id,building_no,street,district,city,postalcode,bank_name,bank_account_number,bank_iban_number FROM company");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function roundvalues($val)
{
	if($val==2.3 || $val==2.30)
	{
	$val=2.31;
	}
	return  number_format(floor($val*100)/100,2, '.', '');
}
function general_setup_define()
{
	global $link;
	$query = mysqli_query($link,"SELECT sales_close_gdn,purchase_close_grn FROM general_setup_tb");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function GETNextNumber($documentType,$cdate,$tablename)
{
	global $link;
	$NextNumberVal="";
		
	$yearvalYYYY=date('Y',strtotime($cdate));
	$yearvalY=date('y',strtotime($cdate));
	$monthval=date('m',strtotime($cdate));


		

	 $query = mysqli_query($link,"SELECT Prefix,Sperator_f,Year,Sperator_s,Month,Sperator_t,NextNumberTxt FROM next_number_auto WHERE  DocumentType='$documentType'");
	 	 $nos = mysqli_num_rows($query);	
			if($nos==0)
			{
				$query1 = mysqli_query($link,"SELECT next_number FROM next_numbering WHERE  document_type='$documentType'");
				$myrow1 = mysqli_fetch_array($query1);
				$array = array("NextNumber" =>  $myrow1[0], "NN" =>  $myrow1[0]);
				return $array;
			}
			else
			{
	 			$myrow = mysqli_fetch_array($query);
	 			if($myrow['Prefix']==""){$NextNumberVal=$NextNumberVal; }else {$NextNumberVal=$NextNumberVal.$myrow['Prefix'];}
				if($myrow['Sperator_f']==""){$NextNumberVal=$NextNumberVal; }else {$NextNumberVal=$NextNumberVal.$myrow['Sperator_f'];}
				if($myrow['Year']==1){$NextNumberVal=$NextNumberVal.$yearvalYYYY; } else if($myrow['Year']==2) {$NextNumberVal=$NextNumberVal.$yearvalY;} else { $NextNumberVal=$NextNumberVal; }				
				if($myrow['Sperator_s']==""){ $NextNumberVal=$NextNumberVal;}else {$NextNumberVal=$NextNumberVal.$myrow['Sperator_s'];}
				if($myrow['Month']==""){$NextNumberVal=$NextNumberVal; }else {$NextNumberVal=$NextNumberVal.$monthval;}
				if($myrow['Sperator_t']==""){$NextNumberVal=$NextNumberVal; }else {$NextNumberVal=$NextNumberVal.$myrow['Sperator_t'];}		
												
											

												$query1 = mysqli_query($link,"SELECT udate,next_number FROM next_numbering WHERE  document_type='$documentType'");
												$myrow1 = mysqli_fetch_array($query1);
												$udateYYYY	=date('Y',strtotime($myrow1[0]));
												$udateMM	=date('m',strtotime($myrow1[0]));

													if($udateYYYY!=$yearvalYYYY) { $yearnotvalid=1; } else {$yearnotvalid=0; }
													if($udateMM!=$monthval) { $monthnotvalid=1; } else {$monthnotvalid=0; }

													if($yearnotvalid==0 && ($monthnotvalid==0 || ($myrow['Month']=="")))
													{	
														$NextNumberVal=$NextNumberVal.$myrow1['next_number'];
														$nn=$myrow1['next_number'];
														$nn1='kooi';
													}
													else
													{														
														if($documentType==4)
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(tran_date) ='$yearvalYYYY' AND MONTH(tran_date) = '$monthval' AND entry_type=0");
														}					
														else if($documentType==15)
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(tran_date) ='$yearvalYYYY' AND MONTH(tran_date) = '$monthval' AND entry_type=1");
														} 
														else if($documentType==17)
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(Fdate) ='$yearvalYYYY' AND MONTH(Fdate) = '$monthval'");
														} 
														else if($documentType==5)
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(payment_date) ='$yearvalYYYY' AND MONTH(payment_date) = '$monthval' and type=1");
														} 	
														else if($documentType==6)	
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(payment_date) ='$yearvalYYYY' AND MONTH(payment_date) = '$monthval' and type=2");
														}
														else if($documentType==7)	
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(payment_date) ='$yearvalYYYY' AND MONTH(payment_date) = '$monthval' and type=3");
														}														
														else if($documentType==16)	// sales without tax
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(cdate) ='$yearvalYYYY' AND MONTH(cdate) = '$monthval' AND Ebill=1 AND (status=1 or status=3)");
														}
														else if($documentType==1)	// sales
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(cdate) ='$yearvalYYYY' AND MONTH(cdate) = '$monthval' AND Ebill=0  AND (status=1 or status=3)");
														}
														else if($documentType==21)	///sales return
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(cdate) ='$yearvalYYYY' AND MONTH(cdate) = '$monthval' AND Ebill=0 AND status=2");
														}
														else if($documentType==22)	// sales return without tax
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(cdate) ='$yearvalYYYY' AND MONTH(cdate) = '$monthval' AND Ebill=1 AND status=2");
														}							
														else
														{
															$query2 = mysqli_query($link,"SELECT max(next_number) FROM $tablename where YEAR(cdate) ='$yearvalYYYY' AND MONTH(cdate) = '$monthval'");
														}
																											

															$myrow2 = mysqli_fetch_array($query2);
															if($myrow2[0]=="" || $myrow2[0]==0)
															{
																$NextNumberVal=$NextNumberVal.$myrow['NextNumberTxt'];
																$nn=$myrow['NextNumberTxt'];
																$nn1='poda';																		
															}
															else
															{
																$nextupdatenumber=str_pad(intval($myrow2[0]) + 1, strlen($myrow2[0]), '0', STR_PAD_LEFT);
																$NextNumberVal=$NextNumberVal.$nextupdatenumber;
																$nn=$nextupdatenumber;	
																$nn1='hai';														
															}
														
													}

				$array = array("NextNumber" => $NextNumberVal, "NN" => $nn, "test" => $nn1);
				return $array;
			}
			

}
function UPDATENextNumber($documentType,$OrderNumber,$cdate)
{
	global $link;	
		$yearvalYYYY=date('Y',strtotime($cdate));		
		$monthval=date('m',strtotime($cdate));
		$udate=date('Y-m-d',strtotime($cdate));

	 $query = mysqli_query($link,"SELECT Prefix,Sperator_f,Year,Sperator_s,Month,Sperator_t,NextNumberTxt FROM next_number_auto WHERE  DocumentType='$documentType'");
	 $nos = mysqli_num_rows($query);		 
			if($nos==0)
			{
				$nextupdatenumber=str_pad(intval($OrderNumber) + 1, strlen($OrderNumber), '0', STR_PAD_LEFT);
				mysqli_query($link,"update next_numbering set next_number='$nextupdatenumber' where document_type='$documentType'");
			}
			else
			{
				

												$query1 = mysqli_query($link,"SELECT udate,next_number FROM next_numbering WHERE  document_type='$documentType'");
												$myrow1 = mysqli_fetch_array($query1);
												$udateYYYY	=date('Y',strtotime($myrow1[0]));
												$udateMM	=date('m',strtotime($myrow1[0]));
													if($udateYYYY!=$yearvalYYYY) { $yearnotvalid=1; } else {$yearnotvalid=0; }
													if($udateMM!=$monthval) { $monthnotvalid=1; } else {$monthnotvalid=0; }
													$nextupdatenumber=str_pad(intval($OrderNumber) + 1, strlen($OrderNumber), '0', STR_PAD_LEFT);
													mysqli_query($link,"update next_numbering set next_number='$nextupdatenumber',udate='$udate' where document_type='$documentType'");													
			}
			

}
?>