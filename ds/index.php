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

include("db/workconfig.php");

include("linechartvalue.php");
	
									////////////////// Total Query's /////////////									
									$Qry_TOtalQuery=mysqli_query($link,"select count(*) from sales where  status=1 and cdate BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
									$Obj_TOtalQuery=mysqli_fetch_array($Qry_TOtalQuery);
									$TOtalQuery=$Obj_TOtalQuery[0];
									////////////////// Solved Query's /////////////									
									$Qry_SolvedQuery=mysqli_query($link,"select count(*) from sales where  status=2 and cdate BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
									$Obj_SolvedQuery=mysqli_fetch_array($Qry_SolvedQuery);
									$SolvedQuery=$Obj_SolvedQuery[0];
$Today=date('Y-m-d');
$Last_day=date('Y-m-d',strtotime("-30 days"));						
									
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
  <script src="js/Chart.bundle.js"></script>
   		 <script type="text/javascript">
		 function changelang(a)
		 {
		 location.reload();
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
       <?php include('sidemenu.php'); ?>
	   <input type="hidden" name="menuid" id="menuid" value="index.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo gettext("Dashboard"); ?> 
      </h1>     
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-6 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3><?php echo $TOtalQuery; ?></h3>

              <p><?php echo gettext("Total Sales"); ?></p>
			  <h6><?php echo gettext("Last 30 days"); ?></h6>
            </div>
            <div class="icon">
              <i class="ion ion-alert"></i>
            </div>
            <a href="SalesHistory.php?fd=<?php echo $Last_day; ?>&&ed=<?php echo $Today; ?>&&tb=1" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i> <?php echo gettext("Details"); ?> </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $SolvedQuery; ?></h3>

              <p><?php echo gettext("Total Return Sales"); ?></p>
			  <h6><?php echo gettext("Last 30 days"); ?></h6>
            </div>
            <div class="icon">
              <i class="ion ion-alert"></i>
            </div>
            <a href="SalesHistory.php?fd=<?php echo $Last_day; ?>&&ed=<?php echo $Today; ?>&&tb=2" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i> <?php echo gettext("Details"); ?> </a>
          </div>
        </div>
        <!-- ./col -->
       <?php /*?> <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
             <h3><?php echo $OpenQuery; ?></h3>

               <p><?php echo gettext("Open/Forwarded/Escalated Tickets"); ?></p>
			   <h6><?php echo gettext("Last 30 days"); ?></h6>
            </div>
            <div class="icon">
              <i class="ion ion-alert"></i>
            </div>
           <a href="OperationHistory.php?fd=<?php echo $Last_day; ?>&&ed=<?php echo $Today; ?>&&tb=2" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i> <?php echo gettext("Details"); ?> </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $DeletedQuery; ?></h3>

              <p><?php echo gettext("Rejected Tickets"); ?></p>
			  <h6><?php echo gettext("Last 30 days"); ?></h6>
            </div>
            <div class="icon">
              <i class="ion ion-alert"></i>
            </div>
           <a href="RecycleBin.php?fd=<?php echo $Last_day; ?>&&ed=<?php echo $Today; ?>" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i> <?php echo gettext("Details"); ?> </a>         </div>
        </div><?php */?>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
         <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo gettext("Sales"); ?></h3>

             
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="bar-chart" width="800" height="400"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo gettext("Sales Profit"); ?></h3>

              
            </div>
            <div class="box-body">
              <div class="chart">                
				<canvas id="bar-chart-horizontal" width="800" height="400"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        

        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

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

<script>
new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May","Jun", "Jul", "Aug", "Spt", "Oct","Nov","Dec"],
      datasets: [
        {
          label: "<?php echo gettext("Number of Sales"); ?>",
          backgroundColor: <?php echo json_encode($dataPoints_0, JSON_NUMERIC_CHECK); ?>,
          data: [<?php echo $JanVal_sales1; ?>,<?php echo $FebVal_sales1; ?>,<?php echo $MarVal_sales1; ?>,<?php echo $AprVal_sales1; ?>,<?php echo $MayVal_sales1; ?>,<?php echo $JunVal_sales1; ?>,<?php echo $JulVal_sales1; ?>,<?php echo $AugVal_sales1; ?>,<?php echo $SepVal_sales1; ?>,<?php echo $OctVal_sales1; ?>,<?php echo $NovVal_sales1; ?>,<?php echo $DecVal_sales1; ?>]
        }
      ]
    },
				options: {
				  legend: { display: false },
				  title: {
					display: true,
					 text: ''    
				  },
						  scaleShowValues: true,
					scales: {
					yAxes: [{
					ticks: {
					beginAtZero: true
					}
					}],
			xAxes: [{
			ticks: {
			autoSkip: false
			}
			}]
			}
				}
	
   
});

new Chart(document.getElementById("bar-chart-horizontal"), {
    type: 'horizontalBar',
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May","Jun", "Jul", "Aug", "Spt", "Oct","Nov","Dec"],
      datasets: [
        {
          label: "Monthly Sales Amount",
          backgroundColor: <?php echo json_encode($dataPoints_0, JSON_NUMERIC_CHECK); ?>,
          data: [<?php echo $JanVal_sales2; ?>,<?php echo $FebVal_sales2; ?>,<?php echo $MarVal_sales2; ?>,<?php echo $AprVal_sales2; ?>,<?php echo $MayVal_sales2; ?>,<?php echo $JunVal_sales2; ?>,<?php echo $JulVal_sales2; ?>,<?php echo $AugVal_sales2; ?>,<?php echo $SepVal_sales2; ?>,<?php echo $OctVal_sales2; ?>,<?php echo $NovVal_sales2; ?>,<?php echo $DecVal_sales2; ?>]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: ''
      }
    }
});

 
</script>
 <script src="jsfiles/bell_notification.js"></script> 
<script>
$(document).ready(function(){
bell_notification();
step_admin_logout();
});
</script>
</body>
</html>
