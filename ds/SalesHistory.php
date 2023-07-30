<?php	
session_start();
include("../conn.php");
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
if($admid=="")
{
include("logout.php");
}
require_once 'multilanguage.php';
$_SESSION['lang']=$RELanguage;
date_default_timezone_set('Asia/Riyadh');
	
	
include("db/workconfig.php");
include("db/Address_db.php");
$useraccess=workconfigDetail($RoleID,202);	 /////24 Role Add

$tb=$_REQUEST['tb'];

	if($_REQUEST['fd']!="" && $_REQUEST['ed']!="")
	{ 
	$_SESSION['FdateSession']=date('Y-m-d',strtotime($_REQUEST['fd']));
	$_SESSION['TdateSession']=date('Y-m-d',strtotime($_REQUEST['ed']));	
	$_SESSION['TUserSession']=$_REQUEST['Users'];
	$_SESSION['TCustomerIDSession']=$_REQUEST['CustomerID'];
$start_date=date('Y-m-d',strtotime($_REQUEST['fd']));
$end_date=date('Y-m-d',strtotime($_REQUEST['ed']));
$TUser=$_REQUEST['Users'];
$TCustomerID=$_REQUEST['CustomerID'];
$st=1;
	}
	else if($_SESSION['FdateSession']!="")
	{
$start_date=date('Y-m-d',strtotime($_SESSION['FdateSession']));
$end_date=date('Y-m-d',strtotime($_SESSION['TdateSession']));
$TUser=$_SESSION['TUserSession'];
$TCustomerID=$_SESSION['TCustomerIDSession'];
$st=1;
	}
	else
	{	
$start_date=date('Y-m-d');
$end_date=date('Y-m-d');
$_SESSION['FdateSession']="";
$_SESSION['TdateSession']="";
$_SESSION['TUserSession']="";
$_SESSION['TCustomerIDSession']="";
$TUser="";
$TCustomerID="";
$st=0;
	}								
$del=$_REQUEST['del'];		
	if($del==1)
	{
	$id_del=$_REQUEST['id'];

		$qryVoucher=mysqli_query($link,"SELECT * FROM voucher_list as A LEFT JOIN voucher as V on A.Voucher_no=V.Voucher_no where A.PONumber='$id_del' and V.type=1");
		$nos1=mysqli_num_rows($qryVoucher);

		$qryNote=mysqli_query($link,"SELECT * FROM credit_note_list as A LEFT JOIN credit_note as V on A.Voucher_no=V.PONumber where A.PONumber='$id_del'");
		$nos2=mysqli_num_rows($qryNote);
		if($nos1>0)
		{
		echo "<script>alert('Voucher Already Proccessed');</script>";		
		}
		else if($nos2>0)
		{
		echo "<script>alert('Credit Note Already Proccessed');</script>";		
		}
		else{
            $qry_initial3=mysqli_query($link, "select location from sales where PONumber='$id_del'");
            $obj_initial3=mysqli_fetch_array($qry_initial3);
            $whid=$obj_initial3['location'];
        
            $qry_initial4=mysqli_query($link, "select * from sales_list where PONumber='$id_del'");
            while ($obj_initial4=mysqli_fetch_array($qry_initial4)) {
                $ItemMasterID=$obj_initial4['item_no'];
                $sno1=$obj_initial4['unitsno'];
                $rqty=$obj_initial4['qty'];
                $qry_uom=mysqli_query($link, "select factor_val from inventory_uom where item_no='$ItemMasterID' and sno='$sno1'");
                $obj_uom=mysqli_fetch_array($qry_uom);
                if ($obj_uom[0]>0) {
                    $factor_val=$obj_uom[0];
                } else {
                    $factor_val=1;
                }
                $finalqty=$factor_val*$rqty;
                $qry_inv1=mysqli_query($link, "select qty from inventory_qty where item_no='$ItemMasterID' and warehouse_id='$whid'");
                $obj_inv1=mysqli_fetch_array($qry_inv1);
                $old_qty=$obj_inv1[0];
                $newqty=$old_qty+$finalqty;
                mysqli_query($link, "update inventory_qty set qty='$newqty' where item_no='$ItemMasterID' and warehouse_id='$whid'");
            }
            $qry_initial=mysqli_query($link, "select journal_id from sales where PONumber='$id_del'");
            $obj_initial=mysqli_fetch_array($qry_initial);
            $journal_id_del=$obj_initial['journal_id'];
            mysqli_query($link, "delete from ac_journal where trans_no='$journal_id_del'");
            mysqli_query($link, "delete from ac_journal_list where trans_no='$journal_id_del'");
            mysqli_query($link, "delete from sales where PONumber='$id_del'");
            mysqli_query($link, "delete from sales_list where PONumber='$id_del'");
            mysqli_query($link, "update sales_order set sales_invoice_no=NULL where sales_invoice_no='$id_del'");
        }
	}								
