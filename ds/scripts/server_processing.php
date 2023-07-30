<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "operation_v";

// Table's primary key
$primaryKey = 'id';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
$rtType=$_GET['rtType'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
     array(
        'db'        => 'CurrentDateTime',
        'dt'        => 0,
        'formatter' => function( $d, $row ) {
            return strtotime($d);
        }
    ),
	array( 'db' => 'Location', 'dt' => 1 ),
    array( 'db' => 'Receivedfrom',  'dt' => 2 ),
    array( 'db' => 'Gender',      'dt' => 3 ),
    array( 'db' => 'Age',     'dt' => 4 ),
    array( 'db' => 'Type',    'dt' => 5 ),
	array( 'db' => 'Facilityservice', 'dt' => 6 ),
	array( 'db' => 'FacilityserviceCategory',    'dt' => 7 ),	
    array(
        'db'        => 'CurrentDateTime',
        'dt'        => 8,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    ),
	 array(
        'db'        => 'CurrentDateTime',
        'dt'        => 9,
        'formatter' => function( $d, $row ) {
            return date( 'H:i:s', strtotime($d));
        }
    ),
	array( 'db' => 'Shift',    'dt' => 10 ),	
	
	array( 'db' => 'CountryName',    'dt' => 11 ),	
    
	array(
        'db'        => 'status',
        'dt'        => 12,
        'formatter' => function( $d, $row ) {
							if($d==0)
							{
							return '<span class="label label-danger">Open</span>';
							}
							else if($d==1)
							{
								$ids=$row[14];
								include('../../conn.php');
								$qry_MaxOperationStatus=mysqli_query($link,"SELECT B.displayname,c.descr FROM operation_status as A left join user as B on A.forward_user_id=B.uid left join role as C on A.forward_role_id=C.id where A.operation_id='$ids'  order by A.id desc");	
								$obj_MaxOperationStatus=mysqli_fetch_array($qry_MaxOperationStatus);
								$forward_user_id=$obj_MaxOperationStatus['displayname'];
								$forward_role_id=$obj_MaxOperationStatus['descr'];
										if($forward_user_id!="")
										{
										$ForwardedToUserName=$forward_user_id;
										}
										else
										{
										$ForwardedToUserName=$forward_role_id;
										}
									
							return '<span class="label label-warning">Forward'."-".$ForwardedToUserName.'</span>';	
							}
							else if($d==2)
							{
							return '<span class="label label-success">Closed</span>';
							}
							else if($d==3)
							{
							return '<span class="label label-warning">Re-Open</span>';
							}
							else if($d==4)
							{
							return '<span class="label label-warning">Re-Send</span>';
							}
							else if($d==5)
							{
							return '<span class="label label-warning">Escalated</span>';
							}
        }
    ),
	array( 'db' => 'CreatedBy',    'dt' => 13 ),
	array( 
            'db' => 'id', 
            'dt' => 14,
            'formatter' => function( $d, $row ) {				
                return '<a href="Operation.php?id='.base64_encode($row[14]).'" class="btn btn-primary btnedit" ><i class="fa fa-edit"></i></a>';
            }
       )

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "ReportType ='$rtType' and CurrentDate between '$formStartDate' and '$formEndDate'";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);