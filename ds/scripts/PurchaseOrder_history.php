<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "purchaseorder_v";

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
                return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'cdate', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));			
                return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	  
	   array( 
            'db' => 'supplier_name', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {				
                return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$row['supplier_name'].'</font></a>';
            }
       ),	 
       array( 
        'db' => 'PurchaseType', 
        'dt' => 4,
        'formatter' => function( $d, $row ) {	
                if($row['PurchaseType']==2)
                {
                    return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">Credit</font></a>';
                }	
                else
                {
                    return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">Cash</font></a>';
                }		
            
        }
   ),	       	 	 		
    array( 'db' => 'DocumentNumber',    'dt' => 5 ),
    array( 'db' => 'VendorInvoiceNumber',    'dt' => 6 ),
    array( 
        'db' => 'ReceivingDate', 
        'dt' => 7,
        'formatter' => function( $d, $row ) {	
                    if($d=="")
                    {
                        return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black"></font></a>';
                    }
                    else
                    {
        $d=date( 'd-m-Y', strtotime($d));			
            return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
                    } 
        }
   ),
   array( 
    'db' => 'PaymentTermDescr', 
    'dt' => 8,
    'formatter' => function( $d, $row ) {	
                if($d=="")
                {
                    return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black"></font></a>';
                }
                else
                {   	
        return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
                } 
    }
),
    array( 'db' => 'sub_total',    'dt' => 9 ),
	array( 'db' => 'net_discount', 'dt' => 10 ),
	array( 'db' => 'tax_amt',    'dt' => 11 ),	   
	array( 'db' => 'net_amt',    'dt' => 12 ),
	array( 
            'db' => 'displayname', 
            'dt' => 13,
            'formatter' => function( $d, $row ) {					
                return '<a href="PurchaseOrder.php?id='.$row[1].'" ><font color="black">'.$row['displayname'].'</font></a>';
            }
       ), 	
	array( 
            'db' => 'PONumber', 
            'dt' => 14,
            'formatter' => function( $d, $row ) {				
                return '<a href="PurchaseOrder.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),      
    array( 
        'db' => 'PONumber', 
        'dt' => 15,
        'formatter' => function( $d, $row ) {				
            if($row[19]==0)
					{				
                        return '<a href="GoodsReceivedNoteAdd.php?id='.$row[1].'" class="btn btn-warning btnedit"><i class="fa fa-sticky-note-o" style="height: 15px"></i></a>';
                    }
                    else
                    {
                    return '<span class="label label-success">Closed</span>';			
                    }
                
        }
        ),
	   array( 
            'db' => 'PONumber', 
            'dt' => 16,
            'formatter' => function( $d, $row ) {				                
                return '<a href="PurchaseOrder_print.php?id='.$row[1].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i></a>';
            }
       ),	
      
	array( 
            'db' => 'PONumber', 
            'dt' => 17,
            'formatter' => function( $d, $row ) {
                            if($row[19]==0)
                            {				
                                if($_GET['deletepermission']==0)
                                {
                                    return '';			
                                }	
                                else{	
                                return '<a href="PurchaseOrderHistory.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
                                }
                            }                            
                            else
                            {
                            return '<span class="label label-success">GRN Created</span>';			
                            }				
                		
						
				
            }
       ),	
       array( 'db' => 'PurchaseNo',    'dt' => 18 ),   
       array( 'db' => 'gdr_status',    'dt' => 19 ),        
);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and gdr_status=0";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);