<?php	
	if($_REQUEST['lanR']!="")
	{
	$_SESSION['lang']=$_REQUEST['lanR'];
	}
	else
	{
	$_SESSION['lang']=$_SESSION['lang'];
	}
	$RELanguage=$_SESSION['lang'];
	$currentlink=$_SERVER['REQUEST_URI'];	

	if (strpos($currentlink, '?') !== false) {
    $a="&&";
	}else {$a="?";}	
?>	
<style type="text/css">
@font-face {
    font-family: myFirstFont;
    src: url(dist/fonts/DroidArabicKufiRegular.ttf);
    font-weight: bold;
}
.ArFont {
   font-family: myFirstFont;
}
</style>
<!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>SISPOS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SISPOS</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
		
		<li><a href="#"><span class="ArFont" dir="rtl"><?php echo gettext("Location"); ?>:-<?php if($RELanguage=="ar_SA"){ echo $descr_ar; } else { echo $descr_en; } ?>123</span></label></a> </li>
		
		<li><a href="#"><span class="ArFont" dir="rtl"><?php echo gettext("Sales Date"); ?>:- <?php echo $salesdate; ?></span></label></a> </li>
		
		<li><?php if($RELanguage=="ar_SA"){?><a href="#" onClick="window.location='<?php echo $currentlink.$a; ?>lanR=en_US'"><span class="EnFont" ><?php echo "English"; ?></span></a><?php } else { ?><a href="#" onClick="window.location='<?php echo $currentlink.$a; ?>lanR=ar_SA'"><span class="ArFont" dir="rtl"><?php echo gettext("العربية"); ?></span></label></a><?php } ?> </li>
		
		
          
         																		<?php /* Bell Icon */ ?>
		 <!-- Notifications: style can be found in dropdown.less -->
         <?php /*?> <li class="dropdown notifications-menu bellicons">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
			  
              <span class="label label-warning count"></span>            </a>
            <ul class="dropdown-menu">
              <li class="headerMessage"></li>
             
            </ul>
          </li><?php */?>
		  
		 																 <!--End Bell Icon-->
		 
		 
		 
		  <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu" >
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
							 <?php if($PROFIMG=="")
							{
							?><img src="dist/img/avatar5.png" class="user-image" alt="User Image"><?php
							}
							else
							{
							?><img src="staff_img/<?php echo $PROFIMG; ?>" class="user-image" alt="User Image"><?php
							}
							?>
							  
							  <span class="hidden-xs"><?php echo $displayname; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
							<?php if($PROFIMG=="")
							{
							?><img src="dist/img/avatar5.png" class="img-circle" alt="User Image" width="160" height="160"><?php
							}
							else
							{
							?><img src="staff_img/<?php echo $PROFIMG; ?>" class="img-circle" alt="User Image" width="160" height="160"><?php
							}
							?>
                <p>
                 <?php echo $displayname; ?>
                </p>
              </li>
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="Profile.php" class="btn btn-default btn-flat"><?php echo gettext("Profile"); ?></a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" onClick="return frmlogout()" class="btn btn-default btn-flat"><?php echo gettext("Sign Out"); ?></a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            &nbsp;
          </li>
        </ul>
      </div>

    </nav>
	
	
	
	