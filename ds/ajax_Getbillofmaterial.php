<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
include("../conn.php");
date_default_timezone_set('Asia/Riyadh'); 
require_once 'multilanguage.php';
$strID=$_POST['strID'];
$a="<table class=\"table table-striped table-condensed table-hover list-table table-responsive\" style=\"margin:0;\" id=\"cart-item\"><thead>
<tr class=\"success\"><th><span class=\"ArFont\">".gettext("Item")."</span></th><th><span class=\"ArFont\">".gettext("Unit")."</span></th><th><span class=\"ArFont\">".gettext("Transfer Qty")."</span></th><th><span class=\"ArFont\">".gettext("Expected Products")."</span></th></tr></thead><tbody>";		



		$i=1;
        $item=mysqli_query($link,"select A.item_no,B.item_descr,B.item_descr_ar,A.unit,A.qty,A.unitsno,U.descr UnitDescr,A.sno from billofmaterial_list as A left join inventory as B on A.item_no=B.item_no left join inventory_uom as C on A.item_no=C.item_no and A.unit=C.unit left join tb_units as U on A.unit=U.id where A.PONumber='$strID' order by A.sno asc");
		$nos=mysqli_num_rows($item);	
		while($objitem=mysqli_fetch_array($item))
		{		
            $item_no=$objitem['item_no'];	
            $item_descr=$item_no."-".$objitem['item_descr']."-".$objitem['item_descr_ar'];
            $Unitidret=$objitem['unit'];
            $sno=$objitem['sno'];
				
				$a=$a."<tr>	<td ><font size=\"2.2\">$item_descr</font></td>
                <td ><select id=\"unit$i\" name=\"unit[]\" style=\"width:100px;\"><option>".gettext("Select unit")."</option>";
                $unitqry=mysqli_query($link,"select * from tb_units");
                while($objunit=mysqli_fetch_array($unitqry))
                {
                    $unitid=$objunit['id'];
                    $unitdescr=$objunit['descr'];
                    if($Unitidret==$unitid){ $v="selected=\"selected\"";} else { $v="";}
                    $a=$a."<option value=\"$unitid\" $v>$unitdescr</option>";
                }
                
                $a=$a."</select></td>
                <td><input type=\"text\" name=\"qtytotransfer[]\" id=\"qtytotransfer$i\" onKeyPress=\"return isNumber(event);\" onchange=\"frmExpectedProducts('$i','$sno',this.value,'$strID')\" value='' size=10 style=\"text-align:center;\" autocomplete=\"off\"></td>
                <td><input type=\"text\" name=\"ExpectedProducts[]\" id=\"ExpectedProducts$i\" onKeyPress=\"return isNumber(event);\" style=\"text-align:center;\" size=10 readonly></td>
				</tr>";				
				$i=$i+1;
		}		
		
$users_arr = array();
$users_arr[] = array("Allresult" => $a,"TotalRows"=>$nos);	
echo json_encode($users_arr);
?>	
  