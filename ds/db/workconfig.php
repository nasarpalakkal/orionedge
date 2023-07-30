<?php
//--------------------------------------------------------------------------------------
function workconfig($RoleID,$menuid)
{
	global $link;
	$query=mysqli_query($link,"select sum(add_txt+edit_txt+delete_txt+view_txt) from group_definition where id='$RoleID' and menu_id='$menuid'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];	
}
function workconfigDetail($RoleID,$menuid)
{
	global $link;
	$query=mysqli_query($link,"select * from group_definition where id='$RoleID' and menu_id='$menuid'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;	
}
//-------------------------------------------------------------------
?>