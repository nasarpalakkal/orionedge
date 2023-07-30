<?php
//Include database configuration file
include('../dbConfig.php');
if(isset($_POST["Department"]) && !empty($_POST["Department"])){
$Department=$_POST["Department"];

     $query = $db->query("SELECT class_id FROM ac_chart_types WHERE  id='$Department' order by id asc");
    
    //Count total number of rows
   $rowCount = $query->num_rows;
     //Display cities list
    if($rowCount > 0){
        while($row = $query->fetch_assoc()){ 	
		$cid=$row['class_id'];
				$query1 = $db->query("SELECT class_name FROM ac_chart_class WHERE  cid='$cid' ");
				$obj1=$query1->fetch_assoc();
				$class_name=$obj1['class_name'];
            echo '<option value="'.$cid.'">'.$class_name.'</option>';
        }
    }else{
        echo '<option value="">-Select Class-</option>';
    }
}
?>