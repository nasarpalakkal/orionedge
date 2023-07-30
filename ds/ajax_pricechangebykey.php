<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$Whid=$_SESSION['WHID'];
include("../conn.php");
date_default_timezone_set('Asia/Riyadh'); 
require_once 'multilanguage.php';
$itemid=$_POST['itemid'];
$currentunit=$_POST['currentunit'];
$sno=$_POST['sno'];

		$productByCode = mysqli_query($link,"SELECT A.item_no,A.retail_price,A.w_price,B.item_descr,B.TaxType,B.IncludeTax,C.perc,C.division_val,D.qty,A.factor_val,A.unit from inventory_uom as A LEFT join inventory as B on A.item_no=B.item_no left JOIN tax_type C on B.TaxType=C.id left join inventory_qty as D on A.item_no=D.item_no LEFT JOIN tb_units as E on A.unit=E.id where A.item_no='$itemid' and A.unit='$currentunit' and D.warehouse_id='$Whid'");
		$objectByCode = mysqli_fetch_array($productByCode);		
		$factor_val=$objectByCode["factor_val"];
		$r_price=$objectByCode["retail_price"];
		$w_price=$objectByCode["w_price"];	
		$taxperc=$objectByCode["perc"];
		$stockqty=$objectByCode["qty"];	
		$division_val=$objectByCode["division_val"];	
		$IncludeTax=$objectByCode["IncludeTax"];	
	
		$productByCode1 = mysqli_query($link,"SELECT * from tbl_temp_sales where code='$itemid' and sno='$sno'");
		$objectByCode1 = mysqli_fetch_array($productByCode1);
		$qty=$objectByCode1['qty'];
		$priceret=$objectByCode1['unit_price'];
		$styperet=$objectByCode1['stype'];
			if($styperet==1)
			{			
			
			$stype=2;
													if($IncludeTax==1)
													{
													$price=number_format($w_price/$division_val,2,'.','');
													$TotalWithTax=$qty*$w_price;
													$Total=number_format($TotalWithTax/$division_val,2,'.','');
													$Taxamt=number_format($TotalWithTax-$Total,2,'.','');													
													}
													else
													{
													$price=number_format($w_price,2,'.','');
													$Total=number_format($qty*$price,2,'.','');
													$Taxamt=number_format(($Total*($taxperc/100)),2,'.','');													
													}
			}
			else
			{
			$stype=1;
													if($IncludeTax==1)
													{
													$price=number_format($r_price/$division_val,2,'.','');
													$TotalWithTax=$qty*$r_price;
													$Total=number_format($TotalWithTax/$division_val,2,'.','');
													$Taxamt=number_format($TotalWithTax-$Total,2,'.','');													
													}
													else
													{
													$price=number_format($r_price,2,'.','');
													$Total=number_format($qty*$price,2,'.','');
													$Taxamt=number_format(($Total*($taxperc/100)),2,'.','');													
													}
			}
			
		
			
		mysqli_query($link,"update tbl_temp_sales set unit_price='$price',tax_amt='$Taxamt',total_price='$Total',stype='$stype' where sno='$sno' and code='$itemid'");
				
		
/*$a="<table class=\"table table-striped table-condensed table-hover list-table table-responsive\" style=\"margin:0;\" id=\"cart-item\"><thead>
<tr class=\"success\"><th><span class=\"ArFont\">".gettext("Item")."</span></th><th><span class=\"ArFont\">".gettext("Unit")."[F10]</span></th><th><span class=\"ArFont\">".gettext("Type")."[F10]</span></th><th><span class=\"ArFont\">".gettext("Price")."</span></th><th><span class=\"ArFont\">".gettext("Qty")."</span></th><th><span class=\"ArFont\">".gettext("Total")."</span></th><th><i class=\"fa fa-trash-o\"></i></th></tr></thead><tbody></table>";	*/

$a="<table class=\"table table-striped table-condensed table-hover list-table table-responsive\" style=\"margin:0;\" id=\"cart-item\"><thead>
<tr class=\"success\"><th><span class=\"ArFont\">".gettext("Item")."</span></th><th><span class=\"ArFont\">".gettext("Price")."</span></th><th><span class=\"ArFont\">".gettext("Qty")."</span></th><th><span class=\"ArFont\">".gettext("Total")."</span></th><th><i class=\"fa fa-trash-o\"></i></th></tr></thead><tbody></table>";	
		
		$Total=0;
		$TotalTax=0;
		$TotalQty=0;
		$i=1;
		$item=mysqli_query($link,"SELECT A.invoice_no,A.sno,A.qty,A.unit_price,A.total_price,A.code,A.tax_amt,A.taxperc,B.item_descr,B.item_descr_ar,A.unit,U.descr as UnitDescr,A.stype FROM tbl_temp_sales as A  left join inventory as B on A.code=B.item_no left join tb_units as U on A.unit=U.id WHERE A.uid='$admid' order by A.sno asc");	
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
				if($objitem['stype']==1){$stype="R";}else{$stype="W";}
				
				$unitPriceDisp=preg_replace('~\.0+$~','',number_format($unitprice,2,'.',','));
				$TotalPriceDisp=preg_replace('~\.0+$~','',number_format($total_price+$tax_amt,2,'.',','));
				//$TotalPriceDisp=preg_replace('~\.0+$~','',number_format($total_price,2,'.',','));
				
				$a=$a."<tr>	<td><font size=\"2.2\">$code-$descr <br> $descrAr</font></td>
				
				<input type=\"hidden\" value='$unitDescr' size=4  style=\"text-align:center;\" onKeyDown=\"return checkPhoneKey(event.key,'$i','$code','$unit','$tempid',event.which)\" autocomplete=\"off\">
				
				<input type=\"hidden\" value='$stype' id='SType$i' size=1  style=\"text-align:center;\" onKeyDown=\"return checkSalesType(event.key,'$i','$code','$unit','$tempid',event.which)\" autocomplete=\"off\">	
				
				<td align=\"center\"><input type=\"text\" value='$unitPriceDisp' id='PriceChange$i' size=4  style=\"text-align:center;\" onChange=\"return checkPriceChange('$i','$code','$unit','$tempid',this.value)\" autocomplete=\"off\" onClick=\"this.setSelectionRange(0, this.value.length)\"></td>
				
				<td align=\"center\">
				<i class=\"fa fa-minus-square\" style=\"font-size:30px;color:red\" onClick=\"cartActionMinusPlus('$code',1,'$unit')\"></i>&nbsp;<input type=\"text\" value='$qty' id='T$i' size=1  style=\"text-align:center;\" onKeyPress=\"return isNumber(event);\" onfocus=\"this.select();\" onkeydown=\"frmQtyKey(event,this.value,'$code','$unit','$i')\" onblur=\"frmQty(this.value,'$code','$unit')\" autocomplete=\"off\">&nbsp;<i class=\"fa fa-plus-square\" style=\"font-size:30px;color:green\" onClick=\"cartActionMinusPlus('$code',2,'$unit')\"></i>
				
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
		
$users_arr = array();
$users_arr[] = array("Allresult" => $a,"TotalAmount" => $Total,"TotalItems" => $nos,"TotalTax" => $TotalTax,"FinalAmount" => $FinalAmount,"InvoiceNumber" => $invoice_no,"ResultNO" => $nosByCode,"TotalItemsAll" => $TotalQty,"PCode" => $pcode, "finalqty" => "(العدد (".$finalqty);	
echo json_encode($users_arr);
?>