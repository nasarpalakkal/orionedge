<?php
session_start();
include("../conn.php");
//mysqli_set_charset($link,"utf8");
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
include("db/workconfig.php");
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
if($admid=="")
{
include("logout.php");
}
require_once 'multilanguage.php';
$_SESSION['lang']=$RELanguage;
$useraccess=workconfigDetail($RoleID,702);
$del=$_REQUEST['del'];
	if($del==1)
	{
	$id=$_REQUEST['id'];
	mysqli_query($link,"delete from ac_journal where trans_no='$id'");
	mysqli_query($link,"delete from ac_journal_list where trans_no='$id'");
	}
include("db/Address_db.php");	

if($_REQUEST['fd']!="" && $_REQUEST['ed']!="")
	{ 
	$_SESSION['FdateSession']=date('Y-m-d',strtotime($_REQUEST['fd']));
	$_SESSION['TdateSession']=date('Y-m-d',strtotime($_REQUEST['ed']));	
$start_date=date('Y-m-d',strtotime($_REQUEST['fd']));
$end_date=date('Y-m-d',strtotime($_REQUEST['ed']));
	}
	else if($_SESSION['FdateSession']!="")
	{
$start_date=date('Y-m-d',strtotime($_SESSION['FdateSession']));
$end_date=date('Y-m-d',strtotime($_SESSION['TdateSession']));
	}
	else
	{	
$start_date=date('Y-m-d');
$end_date=date('Y-m-d');
$_SESSION['FdateSession']="";
$_SESSION['TdateSession']="";
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
window.location='gl_journal_list.php?fd='+fd+'&&ed='+ed;
}
function frmPrint(a)
{
var fd=document.getElementById("start_date").value;
var ed=document.getElementById("end_date").value;
window.open('journal_print?StartDate='+fd+'&&EndDate='+ed+'&&type='+a);	
}
  </script>

 <script src="jsfiles/logout.js"></script>
