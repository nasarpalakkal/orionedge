<?php
include("../../dbDetails.php");
// DB table to use
$table = "itemtransfer_v";

// Table's primary key
$primaryKey = 'TransferNumber';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 
            'db' => 'cdate', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.$row['cdate'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'TransferNumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.$row['TransferNumber'].'</font></a>';
            }
       ),		
	    array( 
            'db' => 'Fdate', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {	
                $d=date( 'd-m-Y', strtotime($d));					
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
            }
       ),
	   array( 
            'db' => 'FromWarehouseDescr', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.$row['FromWarehouseDescr'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'ToWarehouseDescr', 
            'dt' => 4,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.$row['ToWarehouseDescr'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'sub_total', 
            'dt' => 5,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.number_format($row['sub_total'],2,'.','').'</font></a>';
            }
       ),	
	   					
	array( 
            'db' => 'tax_amt', 
            'dt' => 6,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.number_format($row['tax_amt'],2,'.','').'</font></a>';
            }
       ),
	   array( 
            'db' => 'net_amt', 
            'dt' => 7,
            'formatter' => function( $d, $row ) {				
                return '<a href="item_transfer.php?id='.$row[1].'" ><font color="black">'.number_format($row['net_amt'],2,'.','').'</font></a>';
            }
       ),	
       array( 
        'db' => 'TransferNumber', 
        'dt' => 8,
        'formatter' => function( $d, $row ) {				
            return '<a href="item_transfer.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
        }
   ),	
   array( 
    'db' => 'TransferNumber', 
    'dt' => 9,
    'formatter' => function( $d, $row ) {
                    if($_GET['deletepermission']==0)
                    {
                        return '';			
                    }                  
                    else
                    {				
        return '<a href="item_transfer_list.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
                    }   
        
    }
),
	          

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);