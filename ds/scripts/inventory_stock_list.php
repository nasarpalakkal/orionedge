<?php
// Database connection info
include("../../dbDetails.php");
// DB table to use
$table = "inventory_qty_v";

// Table's primary key
$primaryKey = 'item_no';

$useridsearch=$_GET['useridsearch'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
      array( 
            'db' => 'item_no', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="update_stock.php?id='.$row[0].'" ><font color="black">'.$row['item_no'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'inventoryDescr', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="update_stock.php?id='.$row[0].'" ><font color="black">'.$row['inventoryDescr'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'inventoryDescrAr', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {				
                return '<a href="update_stock.php?id='.$row[0].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	
       array( 
        'db' => 'qty', 
        'dt' => 3,
        'formatter' => function( $d, $row ) {				
            return '<a href="update_stock.php?id='.$row[0].'" ><font color="black">'.$d.'</font></a>';
        }
   ),	  
   array( 
    'db' => 'descr', 
    'dt' => 4,
    'formatter' => function( $d, $row ) {				
        return '<a href="update_stock.php?id='.$row[0].'" ><font color="black">'.$d.'</font></a>';
    }
),	  
   array( 
    'db' => 'WarehouseDescrEn', 
    'dt' => 5,
    'formatter' => function( $d, $row ) {				
        return '<a href="update_stock.php?id='.$row[0].'" ><font color="black">'.$d.'</font></a>';
    }
),	

);

// Include SQL query processing class
require( 'ssp.class.php' );

if($useridsearch=="")
			{
$wherecondition = "";
			}
			else
			{
$wherecondition = " warehouse_id='$useridsearch'";
			}

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);