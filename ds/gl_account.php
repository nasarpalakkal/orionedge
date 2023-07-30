<?php
session_start();
include("../conn.php");
mysqli_set_charset($link,"utf8"); 
$admid=$_SESSION['ADUSER'];$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
if($admid=="")
{
include("logout.php");
}
require_once 'multilanguage.php';include("db/workconfig.php");
$_SESSION['lang']=$RELanguage;
include("db/gl_db.php");
$useraccess=workconfigDetail($RoleID,705);
$del=$_REQUEST['del'];
	if($del==1)
	{
	$id=$_REQUEST['id'];
			$qry=mysqli_query($link,"select * from ac_journal_list where account='$id'");
			$nos=mysqli_num_rows($qry);
			if($nos==0)
			{
			mysqli_query($link,"delete from ac_chart_master where account_code='$id'");
			}
			else
			{
			echo '<script>window.location.href = "gl_account.php?Error";</script>';
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
function frmaddnew()
{
window.location='gl_accounts_add.php';
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
	   <input type="hidden" name="menuid" id="menuid" value="gl_account.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
        <?php echo gettext("GL Accounts"); ?>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content" <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
    <div class="row">
        <div class="col-xs-12">
          
		  <div class="box">            
            <!-- /.box-header -->
            <div class="box-body">
			<?php if($useraccess['add_txt']>0) { ?> <button type="button" class="btn btn-primary " id="btnadd" name="btnadd" onClick="frmaddnew();"><i class="fa fa-plus"></i>  <?php echo gettext("Add New"); ?></button><?php } ?>
			 <div id="message"><?php  if(isset($_REQUEST['Error'])){ echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"3\"><strong>Error!!Account Number Already used</strong></font></div>"; }  ?></div>
			 <div class="box-body"  >
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo gettext("Account Code"); ?></th>                  
				  <th><?php echo gettext("Account Name"); ?>-<?php echo gettext("En"); ?></th> 
                  <th><?php echo gettext("Account Name"); ?>-<?php echo gettext("Ar"); ?></th> 
				  
				  <th><?php echo gettext("Level 4"); ?></th> 
				  <th><?php echo gettext("Level 3"); ?></th> 
				  <th><?php echo gettext("Level 2"); ?></th> 
				  <th><?php echo gettext("Level 1"); ?></th> 
				  
				  <th><?php echo gettext("Account Status"); ?></th> 
				  <?php if($useraccess['edit_txt']>0) { ?> <th><?php echo gettext("Edit"); ?></th><?php } ?>
                 <?php if($useraccess['delete_txt']>0) { ?> <th><?php echo gettext("Delete"); ?></th><?php } ?>
                </tr>
                </thead>
                <tbody>
				<?php
					$qry=mysqli_query($link,"select * from ac_chart_master  order by account_type asc");
					while($obj=mysqli_fetch_object($qry))
					{
					$id=$obj->account_code;
					$name=$obj->account_name;
        			$name_ar=$obj->account_name_ar;
					$ac_type=$obj->account_type;
					
					$charcount=strlen($ac_type);
						if($charcount==2)
						{
						$level2=ChartTypeName($ac_type)["name"];
						$level3="";	
						$level4="";
						}
						else if($charcount==3)
						{
						$level3=ChartTypeName($ac_type)["name"];
						
							$level2id=substr($ac_type, 0, -1);								
						$level2=ChartTypeName($level2id)["name"];	
						$level4="";
						}
						else if($charcount==4)
						{
						$level4=ChartTypeName($ac_type)["name"];
						
						$level3id=substr($ac_type, 0, -1);
						$level3=ChartTypeName($level3id)["name"];
							
							$level2id=substr($ac_type, 0, -2);								
						$level2=ChartTypeName($level2id)["name"];	
						}
										
					
					//$ChartTypeName1=ChartTypeName($ac_type);					
					
					$className=ClassName($obj->cid)[0];
          			if($RELanguage=="ar_SA" && $ChartTypeName1[1]!=""){ $ChartTypeName=$ChartTypeName1[1]; } else { $ChartTypeName=$ChartTypeName1[0]; }
					$status=$obj->inactive; if($status==0){ $status1="Active"; } else { $status1="InActive"; }
				?>	
                <tr>
                  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $id; ?></font></a></td>
				  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $name; ?></font></a></td>
          		  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $name_ar; ?></font></a></td>
				  
				  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $level4; ?></font></a></td>
				  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $level3; ?></font></a></td>		
				  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $level2; ?></font></a></td>	
				  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $className; ?></font></a></td>
				  	
				  <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $status1; ?></font></a></td>				 
				  <?php if($useraccess['edit_txt']>0) { ?> <td><a href="gl_accounts_add.php?id=<?php echo $id; ?>" class="btn btn-primary btnedit"><i class="fa fa-edit"></i></a></td><?php } ?>
                  <?php if($useraccess['delete_txt']>0) { ?><td><a href="gl_account.php?id=<?php echo $id; ?>&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove"></i></a></td><?php } ?>
                </tr>
              <?php
					}
				?>	
               
                </tbody>
               
              </table>
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
<!-- Select2 
"order": [[ 3, "asc" ], [ 4, "asc" ]],-->
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({		
"order": [[0,"asc"]],
"draw": false,
"iDisplayLength": 10,
"bStateSave": true,
"dom":
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			
			"buttons":['Export To Excel'],	
			"buttons":[{
						extend: 'excelHtml5',
						footer: true,
						text: '<?php echo gettext("Chart of Account"); ?>',
						className: 'btn-success',
						color:'red',
						title:"<?php echo gettext("Chart of Account"); ?>",
						exportOptions: {
							columns: ':visible',
							columns: [  0,1, 2, 3,4,5,6],
							
						}
						
					},
					,
					
					//'colvis'      
			
            
       			 ],	
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
        '<option value="25">25</option>'+
        '<option value="50">50</option>'+
		'<option value="100">100</option>'+
       '<option value="-1"><?php echo gettext("All") ?></option>'+
        '</select> <?php echo gettext("entries") ?>'
},
 
 
});



  });
</script>
</body>
</html>
