<?php
session_start();
$displayname=$_SESSION['USERDISPLAYNAME'];
include("../conn.php");
include("db/Address_db.php");
$id=$_REQUEST['id'];
$qry_initial=mysqli_query($link,"select * from sales where PONumber='$id'");
		$obj_initial=mysqli_fetch_object($qry_initial);
		$OrderNo=$id;
		$net_amt_final=$obj_initial->net_amt;
		$cdate=date('d-m-Y',strtotime($obj_initial->cdate));
		$sub_total=$obj_initial->sub_total;
		$Tax_amt=$obj_initial->tax_amt;
		$discount_amt=$obj_initial->net_discount;
		$net_amt_final=$obj_initial->net_amt;
		$CustomerName=$obj_initial->CustomerName;
		$bill_no=$obj_initial->bill_no;
		$PurchaseNumber=$obj_initial->PurchaseNumber;
		$CustomerNameDescr=CustomerDetails($obj_initial->CustomerName)["customer_name"];
    $CustomerNameDescrAr=CustomerDetails($obj_initial->CustomerName)["customer_name_ar"];
    $customerTax=CustomerDetails($obj_initial->CustomerName)["VatNumber"];
    $customerAddress=CustomerDetails($obj_initial->CustomerName)["customer_address"];
		$Status=$obj_initial->status;
		if($obj_initial->CashType==1){$CashType="Cash";} else {$CashType="Credit";}
		//$CashType=$obj_initial->CashType;
		$status=$obj_initial->status;
    $payment_terms=$obj_initial->payment_terms;
    $ReferenceDetails=$obj_initial->ReferenceDetails;
    $PONumberret=$obj_initial->PONumberret;
   
    $cdateforqr=date('Y-m-d',strtotime($obj_initial->cdatetime));
    $cdatetimeforqr=date('H:i:s',strtotime($obj_initial->cdatetime));

    //$cdatetimeforqrcode=date('d-m-Y H:i:s',strtotime($obj_initial->cdatetime));
    $cdatetimeforqrcode=$cdateforqr.'T'.$cdatetimeforqr.'Z';
  

      if($status==1) { $doctype="Tax Invoice"; $doctypeAr="ضريبة فاتورة"; } else if($status==2) { $doctype="Invoice Return"; $doctypeAr="إرجاع الفاتورة"; } else { $doctype="Holding Invoice"; $doctypeAr="فاتورة عقد "; }




		function AmountInWords(float $amount)
{
   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
   // Check if there is any number after decimal
   $amt_hundred = null;
   $count_length = strlen($num);
   $x = 0;
   $string = array();
   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $x < $count_length ) {
      $get_divider = ($x == 2) ? 10 : 100;
      $amount = floor($num % $get_divider);
      $num = floor($num / $get_divider);
      $x += $get_divider == 10 ? 1 : 2;
      if ($amount) {
       $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
       $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
       $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
       '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
       '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
        }
   else $string[] = null;
   }
   $implode_to_Rupees = implode('', array_reverse($string));
   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' Halala' : '';
   return ($implode_to_Rupees ? $implode_to_Rupees . 'Riyal ' : '') . $get_paise;
}

// require ('tc-lib-barcode/vendor/autoload.php');	
// $barcode = new \Com\Tecnick\Barcode\Barcode();
// $targetPath = "qr-code/";
// if (! is_dir($targetPath)) {
//         mkdir($targetPath, 0777, true);
// }
//         $qrcodeDetails=CompanyDetails()['cnameEn']."\n";
//         $qrcodeDetails=$qrcodeDetails."VAT No# ".CompanyDetails()['VATNumber']."\n";
//         $qrcodeDetails=$qrcodeDetails."BILL No# ".$OrderNo."\n";
//         $qrcodeDetails=$qrcodeDetails."Date&Time ".$cdatetimeforqrcode."\n";
//         $qrcodeDetails=$qrcodeDetails."BILL TOTAL : ".$net_amt_final."\n";
//         $qrcodeDetails=$qrcodeDetails."VAT(15%) : ".$Tax_amt;
// $bobj = $barcode->getBarcodeObj('QRCODE,H', $qrcodeDetails, - 16, - 16, 'black', array(
//         - 2,
//         - 2,
//         - 2,
//         - 2
// ))->setBackgroundColor('#f0f0f0');

