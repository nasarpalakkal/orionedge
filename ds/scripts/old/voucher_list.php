<?php
// Database connection info
include("../../dbDetails.php");
// DB table to use
$table = "voucher_v";

// Table's primary key
$primaryKey = 'Voucher_no';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
$type=$_GET['type'];
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 
            'db' => 'cdatetime', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'" ><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'Voucher_no', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {			
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['Voucher_no'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'account_code', 
            'dt' => 2,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['account_code'].'</font></a>';
            }
       ),	
	    array( 
            'db' => 'account_name', 
            'dt' => 3,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['account_name'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'account_name_ar', 
            'dt' => 4,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['account_name_ar'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'payment_date', 
            'dt' => 5,
            'formatter' => function( $d, $row ) {	
			$d=date( 'd-m-Y', strtotime($d));		
                return '<a href="Sales.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$d.'</font></a>';
            }
       ),	
	   				
	array(
        'db'        => 'PaymentType',
        'dt'        => 6,
        'formatter' => function( $d, $row ) {
            	
				
				if($d==1){ return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">Cash</font></a>'; } else if($d==2){ return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">Cheque</font></a>';  } else if($d==3) { return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">Transfer</font></a>';  }
        }
    ),
	array( 
            'db' => 'ReferenceNumber', 
            'dt' => 7,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['ReferenceNumber'].'</font></a>';
            }
       ),
	   array( 
            'db' => 'BankCODE', 
            'dt' => 8,
            'formatter' => function( $d, $row ) {							
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['BankCODE'].'</font></a>';
            }
       ),	
	   array( 
            'db' => 'BankDescr', 
            'dt' => 9,
            'formatter' => function( $d, $row ) {							
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.$row['BankDescr'].'</font></a>';
            }
       ),	
	   array( 
            'db' => 'paying_amt', 
            'dt' => 10,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" ><font color="black">'.number_format($row['paying_amt'],2,'.','').'</font></a>';
            }
       ),	   
     
	array( 
            'db' => 'Voucher_no', 
            'dt' => 11,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
            }
       ),	
	array( 
            'db' => 'Voucher_no', 
            'dt' => 12,
            'formatter' => function( $d, $row ) {				
                return '<a href="Voucher_Entry.php?id='.$row[1].'&&type='.$row[14].'" class="btn btn-success btnedit"><i class="fa  fa-print" style="height: 15px"></i></a>';
            }
       ),	   
	array( 
            'db' => 'Voucher_no', 
            'dt' => 13,
            'formatter' => function( $d, $row ) {	
					if($row[14]==1){
								
                return '<a href="ReceiptVoucher.php?id='.$row[1].'&&type='.$row[14].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
								}
								else if($row[14]==2)
								{
				return '<a href="PaymentVoucher.php?id='.$row[1].'&&type='.$row[14].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';					
								}
								else if($row[14]==3)
								{
				return '<a href="ExpensesVoucher.php?id='.$row[1].'&&type='.$row[14].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';					
								}
				
            }
       ),	   
	array( 
            'db' => 'type', 
            'dt' => 14,
            'formatter' => function( $d, $row ) {				
                return $row['type'];			
				
            }
       ) 	
	    	
	     

);

// Include SQL query processing class
require( 'ssp.class.php' );

$wherecondition = "payment_date between '$formStartDate' and '$formEndDate' and type='$type'";

// Output data as json format
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);