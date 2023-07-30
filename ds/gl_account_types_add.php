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
$id=$_REQUEST['id'];

$qry=mysqli_query($link,"select id,name,name_ar,class_id,parent from ac_chart_types where id='$id'");
$obj=mysqli_fetch_array($qry);
$cid=$obj['id'];
$name=$obj['name'];
$name_ar=$obj['name_ar'];
$class_id=$obj['class_id'];
$parent=$obj['parent'];
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
  function frmcancel()
  {
  window.location='gl_account_types.php';
  }
function frmvalid()
{
	if(document.getElementById("GroupID").value == "")
	{
	alert("Enter ID");
	document.getElementById("GroupID").focus();
	return false;
	}
	if(document.getElementById("GroupName").value == "")
	{
	alert("Enter Name");
	document.getElementById("GroupName").focus();
	return false;
	}
	if(document.getElementById("ClassType").value == "")
	{
	alert("Select Class ");
	document.getElementById("ClassType").focus();
	return false;
	}	
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
	   <input type="hidden" name="menuid" id="menuid" value="gl_account_types.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <section class="content-header">
      <h1 <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
        <?php echo gettext("GL Account Groups"); ?>     
      </h1>     
    </section>

    <!-- Main content -->
    <section class="content" <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
    <div class="row">
        <div class="col-xs-12">
          
		  <div class="box">
           
            <!-- /.box-header -->
            <div class="box-body">
			<?php
if(isset($_REQUEST['save']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"1\"><strong>".gettext("Account Group Added Successfully")."</strong></font></div>";
}
if(isset($_REQUEST['update']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"1\"><strong>".gettext("Account Group Updated Successfully")."</strong></font></div>";
}
if(isset($_REQUEST['error']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"1\"><strong>".gettext("Account Group Already Exists")."</strong></font></div>";
}
?>
              <form class="form-horizontal" name="frmdepartment" method="post" action="gl_account_types_add_action.php">
			  <div class="box-body">
<input type="hidden" name="EditID" value="<?php echo $cid; ?>">
			   <div class="form-group">
                  			 <div class="col-sm-4">
							 <label ><?php echo gettext("ID"); ?></label>
							  <input type="number" class="form-control" id="GroupID" name="GroupID" placeholder="<?php echo gettext("ID"); ?>" value="<?php echo $cid; ?>" <?php if($cid!="") { ?> readonly="" <?php } ?>>
						   </div>       
						   <div class="col-sm-4">
							 <label ><?php echo gettext("Name"); ?>-En</label>
							 <input type="text" class="form-control" id="GroupName" name="GroupName" placeholder="<?php echo gettext("Name"); ?>" value="<?php echo $name; ?>" >
						   </div>
						   <div class="col-sm-4">
							 <label ><?php echo gettext("Name"); ?>-Ar</label>
							 <input type="text" class="form-control" id="GroupNameAr" name="GroupNameAr" placeholder="<?php echo gettext("Name"); ?>" value="<?php echo $name_ar; ?>" >
						   </div>
                  </div>
				  
				  <div class="form-group">
				 			 <div class="col-sm-6">
							 <label ><?php echo gettext("Subgroup Of"); ?></label>
							  <select class="select2" name="Subgroup" id="Subgroup" style="width:100%" onChange="frmsubgroupof(this.value)">
																	<option value="" ><?php echo gettext("Select Subgroup Of"); ?></option>
																	<?php 
																		$qry=mysqli_query($link,"SELECT id,name FROM ac_chart_types ");
																		while($r=mysqli_fetch_array($qry))
																		{
																		?>
																		<option value="<?php echo $r['id']; ?>" <?php if($r['id']==$parent){ ?> selected="selected" <?php } ?>><?php echo $r['id']."--".$r['name']; ?></option>
																	<?php }	?> 
																</select>
						   </div> 
						    <div class="col-sm-6">
							 <label ><?php echo gettext("Class"); ?></label>
							 									<select class="select2" name="ClassType" id="ClassType" style="width:100%" >
																	<option value="" ><?php echo gettext("Select Class"); ?></option>
																	<?php 
																		$qry1=mysqli_query($link,"SELECT cid,class_name FROM ac_chart_class ");
																		while($r1=mysqli_fetch_array($qry1))
																		{
																		?>
																		<option value="<?php echo $r1['cid']; ?>" <?php if($class_id==$r1['cid'] ){?> selected="selected" <?php }  ?>><?php echo $r1['cid']."--".$r1['class_name']; ?></option>
																	<?php }	?> 
																</select>
						   </div>       
						   
                  </div>
				  
				  
				 
				
				</div>
				
				
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" class="btn btn-default pull-left" onClick="frmcancel()"><?php echo gettext("Cancel"); ?></button>
                <?php if($useraccess['add_txt']>0 || $useraccess['edit_txt']>0) { ?><button type="submit" class="btn btn-info pull-right" onClick="return frmvalid();"><?php echo gettext("Save"); ?></button><?php }	?> 
              </div>
              <!-- /.box-footer -->
            </form>
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
  <script type="text/javascript">
							function frmsubgroupof(a)
							{
																	if(a!="")
																	{
																	$.ajax({
																		type:'POST',
																		url:'ajaxData_ClassType.php',
																		data:'Department='+a,
																		success:function(html){	
																			$('#ClassType').html(html);
																		}
																	});
																	}
																	else
																	{
																	var a=1;
																	$.ajax({
																		type:'POST',
																		url:'ajaxData_ClassType1.php',
																		data:'Department='+a,
																		success:function(html){	
																		
																			$('#ClassType').html(html);
																		}
																	});
																	} 
																
							}
	</script>						

 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<?php include('Topbottom.php'); ?>
<!-- page script -->
<!-- jQuery 2.2.3 -->
<!-- Bootstrap 3.3.6 -->
<!-- Select2 -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
	
	
	

 });
 
</script>
</body>
</html>
