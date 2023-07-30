<?php
//Include database configuration file
include('../conn.php');
if(isset($_POST["Department"]) && !empty($_POST["Department"])){
$Department=$_POST["Department"];

     $query =mysqli_query($link,"SELECT * FROM ac_chart_types WHERE  id='$Department'");    
    //Count total number of rows
   $rowCount = mysqli_num_rows($query);
     //Display cities list
    if($rowCount > 0){
        $row = mysqli_fetch_array($query);
		echo $cid=$row['class_id'];
        
    }
}
?>