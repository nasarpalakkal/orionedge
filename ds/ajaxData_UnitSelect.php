<?php
include('../dbConfig.php');
if(isset($_POST["ItemID"]) && !empty($_POST["ItemID"])){
$userid=$_POST["ItemID"];

     $query = $db->query("SELECT  A.unit,B.descr,A.Packing_Setup,A.sno  from inventory_uom as A  left join tb_units as B on A.unit=B.id where A.item_no='$userid'");
    
    //Count total number of rows
    $rowCount = $query->num_rows;
     //Display cities list
    if($rowCount > 0){	       
	echo '<option value=""></option>';
		while($row = $query->fetch_assoc()){    				         
			
			//$unitval=$row['unit']."||".$row['Packing_Setup'];
			
			$unitval=$row['sno'];
			
			echo '<option value="'.$unitval.'">'.$row['descr']." ".$row['Packing_Setup'].'</option>';
        }
    }else{
        echo '<option value=""></option>';
    }
}
?>