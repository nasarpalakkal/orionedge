<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "salesquotation_view";

// Table's primary key
$primaryKey = 'PONumber';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
$useridsearch=$_GET['useridsearch'];
$deletepermission=$_GET['deletepermission'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
      array( 
            'db' => 'cdatetime', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'cdate', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	
	    array( 
            'db' => 'customer_name', 
            'dt' =>3,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">'.$row['customer_name'].'</font></a>';
            }
       ),	
       array( 
        'db' => 'SalesType', 
        'dt' =>4,
        'formatter' => function( $d, $row ) {
                if($d==1)	
                {
                    return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">Retail Sales</font></a>';
                }
                else
                {
                    return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">Whole Sales</font></a>';
                }
            
        }
   ),	
   array( 
    'db' => 'CashType', 
    'dt' =>5,
    'formatter' => function( $d, $row ) {	
        if($d==1)	
        {
            return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">Cash Sale</font></a>';
        }
        else
        {
            return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">Credit Sales</font></a>';
        }
    
    }
),		   
    array( 'db' => 'sub_total',    'dt' => 6 ),
	array( 'db' => 'net_discount', 'dt' => 7 ),
	array( 'db' => 'tax_amt',    'dt' => 8),	   
	array( 'db' => 'net_amt',    'dt' => 9 ),		
	array( 
            'db' => 'displayname', 
            'dt' => 10,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="SalesQuotation.php?id='.$row[1].'" ><font color="black">'.$row['displayname'].'</font></a>';
            }
       ),		
	array( 
            'db' => 'PONumber', 
            'dt' => 11,
            'formatter' => function( $d, $row ) {			               	
                return '<a href="SalesQuotation.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),
	 array( 
            'db' => 'PONumber', 
            'dt' => 12,
            'formatter' => function( $d, $row ) {				
                return '<a href="salesquotation_print.php?id='.$row[1].'" class="btn btn-success" target="_blank"><i class="fa fa-print" style="height: 15px"></i></a>';
            }
       ),  	
	array( 
            'db' => 'PONumber', 
            'dt' => 13,
            'formatter' => function( $d, $row ) {
					if($row[14]=="")
					{	
                            if($_GET['deletepermission']==0)
                            {
                                return '';			
                            }	
                            else{
                                return '<a href="SalesQuotationHistory.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
                            }		
                
				}
				else
				{
				return '<span class="label label-success">Converted To Sales Order-'.$row[14].'</span>';			
				}
				
            }
       ),
       array( 'db' => 'SalesOrderNumber',    'dt' => 14 ),    
      // array( 'db' => 'gdn_status',    'dt' => 13 ),   

);

// Include SQL query processing class
require( 'ssp.class.php' );

if($useridsearch=="")
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and SalesOrderNumber!=''";
			}
			else
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and SalesOrderNumber!='' and created_by='$useridsearch'";
			}

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);