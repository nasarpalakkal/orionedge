<?php
ini_set('display_errors','off');
$DBhost = "localhost";
$DBuser = "root";
$DBpass = "";
$DBName = "exam";
$link = mysqli_connect($DBhost,$DBuser,$DBpass,$DBName)or die("Can not Connect to Database Please Try Again!!!");
$db = new mysqli($DBhost, $DBuser, $DBpass, $DBName);
mysqli_set_charset($link,"utf8");
?>