  <script>
	function pageLoad() {
	 $('.sidebar-menu li').removeClass('active');
		var url_selector = document.getElementById("menuid").value;			
		var detectPage = $('.sidebar-menu li a[href="'+url_selector+'"]').parent('li');
		detectPage.addClass('active');
		detectPage.parent('ul').parent('li').removeClass('active').addClass('active');
		detectPage.parent('ul').parent('li').parent('ul').parent('li').removeClass('active').addClass('active');		
		}

</script>

<?php 
session_start();
$a=explode("/",$_SERVER['REQUEST_URI']); 
$currentFolder=$a[count($a)-2];
$daily_close=1;
$daily_close=$_SESSION['daily_close'];
?>

<div class="user-panel">
	
         <div class="pull-left image">
		 <?php if($PROFIMG=="")
			{
			?><img src="../dist/img/avatar5.png" class="img-circle" alt="User Image"><?php
			}
			else
			{
			?><img src="staff_img/<?php echo $PROFIMG; ?>" class="img-circle" alt="User Image" ><?php
			}
			?>
          
        </div>
        <div class="pull-left info">
          <p><?php echo $displayname; ?></p>
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">&nbsp;</li>
    <!-- -----------------------------------Sales Module ----------------------------------------------------------->

		<li class="treeview">
          <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span><?php echo gettext("Sales"); ?></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">		  
      
      <li><a href="Sales.php"><i class="fa fa-circle-o"></i><span> <?php echo gettext("Sales Invoice"); ?></span></a></li>
      <li><a href="SalesHistory.php"><i class="fa fa-circle-o"></i><span> <?php echo gettext("Sales History"); ?></span></a></li>
           
		  
          </ul>
        </li>


        <li class="treeview">
          <a href="#">
            <i class="fa fa-money"></i> <span title="<?php echo gettext("Finance"); ?>"><?php echo gettext("Finance"); ?></span>
           <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="gl_journal.php"><i class="fa fa-circle-o"></i> <span title="<?php echo gettext("Journal Entry"); ?>" > <?php echo gettext("Journal Entry"); ?> </span></a></li>
			<li><a href="gl_journal_list.php"><i class="fa fa-circle-o"></i> <span title="<?php echo gettext("Journal Inquiry"); ?>" > <?php echo gettext("Journal Inquiry"); ?> </span></a></li>
			
			
			
			
           
		    <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> <?php echo gettext("Accounts"); ?>
               <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			    <li><a href="gl_account_classes.php"><i class="fa fa-circle-o"></i> <span title="<?php echo gettext("GL Account Classes"); ?>" > <?php echo gettext("GL Account Classes"); ?> </span></a></li>
				<li><a href="gl_account_types.php"><i class="fa fa-circle-o"></i> <span title="<?php echo gettext("GL Account Groups"); ?>" > <?php echo gettext("GL Account Groups"); ?> </span></a></li>					
				<li><a href="gl_account.php"><i class="fa fa-circle-o"></i> <span title="<?php echo gettext("GL Accounts"); ?>" > <?php echo gettext("GL Accounts"); ?> </span> </a></li>               
              </ul>
            </li>

          </ul>

        </li>
		
		
    
    
		

		
			
		
		   
       <li><a href="logout.php" onClick="return frmlogout()"><i class="fa fa-sign-out"></i> <span><?php echo gettext("Sign Out"); ?></span>   </a></li>
       
        
       
       
      </ul>