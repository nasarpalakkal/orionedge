<?php
include("../../conn.php");

$request=$_REQUEST;

$sql ="SELECT * FROM inventory";
$query=mysqli_query($link,$sql);

$totalData=mysqli_num_rows($query);

$totalFilter=$totalData;

//Search
$sql ="SELECT * FROM inventory";
if(!empty($request['search']['value'])){
    $sql.=" AND (item_no Like '".$request['search']['value']."%' ";
    $sql.=" OR item_descr Like '".$request['search']['value']."%' ";
}
$query=mysqli_query($link,$sql);
$totalData=mysqli_num_rows($query);

//Order
//$sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
  //  $request['start']."  ,".$request['length']."  ";

$query=mysqli_query($link,$sql);

$data=array();

while($row=mysqli_fetch_array($query)){
    $subdata=array();
    $subdata[]=$row[0]; //id
    $subdata[]=$row[1]; //name	
  	$subdata[]=$row[1]; //name
	
		$a=mysqli_query($link,"select id,code,descr_en,descr_ar from warehouse_master");
		while($b=mysqli_fetch_array($a))
		{
		$whid=$b['id'];
		$a2=mysqli_query($link,"select qty from inventory_qty  where item_no='".$row[0]."' and warehouse_id='$whid'");
																$b2=mysqli_fetch_array($a2);
																$qty=$b2[0];	
		
	$subdata[]=$qty; //name
		
		}	
	$subdata[]=$row[0]; //name	
    $data[]=$subdata;
}

$json_data=array(
    "draw"              =>  intval($request['draw']),
    "recordsTotal"      =>  intval($totalData),
    "recordsFiltered"   =>  intval($totalFilter),
    "data"              =>  $data
);

echo json_encode($json_data);

?>
