<?php
//-----------------------------------Sales & Purchase ---------------------------------------------------

function StockUpdate($type,$ponumber,$salesInvoiceID,$whid)
{
	global $link;
                        if($type==1)
                        {
                            $qry1=mysqli_query($link,"select invoice_no,sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc FROM tbl_temp_sales WHERE invoice_no='$ponumber'");
                            while($obj1=mysqli_fetch_array($qry1))
                            {
                                $invoice_no=$obj1['invoice_no'];
                                $sno=$obj1['sno'];
                                $code=$obj1['code'];
                                $unit=$obj1['unit'];
                                $qty=floatval($obj1['qty']);
                                $unit_price=$obj1['unit_price'];
                                $tax_amt=$obj1['tax_amt'];
                                $total_price=$obj1['total_price'];
                                $uid=$obj1['uid'];
                                $datetime=$obj1['datetime'];
                                $taxperc=$obj1['taxperc'];
                                
                                $qryfactor=mysqli_query($link,"select factor_val FROM inventory_uom WHERE item_no='$code' and unit='$unit'");	
                                $objfactor=mysqli_fetch_array($qryfactor);	
                                $factor_val=$objfactor['factor_val'];
                            
                                
                                $qryqty=mysqli_query($link,"select qty FROM inventory_qty WHERE warehouse_id='$whid' and item_no='$code'");	
                                $objval=mysqli_fetch_array($qryqty);	
                                $itemqty=floatval($objval['qty']);
                                
                                
                                $orderedqty=$qty*$factor_val;
                                $cdate=date('Y-m-d');
                                $ctime=date('Y-m-d H:i:s');
                                
                                $finalqty=floatval($itemqty-$orderedqty);
                                mysqli_query($link,"update inventory_qty set qty='$finalqty' WHERE warehouse_id='$whid' and item_no='$code'");
                                mysqli_query($link,"insert into stock_log(item_no,warehouse_id,orderid,qty,type,cdate,cdatetime,average_cost,total_cost) values('$code','$whid','$salesInvoiceID','$orderedqty','sales','$cdate','$ctime',0,0)");
                            }
                        }
            

	return $type;
}
?>