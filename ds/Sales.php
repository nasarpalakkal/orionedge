<?php	
session_start();
include("../conn.php");
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
$username_session=$_SESSION['username'];
$displayname=$_SESSION['USERDISPLAYNAME'];
$PROFIMG=$_SESSION['PROFIMG'];
$logout_time=$_SESSION['logout_time'];
$DefStore=$_SESSION['DefStore'];
if($admid=="")
{
include("logout.php");
}
require_once 'multilanguage.php';
$_SESSION['lang']=$RELanguage;
date_default_timezone_set('Asia/Riyadh');

include("db/workconfig.php");
include("db/Address_db.php");
include("db/gl_db.php");
$useraccess=workconfigDetail($RoleID,201);
$id=$_REQUEST['id'];
$ret=$_REQUEST['ret'];
if($id!="")
		{
		$qry_initial=mysqli_query($link,"select * from sales where PONumber='$id'");
		$obj_initial=mysqli_fetch_object($qry_initial);
		if($ret==1)
		{
            $SalesCat=2;
			$OrderNo="";
        } else
		{
			$SalesCat=$obj_initial->status;
			$OrderNo=$id;
		}		
		$cdate=date('d-m-Y',strtotime($obj_initial->cdate));
		$DueDate=date('d-m-Y',strtotime($obj_initial->duedate));
		$CustomerName=$obj_initial->CustomerName;
		$CustomerNameDescr=CustomerDetails($obj_initial->CustomerName)["code"]."-".CustomerDetails($obj_initial->CustomerName)["customer_name"]."-".CustomerDetails($obj_initial->CustomerName)["customer_name_ar"]."-".CustomerDetails($obj_initial->CustomerName)["customer_contact1"];
		$BillNumber=$obj_initial->bill_no;
		
		$sub_total=$obj_initial->sub_total;
		$Tax_amt=$obj_initial->tax_amt;
		$discount_amt=$obj_initial->net_discount;
		$net_amt_final=$obj_initial->net_amt;
		$discount_per_amt=$obj_initial->discount_per_amt;
		$journal_id=$obj_initial->journal_id;
			$JournalPosted=GetJournalPost($journal_id)['posting'];
		$discount_type=$obj_initial->discount_type;
		//$SalesCat=$obj_initial->status;
		$CashType=$obj_initial->CashType;
		$SalesType=$obj_initial->SalesType;
		$payment_type=$obj_initial->payment_method;
		$ReasonForReturn=$obj_initial->ReasonForReturn;
		$payment_termret=$obj_initial->payment_terms;
		$CustomerBranchID=$obj_initial->CustomerBranchID;
		$Ebill=$obj_initial->Ebill;
		$storeret=$obj_initial->location;
		$PurchaseNumber=$obj_initial->PurchaseNumber;

		if($CashType==1)
			{
				$payment_amt_cash=$obj_initial->payment_amt_cash;
				$payment_amt_card=$obj_initial->payment_amt_card;
			}
			else
			{
				$payment_amt_cash=0;
				$payment_amt_card=0;
			}

						//$qry_estimate1=mysqli_query($link,"select * from ac_journal where trans_no='$trans_no' and posting=1");
						//$no_posting=mysqli_num_rows($qry_estimate1); /////////// no is zero then it posted else not posted.	*/		
		$qry2=mysqli_query($link,"select max(sno) from sales_list where PONumber='$id'");	
		$qry21=mysqli_query($link,"select * from sales_list where PONumber='$id'");		
		$nos2=mysqli_num_rows($qry21);
		$obj2=mysqli_fetch_array($qry2);
		$maxsno=$obj2[0];
		if($maxsno>7){$ctno=$maxsno; } else { $ctno=7; }		
		}
		else
		{
					$ctno=7;
					$nos2=0;
					$cdate=date('d-m-Y');	
					$DueDate=date('d-m-Y');	
					//$qry_nextnumber=mysqli_query($link,"select next_number from next_numbering where type='sales'");
					//$obj_nextnumber=mysqli_fetch_array($qry_nextnumber);
					//$OrderNo=$obj_nextnumber['next_number'];
					$OrderNo="";
					$SalesType=1;
					$JournalPosted=0;
					$payment_type=1;
					
					$qry_DefaultCustomer=mysqli_query($link,"select id,customer_name,customer_contact1,email_id,CustomerType from customer_details where Default_chk='1'");
					$obj_DefaultCustomer=mysqli_fetch_array($qry_DefaultCustomer);					
					$CustomerName=$obj_DefaultCustomer['id'];					
					$CustomerNameDescr=CustomerDetails($CustomerName)["code"]."-".CustomerDetails($CustomerName)["customer_name"]."-".$obj_DefaultCustomer['descr_en'];
					$CustomerNameDisp=$obj_DefaultCustomer['customer_name'];
					$CustomerMobileDisp=$obj_DefaultCustomer['customer_contact1'];
					$CustomerEmailDisp=$obj_DefaultCustomer['email_id'];
					$CustomerTypeDef=$obj_DefaultCustomer['CustomerType'];

                    if ($CustomerTypeDef=="") {
						$CashType=1;
					}					
						else{
							$CashType=$CustomerTypeDef;

						}	
		
					
					$sub_total=0;
					$Tax_amt=0;
					$discount_amt=0;
					$net_amt_final=0;
					$payment_amt_cash=0;
					$payment_amt_card=0;

					$storeret=$DefStore;
		}	
		
					$qry_customer_nextnumber=mysqli_query($link,"select next_number from next_numbering where type='customer'");
					$obj_customer_nextnumber=mysqli_fetch_array($qry_customer_nextnumber);
					$OrderNo_customer=$obj_customer_nextnumber['next_number'];	
																		
																		//////////////////////// Defualt Warehouse /////////////////////
																		//$qry_def_warehouse=mysqli_query($link,"select id from warehouse_master where transcations='1'");
																		//$obj_def_warehouse=mysqli_fetch_array($qry_def_warehouse);
																		//$whid=$obj_def_warehouse[0];
																		$whid=$storeret;
																		
																		
?>	
<!DOCTYPE html>
<html>
<head>
  <?php include('Topheader.php'); ?>
 <!-- ./wrapper -->
<script src="plugins/jQuery/jquery2.1.3.js"></script>
<script src="plugins/jQuery/jquery-ui.js"></script>
<!-- Bootstrap 3.3.6 -->
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
 <script language="javascript">
 function frmcancel()
{
window.location='Sales.php';
}
function frmaddnew()
{
window.location='Sales.php';
}
function frmvalid(a)
{
	if(document.getElementById("FDate").value=="")
	{
	alert('<?php echo gettext("Select Date"); ?>');
	document.getElementById("FDate").focus();
	return false;
	}	
	if(document.getElementById("CustomerName").value=="")
	{
	alert('<?php echo gettext("Select Customer Name"); ?>');
	document.getElementById("CustomerName").focus();
	return false;
	}
	if(document.getElementById("SalesType").value=="")
	{
	alert('<?php echo gettext("Select Sales"); ?>');
	document.getElementById("SalesType").focus();
	return false;
	}
	if(document.getElementById("SalesCat").value=="")
	{
	alert('<?php echo gettext("Select Sales / Return"); ?>');
	document.getElementById("SalesCat").focus();
	return false;
	}
	if(document.getElementById("CashType").value=="")
	{
	alert('<?php echo gettext("Select Sales Type"); ?>');
	document.getElementById("CashType").focus();
	return false;
	}
	if(document.getElementById("Store").value=="")
	{
	alert('<?php echo gettext("Select Store"); ?>');
	document.getElementById("Store").focus();
	return false;
	}
				var Countnumbmer=document.getElementById("Countnumbmer").value;
					for(var i=0;i<=Countnumbmer;i++)
					{
							var ItemMastertxt = document.getElementsByName('ItemMaster[]');
							var Unittxt = document.getElementsByName('unit[]');
							var spricetxt = document.getElementsByName('sprice[]');
							var qtytxt = document.getElementsByName('qty[]');
							
									if(ItemMastertxt[i].value!="")
									{
										if(Unittxt[i].value=="")
										{
										alert('<?php echo gettext("Select unit"); ?>');
										Unittxt[i].focus();
										return false;
										}	
										if(spricetxt[i].value=="")
										{
										alert('<?php echo gettext("Enter  Unit Price"); ?>');
										spricetxt[i].focus();
										return false;
										}
										if(qtytxt[i].value=="")
										{
										alert('<?php echo gettext("Enter Quantity"); ?>');
										qtytxt[i].focus();
										return false;
										}
									
									}
									
									if(Unittxt[i].value!="")
									{
										if(ItemMastertxt[i].value=="")
										{
										alert('<?php echo gettext("Select Item"); ?>');
										ItemMastertxt[i].focus();
										return false;
										}	
										if(spricetxt[i].value=="")
										{
										alert('<?php echo gettext("Enter  Unit Price"); ?>');
										spricetxt[i].focus();
										return false;
										}
										if(qtytxt[i].value=="")
										{
										alert('<?php echo gettext("Enter Quantity"); ?>');
										qtytxt[i].focus();
										return false;
										}
									
									}
									
									
									if(spricetxt[i].value!="")
									{
										if(ItemMastertxt[i].value=="")
										{
										alert('<?php echo gettext("Select Item"); ?>');
										ItemMastertxt[i].focus();
										return false;
										}	
										if(Unittxt[i].value=="")
										{
										alert('<?php echo gettext("Select unit"); ?>');
										Unittxt[i].focus();
										return false;
										}
										if(qtytxt[i].value=="")
										{
										alert('<?php echo gettext("Enter Quantity"); ?>');
										qtytxt[i].focus();
										return false;
										}
									
									}
									
									if(qtytxt[i].value!="")
									{
										if(ItemMastertxt[i].value=="")
										{
										alert('<?php echo gettext("Select Item"); ?>');
										ItemMastertxt[i].focus();
										return false;
										}	
										if(Unittxt[i].value=="")
										{
										alert('<?php echo gettext("Select unit"); ?>');
										Unittxt[i].focus();
										return false;
										}
										if(spricetxt[i].value=="")
										{
										alert('<?php echo gettext("Enter  Unit Price"); ?>');
										spricetxt[i].focus();
										return false;
										}
									
									}
							
					}
	if(document.getElementById("CashType").value==1)				
	{
	if(document.getElementById("cashAmt").value ==0 && document.getElementById("cardAmt").value ==0)			
	{
		alert('<?php echo gettext("Select Payment Method"); ?>');
		return false;	
	}
	
	}
	
	var c=confirm("<?php echo gettext("Do you want to continue ?"); ?>");
	if(c==true)
	{
		
				if(document.getElementById("CashType").value==1)				
				{	
				var TotalPaymentValue=parseFloat(document.getElementById("cashAmt").value)+parseFloat(document.getElementById("cardAmt").value);
								if(parseFloat(document.getElementById("gross_amt").value)!=parseFloat(TotalPaymentValue))
								{
									var c2=confirm("<?php echo gettext("Amount is not correct. Do you want to continue ?"); ?>");
									if (c2==true) 
									{
											var c1=confirm("<?php echo gettext("Invoice Print ?"); ?>");
												if(c1==true)
												{
												document.getElementById('printval').value=1;												
														if(document.getElementById('JournalPost').value==0)
														{
												document.getElementById('frmdepartment').submit();
														}
												}
												else
												{
												document.getElementById('printval').value=0;
														if(document.getElementById('JournalPost').value==0)
														{	
												document.getElementById('frmdepartment').submit();
														}
												}
									}
									else
									{
										return false;
									}
									
								}
								else
								{
												var c1=confirm("<?php echo gettext("Invoice Print ?"); ?>");
												if(c1==true)
												{
												document.getElementById('printval').value=1;
														
												document.getElementById('frmdepartment').submit();
														
												}
												else
												{
												document.getElementById('printval').value=0;
													
												document.getElementById('frmdepartment').submit();
														
												}
								}
				}			

		else
		{
			var c1=confirm("<?php echo gettext("Invoice Print?"); ?>");
						if(c1==true)
						{												
						document.getElementById('printval').value=1;																						
								//if(document.getElementById('originalinvoicenumber').value!="" && document.getElementById('OrderNumber').value=="")
								//{									
						document.getElementById('frmdepartment').submit();
								//}								
						}
						else
						{							
						document.getElementById('printval').value=0;
								if(document.getElementById('originalinvoicenumber').value!="" && document.getElementById('OrderNumber').value=="")
								{
						document.getElementById('frmdepartment').submit();
								}
						}
		}

		
	
	}
	else
	{
	return false;
	}
	
}

