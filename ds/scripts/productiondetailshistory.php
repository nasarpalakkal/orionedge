<?php
include("../../dbDetails.php");
$table = "production_details_v";

// Table's primary key
$primaryKey = 'PONumber';

$formStartDate=$_GET['formStartDate'];
$formEndDate=$_GET['formEndDate'];
$useridsearch="";
$columns = array(
      array( 
            'db' => 'cdatetime', 
            'dt' => 0,
            'formatter' => function( $d, $row ) {				
                return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
       array( 
        'db' => 'cdate', 
        'dt' => 2,
        'formatter' => function( $d, $row ) {				
            return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['cdate'].'</font></a>';
        }
     ),
     array( 
        'db' => 'item_no', 
        'dt' => 3,
        'formatter' => function( $d, $row ) {	
            return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['item_no']."-".$row['item_descr']."-".$row['item_descr_ar'].'</font></a>';
        }
   ),	
   array( 
    'db' => 'FromWarehouse', 
    'dt' => 4,
    'formatter' => function( $d, $row ) {	
        return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['FromWarehouse']."(".$row['FrommainWarehouse'].")".'</font></a>';
    }
),	
array( 
    'db' => 'Towarehouse', 
    'dt' => 5,
    'formatter' => function( $d, $row ) {	
        return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['Towarehouse']."(".$row['ToMainWarehouse'].")".'</font></a>';
    }
),	
array( 
    'db' => 'ExpectedProducts', 
    'dt' => 6,
    'formatter' => function( $d, $row ) {	
        return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['ExpectedProducts'].'</font></a>';
    }
),	
array( 
    'db' => 'status', 
    'dt' => 7,
    'formatter' => function( $d, $row ) {	
            if($row['status']==1)
            {
                return '<a href="ProductionDetails.php?id='.$row[1].'"><span class="label label-success">Item Transferred</span></a>';
            }
            else if($row['status']==2)
            {
                return '<a href="ProductionDetails.php?id='.$row[1].'"><span class="label label-warning">Work In Progress</span></a>';
            }
            else if($row['status']==3)
            {
                return '<a href="ProductionDetails.php?id='.$row[1].'"><span class="label label-success">Finished</span></a>';
            }
            else
            {
                return '';
            }
    }
),	
array( 
    'db' => 'notes', 
    'dt' => 8,
    'formatter' => function( $d, $row ) {	
        return '<a href="ProductionDetails.php?id='.$row[1].'"><font color="black">'.$row['notes'].'</font></a>';
    }
),	

array( 
    'db' => 'notes', 
    'dt' => 9,
    'formatter' => function( $d, $row ) {	
        return '<a href="ProductionDetails.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';      
    }
),	
array( 
    'db' => 'notes', 
    'dt' => 10,
    'formatter' => function( $d, $row ) {	
        return '<a href="ProductionDetails_print.php?id='.$row[1].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i></a>';
    }
),	

array( 
    'db' => 'notes', 
    'dt' => 11,
    'formatter' => function( $d, $row ) {	
                                if($_GET['deletepermission']==0)
                                {
                                    return '-';			
                                }
                                else if($row[7]>1)
                                {
                                    return '<span class="label label-danger">Status Changed</span>';			
                                }	
                                else
                                {	
                                return '<a href="ProductionDetailsHistory.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';			
                                }
    }
),	
    
array( 'db' => 'item_descr',    'dt' => 12 ),	 
array( 'db' => 'item_descr_ar',    'dt' => 13 ),
array( 'db' => 'FrommainWarehouse',    'dt' => 14 ),	 
array( 'db' => 'ToMainWarehouse',    'dt' => 15 ),	   	   

);
require( 'ssp.class.php' );
if($useridsearch=="")
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and status!=3";
			}
			else
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' and status!=3";
			}
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);