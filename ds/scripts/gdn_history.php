<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "sales_gdn_view";

// Table's primary key
$primaryKey = 'PONumber';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
$useridsearch=$_GET['useridsearch'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
      array( 
            'db' => 'cdatetime', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'cdate', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	
	    array( 
            'db' => 'customer_name', 
            'dt' =>3,
            'formatter' => function( $d, $row ) {				
                        return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$row['customer_name'].'</font></a>';
            }
       ),	
       array( 
        'db' => 'StoreName', 
        'dt' =>4,
        'formatter' => function( $d, $row ) {				
                    return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$row['StoreName'].'</font></a>';
        }
   ),	     
   array( 
    'db' => 'PurchaseNumber', 
    'dt' =>5,
    'formatter' => function( $d, $row ) {				
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
    }
    ),	   
    array( 
        'db' => 'DeliveryDate', 
        'dt' => 6,
        'formatter' => function( $d, $row ) {	
        $d=date( 'd-m-Y', strtotime($d));		
            return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
        }
   ),       	
       array( 
        'db' => 'displayname', 
        'dt' => 7,
        'formatter' => function( $d, $row ) {	      
            return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" ><font color="black">'.$row['displayname'].'</font></a>';
        }
   ),	
   array( 
    'db' => 'PONumber', 
    'dt' => 8,
    'formatter' => function( $d, $row ) {				
        return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
    }
),	   
       array( 
        'db' => 'PONumber', 
        'dt' => 9,
        'formatter' => function( $d, $row ) {				
                 return '<a href="GoodsDeliveryNotes_print.php?id='.$row[1].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i> A4</a>';          
                        }    
        
   ),	   
        array( 'db' => 'customer_code',    'dt' => 10 ),   

);

// Include SQL query processing class
require( 'ssp.class.php' );

if($useridsearch=="")
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate'";
			}
			else
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and created_by='$useridsearch'";
			}

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);