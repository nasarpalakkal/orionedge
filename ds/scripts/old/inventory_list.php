<?php
include("../../dbDetails.php");
// DB table to use
$table = "inventory_v";

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
            'db' => 'TaxDescr', 
            'dt' => 5,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['TaxDescr'].'</font></a>';
            }
       ),	
	   				
	array(
        'db'        => 'Type',
        'dt'        => 6,
        'formatter' => function( $d, $row ) {
            	
				
				if($d==2){ return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">Fixed Asset</font></a>'; } else if($d==3){ return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">Consumables</font></a>';  } else { return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">Sellable</font></a>';  }
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
            'db' => 'SalesPrice', 
            'dt' => 10,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['SalesPrice'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'BaseWPrice', 
            'dt' => 11,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" ><font color="black">'.$row['BaseWPrice'].'</font></a>';
            }
       ),	
	
	array( 
            'db' => 'item_no', 
            'dt' => 12,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master_add.php?id='.$row[0].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),	
	array( 
            'db' => 'item_no', 
            'dt' => 13,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_master.php?id='.$row[0].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
				
            }
       )        

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);