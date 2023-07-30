<?php
	session_start();
	session_destroy();
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$session=$_REQUEST['session'];
	if($session==1)
	{
	$extra = '../index.php?session';
	}
	else
	{
	$extra = '../index.php';
	}
	header("Location:$extra");
	?>

