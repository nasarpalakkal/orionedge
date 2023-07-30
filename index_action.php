<?php
session_start();
include("conn.php");
$user=$_POST['userid'];
$pass=$_POST['password'];
$computername=gethostbyaddr($_SERVER['REMOTE_ADDR']);
$qry=mysqli_query($link,"select * from user left join role on user.role=role.id where username='$user' and password='$pass' and active='1' ");
$no=mysqli_num_rows($qry);
if($no>0)
{
$obj=mysqli_fetch_object($qry);
$id=$obj->uid;					
$role=$obj->role;
$username=$obj->username;
$displayname=$obj->displayname;	
$prof_img=$obj->prof_img;	
$role_master=$obj->role_master;	
$storeID=$obj->store;
date_default_timezone_set('Asia/Riyadh');		
$cdate=date('Y-m-d');		
$ctime=date('H:i:s');		
$_SESSION['lang']='en_US';
$_SESSION['ADUSER']=$id;
$_SESSION['RoleID']=$role;	
$_SESSION['USERDISPLAYNAME']=$displayname;
$_SESSION['PROFIMG']=$prof_img;	
$_SESSION['RoleMaster']=$role_master;
$_SESSION['DefStore']=$storeID;	

					if($_POST["remember_me"]=='1' || $_POST["remember_me"]=='on')
                    {
                    $hour = time() + 3600 * 24 * 30;
                    setcookie('userid', $user, $hour);
                    setcookie('password', $pass, $hour);
                    }	
	
		
				
	
																$_SESSION['ComputerID']=1;
																$_SESSION['daily_close']=$daily_close;
																$_SESSION['WHID']=$storeID;
																$_SESSION['SalesDate']=$salesdate;		
																$_SESSION['SalesOpeningAmt']=$salesopeningAmt;
																$_SESSION['tax_hd']=$tax_hd;
				
header("location:ds/Sales.php");	
				
}
else
{
header("location:index.php?error");
}
?>


