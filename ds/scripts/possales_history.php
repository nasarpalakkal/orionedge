<?php
include("../../dbDetails.php");
$table = "pos_sales_v";

// Table's primary key
$primaryKey = 'invoice_no';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
     array(
        'db'        => 'dateTime',
        'dt'        => 0,
        'formatter' => function( $d, $row ) {
            return strtotime($d);
        }
    ),
	array( 'db' => 'invoice_no',  'dt' => 1 ),
	 array(
        'db'        => 'date',
        'dt'        => 2,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    ),    
	array(
        'db'        => 'dateTime',
        'dt'        => 3,
        'formatter' => function( $d, $row ) {
            return date( 'H:i:s', strtotime($d));
        }
    ),    
	array( 'db' => 'customer_name',      'dt' => 4 ),
	array( 'db' => 'customer_mobile',      'dt' => 5 ),  	
    array( 'db' => 'total_amt',    'dt' => 6 ),
	array( 'db' => 'discount_amt', 'dt' => 7 ),
	array( 'db' => 'vat',    'dt' => 8),	   
	array( 'db' => 'final_amt',    'dt' => 9 ),
	array( 
            'db' => 'cash_pay', 
            'dt' => 10,
            'formatter' => function( $d, $row ) {				
                return number_format($row[10]-$row[16],2,'.','');
            }
      ),  
	array( 
            'db' => 'card_pay', 
            'dt' => 11,
            'formatter' => function( $d, $row ) {				
							if($row[11]>0)
							{
                return number_format($row[11]-$row[16],2,'.','');
							}
            }
      ),    		
	array( 'db' => 'pos_name',    'dt' => 12 ),
	array( 'db' => 'displayname',    'dt' => 13 ),
	 array( 
            'db' => 'invoice_no', 
            'dt' => 14,
            'formatter' => function( $d, $row ) {				
                return '<a href="exportToExcel/pos_sales_details_export.php?id='.$row[1].'&&ret=1" class="btn btn-success"><i class="fa fa-file-excel-o" style="height: 15px"></i></a>';
            }
       ),  	
	array( 
            'db' => 'invoice_no', 
            'dt' => 15,
            'formatter' => function( $d, $row ) {				
                return '<a href="pos_salesDetails.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),
	array( 'db' => 'balance_amt',    'dt' => 16 ),   
	         

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "date between '$formStartDate' and '$formEndDate' and SalesType=0";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);