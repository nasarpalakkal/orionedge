<?php
// Database connection info
include("../../dbDetails.php");
include("../db/Address_db.php");
// DB table to use
$table = "sales_view";

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
                return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'cdate', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	
	    array( 
            'db' => 'customer_name', 
            'dt' =>3,
            'formatter' => function( $d, $row ) {				
                        return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$row[22]."-".$row['customer_name'].'</font></a>';
            }
       ),	
       array( 
        'db' => 'SalesType', 
        'dt' => 4,
        'formatter' => function( $d, $row ) {	
                            if($row['SalesType']==1)
                            {
                         return '<a href="Sales.php?id='.$row[1].'" ><font color="black">Retail</font></a>';	
                            }
                            else
                            {
                         return '<a href="Sales.php?id='.$row[1].'" ><font color="black">Whole</font></a>';	
                            }               
        }
   ),	   	 
   array( 
    'db' => 'CashType', 
    'dt' => 5,
    'formatter' => function( $d, $row ) {	
                        if($row['CashType']==1)
                        {
                     return '<a href="Sales.php?id='.$row[1].'" ><font color="black">Cash</font></a>';	
                        }
                        else
                        {
                     return '<a href="Sales.php?id='.$row[1].'" ><font color="black">Credit</font></a>';	
                        }               
    }
),	
array( 
    'db' => 'bill_no', 
    'dt' => 6,
    'formatter' => function( $d, $row ) {	    
        return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$row['PaymentDescr'].'</font></a>';
    }
),	
array( 
    'db' => 'PaymentDescr', 
    'dt' => 7,
    'formatter' => function( $d, $row ) {	    
        return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$row['bill_no'].'</font></a>';
    }
),	   	   
    array( 'db' => 'sub_total',    'dt' => 8 ),
	array( 'db' => 'net_discount', 'dt' => 9 ),
	array( 'db' => 'tax_amt',    'dt' => 10 ),	   
	array( 'db' => 'net_amt',    'dt' => 11 ),
    array( 'db' => 'sales_profit',    'dt' => 12 ),
	array( 
            'db' => 'payment_method', 
            'dt' => 13,
            'formatter' => function( $d, $row ) {	
                    if($row['payment_method']==1)			
                    {
                        return '<a href="Sales.php?id='.$row[1].'" ><font color="black">Cash</font></a>';
                    }
                    else if($row['payment_method']==2)	
                    {
                        return '<a href="Sales.php?id='.$row[1].'" ><font color="black">Card</font></a>';
                    }
                    else
                    {
                        return '<a href="Sales.php?id='.$row[1].'" ><font color="black"></font></a>';
                    }
                
            }
       ),	  
       array( 
        'db' => 'payment_status', 
        'dt' => 14,
        'formatter' => function( $d, $row ) {	
                if($row['payment_status']==1)			
                {
                    return '<a href="Sales.php?id='.$row[1].'" ><span class="label label-success">Paid</span></a>';
                }               
                else
                {
                    return '<a href="Sales.php?id='.$row[1].'" ><span class="label label-warning">Pending</span></a>';
                }
            
        }
   ),	
   array( 'db' => 'duedate',    'dt' => 15 ),
	array( 'db' => 'PurchaseNumber', 'dt' => 16 ),       	
       array( 
        'db' => 'displayname', 
        'dt' => 17,
        'formatter' => function( $d, $row ) {	
        $d=date( 'd-m-Y', strtotime($d));		
            return '<a href="Sales.php?id='.$row[1].'" ><font color="black">'.$row['displayname'].'</font></a>';
        }
   ),
	array( 
            'db' => 'PONumber', 
            'dt' => 18,
            'formatter' => function( $d, $row ) {				
                return '<a href="Sales.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),	
	array( 
            'db' => 'PONumber', 
            'dt' => 19,
            'formatter' => function( $d, $row ) {				
               // return '<a href="exportToExcel/sales_details_export.php?id='.$row[1].'&&ret=3" class="btn btn-success"><i class="fa fa-file-excel-o" style="height: 15px"></i></a>';
              
               if ($row['Ebill']==1) {
                return '<a href="sales_print_Tab.php?id='.$row[1].'" target="_blank" class="btn btn-primary"><i class="fa fa-print" style="height: 15px"></i> 80mm</a>
                <br>
                <br>
                <a href="sales_print_wv.php?id='.$row[1].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i> A4</a>';
               }
                            else{
                                return '<a href="sales_print_Tab.php?id='.$row[1].'" target="_blank" class="btn btn-primary"><i class="fa fa-print" style="height: 15px"></i> 80mm</a>
                                <br>
                                <br>
                                <a href="sales_print.php?id='.$row[1].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i> A4</a>';          
                            }    
            }
       ),	 
       array( 
        'db' => 'PONumber', 
        'dt' => 20,
        'formatter' => function( $d, $row ) {				
        
                            return '<a href="sales_gdn_save.php?id='.$row[1].'" target="_blank" class="btn btn-primary"><i class="fa fa-print" style="height: 15px"></i> GDN</a>';
                            
                
        }
   ),	     
	array( 
            'db' => 'PONumber', 
            'dt' => 21,
            'formatter' => function( $d, $row ) {
                            if($_GET['deletepermission']==0)
                            {
                                return '';			
                            }
                            else if($row['posting']==1)
                            {
                                //return '<span class="label label-success">Posted</span>';
                                return '<a href="Sales.php?id='.$row[1].'&&ret=1" class="btn btn-danger btnedit"><i class="fa fa-repeat" style="height: 15px"></i></a>';
                            }	
                            else
                            {				
               // return '<a href="SalesHistory.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
               return '<a href="Sales.php?id='.$row[1].'&&ret=1" class="btn btn-danger btnedit"><i class="fa fa-repeat" style="height: 15px"></i></a>';
                            }   
				
            }
        ),
        array( 'db' => 'customer_code',    'dt' => 22 ),       
        array( 'db' => 'CustomerBranchName',    'dt' => 23 ),      
        array( 'db' => 'CustomerBranchCode',    'dt' => 24 ),
        array( 'db' => 'posting',    'dt' => 25 ),           
        array( 'db' => 'Ebill',    'dt' => 26 ),           

);

// Include SQL query processing class
require( 'ssp.class.php' );

if($useridsearch=="")
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and status=1";
			}
			else
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and status=1 and created_by='$useridsearch'";
			}

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);