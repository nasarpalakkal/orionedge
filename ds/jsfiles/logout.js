function frmlogout() 
{
var c=confirm("Are you sure you want to Logout?");
if(c==true)
{
	window.location("logout.php");
}
else
{
	return false;
}
}

function frmdel()
{
var c=confirm("Do You Want To Delete?");
if(c==true)
{
window.submit();
}
else
{
return false;
}
}


