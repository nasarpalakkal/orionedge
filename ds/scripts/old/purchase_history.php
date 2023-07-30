<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "purchase_v";

// Table's primary key
$primaryKey = 'PONumber';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
      array( 
            'db' => 'cdatetime', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'cdate', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));			
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	  
	   array( 
            'db' => 'supplier_name', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {				
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$row['supplier_name'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'DocumentNumber', 
            'dt' => 4,
            'formatter' => function( $d, $row ) {				
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$row['DocumentNumber'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'VendorInvoiceNumber', 
            'dt' => 5,
            'formatter' => function( $d, $row ) {				
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$row['VendorInvoiceNumber'].'</font></a>';
            }
       ),  	 
	    array( 
            'db' => 'ReceivingDate', 
            'dt' => 6,
            'formatter' => function( $d, $row ) {	
			if($d==""){ $ReceivingDate=""; } else{  $ReceivingDate=date( 'd-m-Y', strtotime($d)); }			
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$ReceivingDate.'</font></a>';
            }
       ),  	 	 		
    array( 'db' => 'sub_total',    'dt' => 7 ),
	array( 'db' => 'net_discount', 'dt' => 8 ),
	array( 'db' => 'tax_amt',    'dt' => 9 ),	   
	array( 'db' => 'net_amt',    'dt' => 10 ),
	array( 
            'db' => 'displayname', 
            'dt' => 11,
            'formatter' => function( $d, $row ) {					
                return '<a href="Purchase.php?id='.$row[1].'" ><font color="black">'.$row['displayname'].'</font></a>';
            }
       ), 	
	array( 
            'db' => 'PONumber', 
            'dt' => 12,
            'formatter' => function( $d, $row ) {				
                return '<a href="Purchase.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),
	   array( 
            'db' => 'PONumber', 
            'dt' => 13,
            'formatter' => function( $d, $row ) {				
                return '<a href="exportToExcel/purchase_details_export.php?id='.$row[1].'&&ret=1" class="btn btn-success"><i class="fa fa-file-excel-o" style="height: 15px"></i></a>';
            }
       ),	
	array( 
            'db' => 'PONumber', 
            'dt' => 14,
            'formatter' => function( $d, $row ) {				
                return '<a href="PurchaseHistory.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
				
            }
       )        

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and status=1";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);