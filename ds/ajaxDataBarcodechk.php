<?php
//Include database configuration file
include('../dbConfig.php');
if(isset($_POST["val"]) && !empty($_POST["val"])){
$val=$_POST["val"];
$ProductID=$_POST["ProductID"];
	 $query = $db->query("SELECT * FROM inventory_uom WHERE  barcode='$val' and item_no!='$ProductID'");
	 $rowCount = $query->num_rows;
	 echo $rowCount;
       
}
?>