// $imageData = $bobj->getPngData();
// $timestamp = time();

// file_put_contents($targetPath . $OrderNo . '.png', $imageData);
require('vendor/autoload.php');
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

$displayQRCodeAsBase64 = GenerateQrCode::fromArray([
  new Seller(CompanyDetails()['cnameEn']), // seller name        
  new TaxNumber(CompanyDetails()['VATNumber']), // seller tax number
  new InvoiceDate($cdatetimeforqrcode), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
  new InvoiceTotalAmount($net_amt_final), // invoice total amount
  new InvoiceTaxAmount($Tax_amt) // invoice tax amount
  // TODO :: Support others tags
])->render();

$html =$html.'
<html>
<head>
<style>
body {font-family: sans-serif;
	font-size: 10pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
td { vertical-align: top; }
.items td {
	border-left: 0.1mm solid #cccccc;
	border-right: 0.1mm solid #cccccc;
	border-bottom: 0.1mm solid #cccccc;
	height:50px;
}
table thead td { background-color: #EEEEEE;
	text-align: center;
	border: 0.1mm solid #000000;
	font-variant: small-caps;
}

.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items tr.border-bottom {
            border-bottom: 1px solid black;
        }
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}
table {
  border-collapse: collapse;
}

tr {
  border-bottom: 1pt solid black;
}
</style>
</head>
<body>
<!--mpdf
<htmlpageheader name="myheader">
<table width="100%"><tr>
<td width="33%" style="color:#000000; "><span style="font-weight: bold; font-size: 12pt;">';
$html=$html.CompanyDetails()["cnameEn"].'</span><br /><span style="font-size: 11pt;">';
$html=$html.nl2br(CompanyDetails()['address']).'<br />';
$html=$html."VAT Number:".nl2br(CompanyDetails()['VATNumber']).'</span><br />
</td>';
if(CompanyDetails()['logo']!=""){
$html=$html.'<td width="33%" style="text-align: center;"><img src="logo/'.CompanyDetails()['logo'].' "  width="30%" height="60px;"></td>
<td width="33%" style="text-align: right;"><span style="font-family: freesans;font-weight: bold; font-size: 13pt;">';    
}
else
{
    $html=$html.'<td width="33%" style="text-align: center;"></td>
<td width="33%" style="text-align: right;"><span style="font-family: freesans;font-weight: bold; font-size: 13pt;">';
}

$html=$html.CompanyDetails()['cnameAr'].'</span><br />';
$html=$html.'<span style="font-family: freesans;font-size: 11pt;">'.nl2br(CompanyDetails()['address_ar']).'</span><br />';
$html=$html.'<span style="font-family: freesans;font-size: 11pt;"> الرقم الضريبي : '.nl2br(CompanyDetails()['VATNumberAr']).'</span><br />
</td>
</td>
</tr></table>

<table width="100%" style="font-family: serif;" cellpadding="10"><tr>
<td width="45%" style="border: 0.0mm solid #888888; ">

<table width="100%" style="font-family: sans;">
			<tr> <td width="70%" align="center">'.$OrderNo.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Invoice No. <br>  رقم الفاتورة</span></td></tr>	
     
      <tr> <td width="70%" align="center"></td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;"></span></td></tr>	

			
   
      <tr> <td width="70%" align="center">'.$cdate.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Date <br> تاريخ </span></td></tr>  
      
     <tr> <td width="70%" align="center">'.$CashType.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Type <br> نوع المب </span></td></tr>
     
     <tr> <td width="70%" align="center">'.$PurchaseNumber.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">PO Number <br> رقم طلب الشراء</span></td></tr>
     
		</table>

