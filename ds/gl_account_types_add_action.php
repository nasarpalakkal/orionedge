<?php
session_start();
include("../conn.php");
mysqli_set_charset($link,"utf8");
$admid=$_SESSION['ADUSER'];$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
if($admid=="")
{
include("logout.php");
}

$EditID=$_REQUEST['EditID'];
$GroupID=$_REQUEST['GroupID'];
$GroupName=$_REQUEST['GroupName'];
$GroupNameAr=$_REQUEST['GroupNameAr'];
$Subgroup=$_REQUEST['Subgroup'];
$ClassType=$_REQUEST['ClassType'];

$qry1=mysqli_query($link,"select * from ac_chart_types where id='$GroupID'");
$no1=mysqli_num_rows($qry1);
if($no1>0)
{
if($EditID>0)
{
mysqli_query($link,"update ac_chart_types set name='$GroupName',name_ar=".(($GroupNameAr=='')?"NULL":("'".$GroupNameAr."'")) . ",class_id='$ClassType',parent='$Subgroup' where id='$GroupID' ");
header("location:gl_account_types_add.php?id=$GroupID&&update");
}
else
{
header("location:gl_account_types_add.php?error");
}
}
else
{	
mysqli_query($link,"insert into ac_chart_types(id,name,name_ar,class_id,parent,inactive) values('$GroupID','$GroupName',".(($GroupNameAr=='')?"NULL":("'".$GroupNameAr."'")) . ",'$ClassType','$Subgroup','0')");
header("location:gl_account_types_add.php?save");
}
?>