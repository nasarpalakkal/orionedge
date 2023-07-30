<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$COMPort=$_SESSION['COMPort'];
include("../conn.php");
date_default_timezone_set('Asia/Riyadh'); 
require_once 'multilanguage.php';

function display_format($name, $price) {
    $maxChar = 18;
    $formatedPrice = number_format($price, 2, '.', ',');

    $fmtPriceLength = $maxChar -1 - strlen($formatedPrice);
    $displayStr = str_pad( substr($name,0, $fmtPriceLength) , $fmtPriceLength, " "). " " . $formatedPrice. chr(13);
    return $displayStr;
}


if(!empty($_POST["strID"])) 
{
			$strID=$_POST["strID"];
			
			$qrycheck=mysqli_query($link,"SELECT * from tbl_temp_sales WHERE uid='$admid'");
			$noscheck=mysqli_num_rows($qrycheck);
			if($noscheck==0)
			{
			mysqli_query($link,"insert into tbl_temp_sales(invoice_no,sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc,stype,kot,pstatus) select '$strID',sno,code,qty,unit,unit_price,tax_amt,total_price,'$admid',datetime,taxperc,stype,kot,pstatus from tbl_temp_sales_list_hold where invoice_no='$strID'");

			$varibaletxt="insert into tbl_temp_sales(invoice_no,sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc,stype,kot) select '$strID',sno,code,qty,unit,unit_price,tax_amt,total_price,uid,datetime,taxperc,stype,kot from tbl_temp_sales_list_hold where invoice_no='$strID'";

														$myfile = fopen("abc.txt", "a");
														fwrite($myfile, $varibaletxt);
														fclose($myfile);

			
			mysqli_query($link,"delete from tbl_temp_sales_list_hold where invoice_no='$strID'");
			mysqli_query($link,"delete from tbl_temp_sales_hold where invoice_no='$strID'");
			$checkvalue=1;
			}
			else
			{
			$checkvalue=0;
			}
			
			
	
}
$a="<table class=\"table table-striped table-condensed table-hover list-table table-responsive\" style=\"margin:0;\" id=\"cart-item\"><thead>
<tr class=\"success\"><th><span class=\"ArFont\">".gettext("Item")."</span></th><th><span class=\"ArFont\">".gettext("Unit")."[F10]</span></th><th><span class=\"ArFont\">".gettext("Price")."</span></th><th><span class=\"ArFont\">".gettext("Qty")."</span></th><th><span class=\"ArFont\">".gettext("Total")."</span></th><th><i class=\"fa fa-trash-o\"></i></th></tr></thead><tbody></table>";				
		
		$Total=0;
		$TotalTax=0;
		$TotalQty=0;
		$i=1;
		$item=mysqli_query($link,"SELECT A.invoice_no,A.sno,A.qty,A.unit_price,A.total_price,A.code,A.tax_amt,A.taxperc,B.item_descr,B.item_descr_ar,A.unit,U.descr as UnitDescr FROM tbl_temp_sales as A  left join inventory as B on A.code=B.item_no left join tb_units as U on A.unit=U.id WHERE A.uid='$admid' order by A.sno asc");	
		$nos=mysqli_num_rows($item);	
		while($objitem=mysqli_fetch_array($item))
		{		
    			$invoice_no=$objitem['invoice_no'];
				$tempid=$objitem['sno'];
				$descr=$objitem['item_descr'];
				$descrAr=$objitem['item_descr_ar'];
				$qty=$objitem['qty'];
				$unitprice=$objitem['unit_price'];
				$total_price=$objitem['total_price'];
				$code=$objitem['code'];
				$tax_amt=$objitem['tax_amt'];
				$TotalQty=$TotalQty+$qty;
				$taxperc_ret=$objitem['taxperc'];
				$unit=$objitem['unit'];
				$unitDescr=$objitem['UnitDescr'];
				
				$unitPriceDisp=preg_replace('~\.0+$~','',number_format($unitprice,2,'.',','));
				$TotalPriceDisp=preg_replace('~\.0+$~','',number_format($total_price+$tax_amt,2,'.',','));
				
				$a=$a."<tr>	<td><font size=\"2.2\">$code-$descr <br> $descrAr</font></td>
				
				<td><input type=\"text\" value='$unitDescr' size=4  style=\"text-align:center;\" onKeyDown=\"return checkPhoneKey(event.key,'$i','$code','$unit','$tempid',event.which)\" autocomplete=\"off\"></td>
				
				<td align=\"center\"><font size=\"2.2\">$unitPriceDisp</font></td>
				
				<td align=\"center\">
				<i class=\"fa fa-minus-square\" style=\"font-size:15px;color:red\" onClick=\"cartActionMinusPlus('$code',1,'$unit')\"></i>&nbsp;<input type=\"text\" value='$qty' id='T$i' size=1  style=\"text-align:center;\" onKeyPress=\"return isNumber(event);\" onfocus=\"this.select();\" onkeydown=\"frmQtyKey(event,this.value,'$code','$unit','$i')\" onblur=\"frmQty(this.value,'$code','$unit')\" autocomplete=\"off\">&nbsp;<i class=\"fa fa-plus-square\" style=\"font-size:15px;color:green\" onClick=\"cartActionMinusPlus('$code',2,'$unit')\"></i>
				
				<input type='hidden' name='Hunit' id='' value='$unit'>
				<input type='hidden' name='Hcode' id='' value='$code'>
				<input type='hidden' name='Htype' id='' value='1'>
				</td>
					
				<td align=\"center\"><font size=\"2.2\">$TotalPriceDisp</font></td>				
				<td align=\"center\"><a onClick=\"cartAction('remove','$code','$unit')\" class=\"btnRemoveAction cart-action\"><i class=\"fa fa-trash-o\" style=\"font-size:15px;\"></i></a></td>
				</tr>";
				$Total=$Total+$total_price;
				$TotalTax=$TotalTax+$tax_amt;
				$FinalAmount=$Total+$TotalTax;
				$i=$i+1;
		}		

		$itemDescriptionPrice=display_format($descr, $total_price);		
		$message=display_format("Total", $FinalAmount);

		if ($COMPort!='') {
            exec("copy /b Hex1.txt $COMPort");
            exec("echo ".$itemDescriptionPrice." > $COMPort");
            exec("echo ".$message." > $COMPort");
        }


$users_arr = array();
$users_arr[] = array("Allresult" => $a,"TotalAmount" => $Total,"TotalItems" => $nos,"TotalTax" => $TotalTax,"FinalAmount" => $FinalAmount,"InvoiceNumber" => $invoice_no,"ResultNO" => $nosByCode,"TotalItemsAll" => $TotalQty,"PCode" => $pcode,"Chkvalue" => $checkvalue,"finalqty" => "(العدد (".$finalqty);	
echo json_encode($users_arr);
?>	
  