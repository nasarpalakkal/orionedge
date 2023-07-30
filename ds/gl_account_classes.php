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
	mysqli_query($link,"delete from ac_chart_class where cid='$id'");
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
window.location='gl_account_classes_add.php';
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
	   <input type="hidden" name="menuid" id="menuid" value="gl_account_classes.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
        <?php echo gettext("GL Account Classes"); ?>
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
			 <div class="box-body"  >
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo gettext("Class ID"); ?></th>
                  <th><?php echo gettext("Class Name"); ?>-En</th>
                  <th><?php echo gettext("Class Name"); ?>-Ar</th>				 
				  <th><?php echo gettext("Class Type"); ?></th> 
				   <th><?php echo gettext("Account Type"); ?></th> 
				  <?php if($useraccess['edit_txt']>0) { ?><th><?php echo gettext("Edit"); ?></th><?php } ?>
                  <?php /*?><th><?php echo gettext("Delete"); ?></th><?php */?>
                </tr>
                </thead>
                <tbody>
				<?php
					$qry=mysqli_query($link,"select * from ac_chart_class order by ctype");
					while($obj=mysqli_fetch_object($qry))
					{
					$id=$obj->cid;
					$class_name=$obj->class_name;
          $class_name_ar=$obj->class_name_ar;
					$ctype=ClassType($obj->ctype);
					$AccountType=$obj->AccountType;
          if($RELanguage=="ar_SA" && $ctype['descr_ar']!=""){ $ctype1=$ctype['descr_ar'];} else{$ctype1=$ctype['descr'];} 
				?>	
                <tr>
                  <td><a href="gl_account_classes_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $id; ?></font></a></td>
                  <td><a href="gl_account_classes_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $class_name; ?></font></a></td>
                  <td><a href="gl_account_classes_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $class_name_ar; ?></font></a></td>
				  <td><a href="gl_account_classes_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $ctype1; ?></font></a></td>
				  <td><a href="gl_account_classes_add.php?id=<?php echo $id; ?>"><font color="#000000"><?php echo $AccountType; ?></font></a></td>
				  <?php if($useraccess['edit_txt']>0) { ?><td><a href="gl_account_classes_add.php?id=<?php echo $id; ?>" class="btn btn-primary btnedit"><i class="fa fa-edit"></i></a></td><?php } ?>
         <?php /*?> <td><a href="gl_account_classes.php?id=<?php echo $id; ?>&&del=1" class="btn btn-danger btnedit" onClick="return frmdel()"><i class="fa fa-remove"></i></a></td> <?php */?>
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
<!-- page script -->
<!-- Bootstrap 3.3.6 -->
<!-- Select2 -->

<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({
"order": [[0,"asc"]],
"iDisplayLength": 100,	
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
        '<option value="25">25</option>'+
        '<option value="50">50</option>'+
		'<option value="100">50</option>'+
       '<option value="-1"><?php echo gettext("All") ?></option>'+
        '</select> <?php echo gettext("entries") ?>'
}
 
});
  
  });
</script>
</body>
</html>