</head>
<body class="fixed  sidebar-open hold-transition skin-purple sidebar-mini" onLoad="pageLoad()">
<div class="wrapper">

  <header class="main-header">
     <?php include('header.php'); ?>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar direction">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <?php include('sidemenu.php'); ?>
	   <input type="hidden" name="menuid" id="menuid" value="gl_journal_list.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
         <?php echo gettext("Journal Inquiry"); ?>
      </h1>    
    </section>

    <!-- Main content -->
    <section class="content" <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
    <div class="row">
        <div class="col-xs-12">
          
		  <div class="box">            
            <!-- /.box-header -->
			 
            <div class="box-body">
			<?php /*?><button type="button" class="btn btn-primary " id="btnadd" name="btnadd" onClick="frmaddnew();"><i class="fa fa-plus"></i> <?php echo gettext("Add New Payment"); ?></button><?php */?>
			<br><br><br>
										<div class="row">
										 	<div class="input-daterange">
											  <div class="col-md-6">
											   <label ><?php echo gettext("From Date"); ?></label>
											   <input type="text" name="start_date" id="start_date" class="form-control" autocomplete="off" value="<?php echo $start_date;?>"/>
											  </div>
											  <div class="col-md-6">
											   <label ><?php echo gettext("To Date"); ?></label>
											   <input type="text" name="end_date" id="end_date" class="form-control" autocomplete="off" value="<?php echo $end_date;?>"/>
											  </div> 											       
										 	</div>												
										 
								</div>
								
								
								
								
								
								 <br>
								 
								<div class="row">
												<div class="col-md-3">
											   
											   </div>   
											   <div class="col-md-3">
											   <input type="button" name="search" id="search" value="<?php echo gettext("Search"); ?>" class="btn btn-info" onClick="return frmSearch()"/>
											   </div> 
											   
											  <div class="col-md-3">											  
											  <a onclick="frmPrint(0)" target="_blank" class="btn btn-success btnedit"><i class="fa fa-print"> Print Unposted Journals</i></a>
											  </div>   
											  <div class="col-md-3">
											  <a onclick="frmPrint(1)" target="_blank" class="btn btn-success btnedit"><i class="fa fa-print"> Print Posted Journals</i></a>
											  </div>      
										 
								</div>
								
			 <div class="box-body"  >
              <!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation"><a href="#example1-tab1" aria-controls="example1-tab1" role="tab" data-toggle="tab"><?php echo gettext("Unposted Manual Journal"); ?></a></li>
				<li role="presentation"><a href="#example1-tab2" aria-controls="example1-tab2" role="tab" data-toggle="tab"><?php echo gettext("Unposted Journal"); ?></a></li>
				<li role="presentation"><a href="#example1-tab3" aria-controls="example1-tab3" role="tab" data-toggle="tab"><?php echo gettext("Posted Journal"); ?></a></li>
			</ul>

    <!-- Tab panes -->
    <div class="tab-content">
	<div role="tabpanel" class="tab-pane fade in active" id="example1-tab1">
             <table id="example1-tab3-dt" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                    <tr>
                           <th><?php echo gettext("S Date"); ?></th>
						  <th><?php echo gettext("Transcation Date"); ?></th>
                  		  <th><?php echo gettext("Type"); ?></th>				 
						  <th><?php echo gettext("Trans #"); ?></th> 
						  <th><?php echo gettext("Reference"); ?></th> 
						  <th><?php echo gettext("Amount"); ?></th>
						  <th><?php echo gettext("Memo"); ?></th>
						  <th><?php echo gettext("Status"); ?></th>
						  <th><?php echo gettext("Created By"); ?></th>						 
						  <?php if($useraccess['edit_txt']>0){ ?><th><?php echo gettext("view"); ?></th><?php } ?>
						  <?php if($useraccess['edit_txt']>0){ ?><th><?php echo gettext("Print"); ?></th><?php } ?>
						 <?php if($useraccess['delete_txt']>0){?> <th><?php echo gettext("Delete"); ?></th><?php } ?>
                    </tr>
                </thead>
                <tbody>
							<?php								
							$qry=mysqli_query($link,"select * from ac_journal where posting=0 and entry_type=1 and tran_date between '$start_date' and '$end_date' order by tran_date");
							while($obj=mysqli_fetch_array($qry))
							{
							$sDate=strtotime($obj['cdatetime']);	
							$tran_date=date('d-m-Y',strtotime($obj['tran_date']));
							if($obj['posting_date']==''){$posting_date='';} else{$posting_date=date('d-m-Y',strtotime($obj['posting_date']));}
							$type=$obj['type']; if($type==0) { $type_descr='Journal Entry'; } else {$type_descr=''; }
							$trans_no=$obj['trans_no'];
							$reference=$obj['reference'];
							$amount=$obj['amount'];
							$memo=$obj['memo'];							
							$uid=UserDetails($obj['uid'])[0];
							$uid1=UserDetails($obj['posting_by'])[0];
							$PONumber=$obj['PONumber'];				
							if($amount=="0"){ $colorstatus="#ffcccc";}else{$colorstatus="";}
							$posting=$obj['posting'];
						?>	
						<tr style="background-color:<?php echo $colorstatus; ?>">
						  <td class="center"><?php echo $sDate; ?></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $tran_date; ?></font></a></td>						 
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $type_descr; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $PONumber; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $reference; ?></font></a></td>
						  
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo number_format($amount,2,'.',','); ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $memo; ?></font></a></td>
						  
						  <td><?php if($posting==0){ ?><label class="alert-warning"><?php echo gettext("Created"); ?></label><?php } else { ?> <label class="alert-success"><?php echo gettext("Posted"); ?></label> <?php } ?></td>
						  
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $obj['uid']."-".$uid; ?></font></a></td>						  
						 
						  <?php if($useraccess['edit_txt']>0){ ?><td><a href="gl_journal.php?id=<?php echo $trans_no; ?>" class="btn btn-primary btnedit"><i class="fa fa-edit"></i></a></td><?php } ?>
						  <?php if($useraccess['edit_txt']>0){ ?><td><a href="gl_print.php?id=<?php echo $trans_no; ?>" target="_blank" class="btn btn-success btnedit"><i class="fa fa-print"></i></a></td><?php } ?>
						 <?php if($useraccess['delete_txt']>0){?>  <td><?php if($posting==0){?><a href="gl_journal_list.php?id=<?php echo $trans_no; ?>&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove"></i></a><?php } ?></td><?php } ?>
						</tr>
					  <?php
							}
						?>
				
                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade in" id="example1-tab2">
             <table id="example1-tab1-dt" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                    <tr>
                           <th><?php echo gettext("S Date"); ?></th>
						  <th><?php echo gettext("Transcation Date"); ?></th>
                  		  <th><?php echo gettext("Type"); ?></th>				 
						  <th><?php echo gettext("Trans #"); ?></th> 
						  <th><?php echo gettext("Reference"); ?></th> 
						  <th><?php echo gettext("Amount"); ?></th>
						  <th><?php echo gettext("Memo"); ?></th>
						  <th><?php echo gettext("Status"); ?></th>
						  <th><?php echo gettext("Created By"); ?></th>						 
						  <?php if($useraccess['edit_txt']>0){ ?><th><?php echo gettext("view"); ?></th><?php } ?>
						  <?php if($useraccess['edit_txt']>0){ ?><th><?php echo gettext("Print"); ?></th><?php } ?>
						 <?php if($useraccess['delete_txt']>0){?> <th><?php echo gettext("Delete"); ?></th><?php } ?>
                    </tr>
                </thead>
                <tbody>
							<?php
								if($_REQUEST['fd']=="")
								{
							$qry=mysqli_query($link,"select * from ac_journal where posting=0 and tran_date between '$start_date' and '$end_date' order by tran_date");
								}
								else
								{
							$qry=mysqli_query($link,"select * from ac_journal where posting=0 and tran_date between '$start_date' and '$end_date' order by tran_date");
								}
							while($obj=mysqli_fetch_array($qry))
							{
							$sDate=strtotime($obj['cdatetime']);	
							$tran_date=date('d-m-Y',strtotime($obj['tran_date']));
							if($obj['posting_date']==''){$posting_date='';} else{$posting_date=date('d-m-Y',strtotime($obj['posting_date']));}
							$type=$obj['type']; if($type==0) { $type_descr='Journal Entry'; } else {$type_descr=''; }
							$trans_no=$obj['trans_no'];
							$reference=$obj['reference'];
							$amount=$obj['amount'];
							$memo=$obj['memo'];							
							$uid=UserDetails($obj['uid'])[0];
							$uid1=UserDetails($obj['posting_by'])[0];
							$PONumber=$obj['PONumber'];				
							if($amount=="0"){ $colorstatus="#ffcccc";}else{$colorstatus="";}
							$posting=$obj['posting'];
						?>	
						<tr style="background-color:<?php echo $colorstatus; ?>">
						  <td class="center"><?php echo $sDate; ?></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $tran_date; ?></font></a></td>						 
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $type_descr; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $PONumber; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $reference; ?></font></a></td>
						  
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo number_format($amount,2,'.',','); ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $memo; ?></font></a></td>
						  
						  <td><?php if($posting==0){ ?><label class="alert-warning"><?php echo gettext("Created"); ?></label><?php } else { ?> <label class="alert-success"><?php echo gettext("Posted"); ?></label> <?php } ?></td>
						  
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $obj['uid']."-".$uid; ?></font></a></td>						  
						 
						  <?php if($useraccess['edit_txt']>0){ ?><td><a href="gl_journal.php?id=<?php echo $trans_no; ?>" class="btn btn-primary btnedit"><i class="fa fa-edit"></i></a></td><?php } ?>
						  <?php if($useraccess['edit_txt']>0){ ?><td><a href="gl_print.php?id=<?php echo $trans_no; ?>" target="_blank" class="btn btn-success btnedit"><i class="fa fa-print"></i></a></td><?php } ?>
						 <?php if($useraccess['delete_txt']>0){?>  <td><?php if($posting==0){?><a href="gl_journal_list.php?id=<?php echo $trans_no; ?>&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove"></i></a><?php } ?></td><?php } ?>
						</tr>
					  <?php
							}
						?>
				
                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="example1-tab3">
            <table id="example1-tab2-dt" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        	
                          <th><?php echo gettext("S Date"); ?></th>
						  <th><?php echo gettext("Transcation Date"); ?></th>
                  		  <th><?php echo gettext("Posted Date"); ?></th>
						  <th><?php echo gettext("Type"); ?></th>				 
						  <th><?php echo gettext("Trans #"); ?></th> 
						  <th><?php echo gettext("Reference"); ?></th> 
						  <th><?php echo gettext("Amount"); ?></th>
						  <th><?php echo gettext("Memo"); ?></th>
						  <th><?php echo gettext("Status"); ?></th>
						  <th><?php echo gettext("Created By"); ?></th>
						  <th><?php echo gettext("Posted By"); ?></th>
						  <th><?php echo gettext("Print"); ?></th>
						  <?php if($useraccess['edit_txt']>0){ ?><th><?php echo gettext("view"); ?></th>	<?php } ?>					 
                    
                    </tr>
                </thead>
                <tbody>
                   			<?php
								if($_REQUEST['fd']=="")
								{
							$qry=mysqli_query($link,"select * from ac_journal where posting=1 and tran_date between '$start_date' and '$end_date' order by tran_date");
								}
								else
								{
							$qry=mysqli_query($link,"select * from ac_journal where posting=1 and tran_date between '$start_date' and '$end_date' order by tran_date");	
								}
							while($obj=mysqli_fetch_array($qry))
							{
							$sDate=strtotime($obj['cdatetime']);
							$tran_date=date('d-m-Y',strtotime($obj['tran_date']));
							if($obj['posting_date']==''){$posting_date='';} else{$posting_date=date('d-m-Y',strtotime($obj['posting_date']));}
							$type=$obj['type']; if($type==0) { $type_descr='Journal Entry'; } else {$type_descr=''; }
							$trans_no=$obj['trans_no'];
							$reference=$obj['reference'];
							$amount=$obj['amount'];
							$memo=$obj['memo'];
							$uid=UserDetails($obj['uid'])[0];
							$uid1=UserDetails($obj['posting_by'])[0];
							$PONumber=$obj['PONumber'];			
							if($amount=="0"){ $colorstatus="#ffcccc";}else{$colorstatus="";}
							$posting=$obj['posting'];
						?>	
						<tr style="background-color:<?php echo $colorstatus; ?>">
						  <td class="center"><?php echo $sDate; ?></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $tran_date; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $posting_date; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $type_descr; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $PONumber; ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $reference; ?></font></a></td>
						  
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo number_format($amount,2,'.',','); ?></font></a></td>
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $memo; ?></font></a></td>
						  
						  <td><?php if($posting==0){ ?><label class="alert-warning"><?php echo gettext("Created"); ?></label><?php } else { ?> <label class="alert-success"><?php echo gettext("Posted"); ?></label> <?php } ?></td>
						  
						  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $obj['uid']."-".$uid; ?></font></a></td>
						  <?php if($useraccess['edit_txt']>0){ ?><td><a href="gl_journal.php?id=<?php echo $trans_no; ?>"><font color="#000000"><?php echo $obj['posting_by']."-".$uid1; ?></font></a></td><?php } ?>
						  <?php if($useraccess['edit_txt']>0){ ?><td><a href="gl_print.php?id=<?php echo $trans_no; ?>" target="_blank" class="btn btn-success btnedit"><i class="fa fa-print"></i></a></td><?php } ?>
						 <?php if($useraccess['delete_txt']>0){?>  <td><a href="gl_journal.php?id=<?php echo $trans_no; ?>" class="btn btn-primary btnedit"><i class="fa fa-edit"></i></a></td><?php } ?>
						 
						</tr>
					  <?php
							}
						?>
                </tbody>
            </table>
        </div>
       
		</div>
			  </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   <footer class="main-footer">
    <?php include('footer.php'); ?>
  </footer>

 
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
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
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<!-- page script -->
<!-- Select2 -->