</td>
<td width="55%" style="border: 0.0mm solid #888888; ">

		<table width="100%" style="font-family: sans;">
      <tr> <td width="70%" align="center">'.$CustomerNameDescr.'<br><span dir="rtl">'.$CustomerNameDescrAr.'</span></td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Customer Name<br>اسم العميل</span></td></tr>
 <tr><td width="70%" align="center">'.$customerTax.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Customer VAT No.<br>رقم الضريبي العميل </span></td></tr>

      <tr><td width="70%" align="center">'.$customerAddress.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Customer Address<br> عنوان </span></td></tr>';
      if($status==2)
		{
                        $html=$html.'<tr> <td width="70%" align="center">'.$PONumberret.'</td> <td width="30%" align="right" style="font-weight: bold; font-size: 9pt;"><span style="font-family: freesans;">Original Invoice No.<br> رقم الفاتورة أصلي </span></td></tr>';

                }	
     
      
		$html=$html.'</table>
	</td>
</tr></table>
<div style="font-size: 10pt; text-align: center; "><strong>'.$doctype.' | <span style="font-family: freesans;">'.$doctypeAr.' </span></strong></div>
</htmlpageheader>
<htmlpagefooter name="myfooter">

<div style="border-top: 0px solid #000000; font-size: 6pt; text-align: center;">
<table width="100%">
<tr><td width="50%"><span style="font-family: freesans;">
</td>
<td width="50%" align=right><span style="font-family: freesans;">
</td>
</tr>
</table>
</div>
<div style="border-top: 1px solid #000000; font-size: 7pt; text-align: center;">

<div style="text-align: center; font-style: italic;">
<table width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<tr>
<td width="50%"><p style="width: 200px; display: table;">
  <span style="display: table-cell; width: 100px;">Prepared By: </span>
  <span style="display: table-cell; border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$displayname.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
  <span style="font-family: freesans;display: table-cell; width: 100px;">: مسؤول المبيعات  </span>
  </p>
  <br>
 

  <p style="width: 200px; display: table;">
  <span style="display: table-cell; width: 100px;">Signature : </span>
  <span style="display: table-cell; border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
  <span style="font-family: freesans;display: table-cell; width: 100px;">: التوقيع  </span>
  
  </p>


</td>
<td width="50%"><p style="width: 200px; display: table;">
  <span style="display: table-cell; width: 100px;">Received By: </span>
  <span style="display: table-cell; border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
  <span style="font-family: freesans;display: table-cell; width: 100px;">: اسم المستلم</span>
</p>
<br>

  <p style="width: 200px; display: table;">
  <span style="display: table-cell; width: 100px;">Signature : </span>
  <span style="display: table-cell; border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
  <span style="font-family: freesans;display: table-cell; width: 100px;">: التوقيع  </span>
  
  </p>
</td>
</tr>

</table>
</div>
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>
<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->


