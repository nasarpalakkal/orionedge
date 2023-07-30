<?php
include("../../dbDetails.php");
$table = "pos_close_details_v";

// Table's primary key
$primaryKey = 'id';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
     array(
        'db'        => 'closed_datetime',
        'dt'        => 0,
        'formatter' => function( $d, $row ) {
            return strtotime($d);
        }
    ),
	array( 'db' => 'displayname',  'dt' => 1 ),
	array( 'db' => 'pos_name',  'dt' => 2 ),
	 array(
        'db'        => 'salesdate',
        'dt'        => 3,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    ),    
	array(
        'db'        => 'closed_datetime',
        'dt'        => 4,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    ),    
	array(
        'db'        => 'closed_datetime',
        'dt'        => 5,
        'formatter' => function( $d, $row ) {
            return date( 'H:i:s', strtotime($d));
        }
    ),    
	array( 'db' => 'ClosedByName',      'dt' => 6 ),
	array( 'db' => 'ClosingCash', 'dt' => 7 ),
	array( 'db' => 'ClosingCardAmount',    'dt' => 8),	   
	array( 'db' => 'EnvelopeNumber',    'dt' => 9 ),
	array( 'db' => 'cash_amt_sys',      'dt' => 10 ),  
	array( 'db' => 'card_amt_sys',      'dt' => 11 ),    
	array( 'db' => 'cash_ret_amt',    'dt' => 12 )

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "salesdate between '$formStartDate' and '$formEndDate'";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);