<?php
include("../../dbDetails.php");
$table = "credit_note_v";

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
                return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['cdatetime'].'</font></a>';
            }
       ),
	    array( 
            'db' => 'PONumber', 
            'dt' => 1,
            'formatter' => function( $d, $row ) {				
                return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['PONumber'].'</font></a>';
            }
       ),
       array( 
        'db' => 'cdate', 
        'dt' => 2,
        'formatter' => function( $d, $row ) {				
            return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['cdate'].'</font></a>';
        }
     ),
     array( 
        'db' => 'refernce_details', 
        'dt' => 3,
        'formatter' => function( $d, $row ) {	
            return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['refernce_details'].'</font></a>';
        }
   ),	
    array( 
        'db' => 'CustomerNameDisplay', 
        'dt' =>4,
        'formatter' => function( $d, $row ) {			
            return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['CustomerNameDisplay'].'</font></a>';
        }
   ),	
   array( 
    'db' => 'MainMemo', 
    'dt' =>5,
    'formatter' => function( $d, $row ) {			
        return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['MainMemo'].'</font></a>';
    }
),	
array( 
    'db' => 'Type', 
    'dt' =>6,
    'formatter' => function( $d, $row ) {		
            if($row['Type']==1)
            {
                return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">Discount</font></a>';
            }
            else if($row['Type']==2)
            {
                return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">Discard</font></a>';
            }
            else if($row['Type']==3)
            {
                return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">Sales Return</font></a>';
            }        
    }
),	
array( 
    'db' => 'account_name', 
    'dt' =>7,
    'formatter' => function( $d, $row ) {			
        return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row[13]."-".$row['account_name'].'</font></a>';
    }
),	
array( 
    'db' => 'net_amt', 
    'dt' =>8,
    'formatter' => function( $d, $row ) {		
        $d=number_format($row['net_amt'],2,'.','');	
        return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$d.'</font></a>';
    }
),	
   array( 
    'db' => 'displayname', 
    'dt' =>9,
    'formatter' => function( $d, $row ) {			
        return '<a href="CreditNoteAdd.php?id='.$row[1].'"><font color="black">'.$row['displayname'].'</font></a>';
    }
),    
array( 
'db' => 'PONumber', 
'dt' => 10,
'formatter' => function( $d, $row ) {				    
    return '<a href="CreditNoteAdd.php?id='.$row[1].'" class="btn btn-primary btnedit"><i class="fa fa-pencil-square-o" style="height: 15px"></i></a>';
}
),  	 
array( 
'db' => 'PONumber', 
'dt' => 11,
'formatter' => function( $d, $row ) {				    
    return '<a href="CreditNote_print.php?id='.$row[1].'" target="_blank" class="btn btn-success"><i class="fa fa-print" style="height: 15px"></i></a>';
}
),
array( 
'db' => 'PONumber', 
'dt' => 12,
'formatter' => function( $d, $row ) {		
    if($_GET['deletepermission']==0 || $row[14]==1)
    {
                            if($row[14]==1)
									{					
                                        return '<span class="label label-success">Posted</span>';			
									}
									else
									{
                                        return '<span class="label label-danger">No Permission</span>';	
                                    }
    }	
    else{			
    return '<a href="CreditNote.php?id='.$row[1].'&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove" style="height: 15px"></i></a>';	
    }
}
), 
array( 'db' => 'account_code',    'dt' => 13 ),	 
array( 'db' => 'posting',    'dt' => 14 ),	   	   

);
require( 'ssp.class.php' );
if($useridsearch=="")
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate'";
			}
			else
			{
$wherecondition = "cdate between '$formStartDate' and '$formEndDate' ";
			}
echo json_encode(
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $wherecondition  )
);