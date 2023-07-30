<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "sales_gdn";

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
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['createdDate'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
       array( 
        'db' => 'PONumberGDN', 
        'dt' => 2,
        'formatter' => function( $d, $row ) {				
            return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['PONumberGDN'].'</font></a>';
        }
     ),
	    array( 
            'db' => 'GDNDate', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$d.'</font></a>';
            }
       ),	
	    array( 
            'db' => 'customer_name', 
            'dt' =>4,
            'formatter' => function( $d, $row ) {			
                return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['customer_name'].'</font></a>';
            }
       ),	
       array( 
        'db' => 'displayname', 
        'dt' =>5,
        'formatter' => function( $d, $row ) {			
            return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'"><font color="black">'.$row['displayname'].'</font></a>';
        }
   ),    
   array( 
    'db' => 'PONumber', 
    'dt' => 6,
    'formatter' => function( $d, $row ) {				
        //return '<a href="exportToExcel/sales_details_export.php?id='.$row[1].'&&ret=1" class="btn btn-success"><i class="fa fa-file-excel-o" style="height: 15px"></i></a>';
        return '<a href="GoodsDeliveryNoteAdd.php?id='.$row[1].'&&gdn='.$row[2].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
    }
),
array( 
    'db' => 'PONumber', 
    'dt' => 7,
    'formatter' => function( $d, $row ) {	
        if($row['11']=="")
        {	        
        return '<a class="btn btn-warning btnedit" href="GoodsDeliveredToSales.php?ordernumber='.$row[1].'&&grnnumber='.$row[2].'" onclick="javascript:frmMakeInvoice();"><i class="fa fa-money" style="height: 15px"></i></a>';
       // return '<a class="btn btn-warning btnedit" onclick="frmMakeInvoice('.$row[1].','.$row[2].')"><i class="fa fa-money" style="height: 15px"></i></a>';
        }
        else
        {
            return '<span class="label label-success">Invoice No#.'.$row[11].'</span>';		
        }		                       
    }
),   	 
   array( 
    'db' => 'PONumber', 
    'dt' => 8,
    'formatter' => function( $d, $row ) {				
        //return '<a href="exportToExcel/sales_details_export.php?id='.$row[1].'&&ret=1" class="btn btn-success"><i class="fa fa-file-excel-o" style="height: 15px"></i></a>';
        return '<a href="GoodsDeliveryNotes_print.php?id='.$row[1].'&&gdn='.$row[2].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i></a>';
    }
),
array( 
    'db' => 'PONumber', 
    'dt' => 9,
    'formatter' => function( $d, $row ) {	
                if($row[11]=='')
                {
                    if($_GET['deletepermission']==0)
                    {
                        return '';			
                    }	
                    else
                    {			
                        return '<a href="GoodsDeliveryNotes.php?id='.$row[1].'&&gdn='.$row[2].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';	
                    } 
                }
                else
                {
                    return '';		
                }            
    }
),  
array( 'db' => 'gdn_status',    'dt' => 10 ),	
array( 'db' => 'sales_invoice_no',    'dt' => 11 ),	   

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