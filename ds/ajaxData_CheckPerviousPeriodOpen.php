<?php
include('../conn.php');
$id=$_POST["id"];
$no=$_POST["nos"];
	if($id==0)
	{
		$nos=$no-1;
		if($nos>0)
		{
	$query = mysqli_query($link,"SELECT * FROM ac_period WHERE id='$nos' and closed='0'");
	echo $nos=mysqli_num_rows($query);
		}
	}	
?>