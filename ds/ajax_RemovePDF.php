<?php
if(isset($_POST['path'])){
    $path = "invoice/".$_POST['path']; 
 
    // Check file exist or not 
    if( file_exists($path) ){ 
       // Remove file 
       unlink($path); 
 
       // Set status 
       echo 1; 
    }else{ 
       // Set status 
       echo 0; 
    } 
    die;
 }
?>