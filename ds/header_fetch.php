<?php
session_start();
include("../conn.php");
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
if(isset($_POST['view'])){
// $con = mysqli_connect("localhost", "root", "", "notif");
/*if($_POST["view"] != '')
{
    $update_query = "UPDATE comments SET comment_status = 1 WHERE comment_status=0";
    mysqli_query($con, $update_query);
}


$query_notification = "SELECT * FROM operation WHERE status=1";
$result = mysqli_query($link, $query_notification);
$output = '';
if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_array($result))
 {
   $output .= '
   <li>
   <a href="#">
   <strong>'.$row["comment_subject"].'</strong><br />
   <small><em>'.$row["comment_text"].'</em></small>
   </a>
   </li>
   ';
 }
}
else{
     $output .= '
     <li><a href="#" class="text-bold text-italic">No Noti Found</a></li>';
}


*/

$status_query = "SELECT * FROM operation_status left join operation on operation_status.operation_id=operation.id WHERE (operation.status=1 || operation.status=3 || operation.status=4 ) and operation_status.forward_user_id='$admid' group by (operation_status.operation_id) order by operation_status.id desc";
$result_query = mysqli_query($link, $status_query);
$count = mysqli_num_rows($result_query);

$status_query1 = "SELECT * FROM operation_status left join operation on operation_status.operation_id=operation.id WHERE operation.status=5 and (operation_status.forward_user_id='$admid' || operation_status.forward_role_id='$RoleID')  group by (operation_status.operation_id) order by operation_status.id desc";
$result_query1 = mysqli_query($link, $status_query1);
$count1 = mysqli_num_rows($result_query1);

if($count>0)
{
$output = $output. '<li><a href="OperationHistory.php?tb=2" class="label-warning text-bold text-italic">You have '.$count.' Forwarded Tickets</a></li>';
}
if($count1>0)
{
$output =$output. '<li><a href="OperationHistory.php?tb=2" class="label-warning text-bold text-italic">You have '.$count1.' Esclated Tickets</a></li>';
}

$data = array(
    'notification' => $output,
    'unseen_notification'  => $count+$count1
);
echo json_encode($data);
}
?>