<!-- page script -->
<script>
  $(function () {
   $(".select2").select2();
   
  $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 }); 

//  jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options,test ) {		 	
// 		   var fd=document.getElementById("start_date").value;
// 		 var ed=document.getElementById("end_date").value;
// 		 alert(this.context);
// 		if ( this.context.length ) {		
// 			window.open('journal_print.php?StartDate='+fd+'&&EndDate='+ed);
// 		//window.location.href = 'journal_print.php?StartDate='+fd+'&&EndDate='+ed;
// 		//return {body: jsonResult.responseJSON.data, header: $("#example1-tab1-dt thead tr th").map(function() { return this.innerHTML; }).get()};
// 		}
// 	} );



   
				 $('#example1-tab1-dt').DataTable({	

				"order": [[0,"desc"]],				
				"columnDefs": [
				{
					"targets": [ 0 ],
					"visible": false,
					"searchable": false
				}],	
								
				"iDisplayLength": 20,
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
						'<option value="20">20</option>'+
						'<option value="30">30</option>'+
						'<option value="50">50</option>'+
						'<option value="100">50</option>'+
					   '<option value="-1"><?php echo gettext("All") ?></option>'+
						'</select> <?php echo gettext("entries") ?>'
				}
				 
				});

				


				 $('#example1-tab2-dt').DataTable({	
				"order": [[0,"desc"]],				
				"columnDefs": [
				{
					"targets": [ 0 ],
					"visible": false,
					"searchable": false
				}],	
		
				"iDisplayLength": 20,
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
						'<option value="20">20</option>'+
						'<option value="30">30</option>'+
						'<option value="50">50</option>'+
						'<option value="100">50</option>'+
					   '<option value="-1"><?php echo gettext("All") ?></option>'+
						'</select> <?php echo gettext("entries") ?>'
				}
				 
				});


				$('#example1-tab3-dt').DataTable({	
				"order": [[0,"desc"]],				
				"columnDefs": [
				{
					"targets": [ 0 ],
					"visible": false,
					"searchable": false
				}],	
		
				"iDisplayLength": 20,
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
						'<option value="20">20</option>'+
						'<option value="30">30</option>'+
						'<option value="50">50</option>'+
						'<option value="100">50</option>'+
					   '<option value="-1"><?php echo gettext("All") ?></option>'+
						'</select> <?php echo gettext("entries") ?>'
				}
				 
				});

   }); 
</script>
<script>
$(document).ready(function(){
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });

	var lastTab = localStorage.getItem('lastTab');	
    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
    }
	else
	{
		activaTab('example1-tab1');	
	}
});    

function activaTab(tab){
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};
</script>

</body>
</html>