function frmHold()
{		
		if(document.getElementById('JournalPost').value==0)
				{
	var c=confirm("<?php echo gettext("Do you want to hold the bill ?"); ?>");
	if(c==true)
	{
	document.getElementById('holdval').value =1;	
	document.getElementById('frmdepartment').submit();
	}
	else
	{
	document.getElementById('holdval').value =0;
	return false;
	}
				}
}

function frmcheck(a)
{
	if(a==3)
	{
	document.getElementById("divh1").style.display="block";
	document.getElementById("divh").style.display="none";
	document.getElementById('percenatage_val').value = "";
	document.getElementById('discount_amt').value = 0;
	frmdiscountrate(0,2);
	document.getElementById('discount_amt').readOnly = true;		
	}
	else if(a==2)
	{
	document.getElementById("divh").style.display="block";
	document.getElementById("divh1").style.display="none";
	document.getElementById('RoundOffVal').value = "";
	frmdiscountrate(0,2);
	document.getElementById('discount_amt').readOnly = true; 
	}
	else if(a==4)
	{
	document.getElementById("divh1").style.display="none";
	document.getElementById("divh").style.display="none";
	document.getElementById('percenatage_val').value = "";
	document.getElementById('discount_amt').value = 0;
	frmdiscountrate(0,4);
	document.getElementById('discount_amt').readOnly = true;	
	}
	else
	{
	document.getElementById("divh").style.display="none"; 
	document.getElementById("divh1").style.display="none"; 
	document.getElementById('discount_amt').readOnly = false;
	}
	document.getElementById('discount_note').readOnly = false;
}
</script>
 <script>
function frmPost()
{
	if(document.getElementById("FDate").value=="")
	{
	alert("Select Purchase Date");
	document.getElementById("FDate").focus();
	return false;
	}
	var c=confirm("<?php echo gettext("Do you want to post this sale ?"); ?>");
	if(c==true)
	{
	window.location='purchase_journal_post.php?id=<?php echo $id; ?>&&type=1&&PD='+document.getElementById("FDate").value;
	}
	else
	{
	return false;
	}		
}	
</script>	
 <script src="jsfiles/logout.js"></script> 
 <style>
