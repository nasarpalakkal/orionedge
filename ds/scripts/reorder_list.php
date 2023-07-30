<?php
include("../../dbDetails.php");
// DB table to use
$table = "reorder_level_v";

// Table's primary key
$primaryKey = 'item_no';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 
            'db' => 'item_no', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['item_no'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'item_descr', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['item_descr'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'item_descr_ar', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['item_descr_ar'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'CategoryDescrEn', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['CategoryDescrEn'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'BrandName', 
            'dt' => 4,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['BrandName'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'BarcodeDisplay', 
            'dt' => 5,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['BarcodeDisplay'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'TaxDescr', 
            'dt' => 6,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['TaxDescr'].'</font></a>';
            }
       ),		   				
	
	array( 
            'db' => 'BaseUnitDescr', 
            'dt' => 7,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['BaseUnitDescr'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'AvgCost', 
            'dt' => 8,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['AvgCost'].'</font></a>';
            }
       ),	
	   array( 
            'db' => 'BaseLastCost', 
            'dt' => 9,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['BaseLastCost'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'qty', 
            'dt' => 10,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['qty'].'</font></a>';
            }
       )
	        

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = " ReorderLevel > qty";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);