?>	
<!DOCTYPE html>
<html>
<head>
  <?php include('Topheader.php'); ?>
  
 
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<script language="javascript">
function frmSearch()
{
	if(document.getElementById("start_date").value=="")
	{
	alert('<?php echo gettext("Select Start Date"); ?>');
	document.getElementById("start_date").focus();
	return false;
	}
	if(document.getElementById("end_date").value=="")
	{
	alert('<?php echo gettext("Select End Date"); ?>');
	document.getElementById("end_date").focus();
	return false;
	}	
var fd=document.getElementById("start_date").value;
var ed=document.getElementById("end_date").value;
var Users=document.getElementById("Users").value;
var CustomerID=document.getElementById("CustomerID").value;
window.location='SalesHistory.php?fd='+fd+'&&ed='+ed+'&&Users='+Users+'&&CustomerID='+CustomerID;
}
 function frmSalesClosePrint()
 {
 var fd=document.getElementById("start_date").value;
 var ed=document.getElementById("end_date").value;
 var Users=document.getElementById("Users").value;
 var split1 = fd.split('-');
 var split2 = ed.split('-');

 	// if(Users=="")
 	// {
 	// alert('<?php echo gettext("Select User"); ?>');
 	// document.getElementById("Users").focus();
 	// return false;
 	// }
	if(split1[1]!=split2[1]) 
	{
	alert('<?php echo gettext("For Closing Please select monthly"); ?>'); 	
 	return false;
	}
  	else
  	{		  
  			var c=confirm("<?php echo gettext("Do you want to close the sales?"); ?>");
  			if(c==true)
  			{
  window.location="Sales_Close.php?fd="+fd+"&&ed="+ed+"&&Users="+Users;
  			}
  			else
  			{
  				return false;
  			}	
	}
 }
  </script>
  <script src="jsfiles/logout.js"></script>