/* The container */
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 15px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container .checkmark:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
.ui-autocomplete {
			max-height: 300px;
            overflow-y: auto;
			 overflow-x: hidden;
			 padding-right: 20px;
    position: absolute;
    z-index: 1000;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.ui-autocomplete > li {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.ui-autocomplete > li.ui-state-focus {
  background-color: #e9e9e9; 
}
.ui-helper-hidden-accessible {
  display: none;
}
div.ex1 { 
  height: 400px;
  width: 100%;
  overflow-y: scroll;
  overflow-x: scroll;
}
.ui-state-focus
{
    color:#000099;
    background:#0099CC;
    outline:none;
}
select.btn {
    -webkit-appearance: button;
       -moz-appearance: button;
            appearance: button;
    width: auto;
}

select.btn-mini {
    height: auto;
    line-height: 14px;
    padding-right: 16px;
}

select.btn-mini + .caret {
    margin-left: -20px;
    margin-top: 9px;
}
textboxnew
{
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
}
</style>
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
	  	   <input type="hidden" name="menuid" id="menuid" value="Sales.php">
	  
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo gettext("Sales"); ?> 
      </h1>     
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row" <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
        
		
		<div class="col-xs-12">
          
		 					
		
								
		  
		  
		  
		   <div class="box">           
            <!-- /.box-header -->
            <div class="box-body">
			 <?php if($id!=""){ if($useraccess['add_txt']>0){?><button type="button" class="btn btn-primary " id="btnadd" name="btnadd" onClick="frmaddnew();"><i class="fa fa-plus"></i> <?php echo gettext("New"); ?></button>  <?php } } 			
if(isset($_REQUEST['save']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"2\"><strong>". gettext("Invoice Created successfully")."</strong></font></div>";
}
if(isset($_REQUEST['update']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"2\"><strong>". gettext("Sales updated successfully")."</strong></font></div>";
}
if(isset($_REQUEST['perror']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"3\"><strong>".gettext("Error!! Sales not posted.")."</strong></font></div>";
}
if(isset($_REQUEST['Berror']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"3\"><strong>".gettext("Error!! Sales not posted.")."</strong></font></div>";
}
if(isset($_REQUEST['Psuccess']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"3\"><strong>".gettext("Sales Posted Sucessfully")."</strong></font></div>";
}
?>
              <form class="form-horizontal" name="frmdepartment" id="frmdepartment" method="post" action="sales_add_action.php">
			 <input type="hidden" name="eid" id="eid" value="<?php echo $id; ?>">
			 <input type="hidden" name="ret" id="ret" value="<?php echo $ret; ?>">
			 <input type="hidden" name="Countnumbmer" id="Countnumbmer" value="<?php echo $ctno; ?>">
			<input type="hidden" name="journal_id" id="journal_id" value="<?php echo $journal_id; ?>">
			<input type="hidden" name="holdval" id="holdval" value="">
			<input type="hidden" name="printval" id="printval" value="">
			<input type="hidden" name="JournalPost" id="JournalPost" value="<?php echo $JournalPosted; ?>">
              <div class="box-body">		
  
  <!-- Second Modal -->
<div id="CostModal" class="modal fade" role="dialog" tabindex = "-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onClick="frmClose(Itemcost.value)">&times;</button>
        <h4 class="modal-title"><?php echo gettext("Item Cost"); ?></h4>
      </div>
      <div class="modal-body">
       			 <table border="1" width="100%">
				  <tr><td></td></tr>
				  
				   <tr><td align="center"><?php echo gettext("Item Cost"); ?></td><td align="center"><?php echo gettext("Last Sales Price"); ?></td></tr>
				   <tr><td align="center"><span id="Tprescription1"></span></td><td align="center"><span id="Tprescription2"></span></td></tr>
				  </table>
				   
				   
				   
				   
				   <input type="hidden" id="Itemcost" name="Itemcost" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="frmClose(Itemcost.value)"><?php echo gettext("Close"); ?></button>
      </div>
    </div>

  </div>
</div>

  <!-- Sales Modal -->
  <div id="SalesModal" class="modal fade" role="dialog" tabindex = "-1" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onClick="frmClose(Itemcost.value)">&times;</button>
        <h4 class="modal-title"><?php echo gettext("Sales History"); ?></h4>
      </div>
      <div class="modal-body">

					<h4>Customer Sales History</h4>

       			 <table border="1" width="100%" id="sales-item">
					<thead>
						<tr>
							<th> Invoice Number </th>
							<th> Date & Time</th>
							<th> Qty Recieved</th>
							<th> Customer </th>
							<th> Unit Price </th>
							<th> Sub Total </th>
							<th> Tax </th>
							<th> Total </th>
						</tr>
					</thead>					
				  
				  </table>


				  <h4>Complete Sales History</h4>

				  <table border="1" width="100%" id="sales-item1">
					<thead>
						<tr>
							<th> Invoice Number </th>
							<th> Date & Time</th>
							<th> Qty Recieved</th>
							<th> Customer </th>
							<th> Unit Price </th>
							<th> Sub Total </th>
							<th> Tax </th>
							<th> Total </th>
						</tr>
					</thead>					
				  
				  </table>
				   
				   
				   
				   
				   <input type="hidden" id="Itemcost" name="Itemcost" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="frmClose(Itemcost.value)"><?php echo gettext("Close"); ?></button>
      </div>
    </div>

  </div>
</div>


  <!-- Purchase Modal -->
  <div id="PurchaseModal" class="modal fade" role="dialog" tabindex = "-1" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onClick="frmClose(Itemcost.value)">&times;</button>
        <h4 class="modal-title"><?php echo gettext("Purchase History"); ?></h4>
      </div>
      <div class="modal-body">
       			 <table border="1" width="100%" id="purchase-item">
					<thead>
						<tr>
							<th> PO Number </th>
							<th> Date & Time</th>
							<th> Qty Recieved</th>
							<th> Supplier </th>
							<th> Unit Price </th>
							<th> Sub Total </th>
							<th> Tax </th>
							<th> Total </th>
						</tr>
					</thead>					
				  
				  </table>
				   
				   
				   
				   
				   <input type="hidden" id="Itemcost" name="Itemcost" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="frmClose(Itemcost.value)"><?php echo gettext("Close"); ?></button>
      </div>
    </div>

  </div>
</div>


  <!-- Thired Modal -->
<div id="CostModalSummary" class="modal fade" role="dialog" tabindex = "-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" onClick="frmClose(Itemcost.value)">&times;</button>
        <h4 class="modal-title"><?php echo gettext("Item Summary Cost"); ?></h4>
      </div>
      <div class="modal-body">
       			 <table border="1" width="100%">
				  <tr><td></td></tr>				  
				   <tr><td align="center"><?php echo gettext("Item Summary Cost"); ?></td><td align="center"><span id="Tprescription3"></span></td></tr>
				  </table>
				   
				   
				   
				   
				   <input type="hidden" id="Itemcost" name="Itemcost" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="frmClose(Itemcost.value)"><?php echo gettext("Close"); ?></button>
      </div>
    </div>

  </div>
</div>
  
			  
			  
			  	<div class="form-group">
				 			<div class="col-sm-2">
							 <label ><?php echo gettext("Invoice Number"); ?><span><font color="red">*</font></span></label>
							 <input type="text" class="form-control" id="OrderNumber" name="OrderNumber" tabindex = "-1" value="<?php echo $OrderNo; ?>" placeholder="<?php echo gettext("Order Number"); ?>" readonly="" />
						   </div>       
						   <div class="col-sm-2">
							 <label ><?php echo gettext("Date"); ?><span><font color="red">*</font></span></label>
							 <input type="text" class="form-control" id="FDate" name="FDate" tabindex = "-1" placeholder="<?php echo gettext("Date"); ?>" value="<?php echo $cdate; ?>" autocomplete="off">
						   </div>
						   
						    
						   
						    <div class="col-sm-2">
							<label ><?php echo gettext("Sales"); ?></label>
								 <select name="SalesType" id="SalesType" tabindex = "-1" class="form-control" style="width:100%" onChange="frmSalesType(this.value)">																
								<option value="1"  <?php if($SalesType==1){?> selected="selected" <?php } ?>><?php echo gettext("Retail Sales"); ?></option>								
								<option value="2"  <?php if($SalesType==2){?> selected="selected" <?php } ?>><?php echo gettext("Whole Sales"); ?></option>									
							   </select>							
							   
						   </div>  
						   
						 
						   
						    <div class="col-sm-2">
							<label ><?php echo gettext("Sale/Return"); ?></label>
								 <select name="SalesCat" id="SalesCat" tabindex = "-1" class="form-control" style="width:100%" onChange="frmSalesCategory(this.value)">																
								
								 <?php if($ret=='')
								{
									?>
								 <option value="1" <?php if($SalesCat==1){?> selected="selected" <?php } ?>><?php echo gettext("Sale"); ?></option>								
								<?php
								 }
								 else
								{
									?>
								<option value="2" <?php if($SalesCat==2){?> selected="selected" <?php } ?>><?php echo gettext("Return"); ?></option>	
								<?php
								}
								?>						
							   </select>							
							   
						   </div>
						   
						     <div class="col-sm-2">
							<label ><?php echo gettext("Sale Type"); ?></label>
								<select name="CashType" id="CashType" class="form-control" style="width:100%" onChange="frmCashTypeChange(this.value)">																
								<option value="1" <?php if($CashType==1){?> selected="selected" <?php } ?>><?php echo gettext("Cash Sale"); ?></option>
								<option value="2" <?php if($CashType==2){?> selected="selected" <?php } ?>><?php echo gettext("Credit Sale"); ?></option>								
								<?php /*?><option value="2" <?php if($CashType==2){?> selected="selected" <?php } ?>><?php echo gettext("Bank"); ?></option>	<?php */?>							
								
							   </select>						
							   
						   </div> 

						   <div class="col-sm-2">
							<label ><?php echo gettext("Store"); ?></label>
								<select name="Store" id="Store" class="form-control" style="width:100%" >
								<?php
								$qry_store=mysqli_query($link,"select * from warehouse_master where type=2 ");
								while($obj_store=mysqli_fetch_object($qry_store))
								{
								$storeid=$obj_store->id;								
								$storename=$obj_store->code."-".$obj_store->descr_en;
								$defualt_wh=$obj_store->defualt_wh;	
								?>																
								<option value="<?php echo $storeid; ?>" <?php if($storeret==$storeid){?> selected="selected" <?php } ?>><?php echo $storename; ?></option>																						
								<?php
								}
								?>
							   </select>						
							   
						   </div> 
						    
						  
                  </div>	



				  <div class="form-group" >
				  <div class="col-sm-3">
							 <label ><?php echo gettext("Customer"); ?> <span><font color="red">*</font></span> </label>
					<input type="text" class="form-control customerlist" id="CustomerNameDescr"  name="CustomerNameDescr" value="<?php echo $CustomerNameDescr; ?>" autocomplete="off" placeholder="<?php echo gettext("Search By Customer code/Name/Account Number"); ?>" onClick="this.setSelectionRange(0, this.value.length)">
					
					<input type="hidden" id="CustomerName"  name="CustomerName" value="<?php echo $CustomerName; ?>" autocomplete="off">
							   <a href="customer_master.php" style="color:black" target="_blank" class="btn"><i class="fa fa-folder"><?php echo gettext("Add New Customer"); ?></i></a>
						   </div>  	
				  	<div class="col-sm-3">
						<label ><?php echo gettext("Payment Term"); ?></label>
							  	<select name="PaymentTerm" id="PaymentTerm" class="form-control" onchange="frmPaymentTermChange(this.value,FDate.value)">
								<option value="">-<?php echo gettext("Select Payment Term"); ?>-</option>
								<?php
								$qry_country1=mysqli_query($link,"select * from payment_terms_master ");
								while($obj_country1=mysqli_fetch_object($qry_country1))
								{
								$pid=$obj_country1->id;
								$paymentterms_name=$obj_country1->descr;
								?>
								<option value="<?php echo $pid; ?>" <?php if($pid==$payment_termret){ ?> selected="selected" <?php } ?>><?php echo $paymentterms_name; ?></option>
								<?php
								}
								?>
							   </select>
						</div>

						<div class="col-sm-3" >
						<label ><?php echo gettext("Due Date"); ?></span></label>
							  <br>
							  <input type="text" class="form-control" id="DueDate" name="DueDate" tabindex = "-1" placeholder="<?php echo gettext("Due Date"); ?>" value="<?php echo $DueDate; ?>" autocomplete="off">
						</div>

						<div class="col-sm-3" >
						<label ><?php echo gettext("Reference Details"); ?></span></label>
							  <br>
				  			<textarea  name="ReferenceDetails" id="ReferenceDetails" rows="2" cols="40%"><?php echo nl2br($BillNumber); ?></textarea>
						</div>

						
						

				  </div>

				  <div class="form-group" >
				  <div class="col-sm-3" >
						<label ><?php echo gettext("PO Number"); ?></span></label>
							  <br>
				  			<input type="text" class="form-control" id="PurchaseNumber" name="PurchaseNumber" tabindex = "-1" placeholder="<?php echo gettext("PO Number"); ?>" value="<?php echo $PurchaseNumber; ?>" autocomplete="off">
						</div>
				  </div>
				  
				  <div class="form-group" >
				  		
				  		<div class="col-sm-4" id="OIN">
						<label ><?php echo gettext("Original Invoice Number"); ?></label>
						<input type="text" class="form-control" id="originalinvoicenumber" name="originalinvoicenumber" value="<?php echo $id; ?>" readonly>
						</div>	

						<div class="col-sm-4" id="RFR">
						<label ><?php echo gettext("Reason For Return"); ?></label>
						<br>
						<textarea  name="ReasonForReturn" id="ReasonForReturn" rows="2" cols="40%"><?php echo nl2br($ReasonForReturn); ?></textarea>
						</div>
								
						<div class="col-sm-2" style="display:none">
						<label ><?php echo gettext("E.Tax Bill"); ?></span></label>
							  <br>
				  			<input type="checkbox" id="Ebill" name="Ebill" value="1" onClick="frmETaxBill()" <?php if($Ebill==1){ ?> checked <?php } ?>>
						</div>

					
				  </div>

				  
				 
				 	
				  
			</div>	  
				  
				 
				<div class="box-body table-responsive">
				<?php /*?><div class="ex1"><?php */?>
			<?php /*?> <h3><?php echo gettext("Item List"); ?></h3><?php */?>
			 <table class="table order-listmain" style="width:100%;background-color:#DFDFDF" id="myTable">
													<thead class="bordered-darkorange">
														<tr role="row"> 																										
															<th ><?php echo gettext("Item"); ?></th>
															<th ><?php echo gettext("Details"); ?></th>
															<th ><?php echo gettext("Unit"); ?></th>
															<th ><?php echo gettext("Qty"); ?>- (F9-Sales History)<br>(F2-Purchase History)</th>
															<th ><?php echo gettext("Unit Price"); ?></th>
															<th ><?php echo gettext("Unit Tax"); ?></th>
															<th ><?php echo gettext("Qty On Hand"); ?></th>															
															<th ><?php echo gettext("Amount"); ?></th>
															<th ><?php echo gettext("Action"); ?></th>
														</tr>
													</thead>
													<?php 
														/*$a=mysqli_query($link,"select * from sales_list where PONumber='$id'");
														if(!mysqli_num_rows($a))
														{*/
														for($i=0;$i<=$ctno;$i++)
														{
														$item_no="";
														$a=mysqli_query($link,"select A.item_no,A.details,B.item_descr,B.BaseLastCost,B.IncludeTax,A.TaxPer,A.item_type,A.unit,A.unit_price,A.qty,A.total_price,A.tax_amt,C.retail_price,C.w_price,B.AvgCost,C.factor_val,C.Packing_Setup,A.unitsno,Q.qty as QtyonHand,U.descr UnitDescr,T.division_val,A.unitprice_withtax,C.barcode,A.item_profit from sales_list as A left join inventory as B on A.item_no=B.item_no left join inventory_uom as C on A.item_no=C.item_no and A.unit=C.unit left join inventory_qty as Q on A.item_no=Q.item_no and Q.warehouse_id='$whid' left join tb_units as U on A.unit=U.id left join tax_type as T on A.TaxPer=T.perc where A.PONumber='$id' and A.sno='$i'");		
														$nob=mysqli_num_rows($a);
														if($nob==0)
														{
														   $unitsno="";
															$item_descr="";
															$TaxPerc="";
															$ItemType="";
															$unitid="";
															$unit_price="";
															$unit_tax="";
															$qty="";
															$total_price="";
															$tax_amt="";
															$retail_price="";
															$w_price="";
															$AvgCost="";
															$LastCost="";
															$factor_val="";
															$QtyonHand="";
															$UnitDescr="";
															$division_val="";
															$AmountwithTax="";
															$IncludeTax="";
															$division_val="";
															$details="";
															$item_profit="";
														}
														else
														{														
														$b=mysqli_fetch_array($a);
														$item_no=$b['item_no'];	
														if($item_no!="")
																{																
															$item_descr=$item_no."-".$b['item_descr']."-".$b['barcode']."-".$b['w_price'];
															$TaxPerc=$b['TaxPer'];						
															$ItemType=$b['item_type'];
															$unitid=$b['unit'];
															$unit_price=$b['unit_price'];
															$retail_price=$b['retail_price'];
															$w_price=$b['w_price'];
															$IncludeTax=$b['IncludeTax'];
															$division_val=$b['division_val'];
															$unit_tax=$b['tax_amt'];
															$details=$b['details'];
															$item_profit=$b['item_profit'];
																/*if($IncludeTax==1)
																{
																	if($SalesType==1)
																	{
																	$unit_tax=number_format($b['unit_price']-($b['unit_price']/$division_val),2,'.','');
																	}
																	else
																	{
																	$unit_tax=number_format($b['unit_price']-($b['unit_price']/$division_val),2,'.','');
																	}																														
																}
																else
																{
																$unit_tax=number_format(($b['unit_price']*($TaxPerc/100)),2,'.','');
																}*/
															$qty=$b['qty'];
															$total_price=$b['total_price'];
															$tax_amt=$b['tax_amt'];
															
															$Packing_Setup=$b['Packing_Setup'];
															$AvgCost=$b['AvgCost'];
															$LastCost=$b['BaseLastCost'];
															$factor_val=$b['factor_val'];
															$unitsno=$b['unitsno'];
															if($b['QtyonHand']!=""){ $QtyonHand=round($b['QtyonHand']/$factor_val); } else {$QtyonHand=0;}
															if($b['Packing_Setup']==""){$UnitDescr=$b['UnitDescr']; } else {$UnitDescr=$b['UnitDescr'].'-'.$b['Packing_Setup']; }
															$division_val=$b['division_val'];
															$AmountwithTax=$b['unitprice_withtax'];
																}
																else
																{
															$unitsno="";
															$item_descr="";
															$TaxPerc="";
															$ItemType="";
															$unitid="";
															$unit_price="";
															$unit_tax="";
															$qty="";
															$total_price="";
															$tax_amt="";
															$retail_price="";
															$w_price="";
															$AvgCost="";
															$LastCost="";
															$factor_val="";
															$QtyonHand="";
															$UnitDescr="";
															$division_val="";
															$AmountwithTax="";
															$IncludeTax="";
															$division_val="";
															$details="";
															$item_profit="";
																}
														}											
														
															
																
														?>
														<tr>
															<td  >
															<input type="text" class="form-control diseaseslist" id="ItmeMaster<?php echo $i; ?>"  name="ItemMaster[]" value="<?php echo $item_descr; ?>" autocomplete="off" size="25" onClick="this.setSelectionRange(0, this.value.length)" onChange="frmAddItem(this.value,'<?php echo $i; ?>',ItemMasterID<?php echo $i; ?>.value,unit<?php echo $i; ?>.value)" onDblClick="frmnewtest(ItemMasterID<?php echo $i; ?>.value)">
															<input type="hidden" name="ItemMasterID[]" id="ItemMasterID<?php echo $i; ?>" value="<?php echo $item_no; ?>">
															<input type="hidden" name="TaxPerc[]" id="TaxPerc<?php echo $i; ?>" value="<?php echo $TaxPerc; ?>">
															<input type="hidden" name="ItemType[]" id="ItemType<?php echo $i; ?>" value="<?php echo $ItemType; ?>">
															<input type="hidden" name="AvgCost[]" id="AvgCost<?php echo $i; ?>" value="<?php echo $AvgCost; ?>">
															<input type="hidden" name="LastCost[]" id="LastCost<?php echo $i; ?>" value="<?php echo $LastCost; ?>">
															<input type="hidden" name="factor_val[]" id="factor_val<?php echo $i; ?>" value="<?php echo $factor_val; ?>">
															<input type="hidden" name="division_val[]" id="division_val<?php echo $i; ?>" value="<?php echo $division_val; ?>">
															<input type="hidden" name="IncludeTax[]" id="IncludeTax<?php echo $i; ?>" value="<?php echo $IncludeTax; ?>">															
															</td>

															<td><textarea class="textboxnew" name="details[]" id="details<?php echo $i; ?>" rows="3" cols="15"><?php echo nl2br($details); ?></textarea></td>
															
															<td ><input type="text" class="textboxnew" name="unitDisplay[]" id="unitDisplay<?php echo $i; ?>" size="3" value="<?php echo $UnitDescr; ?>" readonly="">
															<input type="hidden" name="unit[]" id="unit<?php echo $i; ?>" value="<?php echo $unitsno; ?>"></td>
												
													<td ><input type="text" class="form-control" name="qty[]" id="qty<?php echo $i; ?>" onKeyPress="return isNumber(event)" onKeyDown="return checkPhoneKey(event.key,<?php echo $i; ?>)"  value="<?php echo $qty; ?>" onChange="frminputboxprice2(this.value,sprice<?php echo $i; ?>.value,spricevat<?php echo $i; ?>.value,TaxPerc<?php echo $i; ?>.value,<?php echo $i; ?>,AmountwithTax<?php echo $i; ?>.value,division_val<?php echo $i; ?>.value)"  autocomplete="off" size="5"/>
												<input type="hidden" name="qty_old[]" id="qty_old<?php echo $i; ?>" value="<?php echo $qty; ?>"/>
												</td>
												
												<td ><input type="text" class="form-control"  name="sprice[]" id="sprice<?php echo $i; ?>" onKeyPress="return isNumber(event);" value="<?php echo $unit_price; ?>" autocomplete="off" onChange="frminputboxprice3(qty<?php echo $i; ?>.value,this.value,spricevat<?php echo $i; ?>.value,TaxPerc<?php echo $i; ?>.value,<?php echo $i; ?>,division_val<?php echo $i; ?>.value,IncludeTax<?php echo $i; ?>.value)" onClick="this.setSelectionRange(0, this.value.length)"size="5"/>
													<input type="hidden" name="sprice_retail[]" id="sprice_retail<?php echo $i; ?>" value="<?php echo $retail_price; ?>"  readonly="" />
													<input type="hidden" name="sprice_whole[]" id="sprice_whole<?php echo $i; ?>" value="<?php echo $w_price; ?>"  readonly="" />
													
													
													<input type="hidden" name="AmountwithTax[]" id="AmountwithTax<?php echo $i; ?>" onKeyPress="return isNumber(event);" readonly="" value="<?php echo $AmountwithTax; ?>" />
													</td>
												
												<td ><input type="text" class="form-control" name="spricevat[]" id="spricevat<?php echo $i; ?>" onKeyPress="return isNumber(event);" readonly="" value="<?php echo $unit_tax; ?>" size="5"/></td>	
												
												<td ><input type="text" class="textboxnew"   name="qtyonhand[]" id="qtyonhand<?php echo $i; ?>" readonly="" autocomplete="off" size="4" value="<?php echo $QtyonHand; ?>"/></td>
												
												<td ><input type="text" class="form-control" tabindex = "-1" name="amount[]" onKeyPress="return isNumber(event);" id="amount<?php echo $i; ?>" value="<?php echo $total_price; ?>" autocomplete="off" readonly="" size="5"/>
													<input type="hidden" name="taxamt_unit[]" id="taxamt_unit<?php echo $i; ?>" value="<?php echo $tax_amt; ?>"></td>

												<td ><input type="button" class="ibtnDelMain btn btn-xs btn-danger " tabindex = "-1"  value="<?php echo gettext("Delete"); ?>"></td>
													
													</tr>
													<?php
													}
													?>
														
													
													
			   </table>	
			   
			    <table width="100%" border="0" id="myTable2">								
								<tr >
								<td align="left"><button type="button" class='addmore btn btn-xs btn-info' onClick="displayResult()" id="addmore">+ <?php echo gettext("Add More"); ?></button></td>
								</tr>
								<tr >
								<td align="left">Total Item Selected:- <span id="total_item_selected"><?php echo $nos2; ?></span></td>
								</tr>
						</table>	
						
				</div>		
			   <?php /*?></div><?php */?>
			   
			  <div class="box-body table-responsive">
			  <div class="col-xs-4">
		  <p class="lead"><?php echo gettext("Payment Methods"); ?></p>
		  <strong><?php echo gettext("Cash"); ?></strong> <input type="text" name="cashAmt" id="cashAmt" value="<?php echo number_format($payment_amt_cash,2,'.',''); ?>" onClick="this.setSelectionRange(0, this.value.length)">			<br>
		  <br>
		  <strong> <?php echo gettext("Card"); ?></strong> <input type="text" name="cardAmt" id="cardAmt" value="<?php echo number_format($payment_amt_card,2,'.',''); ?>" onClick="this.setSelectionRange(0, this.value.length)">			
		</div> 
		
						 <div class="col-xs-4">
          <p class="lead"><?php echo gettext("Discount Type"); ?></p>
          <div class="form-group">
					  <label class="container">
					  <input type="radio" name="radio" value="1" onClick="frmcheck(this.value)" <?php if($discount_type==1){?> checked="checked" <?php } ?>>
					  <span class="checkmark"></span><?php echo gettext("Amount"); ?>
					</label>
					<label class="container">
					  <input type="radio" name="radio" value="2" onClick="frmcheck(this.value)" <?php if($discount_type==2){?> checked="checked" <?php } ?>>
					  <span class="checkmark"></span> <?php echo gettext("Percentage(%)"); ?>
					  
					</label>	<div id="divh"><input type="text" name="percenatage_val" id="percenatage_val" onChange="frmdiscountrate(this.value,2)" autocomplete="off" value="<?php echo $discount_per_amt; ?>"></div>
					
					<label class="container">
					  <input type="radio" name="radio" value="3" onClick="frmcheck(this.value)" <?php if($discount_type==3){?> checked="checked" <?php } ?>>
					  <span class="checkmark"></span> <?php echo gettext("Round Off To"); ?>
					  
					</label>	<div id="divh1"><input type="text" name="RoundOffVal" id="RoundOffVal" onChange="frmdiscountrate(this.value,3)" autocomplete="off" value="<?php if($discount_type==3){ echo $net_amt_final; } ?>"></div>
					 
					
																			
              </div>
			   
        </div>
		
		
			
		
		
		<div class="col-xs-4">
          
		  
          <table class="table table-bordered table-hover table-striped" style="width:100%" id="myTable1">
							<?php /*?><tr>
															
																
																
																<td  <?php if($RELanguage=="ar_SA"){ ?>align="left" <?php } else { ?>align="right"<?php } ?>><strong><?php echo gettext("Additional Charge"); ?></strong></td>													
																<td ><input type="text" id="additional_amt" name="additional_amt" value="<?php echo number_format($additional_amt,2,'.',''); ?>" class="form-control" readonly=""/></td>
																
															</tr><?php */?>
															
															<tr>
																
															  
																<td width="10%" <?php if($RELanguage=="ar_SA"){ ?>align="left" <?php } else { ?>align="right"<?php } ?>><strong><?php echo gettext("Sub Total"); ?></strong></td>													
																<td width="10%"><input type="text" id="sub_total" name="sub_total" value="<?php echo number_format($sub_total,2,'.',''); ?>" class="form-control" readonly="" autocomplete="off" tabindex = "-1" onKeyDown="return checkPhoneKeySummary(event.key)"/>
															
																</td>
																
															</tr>
															
															<tr>
																
															  
																<td width="10%" <?php if($RELanguage=="ar_SA"){ ?>align="left" <?php } else { ?>align="right"<?php } ?>><strong><?php echo gettext("Discount"); ?></strong></td>													
																<td width="10%"><input type="text" id="discount_amt" name="discount_amt" value="<?php echo number_format($discount_amt,2,'.',''); ?>" class="form-control" readonly="" onChange="frmdiscountrate(this.value,1)" autocomplete="off"/></td>
																
															</tr>
															
															<tr>
																
															  
																<td width="10%" <?php if($RELanguage=="ar_SA"){ ?>align="left" <?php } else { ?>align="right"<?php } ?>><strong><?php echo gettext("Tax"); ?></strong></td>													
																<td width="10%"><input type="text" id="Tax_amt" name="Tax_amt" value="<?php echo number_format($Tax_amt,2,'.',''); ?>" class="form-control" readonly="" autocomplete="off"/></td>
																
															</tr>
															
															
															
							<tr>
																
																<td width="10%" <?php if($RELanguage=="ar_SA"){ ?>align="left" <?php } else { ?>align="right"<?php } ?>><strong><?php echo gettext("Gross Total"); ?></strong></td>													
																<td width="10%"><input type="text" id="gross_amt" name="gross_amt" value="<?php echo number_format($net_amt_final,2,'.',''); ?>" class="form-control" readonly=""/></td>
																
															</tr>								
							</table>
							
        </div>
		
                  </div>
				  
				                      
						
			 
				 
			<div class="box-body ">	
				
				
              <!-- /.box-body -->
              <div class="box-footer">
			  
			   	<?php if($id!="")
				{
					if($id!="" && $ret==1)
					{
						?>
						<a href="sales_print.php?id=<?php echo $id; ?>" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> <?php echo gettext("Print"); ?>A4</a>
			 <a href="sales_print_Tab.php?id=<?php echo $id; ?>" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> <?php echo gettext("Print"); ?>80MM</a>
							<?php 
					}
					else
					{
						?>
						<a href="sales_print.php?id=<?php echo $OrderNo; ?>" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> <?php echo gettext("Print"); ?>A4</a>
			 <a href="sales_print_Tab.php?id=<?php echo $OrderNo; ?>" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> <?php echo gettext("Print"); ?>80MM</a>
							<?php 
					}
				?>
			 
			 				<?php 							
							 /*if($JournalPosted==0)
							 {
							 ?>
			   <button type="button" class="btn pull-center"  style="margin:0 0 0 3%; background:#17a2b8"onClick="return frmPost();"><font color="#FFFFFF"><i class="fa fa-money"></i> &nbsp;<?php echo gettext("Post"); ?></font></button>
			 
			    			      
                 <?php 
				 			}*/
				 }
				 if($useraccess['edit_txt']>0 || $useraccess['add_txt']>0 ){
					if($id=="" || ($id!="" && $ret==1))
					{

					
				 ?>				  
				<button type="button" class="btn btn-warning pull-left" onClick="return frmHold();" style="display: none;"><?php echo gettext("Hold"); ?> [F7]</button>

				 <button type="button" class="btn btn-primary pull-right" onClick="return frmvalid();"><?php echo gettext("Submit"); ?> [F8]</button> 
				 <?php 
				 }
				}
				?>
				
              </div>
              <!-- /.box-footer -->
            
            </div>
            <!-- /.box-body -->
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

 
</div>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- Morris.js charts -->
<?php /*?><script src="plugins/morris/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script><?php */?>
<!-- Sparkline -->
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="plugins/daterangepicker/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<?php /*?><script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script><?php */?>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<!-- AdminLTE App -->

<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>

<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>

 <script src="<?php if($RELanguage=="ar_SA"){?> dist/js/app.min.js <?php } else { ?> dist_en/dist/js/app.min.js <?php } ?>"></script>
<script>
        $.AdminLTESidebarTweak = {};

        $.AdminLTESidebarTweak.options = {
            EnableRemember: true,
            NoTransitionAfterReload: false
            //Removes the transition after page reload.
        };

        $(function () {
            "use strict";

            $("body").on("collapsed.pushMenu", function(){
                if($.AdminLTESidebarTweak.options.EnableRemember){
                    document.cookie = "toggleState=closed";
                }
            }).on("expanded.pushMenu", function(){
                if($.AdminLTESidebarTweak.options.EnableRemember){
                    document.cookie = "toggleState=opened";
                }
            });
        });
    </script>
<?php /*?><!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script><?php */?>


<?php //include('Topbottom.php'); ?>

<script>
  $(function () { 
   
	 
  			document.getElementById("ItmeMaster0").focus();

			  if(document.getElementById("SalesCat").value==2)  
			{
				document.getElementById("OIN").style.display='block';
				document.getElementById("RFR").style.display='block';

			}
			else
			{
				document.getElementById("OIN").style.display='none';
				document.getElementById("RFR").style.display='none';
			}
			
   			<?php if($discount_type==3){?> 
			document.getElementById("divh1").style.display="block";
			document.getElementById("divh").style.display="none";
			document.getElementById('discount_amt').readOnly = true;
			<?php } else if($discount_type==2){?>
			document.getElementById("divh").style.display="block";
			document.getElementById("divh1").style.display="none";
			document.getElementById('discount_amt').readOnly = true;
			<?php } else if($discount_type==1){?>
			document.getElementById('discount_amt').readOnly = false;
			document.getElementById("divh1").style.display="none";
			document.getElementById("divh").style.display="none";
			<?php } else if($discount_type==4){?>
			document.getElementById("divh").style.display="none";
			document.getElementById("divh1").style.display="none";
			document.getElementById('discount_amt').readOnly = true;
			<?php } else { ?>		
			document.getElementById("divh").style.display="none";
			document.getElementById("divh1").style.display="none";
			<?php } ?>
			
    //Timepicker
   $(".select2").select2();	
   
   $(".select2").select2({   
  		selectOnClose: true
	});
	
	$('#FDate').datepicker({
      format: 'dd-mm-yyyy',
	  autoclose: true
    });
	$('#DueDate').datepicker({
      format: 'dd-mm-yyyy',
	  autoclose: true
    });
	
  
   $(document).on('keydown', function(event) {
 		
	   if (event.key == "F8") { 
	   return frmvalid();
       } 
	   else  if (event.key == "F7") { 
	   return frmHold();
       }   
	   
	  
   });
 	
   
  });
  

$(document).on('focus', '.select2', function() {
    $(this).siblings('select').select2('open');
});


							
</script>

<script>														
			var counter = document.getElementById("Countnumbmer").value;
			$("#addmore").on("click", function () {
			var newRow = $("<tr>");
			var cols = "";	
			//var row_count = $('#myTable tr').length;
			counter =parseFloat(counter)+parseFloat(1);	
cols += '<td><input type="text" class="form-control diseaseslist" id="ItmeMaster'+counter+'" name="ItemMaster[]" value="" autocomplete="off" size="25" onClick="this.setSelectionRange(0, this.value.length)"  onChange="frmAddItem(this.value,'+counter+',ItemMasterID'+counter+'.value,unit'+counter+'.value)" onDblClick="frmnewtest(ItemMasterID'+counter+'.value)"><input type="hidden" name="ItemMasterID[]" id="ItemMasterID'+counter+'" value=""><input type="hidden" name="TaxPerc[]" id="TaxPerc'+counter+'" value=""><input type="hidden" name="ItemType[]" id="ItemType'+counter+'" value=""><input type="hidden" name="AvgCost[]" id="AvgCost'+counter+'"><input type="hidden" name="LastCost[]" id="LastCost'+counter+'"><input type="hidden" name="factor_val[]" id="factor_val'+counter+'"><input type="hidden" name="division_val[]" id="division_val'+counter+'"><input type="hidden" name="IncludeTax[]" id="IncludeTax'+counter+'"></td>';

			cols += '<td><textarea class="textboxnew" name="details[]" id="details'+counter+'" rows="3" cols="15"></textarea></td>';
			
			cols += '<td><input type="text" class="textboxnew" id="unitDisplay'+counter+'" name="unitDisplay[]" size="3" value="" ><input type="hidden" name="unit[]" id="unit'+counter+'" value=""></td>';
			
			
			
			cols += '<td><input type="text" class="form-control" name="qty[]" id="qty'+counter+'" onKeyPress="return isNumber(event);"  onKeyDown="return checkPhoneKey(event.key,'+counter+')"  onChange="frminputboxprice2(this.value,sprice'+counter+'.value,spricevat'+counter+'.value,TaxPerc'+counter+'.value,'+counter+',AmountwithTax'+counter+'.value,division_val'+counter+'.value)" autocomplete="off" size="5"/><input type="hidden" name="qty_old[]" id="qty_old'+counter+'"/></td>';	
			
			
			
			
			cols += '<td><input type="text" class="form-control" name="sprice[]" id="sprice'+counter+'" onKeyPress="return isNumber(event);"  onChange="frminputboxprice3(qty'+counter+'.value,this.value,spricevat'+counter+'.value,TaxPerc'+counter+'.value,'+counter+',division_val'+counter+'.value,IncludeTax'+counter+'.value)" size="5"/><input type="hidden" name="sprice_retail[]" id="sprice_retail'+counter+'"  readonly="" /><input type="hidden" name="sprice_whole[]" id="sprice_whole'+counter+'"  readonly="" /><input type="hidden" name="AmountwithTax[]" id="AmountwithTax'+counter+'" onKeyPress="return isNumber(event);" readonly="" /></td>';
												
			cols += '<td><input type="text" class="form-control" name="spricevat[]" id="spricevat'+counter+'" readonly="" size="5"/></td>';	
			
			cols += '<td><input type="text" class="textboxnew" name="qtyonhand[]" id="qtyonhand'+counter+'" readonly="" autocomplete="off" size="4"/></td>';		
												
			cols += '<td><input type="text" tabindex = "-1" class="form-control" name="amount[]" id="amount'+counter+'"  onkeypress="return isNumber(event);" autocomplete="off" readonly="" size="5"/><input type="hidden" name="taxamt_unit[]" id="taxamt_unit'+counter+'" value=""></td>';
			
			cols += '<td><input type="button" tabindex = "-1" class="ibtnDelMain btn btn-xs btn-danger" value="<?php echo gettext("Delete"); ?>"></td>';
			newRow.append(cols);
			$("table.order-listmain").append(newRow);
			(counter);
		
			//var totalrows=$('#myTable tr').length;

			
		
					var row_count = $('#myTable tr').length;					
					document.getElementById("Countnumbmer").value=row_count-2;
				
					
				
						
				
			});
			
			$("table.order-listmain").on("click", ".ibtnDelMain", function (event) {				
			 $(this).closest("tr").remove();   
			     var row_count = $('#myTable tr').length;				
				 document.getElementById("Countnumbmer").value=row_count-2;
					frmAmt();		
    		});
			
				
		</script>
		<script>
			function isNumber(evt){
				evt = (evt) ? evt : window.event;
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				
				if (charCode > 31 && (charCode < 48 || charCode > 57)) {
								if(charCode!=46)
								{
								return false;
								}
							
				}
				
				return true;
			}			
			
			function frmAmt()
			{
							var TotalPrice=0;
							var TotalTax=0;
							var num=document.getElementById("Countnumbmer").value;	
							for( var i = 0; i<=num; i++)
							{									
							var Tprice=document.getElementsByName('amount[]');
							var price=Tprice[i].value;
								if(price>0)
								{
							TotalPrice=parseFloat(TotalPrice)+parseFloat(price);
								}
							
							var TpriceTax=document.getElementsByName('taxamt_unit[]');
							var Taxprice=TpriceTax[i].value;
								if(Taxprice>0)
								{
							TotalTax=parseFloat(TotalTax)+parseFloat(Taxprice);
								}
							
							document.getElementById("sub_total").value=(parseFloat(TotalPrice)-parseFloat(TotalTax)).toFixed(2);
							
							document.getElementById("discount_amt").value=parseFloat(0).toFixed(2);
							
							document.getElementById("percenatage_val").value="";
							document.getElementById("RoundOffVal").value="";
							
							
							document.getElementById("Tax_amt").value=parseFloat(TotalTax).toFixed(2);
							document.getElementById("gross_amt").value=(parseFloat(TotalPrice)).toFixed(2);	
								if(document.getElementById("CashType").value==1)
								{
								document.getElementById("cashAmt").value=(parseFloat(TotalPrice)).toFixed(2);	
								}
								else
								{
									document.getElementById("cashAmt").value=0;	
									document.getElementById("cardAmt").value=0;	
								}
								
														
							}
			}
			
			function frminputboxprice2(qty,unitamt,unit_tax,taxperc,no,AmountwithTax,division_val)
			{					
					
											if($('#Ebill').prop('checked')) {
																			var Taxchk=1;
																			} else {
																				var Taxchk=0;
																			}						
							
							var TotalWithoutTax=parseFloat(qty)*parseFloat(unitamt);
							var Total=parseFloat(qty)*parseFloat(AmountwithTax);
								if(Taxchk==1)
								{
								var TotalTax=0;
								}
								else
								{
								var TotalTax=parseFloat(Total)-(parseFloat(Total)/parseFloat(division_val));
								}
							
							//document.getElementById("amount"+no).value=parseFloat(TotalWithoutTax).toFixed(2);
							document.getElementById("amount"+no).value=parseFloat(Total).toFixed(2);
							document.getElementById("taxamt_unit"+no).value=parseFloat(TotalTax).toFixed(2);


													
							var TotalPrice=0;
							var TotalTax=0;
							var num=document.getElementById("Countnumbmer").value;	
							for( var i = 0; i<=num; i++)
							{									
							var Tprice=document.getElementsByName('amount[]');
							var price=Tprice[i].value;
								if(price>0)
								{
							TotalPrice=parseFloat(TotalPrice)+parseFloat(price);
								}
							
							var TpriceTax=document.getElementsByName('taxamt_unit[]');
							var Taxprice=TpriceTax[i].value;
								if(Taxprice>0)
								{
							TotalTax=parseFloat(TotalTax)+parseFloat(Taxprice);
								}
							
							document.getElementById("sub_total").value=(parseFloat(TotalPrice)-parseFloat(TotalTax)).toFixed(2);
							
							document.getElementById("discount_amt").value=parseFloat(0).toFixed(2);
							
							document.getElementById("percenatage_val").value="";
							document.getElementById("RoundOffVal").value="";
							
							
							document.getElementById("Tax_amt").value=parseFloat(TotalTax).toFixed(2);
							document.getElementById("gross_amt").value=(parseFloat(TotalPrice)).toFixed(2);
							if(document.getElementById("CashType").value==1)
								{
								document.getElementById("cashAmt").value=(parseFloat(TotalPrice)).toFixed(2);	
								}
								else
								{
									document.getElementById("cashAmt").value=0;	
									document.getElementById("cardAmt").value=0;	
								}
							//document.getElementById("gross_amt").value=(parseFloat(TotalPrice)+parseFloat(TotalTax)).toFixed(2);
							}
							
			}
			
			function frminputboxprice3(qty,unitamt,unit_tax,taxperc,no,division_val,IncludeTax)
			{					
																			if($('#Ebill').prop('checked')) {
																			var Taxchk=1;
																			} else {
																				var Taxchk=0;
																			}


							if(IncludeTax==1)
							{
								if(Taxchk==1)
								{
									var unitprice=parseFloat(unitamt)/parseFloat(division_val);
									var Taxamt=0;																			
								}
								else
								{
									var unitprice=parseFloat(unitamt)/parseFloat(division_val);
									var Taxamt=parseFloat(unitamt)-(parseFloat(unitamt)/parseFloat(division_val));	
																		
								}
								
								var Total=parseFloat(qty)*parseFloat(unitamt);
								var TotalTax=parseFloat(qty)*parseFloat(Taxamt);
							}
							else
							{	
                                if (Taxchk==1) {
									var unitprice=parseFloat(unitamt);
									var Taxamt=0;	
								}
								else{
									var unitprice=parseFloat(unitamt);
									var Taxamt=parseFloat(unitamt)*(parseFloat(taxperc)/parseFloat(100));
								}	

								var Total=parseFloat(qty)*(parseFloat(unitamt)+parseFloat(Taxamt));
								var TotalTax=parseFloat(qty)*parseFloat(Taxamt);			
	
							}

							// if(Taxchk==1)
							// 	{
							// 		var unitprice=parseFloat(unitamt);
							// 		var Taxamt=0;							
							// 	}
							// 	else
							// 	{
							// 		var unitprice=parseFloat(unitamt)/parseFloat(division_val);
							// 		var Taxamt=parseFloat(unitamt)-(parseFloat(unitamt)/parseFloat(division_val));			
							// 	}
							
							
							document.getElementById("sprice"+no).value=parseFloat(unitprice).toFixed(2);
							document.getElementById("spricevat"+no).value=parseFloat(Taxamt).toFixed(2);
							document.getElementById("amount"+no).value=parseFloat(Total).toFixed(2);
							document.getElementById("taxamt_unit"+no).value=parseFloat(TotalTax).toFixed(2);
							document.getElementById("AmountwithTax"+no).value=(parseFloat(unitprice)+parseFloat(Taxamt)).toFixed(2);
							
							var TotalPrice=0;
							var TotalTax=0;
							var num=document.getElementById("Countnumbmer").value;	
							for( var i = 0; i<=num; i++)
							{									
							var Tprice=document.getElementsByName('amount[]');
							var price=Tprice[i].value;
								if(price>0)
								{
							TotalPrice=parseFloat(TotalPrice)+parseFloat(price);
								}
							
							var TpriceTax=document.getElementsByName('taxamt_unit[]');
							var Taxprice=TpriceTax[i].value;
								if(Taxprice>0)
								{
							TotalTax=parseFloat(TotalTax)+parseFloat(Taxprice);
								}
							
							document.getElementById("sub_total").value=(parseFloat(TotalPrice)-parseFloat(TotalTax)).toFixed(2);
							
							document.getElementById("discount_amt").value=parseFloat(0).toFixed(2);
							
							document.getElementById("percenatage_val").value="";
							document.getElementById("RoundOffVal").value="";
							
							
							document.getElementById("Tax_amt").value=parseFloat(TotalTax).toFixed(2);
							document.getElementById("gross_amt").value=(parseFloat(TotalPrice)).toFixed(2);
							if(document.getElementById("CashType").value==1)
								{
								document.getElementById("cashAmt").value=(parseFloat(TotalPrice)).toFixed(2);	
								}
								else
								{
									document.getElementById("cashAmt").value=0;	
									document.getElementById("cardAmt").value=0;	
								}
							//document.getElementById("gross_amt").value=(parseFloat(TotalPrice)+parseFloat(TotalTax)).toFixed(2);
							}
							
			}				
			
			
			function frmdiscountrate(a,b)
			{
				var tax_val=0.15;		
																			if($('#Ebill').prop('checked')) {
																			var Taxchk=1;
																			} else {
																				var Taxchk=0;
																			}		
			if(b==3)
			{
					if(a==""){a=0;}else{a=a;}
					
					if(parseFloat(a)==0)
					{
					document.getElementById("discount_amt").value=Math.round(a* 100) / 100;
					document.getElementById("RoundOffVal").value="";
					var sub_total=document.getElementById("sub_total").value;		
						if(Taxchk==1)
						{
							var tax_amt=0;
							document.getElementById("Tax_amt").value=0;
						}
						else
						{
							var tax_amt=parseFloat(tax_val)*parseFloat(sub_total);
							document.getElementById("Tax_amt").value=Math.round(tax_amt* 100) / 100;
						}
					
						
					document.getElementById("gross_amt").value=Math.round((parseFloat(sub_total)+parseFloat(tax_amt))* 100) / 100;
					if(document.getElementById("CashType").value==1)
								{
									document.getElementById("cashAmt").value=Math.round((parseFloat(sub_total)+parseFloat(tax_amt))* 100) / 100;
								}
								else
								{
									document.getElementById("cashAmt").value=0;	
									document.getElementById("cardAmt").value=0;	
								}
					
					}
					else
					{											
					var sub_total=document.getElementById("sub_total").value;						
							if(Taxchk==1)
						    {
							var TaxAmt=0;	
							var val=parseFloat(sub_total)-parseFloat(a);	
							}
							else
							{
							var TaxAmt=parseInt(1)+parseFloat(tax_val);	
							var val=parseFloat(sub_total)-(parseFloat(a)/parseFloat(TaxAmt));								
							}
					document.getElementById("discount_amt").value=Math.round(val* 100) / 100;
					var subtotal=Math.round((parseFloat(sub_total)-parseFloat(val))* 100) / 100;
							if(Taxchk==1)
						    {
					var tax_amt=0;
					document.getElementById("Tax_amt").value=0;	
							}
							else
							{
					var tax_amt=parseFloat(tax_val)*parseFloat(subtotal);
					document.getElementById("Tax_amt").value=Math.round(tax_amt* 100) / 100;	
							}	
					document.getElementById("gross_amt").value=Math.round((parseFloat(subtotal)+parseFloat(tax_amt))* 100) / 100;	
					if(document.getElementById("CashType").value==1)
								{
									document.getElementById("cashAmt").value=Math.round((parseFloat(subtotal)+parseFloat(tax_amt))* 100) / 100;	
								}
								else
								{
									document.getElementById("cashAmt").value=0;	
									document.getElementById("cardAmt").value=0;	
								}
											
					}									
			}
			else  if(b==2)
			{
				if(a==""){a=0;}else{a=a;}
				
				var sub_total=document.getElementById("sub_total").value;
				var discount_amt=(a/100)*parseFloat(sub_total);
				document.getElementById("discount_amt").value=Math.round(discount_amt* 100) / 100;
				
				
				var after_discount=parseFloat(sub_total)-parseFloat(discount_amt);
					
						if(Taxchk==1)
						{
							var Tax_amt=0;	
						}
						else
						{
				var Tax_amt=after_discount*parseFloat(tax_val);	
						}	
				document.getElementById("Tax_amt").value=Math.round(Tax_amt* 100) / 100;
													
				document.getElementById("gross_amt").value=Math.round((parseFloat(after_discount)+parseFloat(Tax_amt))* 100) / 100;
				document.getElementById("cashAmt").value=Math.round((parseFloat(after_discount)+parseFloat(Tax_amt))* 100) / 100;
			//frmMainAmt(val);			
			}				
			else
			{
				if(a==""){ a=0;} else {a=a;}
				var sub_total=document.getElementById("sub_total").value;
				var after_discount=parseFloat(sub_total)-parseFloat(a);
						if(Taxchk==1)
						{
							var Tax_amt=0;	
						}
						else
						{
				var Tax_amt=after_discount*parseFloat(tax_val);	
						}
				document.getElementById("Tax_amt").value=Math.round(Tax_amt* 100) / 100;
					
				document.getElementById("gross_amt").value=Math.round((parseFloat(after_discount)+parseFloat(Tax_amt))* 100) / 100;
				if(document.getElementById("CashType").value==1)
								{
									document.getElementById("cashAmt").value=Math.round((parseFloat(after_discount)+parseFloat(Tax_amt))* 100) / 100;
								}
								else
								{
									document.getElementById("cashAmt").value=0;	
									document.getElementById("cardAmt").value=0;	
								}
				
			
			//frmMainAmt(a);
			}
				
		}
		
			function frmnewtest(a)
			{
			window.open('item_master_add.php?id='+a);
			}	
			
		</script>
		
<?php /*?> <script src="jsfiles/bell_notification.js"></script><?php */?> 
<script>
$(document).ready(function(){
//////////////////// item list //////////////////////////
$(document).on('keydown', '.diseaseslist', function(event) {
  var id = this.id;
  var countno=id.replace("ItmeMaster",""); 
  var stype=document.getElementById('SalesType').value;
  var storeval=document.getElementById('Store').value;
  if($('#Ebill').prop('checked')) {
  var Taxchk=1;
} else {
	var Taxchk=0;
}
  // Initialize jQuery UI autocomplete
  $( '#'+id ).autocomplete({  
 // autoFocus: true,
   source: function( request, response ) {
    $.ajax({
     url: "getItemsSales.php",
     type: 'post',
     dataType: "json",	
     data: {
      search: request.term,request:1,salestype:stype,store:storeval,Taxchk:Taxchk,
     },
     success: function( data ) {
      response( data );
     }
    });
   },  
  focus: function( event, ui ) {   
  	$(this).val(ui.item.label); // display the selected text
	var userid = ui.item.value; // selected value	
	var AvgCost	= ui.item.AvgCost; // selected value
	var factor_val	= ui.item.factor_val; // selected value	
	var UnitDescr = ui.item.UnitDescr; // selected value	
	var UnitID = ui.item.UnitID; // selected value	
	var TaxPerc= ui.item.TaxPerc; // selected value	
	var ItemType= ui.item.ItemType; // selected value	
	var RetailPrice= ui.item.RetailPrice; // selected value	
	var WholesalePrice= ui.item.WholesalePrice; // selected value	
	var salestype= ui.item.salestype; // selected value	
	var sprice=ui.item.UnitAmount; // selected value	
	var spricevat=ui.item.TaxAmt; // selected value	
	var DivisionVal=ui.item.division_val; // selected value	
	var AmountwithTax=ui.item.AmountwithTax; // selected value		
	var qtyonhand=ui.item.qtyonhand;
	var BaseLastCost=ui.item.BaseLastCost;	
	var IncludeTax=ui.item.IncludeTax;		
	var Countnumbmer=document.getElementById("Countnumbmer").value;
	var s1=0;
	for(var i=0;i<=Countnumbmer;i++)
					{
						var ItemMastertxt = document.getElementsByName('ItemMaster[]');
						if(ItemMastertxt[i].value!="")
						{
							s1=s1+1;
						}
					}
						
					$('#total_item_selected').html(s1);	

	document.getElementById("ItemMasterID"+countno).value=userid;
	document.getElementById("AvgCost"+countno).value=AvgCost;
	document.getElementById("LastCost"+countno).value=BaseLastCost;
	document.getElementById("factor_val"+countno).value=factor_val;	
	document.getElementById("unit"+countno).value=UnitID;
	document.getElementById("unitDisplay"+countno).value=UnitDescr;
	if(document.getElementById('qty'+countno).value=="") { document.getElementById('qty'+countno).value=1; }
	document.getElementById('TaxPerc'+countno).value=TaxPerc;
	document.getElementById('ItemType'+countno).value=ItemType;
	document.getElementById('sprice_retail'+countno).value=RetailPrice;
	document.getElementById('sprice_whole'+countno).value=WholesalePrice;
	document.getElementById('sprice'+countno).value=sprice;
	document.getElementById('spricevat'+countno).value=spricevat;
	document.getElementById('division_val'+countno).value=DivisionVal;		
	document.getElementById('AmountwithTax'+countno).value=AmountwithTax;	
	document.getElementById('qtyonhand'+countno).value=qtyonhand;
	document.getElementById('IncludeTax'+countno).value=IncludeTax;
	var qty=document.getElementById('qty'+countno).value;	
	frminputboxprice2(qty,sprice,spricevat,TaxPerc,countno,AmountwithTax,DivisionVal);	
    return false;
	 },
	select: function (event, ui) {
				$(this).val(ui.item.label);					
            return false;
        } 
	
	 
	 
  });
 });
//////////////////// customer list //////////////////////////
 $(document).on('keydown', '.customerlist', function() {
   var id = this.id; 
  var countno=id.replace("CustomerNameDescr",""); 
  var StoreID=document.getElementById("Store").value; 
  // Initialize jQuery UI autocomplete
  $( '#'+id ).autocomplete({
   source: function( request, response ) {
    $.ajax({
     url: "getCustomerList.php",
     type: 'post',
     dataType: "json",
     data: {
      search: request.term,request:1,Store:StoreID
     },
     success: function( data ) {
      response( data );
     }
    });
   },   
  focus: function( event, ui ) {   
  	$(this).val(ui.item.label); // display the selected text
	var userid = ui.item.value; // selected value
	var CustomerType= ui.item.CustomerType; // selected value
	var customer_name= ui.item.customer_name; // selected value
	var payment_terms= ui.item.payment_terms; // selected value
	//var customer_contact1= ui.item.customer_contact1; // selected value
	//var email_id= ui.item.email_id; // selected value
	//var CustomerBranchID= ui.item.CustomerBranchID; // selected value
	document.getElementById("CustomerName").value=userid;	
	//document.getElementById("CustomerBranchID").value=CustomerBranchID;
	document.getElementById("CashType").value=CustomerType;		
		if(CustomerType==1)
		{
			$("#cashAmt").prop("readonly", false);
			$("#cardAmt").prop("readonly", false);
			
		}
		else
		{
			$("#cashAmt").val(0);
			$("#cardAmt").val(0);
			$("#cashAmt").prop("readonly", true);
			$("#cardAmt").prop("readonly", true);
		}		
		document.getElementById("PaymentTerm").value=CustomerType;			
		document.getElementById("DueDate").value=duedate;	
    return false;
	 },
	select: function (event, ui) {
				$(this).val(ui.item.label);	
            return false;
        } 
	
	 
	 
  });
 });
 
 
 
 
/*bell_notification();
step_admin_logout();*/
});
</script>
<script type="text/javascript">
function frmAddItem(a,countno,b,c)
{
	var stype=document.getElementById('SalesType').value;
	var storeval=document.getElementById('Store').value;
	if($('#Ebill').prop('checked')) {
  var Taxchk=1;
} else {
	var Taxchk=0;
}
	queryString = 'code='+a+'&itemext='+b+'&unitext='+c+'&salestype='+stype+'&store='+storeval+'&Taxchk='+Taxchk;
	jQuery.ajax({
	url: "ajax_AddItemBarcode.php",
	data:queryString,
	type: "POST",
	dataType: 'json',
	success:function(data){
	var len = data.length;						
												 var sprice=data[0]['UnitAmount'];
												 var spricevat=data[0]['TaxAmt'];
												 var TaxPerc=data[0]['TaxPerc'];   
												 var AmountwithTax=data[0]['AmountwithTax'];   
												 var DivisionVal=data[0]['division_val'];
												 var RetailPrice= data[0]['RetailPrice'];
												 var WholesalePrice= data[0]['WholesalePrice'];
												 var IncludeTax=data[0]['IncludeTax'];
												 
												document.getElementById("ItmeMaster"+countno).value=data[0]['label'];
												document.getElementById("ItemMasterID"+countno).value=data[0]['value'];
												document.getElementById("AvgCost"+countno).value=data[0]['AvgCost'];
												document.getElementById("LastCost"+countno).value=data[0]['BaseLastCost'];
												document.getElementById("factor_val"+countno).value=data[0]['factor_val'];
												document.getElementById("unit"+countno).value=data[0]['UnitID'];
												document.getElementById("unitDisplay"+countno).value=data[0]['UnitDescr'];
												if(document.getElementById('qty'+countno).value=="") { document.getElementById('qty'+countno).value=1; }
												var qty=document.getElementById('qty'+countno).value;	
												document.getElementById('sprice'+countno).value=sprice;
												document.getElementById('spricevat'+countno).value=spricevat;
												document.getElementById('TaxPerc'+countno).value=TaxPerc;
												document.getElementById('ItemType'+countno).value=data[0]['ItemType'];
												document.getElementById('sprice_retail'+countno).value=RetailPrice;
												document.getElementById('sprice_whole'+countno).value=WholesalePrice;
												document.getElementById('qtyonhand'+countno).value=data[0]['qtyonhand'];
												document.getElementById('AmountwithTax'+countno).value=AmountwithTax;
												document.getElementById('division_val'+countno).value=DivisionVal;		
												document.getElementById('IncludeTax'+countno).value=IncludeTax;
									frminputboxprice2(qty,sprice,spricevat,TaxPerc,countno,AmountwithTax,DivisionVal);	
									
									
		
						  }		
	    });
}
</script>
<!--<script language="javascript">-->
<script type="text/javascript">
		$('.form-control').keydown(function (e) {		
     	if (e.which === 13) {						     		
            var index = $('.form-control').index(this) + 1;
             $('.form-control').eq(index).focus();			 
			 
     	}
    	 });
		function frmsavecustomer(a,b,c,d)
		{
					if(a=="")
					{
					alert("Enter Customer Code");
					document.getElementById('CustomerID').focus();
					return false;
					}
					if(b=="")
					{
					alert("Enter Customer Name");
					document.getElementById('Customer_descr_en').focus();
					return false;
					}
				$.ajax({
					type:'POST',
					dataType: 'json',
					url:'ajaxData_SaveNewCustomer.php',
					data:'code='+encodeURIComponent(a)+'&&fname='+encodeURIComponent(b)+'&&sname='+encodeURIComponent(c)+'&&aname='+encodeURIComponent(d),
					success:function(data){	
											var len = data.length;																
							$('#CustomerName').val(data[0]['customerid']);							
							$('#CustomerNameDescr').val(data[0]['displayvalue']);	
						//$('#CustomerName').html(html);					
					}
				 }); 
		}	
		function frmSalesCategory(a)
		{
			if(a==2)
			{
				document.getElementById("RFR").style.display='block';
				jQuery.ajax({
				url: "ajax_SalesInvoice_No.php",
				data:'type='+a,
				type: "POST",
				dataType: 'json',
				success:function(data){	
				var len = data.length;				
				//document.getElementById('OrderNumber').value=data[0]['next_number'];
				document.getElementById('OrderNumber').value="";
									}
            							});

			}
			else
			{
				document.getElementById("RFR").style.display='none';
			}
							
		}
		
</script>	

<script type="text/javascript">
function frmSalesType(a)
		{
var Countnumbmer=document.getElementById("Countnumbmer").value;	
					for(var i=0;i<=Countnumbmer;i++)
					{
							var ItemMastertxt=document.getElementsByName('ItemMasterID[]');
							var unittxt=document.getElementsByName('unit[]');
							var unitPrice=document.getElementsByName('sprice[]');
							var spricevat=document.getElementsByName('spricevat[]');
							var qty=document.getElementsByName('qty[]');
							var TaxPerc=document.getElementsByName('TaxPerc[]');
							
							var amount=document.getElementsByName('amount[]');
							var taxamt_unit=document.getElementsByName('taxamt_unit[]');	
										
							var sprice_retailtxt=document.getElementsByName('sprice_retail[]');
							var sprice_wholetxt=document.getElementsByName('sprice_whole[]');
							
							var division_val=document.getElementsByName('division_val[]');
							
							var IncludeTax=document.getElementsByName('IncludeTax[]');
							
							var AmountwithTax=document.getElementsByName('AmountwithTax[]');
								
										if(unittxt[i].value!="")
										{
										
								if(a==1)
								{
											
											if(IncludeTax[i].value ==1)
											{
										unitPrice[i].value=(parseFloat(sprice_retailtxt[i].value)/parseFloat(division_val[i].value)).toFixed(2);	
										spricevat[i].value=(parseFloat(sprice_retailtxt[i].value)-parseFloat(unitPrice[i].value)).toFixed(2);
											}
											else
											{
										unitPrice[i].value=parseFloat(sprice_retailtxt[i].value).toFixed(2);	
										spricevat[i].value=(parseFloat(sprice_retailtxt[i].value)*(parseFloat(TaxPerc[i].value)/100)).toFixed(2);
											}
										
											
								}
								else
								{
											if(IncludeTax[i].value ==1)
											{
										unitPrice[i].value=(parseFloat(sprice_wholetxt[i].value)/parseFloat(division_val[i].value)).toFixed(2);	
										spricevat[i].value=(parseFloat(sprice_wholetxt[i].value)-parseFloat(unitPrice[i].value)).toFixed(2);
											}
											else
											{
										unitPrice[i].value=parseFloat(sprice_wholetxt[i].value).toFixed(2);	
										spricevat[i].value=(parseFloat(sprice_wholetxt[i].value)*(parseFloat(TaxPerc[i].value)/100)).toFixed(2);
											}
										
								}
								taxamt_unit[i].value=(parseFloat(spricevat[i].value)* parseFloat(qty[i].value)).toFixed(2);																			
								amount[i].value=((parseFloat(spricevat[i].value)+parseFloat(unitPrice[i].value))*parseFloat(qty[i].value)).toFixed(2);
										
										}
										
							
					
					}					
					frmAmt();	
					if($('#Ebill').prop('checked')) 
											{
					$("#Ebill").prop("checked", false);
											}
							
		}		
		function frmETaxBill()
		{
											if($('#Ebill').prop('checked')) 
											{
											var Taxchk=1;
											} else {
												var Taxchk=0;
											}										
					var Countnumbmer=document.getElementById("Countnumbmer").value;						
					for(var i=0;i<=Countnumbmer;i++)
					{
							var ItemMastertxt=document.getElementsByName('ItemMasterID[]');
							var unittxt=document.getElementsByName('unit[]');
							var unitPrice=document.getElementsByName('sprice[]');
							var spricevat=document.getElementsByName('spricevat[]');
							var qty=document.getElementsByName('qty[]');
							var TaxPerc=document.getElementsByName('TaxPerc[]');
							
							var amount=document.getElementsByName('amount[]');
							var taxamt_unit=document.getElementsByName('taxamt_unit[]');	
										
							var sprice_retailtxt=document.getElementsByName('sprice_retail[]');
							var sprice_wholetxt=document.getElementsByName('sprice_whole[]');
							
							var division_val=document.getElementsByName('division_val[]');
							
							var IncludeTax=document.getElementsByName('IncludeTax[]');
							
							var AmountwithTax=document.getElementsByName('AmountwithTax[]');
							
										if(unittxt[i].value!="")
										{										
								
											if(IncludeTax[i].value ==1)
											{
												
												unitPrice[i].value=(parseFloat(sprice_retailtxt[i].value)/parseFloat(division_val[i].value)).toFixed(2);
													if(Taxchk==1)
													{
														spricevat[i].value=0;
													}
													else
													{
														spricevat[i].value=(parseFloat(sprice_retailtxt[i].value)-parseFloat(unitPrice[i].value)).toFixed(2);
													}
											
										
											}
											else
											{
                                                unitPrice[i].value=parseFloat(sprice_retailtxt[i].value).toFixed(2);	
													if (Taxchk==1)
												 	{
														spricevat[i].value=0;
                                                	}
													else
													{
														spricevat[i].value=(parseFloat(sprice_retailtxt[i].value)*(parseFloat(TaxPerc[i].value)/100)).toFixed(2);
													}
										
										
											}										
											
								
								taxamt_unit[i].value=(parseFloat(spricevat[i].value)* parseFloat(qty[i].value)).toFixed(2);																			
								amount[i].value=((parseFloat(spricevat[i].value)+parseFloat(unitPrice[i].value))*parseFloat(qty[i].value)).toFixed(2);
										
										}
										
							
					
					}					
					frmAmt();	
							
		}		
</script>	
<script>
function checkPhoneKey(key,a) {							
							if(key=="F9")
							{	
								$('#SalesModal').modal('toggle');	
								var itemno=document.getElementById("ItemMasterID"+a).value;
								var CustomerName=document.getElementById("CustomerName").value;

								jQuery.ajax({
										url: "ajax_LastSalesHistory.php",
										data:'itemid='+encodeURIComponent(itemno)+'&&CustomerName='+encodeURIComponent(CustomerName),								
										type: "POST",
										dataType: 'json',
										success:function(data){	
										var len = data.length;	
										$("#sales-item").html(data[0]['PurchaseList']);		
										$("#sales-item1").html(data[0]['SalesList']);												
																}
            							});

							// $('#CostModal').modal('toggle');
							// var itemno=document.getElementById("ItemMasterID"+a).value;
							// var CustomerName=document.getElementById("CustomerName").value;
							// var AvgCost=document.getElementById("AvgCost"+a).value;
							// var LastCost=document.getElementById("LastCost"+a).value;
							// var factor_val=document.getElementById("factor_val"+a).value;							
							// var finalcost=parseFloat(LastCost)*parseFloat(factor_val);
							// $('#Tprescription1').html(finalcost);
							// jQuery.ajax({
							// 			url: "ajax_LastSalesPrice.php",
							// 			data:'itemid='+encodeURIComponent(itemno)+'&&CustomerName='+encodeURIComponent(CustomerName),										
							// 			type: "POST",
							// 			dataType: 'json',
							// 			success:function(data){	
							// 			var len = data.length;	
							// 			$('#Tprescription2').html(data[0]['SalesPrice']);													
							// 									}
            				// 			});
																	
							// document.getElementById("Itemcost").value=a;
							// return false;		
							}
							if(key=="F2")
							{
								$('#PurchaseModal').modal('toggle');

								var itemno=document.getElementById("ItemMasterID"+a).value;

								jQuery.ajax({
										url: "ajax_LastPurchaseHistory.php",
										data:'itemid='+encodeURIComponent(itemno),										
										type: "POST",
										dataType: 'json',
										success:function(data){	
										var len = data.length;	
										$("#purchase-item").html(data[0]['PurchaseList']);												
																}
            							});

								

							}
							if(key=="F10")
							{
								var itemno=document.getElementById("ItemMasterID"+a).value;	
								var sno=document.getElementById("unit"+a).value;
								var stype=document.getElementById('SalesType').value;
								queryString = 'itemext='+itemno+'&unitext='+sno+'&salestype='+stype;																				
								
										jQuery.ajax({
										url: "ajax_changebasedonunit.php",
										data:queryString,
										type: "POST",
										dataType: 'json',
										success:function(data){
										var len = data.length;												
												 var sprice=data[0]['UnitAmount'];
												 var spricevat=data[0]['TaxAmt'];
												 var TaxPerc=data[0]['TaxPerc'];   
												 var AmountwithTax=data[0]['AmountwithTax'];   
												 var DivisionVal=data[0]['division_val'];
												 var RetailPrice= data[0]['RetailPrice'];
												 var WholesalePrice= data[0]['WholesalePrice'];
												 var IncludeTax=data[0]['IncludeTax'];
												 
												document.getElementById("ItmeMaster"+a).value=data[0]['label'];
												document.getElementById("ItemMasterID"+a).value=data[0]['value'];
												document.getElementById("AvgCost"+a).value=data[0]['AvgCost'];
												document.getElementById("LastCost"+a).value=data[0]['BaseLastCost'];
												document.getElementById("factor_val"+a).value=data[0]['factor_val'];
												document.getElementById("unit"+a).value=data[0]['UnitID'];
												document.getElementById("unitDisplay"+a).value=data[0]['UnitDescr'];
												if(document.getElementById('qty'+a).value=="") { document.getElementById('qty'+a).value=1; }
												var qty=document.getElementById('qty'+a).value;	
												document.getElementById('sprice'+a).value=sprice;
												document.getElementById('spricevat'+a).value=spricevat;
												document.getElementById('TaxPerc'+a).value=TaxPerc;
												document.getElementById('ItemType'+a).value=data[0]['ItemType'];
												document.getElementById('sprice_retail'+a).value=RetailPrice;
												document.getElementById('sprice_whole'+a).value=WholesalePrice;
												document.getElementById('qtyonhand'+a).value=data[0]['qtyonhand'];
												document.getElementById('AmountwithTax'+a).value=AmountwithTax;
												document.getElementById('division_val'+a).value=DivisionVal;		
												document.getElementById('IncludeTax'+a).value=IncludeTax;
												frminputboxprice2(qty,sprice,spricevat,TaxPerc,a,AmountwithTax,DivisionVal);
		
						  }		
	  								  });
		
							}														
 
}
function checkPhoneKeySummary(key)
{
							if(key=="F9")
							{							
							$('#CostModalSummary').modal('toggle');		
								var Countnumbmer=document.getElementById("Countnumbmer").value;	
									var finalcost=0;
									var avgcostval=0;									
									var factorval=0;
									
									for(var i=0;i<=Countnumbmer;i++)
									{
									var AvgCosttxt=document.getElementsByName('AvgCost[]');
									var factor_valtxt=document.getElementsByName('factor_val[]');
									if(parseFloat(AvgCosttxt[i].value) >0){ avgcostval=AvgCosttxt[i].value; } else { avgcostval=0; }
									if(parseFloat(factor_valtxt[i].value) >0){ factorval=factor_valtxt[i].value; } else { factorval=0; }
									var finalcost=finalcost+(factorval*avgcostval);
									}
									$('#Tprescription3').html(finalcost);
									
							
							return false;		
							}	
}
function frmClose(a)
{
var qtytxt = document.getElementsByName('qty[]');
qtytxt[a].focus();
}
function frmCashTypeChange(a)
{
	if(a==1)
		{
			$("#cashAmt").prop("readonly", false);
			$("#cardAmt").prop("readonly", false);
			
		}
		else
		{
			$("#cashAmt").val(0);
			$("#cardAmt").val(0);
			$("#cashAmt").prop("readonly", true);
			$("#cardAmt").prop("readonly", true);
		}	
}
function frmPaymentTermChange(a,b)
{
	queryString = 'itemext='+a+'&&cdate='+b;		
									jQuery.ajax({
										url: "ajax_getduedate.php",
										data:queryString,
										type: "POST",
										dataType: 'json',
										success:function(data){
										var len = data.length;
												document.getElementById("DueDate").value=data[0]['value'];
						  									  }		
	  								  });
}
</script>
		
</body>
</html>
