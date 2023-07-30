<?php
session_start();
include("../conn.php");
//mysqli_set_charset($link,"utf8");
$admid=$_SESSION['ADUSER'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
include("db/gl_db.php");
$eid=$_REQUEST['id'];
date_default_timezone_set('Asia/Riyadh');

mysqli_query($link,"delete from ac_gl_trans where type_no='$eid'");
mysqli_query($link,"update ac_journal set posting='0',posting_date=NULL,posting_by=NULL where trans_no='$eid'");
header("location:gl_journal.php?id=$eid&&UPass");
?>