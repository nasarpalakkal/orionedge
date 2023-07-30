<?php
//Include database configuration file
include('../dbConfig.php');
if(isset($_POST["Department"]) && !empty($_POST["Department"])){
$Department=$_POST["Department"];

     $query = $db->query("SELECT cid,class_name FROM ac_chart_class order by cid asc");
    
    //Count total number of rows
   $rowCount = $query->num_rows;
     //Display cities list
	if($rowCount > 0){
	 echo '<option value="">-Select Class-</option>';
        while($row = $query->fetch_assoc()){ 	
		$cid=$row['cid'];
		$class_name=$row['class_name'];
            echo '<option value="'.$cid.'">'.$class_name.'</option>';
        }
    }else{
        echo '<option value="">-Select Class-</option>';
    }
}
?>