<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
<thead>
<tr>
<td><span style="font-family: freesans;"><font size=2>رقم</font></span> <br /> S#</td>
<td colspan="2"><span style="font-family: freesans;"><font size=2>الوصف</font></span> <br /> Description</td>
<td><span style="font-family: freesans;"><font size=2>الكمية</font></span> <br /> Qty</td>
<td><span style="font-family: freesans;"><font size=2>الواحدة</font></span> <br /> UOM</td>
<td><span style="font-family: freesans;"><font size=2>سءر الواحدة</font></span><br />U.Price</td>
<td><span style="font-family: freesans;"><font size=2>بدون ضريبة</font></span><br />Total <br> Excl.VAT</td>
<td><span style="font-family: freesans;"><font size=2>ضريبة</font></span><br />VAT Amount</td>
<td><span style="font-family: freesans;"><font size=2>الإجمالي مع ضريبة</font> </span><br />Total <br> With VAT</td>
</tr>
</thead>
<tbody>
<!-- ITEMS HERE -->';
		$i=1;		 
		$a=mysqli_query($link,"select A.item_no,B.item_descr,B.item_descr_ar,A.TaxPer,A.item_type,A.unit,A.unit_price,A.qty,A.total_price,A.tax_amt,D.descr as UnitDescr from sales_list as A left join inventory as B on A.item_no=B.item_no left join tb_units as D on A.unit=D.id where A.PONumber='$id' order by A.sno asc");
         while ($b=mysqli_fetch_array($a)) {
             $item_no=$b['item_no'];
             $item_descr=$b['item_descr'];
             $item_descr_ar=$b['item_descr_ar'];
             $TaxPer=$b['TaxPer'];
             $total_price=$b['total_price'];
             $UnitDescr=$b['UnitDescr'];
             $unit_price=$b['unit_price'];
             $qty=$b['qty'];
             $taxamt=$b['tax_amt'];
             $sub_totalsingle=number_format($qty*$unit_price, 2, '.', '');
             $lineTotal=number_format($sub_totalsingle+$taxamt, 2, '.', '');

             $html=$html.'<tr style="border-bottom: 1pt solid black;">
<td>';
             $html=$html.$i.'</td>

<td>';
             $html=$html.$item_descr.'</td><td align="right"><span style="font-family: freesans">';
             $html=$html.$item_descr_ar.'</span></td>			  
<td align="center">';
             $html=$html.$qty.'</td>
<td align="center">';
             $html=$html.$UnitDescr.'</td>
<td align="right">';
             $html=$html.number_format($unit_price, 2, '.', ',').'</td>	
             <td align="right">';
             $html=$html.number_format($sub_totalsingle, 2, '.', ',').'</td>		      
<td align="right">';
             $html=$html.number_format($taxamt, 2, '.', ',').'</td>		      
<td align="right">';
             $html=$html.number_format($lineTotal, 2, '.', ',').'</td>		     
</tr>';
$i=$i+1;		 
}

		 $html=$html.'</table>
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<!-- END ITEMS HERE -->
<tr>
<td colspan=7 rowspan="3" class="totals" align="center"><img src="'.$displayQRCodeAsBase64.'" alt="QR Code" style="width: 100px;"/></td>
<td class="totals" ><b>Sub Total  <span style="font-family: freesans;"> (الاجمالى)</span> </b></td>
<td class="totals cost" align="right"><b>'.number_format($sub_total,2,'.',',').'</b></td>
</tr>
<tr>
<td class="totals"><b>Discount  <span style="font-family: freesans;">  (الخصم)</span> </b></td>
<td class="totals cost" align="right"><b>'.number_format($discount_amt,2,'.',',').'</b></td>
</tr>
<tr>
<td class="totals"><b>(15+)Vat <span style="font-family: freesans;">  (ضريبة)</span> </b></td>
<td class="totals cost" align="right"><b>'.number_format($Tax_amt,2,'.',',').'</b></td>
</tr>
<tr>
<td class="totals" colspan=7  align="left"><b> In Words</b><span style="font-family: freesans;"><b> (للحروف)</b></span>
&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.AmountInWords($net_amt_final).'</b>  </td>

<td class="totals"><b>TOTAL <span style="font-family: freesans;"> (مجموع)</span></b></td>
<td class="totals cost" align="right"><b>'.number_format($net_amt_final,2,'.',',').'</b></b></td>
</tr>



</tbody>
</table>

</body>
</html>
';

$filename="qr-code/".$OrderNo.".png";

require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
            'format' => 'Letter',
	'margin_left' => 7,
	'margin_right' => 12,
	'margin_top' => 80,
	'margin_bottom' => 25,
	'margin_header' => 5,
	'margin_footer' => 5
]);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Invoice Print");
$mpdf->SetAuthor("Invoice Print");
$mpdf->SetWatermarkImage('logo/rmc.PNG');
$mpdf->showWatermarkImage = true;
//$mpdf->SetWatermarkText("RMC");
//$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($html);
$file_name = $OrderNo.'.pdf';
//$mpdf->Output("invoice/".$OrderNo.'.pdf','F');
$mpdf->Output($file_name, 'I');
if (file_exists($filename)) {
        unlink($filename);       
      }
?>
<!-- <script src="webAppHardware/jquery-3.3.1.min.js"></script>
<script>
$(document).ready(function(){		
	window.location='printconfig.php?id='+'<?php echo $OrderNo; ?>';
});
</script> -->