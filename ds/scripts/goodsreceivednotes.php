<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "purchase_gdr";

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
            'db' => 'createdDate', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['createdDate'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
       array( 
        'db' => 'PONumberGDN', 
        'dt' => 2,
        'formatter' => function( $d, $row ) {				
            return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['PONumberGDN'].'</font></a>';
        }
     ),
	    array( 
            'db' => 'GDNDate', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$d.'</font></a>';
            }
       ),	
	    array( 
            'db' => 'supplier_name', 
            'dt' =>4,
            'formatter' => function( $d, $row ) {			
                return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['supplier_name'].'</font></a>';
            }
       ),	
       array( 
        'db' => 'displayname', 
        'dt' =>5,
        'formatter' => function( $d, $row ) {			
            return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['displayname'].'</font></a>';
        }
   ),    
   array( 
    'db' => 'PONumber', 
    'dt' => 6,
    'formatter' => function( $d, $row ) {
        return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
    }
),  	
array( 
    'db' => 'PONumber', 
    'dt' => 7,
    'formatter' => function( $d, $row ) {			
        if($row['11']=="")
        {	        
        return '<a class="btn btn-warning btnedit" href="GoodsReceivedToPurchase.php?ordernumber='.$row[1].'&&grnnumber='.$row[2].'" onclick="javascript:frmMakeInvoice();"><i class="fa fa-money" style="height: 15px"></i></a>';
        }
        else
        {
            return '<a class="btn btn-warning btnedit" href="Purchase.php?id='.$row[11].'"><span class="label label-success">Invoice No#.'.$row[11].'</span></a>';	
        }
    }
), 
   array( 
    'db' => 'PONumber', 
    'dt' => 8,
    'formatter' => function( $d, $row ) {				        
        return '<a href="GoodsReceivedNotes_print.php?id='.$row[1].'&&gdn='.$row[2].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i></a>';
    }
),
array( 
    'db' => 'PONumber', 
    'dt' => 9,
    'formatter' => function( $d, $row ) {		
            if($row['11']=="")
            {
                if($_GET['deletepermission']==0)
                {
                    return '';			
                }	
                else{			
                return '<a href="GoodsReceivedNotes.php?id='.$row[1].'&&gdn='.$row[2].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';	
                }
            }
            else
            {
                return '';		
            }
    }
), 
array( 'db' => 'gdr_status',    'dt' => 10 ), 
array( 'db' => 'PurchaseNo',    'dt' => 11 ),     

);

// Include SQL query processing class
require( 'ssp.class.php' );

if($useridsearch=="")
			{
$wherecondition = "GDNDate between '$formStartDate' and '$formEndDate'";
			}
			else
			{
$wherecondition = "GDNDate between '$formStartDate' and '$formEndDate' ";
			}

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);