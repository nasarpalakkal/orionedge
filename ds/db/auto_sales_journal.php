<?php
//-----------------------------------Sales & Purchase ---------------------------------------------------

function StoreJournal($CustomerID,$OrderNumber,$trans_no,$sub_total,$discount_amt,$Tax_amt,$gross_amt,$paymentType,$cashAmt,$cardAmt,$finalAvgCost,$admid,$JLOrderNumber,$JLOrderNumberNN)
{
	global $link;

   

    $queryCustomer = mysqli_query($link,"SELECT code,customer_name FROM customer_details WHERE  id='$CustomerID'");
    $objCustomer = mysqli_fetch_array($queryCustomer);	
    $CustomerCode=$objCustomer['code'];
	$SupplierNameValue=mysqli_real_escape_string($link,$objCustomer['customer_name']);
    $cdate=date('Y-m-d');

    $MainMemo="Sales from ".$CustomerCode.'-'.$SupplierNameValue."-Sales Order Number-".$OrderNumber;
    $Currency="SAR";
    $exchangeRate=1;


    $qry_acc_cr=mysqli_query($link,"select SalesAccount,COGSAccount,InventoryAccount from ac_general_gl_setup");
    $obj_acc_cr=mysqli_fetch_array($qry_acc_cr);
    $account_code_cr=$obj_acc_cr['SalesAccount'];
    $COGSAccount=$obj_acc_cr['COGSAccount'];
    $InventoryAccount=$obj_acc_cr['InventoryAccount'];
    
    $reference="Sales Invoice Number-".$OrderNumber;
    $source_ref="Sales Invoice Number-".$OrderNumber;

                    /////////////Debit Account////
                if($paymentType==0)
                {
                            $qry_acc_dr=mysqli_query($link,"select CustomerAccount from customer_details where id='$CustomerID'");
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



                    /////// journal header///////                   
                    mysqli_query($link,"insert into ac_journal(type,trans_no,tran_date,reference,source_ref,doc_date,currency,amount,rate,memo,uid,cdate,order_amt,PONumber,next_number,document_number,document_type) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",'$reference',".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",'$cdate',".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'$OrderNumber','sale')");

                    $varibaletxt1="insert into ac_journal(type,trans_no,tran_date,reference,source_ref,doc_date,currency,amount,rate,memo,uid,cdate,order_amt,PONumber,next_number,document_number,document_type) values('0','$trans_no',".(($cdate=='')?"NULL":("'".$cdate."'")) . ",'$reference',".(($source_ref=='')?"NULL":("'".$source_ref."'")) . ",'$cdate',".(($Currency=='')?"NULL":("'".$Currency."'")) . ",".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($exchangeRate=='')?"NULL":("'".$exchangeRate."'")) . ",".(($MainMemo=='')?"NULL":("'".$MainMemo."'")) . ",'$admid',CURRENT_DATE(),".(($gross_amt=='')?"0":("'".$gross_amt."'")) . ",".(($JLOrderNumber=='')?"NULL":("'".$JLOrderNumber."'")) . ",".(($JLOrderNumberNN=='')?"NULL":("'".$JLOrderNumberNN."'")) . ",'$OrderNumber','sale')";

                    $myfile = fopen("acjournal_log.txt", "a");
                    fwrite($myfile, $varibaletxt1);
                    fclose($myfile);
                   	



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

            mysqli_query($link,"update sales set journal_id='$trans_no' where PONumber='$OrderNumber'");
            return 1;

}
?>