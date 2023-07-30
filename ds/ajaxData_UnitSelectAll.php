<?php
include('../dbConfig.php');
if(isset($_POST["ItemID"]) && !empty($_POST["ItemID"])){
$userid=$_POST["ItemID"];

     $query = $db->query("SELECT  id,descr  from tb_units");
    
    //Count total number of rows
    $rowCount = $query->num_rows;
     //Display cities list
    if($rowCount > 0){	       
	echo '<option value=""></option>';
		while($row = $query->fetch_assoc()){             
			echo '<option value="'.$row['id'].'">'.$row['descr'].'</option>';
        }
    }else{
        echo '<option value=""></option>';
    }
}
?>