</head>
<body class="fixed  hold-transition skin-purple sidebar-mini" onLoad="pageLoad()">
<div class="wrapper">

  <header class="main-header">
 <?php include('header.php'); ?>
    
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar direction">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">	
       <?php include('sidemenu.php'); ?>
	   <input type="hidden" name="menuid" id="menuid" value="SalesHistory.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo gettext("Sales History"); ?> 
      </h1>     
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row" <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
        
		
		
		<div class="col-xs-12">
          <div class="box">
            <div class="box-header">   			
            </div>
			
		<?php	
			if(isset($_REQUEST['Terror']))
			{
			echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"2\"><strong>". gettext("Server not connected")."</strong></font></div>";
			}
			if(isset($_REQUEST['msg']))
			{
			echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"2\"><strong>". gettext("Sales closed successfully")."</strong></font></div>";
			}
			if(isset($_REQUEST['TSucess']))
			{
			echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"2\"><strong>". gettext("Sales Detailes Transferred Successfully")."</strong></font></div>";
			}
			if(isset($_REQUEST['TError']))
			{
			echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"2\"><strong>". gettext("Sales Detailes is empty")."</strong></font></div>";
			}			
		?>
			
			 <!-- /.box-header -->
            <div class="box-body">
										<div class="row">
										<div class="col-md-3">
											   <label ><?php echo gettext("Users"); ?></label>
											   <select class="select2 form-control" name="Users" id="Users" >
																	<option value="" >-<?php echo gettext("Select Users"); ?>-</option>
																	<?php																		
																		if($RoleID!=4)
																			{
																				$qry=mysqli_query($link,"SELECT uid,displayname FROM user where uid='$admid'");
																			}
																			else
																			{
																				$qry=mysqli_query($link,"SELECT uid,displayname FROM user ");
																			}
																		while($r=mysqli_fetch_array($qry))
																		{
																			?>
																				<option value="<?php echo $r['uid']; ?>" <?php if($TUser===$r['uid']){ echo 'selected'; } ?> ><?php echo $r['displayname']; ?></option>
																			<?php }	
																			
																			?>
																</select>
											  </div>
											  <div class="col-md-3">
											   <label ><?php echo gettext("Customer"); ?></label>
											   <select class="select2 form-control" name="CustomerID" id="CustomerID" >
																	<option value="" >-<?php echo gettext("Select Customer"); ?>-</option>				
																	<?php 
																		$qry1=mysqli_query($link,"SELECT id,code,customer_name FROM customer_details where id='$TCustomerID' ");
																		while($r1=mysqli_fetch_array($qry1))
																		{
																		?>
																		<option value="<?php echo $r1['id']; ?>" <?php if($TCustomerID===$r1['id']){ echo 'selected'; } ?> ><?php echo $r1['code']."-".$r1['customer_name']; ?></option>
																	<?php }	?> 												
																</select>
											  </div>
										 	<div class="input-daterange">											  
											  <div class="col-md-3">
											   <label ><?php echo gettext("From Date"); ?></label>
											   <input type="text" name="start_date" id="start_date" class="form-control" autocomplete="off" value="<?php echo $start_date;?>"/>
											  </div>
											  <div class="col-md-3">
											   <label ><?php echo gettext("To Date"); ?></label>
											   <input type="text" name="end_date" id="end_date" class="form-control" autocomplete="off" value="<?php echo $end_date;?>"/>
											  </div> 											       
										 	</div>											
										 
								</div>
								
								
								
								 <br>
								 
								<div class="row">
											  <div class="col-md-6"></div>   
											  <div class="col-md-4"><input type="button" name="search" id="search" value="<?php echo gettext("Search"); ?>" class="btn btn-primary" onClick="return frmSearch()"/></div>
											  <div class="col-md-2">
											   <!-- <a  onClick="return frmSalesClosePrint()" class="btn btn-danger"><i class="fa fa-close"></i> <?php echo gettext("Sales Closing"); ?></a> -->
											  </div>      
										 
								</div>
								
			
			 <div class="box-body table-responsive">
              <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#example1-tab1" aria-controls="example1-tab1" role="tab" data-toggle="tab"><?php echo gettext("Sales"); ?></a></li>     
		<li role="presentation"><a href="#example1-tab2" aria-controls="example1-tab2" role="tab" data-toggle="tab"><?php echo gettext("Sales Return"); ?></a></li> 
		<li role="presentation"><a href="#example1-tab3" aria-controls="example1-tab3" role="tab" data-toggle="tab"><?php echo gettext("Holding"); ?></a></li>        
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="example1-tab1">
            <table id="example1-tab1-dt" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th><?php echo gettext("S Date"); ?></th>
				  <th><?php echo gettext("Invoice Number"); ?></th>
				  <th><?php echo gettext("Date"); ?></th>
				  <th><?php echo gettext("Customer"); ?></th>
				  <th><?php echo gettext("Vat Number"); ?></th>
				  <th><?php echo gettext("Sales Category"); ?></th>	
				  <th><?php echo gettext("Sales Type"); ?></th>	
				  <th><?php echo gettext("Payment Terms"); ?></th>	
				  <th><?php echo gettext("Reference Details"); ?></th>				 
				  <th><?php echo gettext("Sub Total"); ?></th>
				  <th><?php echo gettext("Discount"); ?></th>					 			  
                  <th><?php echo gettext("Tax"); ?></th>
				  <th><?php echo gettext("Gross Total"); ?></th>
				  <th><?php echo gettext("Profit"); ?></th>
				  <th><?php echo gettext("Payment Method"); ?></th>
				  <th><?php echo gettext("Payment Status"); ?></th>	
				  <th><?php echo gettext("Due Date"); ?></th>
				  <th><?php echo gettext("Purchase Number"); ?></th>	
				  <th><?php echo gettext("Created User"); ?></th>
				  <th><?php echo gettext("View"); ?></th>	
				  <th><?php echo gettext("Print"); ?></th>
				  <th><?php echo gettext("GDN"); ?></th>
				  <th><?php echo gettext("Return"); ?></th>
                </tr>
                </thead>                
				
				<tfoot>
            <tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
            <th></th>	
            </tr>
		
			
        </tfoot>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="example1-tab2">
		<table id="example1-tab2-dt" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th><?php echo gettext("S Date"); ?></th>
				  <th><?php echo gettext("Invoice Number"); ?></th>
				  <th><?php echo gettext("Date"); ?></th>
				  <th><?php echo gettext("Customer"); ?></th>	
				  <th><?php echo gettext("Sales Category"); ?></th>	
				  <th><?php echo gettext("Sales Type"); ?></th>	
				  <th><?php echo gettext("Payment Terms"); ?></th>	
				  <th><?php echo gettext("Reference Details"); ?></th>				 
				  <th><?php echo gettext("Sub Total"); ?></th>
				  <th><?php echo gettext("Discount"); ?></th>					 			  
                  <th><?php echo gettext("Tax"); ?></th>
				  <th><?php echo gettext("Gross Total"); ?></th>
				  <th><?php echo gettext("Payment Method"); ?></th>
				  <th><?php echo gettext("Payment Status"); ?></th>				  
				  <th><?php echo gettext("Created User"); ?></th>
				  <th><?php echo gettext("View"); ?></th>	
				   <th><?php echo gettext("Print"); ?></th>		  				 
				  <th><?php echo gettext("Remove"); ?></th>
                </tr>
                </thead>                
				
				<tfoot>
            <tr>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
            
            </tr>
		
			
        </tfoot>
            </table>
        </div>
		
		
		<div role="tabpanel" class="tab-pane fade" id="example1-tab3">
		<table id="example1-tab3-dt" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th><?php echo gettext("S Date"); ?></th>
				  <th><?php echo gettext("Invoice Number"); ?></th>
				  <th><?php echo gettext("Date"); ?></th>
				  <th><?php echo gettext("Customer"); ?></th>	
				  <th><?php echo gettext("Sales Category"); ?></th>	
				  <th><?php echo gettext("Sales Type"); ?></th>	
				  <th><?php echo gettext("Reference Details"); ?></th>			
				  <th><?php echo gettext("Payment Terms"); ?></th>	
				  <th><?php echo gettext("Sub Total"); ?></th>
				  <th><?php echo gettext("Discount"); ?></th>					 			  
                  <th><?php echo gettext("Tax"); ?></th>
				  <th><?php echo gettext("Gross Total"); ?></th>
				  <!-- <th><?php echo gettext("Payment Method"); ?></th>
				  <th><?php echo gettext("Payment Status"); ?></th>				   -->
				  <th><?php echo gettext("Created User"); ?></th>
				  <th><?php echo gettext("View"); ?></th>	
				   <th><?php echo gettext("Print"); ?></th>		  				 
				  <th><?php echo gettext("Remove"); ?></th>
                </tr>
                </thead>                
				
				<tfoot>
            <tr>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th></th>
			<th></th>	
			<th></th>	
			<th></th>	
			<th></th>	
			<!-- <th></th>	
			<th></th>	 -->
            
            </tr>
		
			
        </tfoot>
			</table>
        </div>
		
		</div>
			  </div>
			  
			
           
          </div>
          <!-- /.box -->

        
        </div>
        <!-- /.col -->  
     
	 
	  </div>
      <!-- /.row -->    
	 <input type="hidden" name="TabID0" id="TabID0" value="<?php echo $tb; ?>">

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <?php include('footer.php'); ?>
  </footer>

 
</div>
<!-- ./wrapper -->

<?php include('Topbottom.php'); ?>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.buttons.min.js"></script>
<script src="plugins/datatables/buttons.flash.min.js"></script>
<script src="plugins/datatables/jszip.min.js"></script>
<script src="plugins/datatables/pdfmake.min.js"></script>
<script src="plugins/datatables/vfs_fonts.js"></script>
<script src="plugins/datatables/buttons.html5.min.js"></script>
<script src="plugins/datatables/buttons.print.min.js"></script>
<script src="plugins/datatables/buttons.colVis.min.js"></script>
<script src="plugins/datatables/dataTables.colVis.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

<script language="javascript">
function formatMoney(number, decPlaces, decSep, thouSep) {
decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
decSep = typeof decSep === "undefined" ? "." : decSep;
thouSep = typeof thouSep === "undefined" ? "," : thouSep;
var sign = number < 0 ? "-" : "";
var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
var j = (j = i.length) > 3 ? j % 3 : 0;

return sign +
	(j ? i.substr(0, j) + thouSep : "") +
	i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
	(decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}
</script>
    <!-- page script -->
   <script>
  $(function () {
   $(".select2").select2();
   
  $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 }); 
   
			 $('#example1-tab1-dt').DataTable({	
				"order": [[0,"desc"]],
				"columnDefs": [
            {
                "targets": <?php if($useraccess['delete_txt']>0) {?> [ 0 ] <?php } else {?>  [ 0,11 ]  <?php } ?>,
                "visible": false,
                "searchable": false
            }],
			
			"dom":
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			
			"buttons":['Export To Excel'],	
			"buttons":[{
						extend: 'excelHtml5',
						footer: true,
						text: '<?php echo gettext("Export Sales Summary"); ?>',
						className: 'btn-success',
						color:'red',
						title:"<?php echo gettext("Sales Summary"); ?>",
						exportOptions: {
							columns: ':visible',
							columns: [  1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17],
							
						}
						
					},
					,
					
					//'colvis'      
			
            
       			 ],	
			
			
		 "processing" : true,
		"serverSide": true,
		"ajax": {                    
                    "url": 'scripts/sales_history.php',
            "data": {                       
                formStartDate:'<?php echo $start_date; ?>',
                formEndDate: '<?php echo $end_date; ?>',
				useridsearch: '<?php echo $TUser; ?>',
				userid:'<?php echo $admid;  ?>',
				roleid:'<?php echo $RoleID; ?>',
				customeridsearch: '<?php echo $TCustomerID; ?>',
				deletepermission:'<?php echo $useraccess['delete_txt']; ?>',
                }              
            },  
				"iDisplayLength": 10,		
				"bStateSave": true,
				"oLanguage": {
				"oPaginate": {
				"sPrevious": "<?php echo gettext("Previous"); ?>", // This is the link to the previous page
				"sNext": "<?php echo gettext("Next"); ?>", // This is the link to the next page
				},
				"sSearch": "<?php echo gettext("Search :"); ?>",
				"sInfoEmpty": "<?php echo gettext("Showing") ?> 0 <?php echo gettext("to") ?> 0 <?php echo gettext("of") ?> 0 <?php echo gettext("entries") ?>",
				"sInfo": "<?php echo gettext("Showing") ?> _START_ <?php echo gettext("to") ?> _END_ <?php echo gettext("of") ?> _TOTAL_ <?php echo gettext("entries") ?>",
				"sEmptyTable": "<?php echo gettext("No data available in table") ?>",
				"sLengthMenu": '<?php echo gettext("Show") ?> <select>'+
						'<option value="10">10</option>'+
						'<option value="20">20</option>'+
						'<option value="30">30</option>'+
						'<option value="50">50</option>'+
						'<option value="100">100</option>'+
					   '<option value="-1"><?php echo gettext("All") ?></option>'+
						'</select> <?php echo gettext("entries") ?>'
				},
				"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data; 			
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            totalpaid = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(9).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );
				
			totaldiscount = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(10).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
			totalvat = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(11).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
			totalfinal = api
                .column( 12 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(12).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );							
			totalprofit = api
                .column( 13 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(13).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
            // Total over this page
            
 
             var nCells = row.getElementsByTagName('th');
			
    		//nCells[4].innerHTML = formatMoney(totalpaid)+' - '+ formatMoney(totalreturn);			
			nCells[7].innerHTML ='Total :';
			nCells[8].innerHTML =formatMoney(totalpaid);
			nCells[9].innerHTML =formatMoney(totaldiscount);
			nCells[10].innerHTML =formatMoney(totalvat);
			nCells[11].innerHTML =formatMoney(totalfinal);
			nCells[12].innerHTML =formatMoney(totalprofit);

        }

				 
				});

				$('#example1-tab2-dt').DataTable({	
				"order": [[0,"desc"]],
				"columnDefs": [
            {
                "targets": <?php if($useraccess['delete_txt']>0) {?> [ 0 ] <?php } else {?>  [ 0,11 ]  <?php } ?>,
                "visible": false,
                "searchable": false
            }],
			
			"dom":
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			
			"buttons":['Export To Excel'],	
			"buttons":[{
						extend: 'excelHtml5',
						footer: true,
						text: '<?php echo gettext("Export Sales Return Summary"); ?>',
						className: 'btn-success',
						color:'red',
						title:"<?php echo gettext("Sales Return Summary"); ?>",
						exportOptions: {
							columns: ':visible',
							columns: [  1, 2, 3,4,5,6,7,8,9,10,11,12,13,14],
							
						}
						
					},
					,
					
					//'colvis'      
			
            
       			 ],	
			
			
		 "processing" : true,
		"serverSide": true,
		"ajax": {                    
                    "url": 'scripts/salesReturn_history.php',
            "data": {                       
                formStartDate:'<?php echo $start_date; ?>',
                formEndDate: '<?php echo $end_date; ?>',
				useridsearch: '<?php echo $TUser; ?>',
					userid:'<?php echo $admid;  ?>',
					roleid:'<?php echo $RoleID; ?>',
				customeridsearch: '<?php echo $TCustomerID; ?>',
				deletepermission:'<?php echo $useraccess['delete_txt']; ?>',
                }              
            },  
				"iDisplayLength": 10,		
				"bStateSave": true,
				"oLanguage": {
				"oPaginate": {
				"sPrevious": "<?php echo gettext("Previous"); ?>", // This is the link to the previous page
				"sNext": "<?php echo gettext("Next"); ?>", // This is the link to the next page
				},
				"sSearch": "<?php echo gettext("Search :"); ?>",
				"sInfoEmpty": "<?php echo gettext("Showing") ?> 0 <?php echo gettext("to") ?> 0 <?php echo gettext("of") ?> 0 <?php echo gettext("entries") ?>",
				"sInfo": "<?php echo gettext("Showing") ?> _START_ <?php echo gettext("to") ?> _END_ <?php echo gettext("of") ?> _TOTAL_ <?php echo gettext("entries") ?>",
				"sEmptyTable": "<?php echo gettext("No data available in table") ?>",
				"sLengthMenu": '<?php echo gettext("Show") ?> <select>'+
						'<option value="10">10</option>'+
						'<option value="20">20</option>'+
						'<option value="30">30</option>'+
						'<option value="50">50</option>'+
						'<option value="100">100</option>'+
					   '<option value="-1"><?php echo gettext("All") ?></option>'+
						'</select> <?php echo gettext("entries") ?>'
				},
				"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data; 			
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            totalpaid = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(8).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );
				
			totaldiscount = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(9).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
			totalvat = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(10).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
			totalfinal = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(11).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );							
			
            // Total over this page
            
 
             var nCells = row.getElementsByTagName('th');
			
    		//nCells[4].innerHTML = formatMoney(totalpaid)+' - '+ formatMoney(totalreturn);			
			nCells[6].innerHTML ='Total :';
			nCells[7].innerHTML =formatMoney(totalpaid);
			nCells[8].innerHTML =formatMoney(totaldiscount);
			nCells[9].innerHTML =formatMoney(totalvat);
			nCells[10].innerHTML =formatMoney(totalfinal);

        }

				 
				});		
				
				
				$('#example1-tab3-dt').DataTable({	
				"order": [[0,"desc"]],
				"columnDefs": [
            {
                "targets": <?php if($useraccess['delete_txt']>0) {?> [ 0 ] <?php } else {?>  [ 0,11 ]  <?php } ?>,
                "visible": false,
                "searchable": false
            }],
			
			"dom":
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			
			"buttons":['Export To Excel'],	
			"buttons":[{
						extend: 'excelHtml5',
						footer: true,
						text: '<?php echo gettext("Export Sales Holding Invoice Summary"); ?>',
						className: 'btn-success',
						color:'red',
						title:"<?php echo gettext("Sales Holding Invoice Summary"); ?>",
						exportOptions: {
							columns: ':visible',
							columns: [  1, 2, 3,4,5,6,7,8,9,10,11,12],
							
						}
						
					},
					,
					
					//'colvis'      
			
            
       			 ],	
			
			
		 "processing" : true,
		"serverSide": true,
		"ajax": {                    
                    "url": 'scripts/sales_holding_history.php',
            "data": {                       
                formStartDate:'<?php echo $start_date; ?>',
                formEndDate: '<?php echo $end_date; ?>',
				useridsearch: '<?php echo $TUser; ?>',
					userid:'<?php echo $admid;  ?>',
					roleid:'<?php echo $RoleID; ?>',
				customeridsearch: '<?php echo $TCustomerID; ?>',
				deletepermission:'<?php echo $useraccess['delete_txt']; ?>',
                }              
            },  
				"iDisplayLength": 10,		
				"bStateSave": true,
				"oLanguage": {
				"oPaginate": {
				"sPrevious": "<?php echo gettext("Previous"); ?>", // This is the link to the previous page
				"sNext": "<?php echo gettext("Next"); ?>", // This is the link to the next page
				},
				"sSearch": "<?php echo gettext("Search :"); ?>",
				"sInfoEmpty": "<?php echo gettext("Showing") ?> 0 <?php echo gettext("to") ?> 0 <?php echo gettext("of") ?> 0 <?php echo gettext("entries") ?>",
				"sInfo": "<?php echo gettext("Showing") ?> _START_ <?php echo gettext("to") ?> _END_ <?php echo gettext("of") ?> _TOTAL_ <?php echo gettext("entries") ?>",
				"sEmptyTable": "<?php echo gettext("No data available in table") ?>",
				"sLengthMenu": '<?php echo gettext("Show") ?> <select>'+
						'<option value="10">10</option>'+
						'<option value="20">20</option>'+
						'<option value="30">30</option>'+
						'<option value="50">50</option>'+
						'<option value="100">100</option>'+
					   '<option value="-1"><?php echo gettext("All") ?></option>'+
						'</select> <?php echo gettext("entries") ?>'
				},
				"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data; 			
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            totalpaid = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(8).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );
				
			totaldiscount = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(9).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
			totalvat = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(10).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );	
			totalfinal = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
				var cur_index = api.column(11).data().indexOf(b);
				    return intVal(a) + intVal(b);
                }, 0 );							
			
            // Total over this page
            
 
             var nCells = row.getElementsByTagName('th');
			
    		//nCells[4].innerHTML = formatMoney(totalpaid)+' - '+ formatMoney(totalreturn);			
			nCells[6].innerHTML ='Total :';
			nCells[7].innerHTML =formatMoney(totalpaid);
			nCells[8].innerHTML =formatMoney(totaldiscount);
			nCells[9].innerHTML =formatMoney(totalvat);
			nCells[10].innerHTML =formatMoney(totalfinal);

        }

				 
				});
				 
				 
				
		
   }); 
</script>
 <script src="jsfiles/bell_notification.js"></script> 
<script>
$(document).ready(function(){
	$("#CustomerID").select2({
      ajax: {
        url: "GetCustomerAjax.php",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
           return {
              searchTerm: params.term // search term
           };
        },
        processResults: function (response) {
           return {
              results: response
           };
        },
        cache: true
      }
   });

bell_notification();
step_admin_logout();

			if(document.getElementById("TabID0").value==2)
			{
		activaTab('example1-tab2');
			}
			else if(document.getElementById("TabID0").value==1)
			{
		activaTab('example1-tab1');
			}
			else if(document.getElementById("TabID0").value=="")
			{
		activaTab('example1-tab1');	
			}
	
});



    $('#example').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                '$'+pageTotal +' ( $'+ total +' total)'
            );
        }
    } );

function activaTab(tab){
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};
</script>
</body>
</html>
