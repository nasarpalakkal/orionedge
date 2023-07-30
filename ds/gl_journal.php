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
require_once 'multilanguage.php';
$_SESSION['lang']=$RELanguage;
include("db/gl_db.php");
include("db/workconfig.php");
$useraccess=workconfigDetail($RoleID,701);

$id=$_REQUEST['id'];	
$ls=$_REQUEST['ls'];
$lsid=$_REQUEST['lsid'];
	if($id!='')
	{
	$qry_ret=mysqli_query($link,"select * from ac_journal where trans_no='$id'");
	$obj_ret=mysqli_fetch_array($qry_ret);
	$tran_date=date('d-m-Y',strtotime($obj_ret['tran_date']));
	if($obj_ret['posting_date']==''){$PostDate='';} else{$PostDate=date('d-m-Y',strtotime($obj_ret['posting_date']));}
	$currency=$obj_ret['currency'];
	if($obj_ret['doc_date']==''){$DocumentDate='';}else {$DocumentDate=date('d-m-Y',strtotime($obj_ret['doc_date']));}
	if($obj_ret['event_date']==''){$EventDate='';}else{$EventDate=date('d-m-Y',strtotime($obj_ret['event_date']));}
	$Sourceref=$obj_ret['source_ref'];
	$Reference=$obj_ret['reference'];
	$posting=$obj_ret['posting'];
	$PONumber=$obj_ret['PONumber'];
		$qry_ret_sum=mysqli_query($link,"select ROUND(sum(dr),2),ROUND(sum(cr),2) from ac_journal_list where trans_no='$id'");
		$obj_ret_sum=mysqli_fetch_array($qry_ret_sum);
		$net_amt_dr=$obj_ret_sum[0];
		$net_amt_cr=$obj_ret_sum[1];
		$MainMemo=$obj_ret['memo'];
		$qry_journal_list=mysqli_query($link,"select * from ac_journal_list where trans_no='$id'");
		$nos_journal_list=mysqli_num_rows($qry_journal_list);
		$ctno=$nos_journal_list;
	}	
	else
	{
	$tran_date=date('d-m-Y');		
	$currency="SAR";
	$DocumentDate=date('d-m-Y');
	$EventDate=date('d-m-Y');
	$Sourceref="";
	$Reference="";
	$MainMemo="";
	$ctno=0;
	$PONumber="";
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
  function frmcancel()
  {
  var listview=document.getElementById("listview").value;
  var listviewid=document.getElementById("listviewid").value;
  	
 window.location='gl_journal_list.php';	
	
	
  }	 
function frmvalid()
{
	var Countnumbmer=document.getElementById("Countnumbmer").value;
	var TotalDebit=0;
	var TotalCredit=0;	
	for(var i=0;i<=Countnumbmer;i++)
	{
							
							var Accounttxt = document.getElementsByName('SalesAccount[]');
							var Debittxt = document.getElementsByName('Debit[]');
							var Credittxt = document.getElementsByName('Credit[]');

							if(Accounttxt[i].value=="")
							{
							alert('<?php echo gettext("You must select GL account"); ?>');
							Accounttxt[i].focus();
							return false;
							}	
														
							var DebitVal=Debittxt[i].value;
							var DebitVal1=DebitVal.replace(/(\d+),(?=\d{3}(\D|$))/g, "$1");


							var CreditVal=Credittxt[i].value;
							var CreditVal1=CreditVal.replace(/(\d+),(?=\d{3}(\D|$))/g, "$1");
							
							if(parseFloat(DebitVal1)>0)
							{
							var TotalDebit=(parseFloat(TotalDebit)+parseFloat(DebitVal1)).toFixed(2);							
							}
							if(parseFloat(CreditVal1)>0)
							{
							var TotalCredit=(parseFloat(TotalCredit)+parseFloat(CreditVal1)).toFixed(2);							
							}	

	}
	
		if(parseFloat(document.getElementById("gross_amt_debit").value)!=parseFloat(document.getElementById("gross_amt_credit").value))
		{
		alert('<?php echo gettext("Debit Amount And Credit Account are not matching"); ?>');
		return false;
		}
		
		
}
function frmPost()
{
	if(document.getElementById("PostDate").value=="")
	{
	alert("Select Post Date");
	document.getElementById("PostDate").focus();
	return false;
	}
window.location='gl_journal_post.php?id=<?php echo $id; ?>&&PD='+document.getElementById("PostDate").value;
}
function frmUnPost()
{
	var c=confirm('<?php echo gettext("You want to un-post ?"); ?>');
 	if(c==true)
	{
	
	if(document.getElementById("PostDate").value=="")
	{
	alert("Select Post Date");
	document.getElementById("PostDate").focus();
	return false;
	}

	var id=document.getElementById("eid").value; 
	 window.location='gl_journal_unpost.php?id='+id;
 	}
	else
	{
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
	   <input type="hidden" name="menuid" id="menuid" value="gl_journal.php">
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 <?php if($RELanguage=="ar_SA"){ ?>dir="rtl" <?php } else { ?> dir="ltr"<?php } ?>>
         <?php echo gettext("Journal Entry"); ?>
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
if(isset($_REQUEST['Psuccess']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"3\"><strong>".gettext("Journal Posted Sucessfully")."</strong></font></div>";
}
if(isset($_REQUEST['save']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"3\"><strong>".gettext("Journal Entry Saved Sucessfully")."</strong></font></div>";
}
if(isset($_REQUEST['update']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"green\" size=\"1\"><strong>".gettext("Account Updated Successfully")."</strong></font></div>";
}
if(isset($_REQUEST['perror']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"3\"><strong>".gettext("Journal Period is Closed")."</strong></font></div>";
}
if(isset($_REQUEST['perror1']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"3\"><strong>".gettext("Period not defined")."</strong></font></div>";
}
if(isset($_REQUEST['Berror']))
{
echo "<div id=\"black\" align=\"center\"><font color=\"red\" size=\"3\"><strong>".gettext("Debit Amount And Credit Account are not matching")."</strong></font></div>";
}
?>

              <form class="form-horizontal" name="frmdepartment" method="post" action="gl_journal_action.php">			  
<input type="hidden" name="Countnumbmer" id="Countnumbmer" value="<?php echo $ctno; ?>">
<input type="hidden" name="eid" id="eid" value="<?php echo $id; ?>">
<input type="hidden" name="listview" id="listview" value="<?php echo $ls; ?>">
<input type="hidden" name="listviewid" id="listviewid" value="<?php echo $lsid; ?>">
	 <div class="box-body">
			   <div class="form-group">
                  			 <div class="col-sm-6">
							 <label ><?php echo gettext("Journal Number"); ?></label>
							  
							  <input type="text" class="form-control" id="PONumber" name="PONumber" value="<?php echo $PONumber; ?>" readonly>
							  
						   </div>       
						   <div class="col-sm-6">
						   	<label ><?php echo gettext("Journal Date"); ?></label>
							  
							  <input type="text" class="form-control" id="JournalDate" name="JournalDate" value="<?php echo $tran_date; ?>">
							 <input type="hidden" class="form-control" id="Currency" name="Currency" value="SAR">
						   </div>
                  </div>
				  
				  <div class="form-group">
                  			 <div class="col-sm-6">
							 <label ><?php echo gettext("Document Date"); ?></label>
							    <input type="text" class="form-control" id="DocumentDate" name="DocumentDate" value="<?php echo $DocumentDate; ?>" placeholder="<?php echo gettext("Document Date"); ?>">
						   </div>       
						   <div class="col-sm-6">
							 <label ><?php echo gettext("Event Date"); ?></label>
							   <input type="text" class="form-control" id="EventDate" name="EventDate" value="<?php echo $EventDate; ?>" placeholder="<?php echo gettext("Event Date"); ?>">
						   </div>
						   
                  </div>
				  
				  <div class="form-group">
				 			 <div class="col-sm-6">
							 <label ><?php echo gettext("Reference"); ?></label>
							  <input type="text" class="form-control" id="Reference" name="Reference" placeholder="<?php echo gettext("Reference"); ?>" value="<?php echo $Reference; ?>" autocomplete="off">
								
						   </div> 
						    <div class="col-sm-6">
							 	 <label ><?php echo gettext("Source ref"); ?></label>
						<input type="text" class="form-control" id="Sourceref" name="Sourceref" placeholder="<?php echo gettext("Source ref"); ?>" value="<?php echo $Sourceref; ?>" autocomplete="off">						  
						   </div>      
						   
                  </div>
				  
				 
				  
				
				</div> <!---- End General GL-->
				
 <div class="box-body">
 <h4><?php echo gettext("Rows"); ?></h4>
  <table class="table order-listmain" style="width:100%" id="myTable">
													<thead class="bordered-darkorange">
														<tr role="row"> 																										
															<th width="35%"><?php echo gettext("GL Account"); ?></th>
															<th width="35%"><?php echo gettext("Memo"); ?></th>
															<th width="13%"><?php echo gettext("Debit"); ?></th>
															<th width="13%"><?php echo gettext("Credit"); ?></th>
															<th width="4%"><?php echo gettext("Action"); ?></th>
														</tr>
													</thead>
													
																<?php 
																$a=mysqli_query($link,"select * from ac_journal_list where trans_no='$id'");
																if(!mysqli_num_rows($a))
																{
																?>
																
														<tr>
														<td width="35%">
																<select class="select2" style="width:100%" name="SalesAccount[]" id="SalesAccount0">
																								 <option value="" ><?php echo gettext("Select GL Account"); ?></option>
																								 <?php 
																								$qry=mysqli_query($link,"SELECT id,name,name_ar FROM ac_chart_types order by class_id");
																								while($r=mysqli_fetch_array($qry))
																								{
																								$parentid=$r['id'];
																									$qry1=mysqli_query($link,"SELECT account_code,account_name,account_name_ar FROM ac_chart_master where account_type='$parentid'");
																									$nos1=mysqli_num_rows($qry1);
																									if($nos1>0)
																									{	
																							$parentName=$r['name']." ".$r['name_ar'];
																								
																								?>
																				   <optgroup label="<?php echo $parentName; ?>">
																								<?php 
																								while($r1=mysqli_fetch_array($qry1))
																								{
																								$childid=$r1['account_code'];
																								$childName=$r1['account_name']." ".$r1['account_name_ar']; 

																								?>	
																					   <option value="<?php echo $childid; ?>" ><?php echo $childid."--".$childName; ?></option>
																								<?php
																								}
																								}
																								}
																								?>
																					  
																				   </optgroup>
																				  </select>
														</td>	
													<td width="35%"><input type="text" class="form-control" maxlength="50" name="linememo[]" id="linememo0"  autocomplete="off"/></td>			
													<td width="13%"><input type="text" class="form-control" name="Debit[]" id="Debit0" value="" onKeyPress="return isNumber(event);" style="text-align:right;"  onKeyUp="frmKeyEnter(1,1)" onChange="frminputboxprice()" autocomplete="off"/></td>
													<td width="13%"><input type="text" class="form-control" name="Credit[]" id="Credit0" value="" onKeyPress="return isNumber(event);" style="text-align:right;"  onKeyUp="frmKeyEnter(1,2)" onChange="frminputboxprice1()" autocomplete="off"/></td>
													<td width="4%"><input type="button" class="ibtnDelMain btn btn-xs btn-danger "  value="<?php echo gettext("Delete"); ?>"></td>
													
													</tr>
														
													
													
			  
			   													<?php 
																}
																else{
															$i=0;
															$s=0;
															while($a_row=mysqli_fetch_array($a))
															{
															$account=$a_row['account'];
															$dr=$a_row['dr'];															
															$cr=$a_row['cr'];
															$businessunitid_ret=$a_row['dimension_id'];
															$cost_center_ret=$a_row['dimension2_id'];
															$project_ret=$a_row['dimension3_id'];
															$linememo=$a_row['linememo'];
																?>
																
																
																
																<tr>
														<td width="35%">
																<select class="select2" style="width:100%" name="SalesAccount[]" id="SalesAccount<?php echo $i; ?>">
																								 <option value="" ><?php echo gettext("Select GL Account"); ?></option>
																								 <?php 
																								$qry=mysqli_query($link,"SELECT id,name,name_ar FROM ac_chart_types order by class_id");
																								while($r=mysqli_fetch_array($qry))
																								{
																								$parentid=$r['id'];
																									$qry1=mysqli_query($link,"SELECT account_code,account_name,account_name_ar FROM ac_chart_master where account_type='$parentid'");
																									$nos1=mysqli_num_rows($qry1);
																									if($nos1>0)
																									{	
																			 $parentName=$r['name']."-".$r['name_ar']; 
																								?>
																				   <optgroup label="<?php echo $parentName; ?>">
																								<?php 
																								while($r1=mysqli_fetch_array($qry1))
																								{
																								$childid=$r1['account_code'];
  																								$childName=$r1['account_name']."-".$r1['account_name_ar'];
																								?>	
																					   <option value="<?php echo $childid; ?>" <?php if($account==$childid){ echo 'selected="selected"'; } ?>><?php echo $childid."--".$childName; ?></option>
																								<?php
																								}
																								}
																								}
																								?>
																					  
																				   </optgroup>
																				  </select>
														</td>
															
														<td width="35%"><input type="text" class="form-control" maxlength="50" name="linememo[]" id="linememo<?php echo $i; ?>" value="<?php echo $linememo; ?>" autocomplete="off"/></td>			
																
													
													
													<td width="13%"><input type="text" class="form-control" name="Debit[]" id="Debit<?php echo $i; ?>" value="<?php echo $dr; ?>" onKeyPress="return isNumber(event);" style="text-align:right;"  onKeyUp="frmKeyEnter(0,1)" onChange="frminputboxprice()" autocomplete="off" <?php if($cr>0){?> readonly="" <?php } ?>/></td>
													<td width="13%"><input type="text" class="form-control" name="Credit[]" id="Credit<?php echo $i; ?>" value="<?php echo $cr; ?>" onKeyPress="return isNumber(event);" style="text-align:right;"  onKeyUp="frmKeyEnter(0,2)" onChange="frminputboxprice1()" autocomplete="off" <?php if($dr>0){?> readonly="" <?php } ?>/></td>
													<td width="4%"><input type="button" class="ibtnDelMain btn btn-xs btn-danger "  value="<?php echo gettext("Delete"); ?>"></td>
													
													</tr>
													
													
													 <?php
															 $i++; 
															 $s++; 
															 }
														}
															 ?>
															 
									 </table>	
									 		
													 <table class="table table-bordered table-hover table-striped" style="width:100%" id="myTable1">
							<tr>
																<td width="35%" align="right">&nbsp;</td>														<td width="35%" align="right"><?php echo gettext("Total"); ?></td>
															    <td width="13%"><input type="text" id="gross_amt_debit" name="gross_amt_debit" value="<?php echo $net_amt_dr; ?>" class="form-control" readonly=""/></td>	
															    <td width="13%"><input type="text" id="gross_amt_credit" name="gross_amt_credit" value="<?php echo $net_amt_cr; ?>" class="form-control" readonly=""/></td>		
																<td width="4%">&nbsp;</td>																
															</tr>
							</table>
													
													
													 <table width="100%" border="0" id="myTable2">								
								<tr >
								<td align="left"><button type="button" class='addmore btn btn-xs btn-info' onClick="displayResult()" id="addmore">+ <?php echo gettext("Add More"); ?></button></td>
								</tr>
								
						</table>
						
						
 </div>				
				 <div class="box-body">
			   <div class="form-group">
                  			 <div class="col-sm-6">
							 <label ><?php echo gettext("Memo"); ?></label>
							  
							  <textarea class="form-control" id="MainMemo" name="MainMemo" placeholder="<?php echo gettext("Memo"); ?>"><?php echo $MainMemo; ?></textarea>
							  
						   </div>       
						   <div class="col-sm-6">	
						   <label ><?php echo gettext("Post Date"); ?></label>						
						   <input type="text" class="form-control" id="PostDate" name="PostDate" value="<?php echo $PostDate; ?>" <?php if($PostDate!=''){?> readonly="<?php } ?>">
						   </div>
                  </div>
				  </div>
				
              <!-- /.box-body -->
              <div class="box-footer">
			    <?php if($id!=""){?><button type="button" class="btn btn-default pull-left" onClick="frmcancel()"><?php echo gettext("Back"); ?></button><?php } ?>
               <?php if($useraccess['add_txt']>0 || $useraccess['edit_txt']>0){ if($posting==0){?> <button type="submit" class="btn btn-info pull-right" onClick="return frmvalid();"><?php echo gettext("Save"); ?></button><?php } }?>
			   <?php if($useraccess['add_txt']>0 || $useraccess['edit_txt']>0){  if($id!="" && $posting==0){?><button type="button" class="btn btn-success pull-left" onClick="return frmPost();"><?php echo gettext("Post to GL"); ?></button><?php } else if($posting==1){?><button type="button" class="btn btn-danger pull-left" onClick="return frmUnPost();"><?php echo gettext("Un Post"); ?></button> <?php } }?>              
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

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<?php include('Topbottom.php'); ?>


<script>
			
			var counter = 2;
			$("#addmore").on("click", function () {

			var vbval='<?php echo $RELanguage; ?>';
			var newRow = $("<tr>");
			var cols = "";
			var newval=counter-2;

					
			cols += '<td width="35%"><select class="select2" style="width:100%" name="SalesAccount[]" id="SalesAccount'+counter+'"><option value="" ><?php echo gettext("Select GL Account"); ?></option><?php  $qry=mysqli_query($link,"SELECT id,name,name_ar FROM ac_chart_types order by class_id"); while($r=mysqli_fetch_array($qry)) { $parentid=$r['id']; $qry1=mysqli_query($link,"SELECT account_code,account_name,account_name_ar FROM ac_chart_master where account_type='$parentid'"); $nos1=mysqli_num_rows($qry1); if($nos1>0) { $parentName=mysqli_real_escape_string($link,$r['name'])."-".mysqli_real_escape_string($link,$r['name_ar']);  ?> <optgroup label="<?php echo $parentName; ?>"><?php while($r1=mysqli_fetch_array($qry1)){$childid=$r1['account_code']; $childName=mysqli_real_escape_string($link,$r1['account_name'])."-".mysqli_real_escape_string($link,$r1['account_name_ar']);  ?> <option value="<?php echo $childid; ?>" <?php if($SalesAccount==$childid){ echo 'selected="selected"'; } ?>><?php echo $childid."--".$childName; ?></option><?php } } }?> </optgroup></select></td>';	
		 			

			cols += '<td width="35%"><input type="text" class="form-control" maxlength="50" name="linememo[]" id="linememo'+counter+'" autocomplete="off"/></td>';
			cols += '<td width="13%"><input type="text" class="form-control" name="Debit[]" id="Debit'+counter+'" value="" onKeyPress="return isNumber(event);" style="text-align:right;" onKeyUp="frmKeyEnter('+counter+',1)"  onChange="frminputboxprice()" autocomplete="off"/></td>';
			cols += '<td width="13%"><input type="text" class="form-control" name="Credit[]" id="Credit'+counter+'" value="" onKeyPress="return isNumber(event);" style="text-align:right;" onKeyUp="frmKeyEnter('+counter+',2)" onChange="frminputboxprice1()" autocomplete="off"/></td>';
			cols += '<td width="4%"><input type="button" class="ibtnDelMain btn btn-xs btn-danger "  value="<?php echo gettext("Delete"); ?>"></td>';
			newRow.append(cols);
			$("table.order-listmain").append(newRow);
			counter++;
			$('select').select2({
					width:"100%"	
				});
			
					var row_count = $('#myTable tr').length;
					document.getElementById("Countnumbmer").value=row_count-2;
						
				
			});
			
			$("table.order-listmain").on("click", ".ibtnDelMain", function (event) {
       		 $(this).closest("tr").remove();       
       		 counter -= 1;
				 var row_count = $('#myTable tr').length;				
				document.getElementById("Countnumbmer").value=row_count-1;
				frminputboxprice();
				frminputboxprice1();
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
			function frmKeyEnter(row,id){	
				if(id==1)
				{
					if(parseFloat(document.getElementById("Debit"+row).value)>0)
					{
				document.getElementById("Credit"+row).readOnly = true;
					}
					else
					{
				document.getElementById("Credit"+row).readOnly = false;
					}
				}
				
				if(id==2)
				{
					if(parseFloat(document.getElementById("Credit"+row).value)>0)
					{
				document.getElementById("Debit"+row).readOnly = true;
					}
					else
					{
				document.getElementById("Debit"+row).readOnly = false;
					}
				}
			}	
					//////////////////// calculate Dr Total ///////////////
			function frminputboxprice()
							{	
							var num=document.getElementById("Countnumbmer").value;
									// if(num==0)
									// {
									// document.getElementById("gross_amt_debit").value="";
									// }
									// else
									// {
							var TotalPrice=0;							
							for( var i = 0; i<=num; i++)
							{		
							var Tprice=document.getElementsByName('Debit[]');
								if(Tprice[i].value=="")
								{
								var DebitVal1=0;
								}
								else
								{
								var price=Tprice[i].value;
								var DebitVal1=price.replace(/(\d+),(?=\d{3}(\D|$))/g, "$1");
								}
							TotalPrice=(parseFloat(TotalPrice)+parseFloat(DebitVal1)).toFixed(2);
							document.getElementById("gross_amt_debit").value=TotalPrice;	
							}
									//}							
					     }	
						 
						 //////////////////// calculate Cr Total ///////////////
						 function frminputboxprice1()
							{							
							var num=document.getElementById("Countnumbmer").value;	
								// if(num==0)
								// {
								// document.getElementById("gross_amt_credit").value="";
								// }
								// else
								// {
							var TotalPrice=0;							
							for( var i = 0; i<=num; i++)
							{									
							var Tprice=document.getElementsByName('Credit[]');
								if(Tprice[i].value=="")
								{
								var CreditVal1=0;
								}
								else
								{
								var price=Tprice[i].value;
								var CreditVal1=price.replace(/(\d+),(?=\d{3}(\D|$))/g, "$1");
								}
							TotalPrice=(parseFloat(TotalPrice)+parseFloat(CreditVal1)).toFixed(2);
							document.getElementById("gross_amt_credit").value=TotalPrice;
							}
							//	}
							}
		</script>	
<!-- page script -->
<!-- jQuery 2.2.3 -->
<!-- Bootstrap 3.3.6 -->
<!-- Select2 -->

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
	$('#JournalDate').datepicker({
	format: 'dd-mm-yyyy',
      autoclose: true
    });
	$('#DocumentDate').datepicker({
	format: 'dd-mm-yyyy',
      autoclose: true
    });
	$('#EventDate').datepicker({
	format: 'dd-mm-yyyy',
      autoclose: true
    });
	$('#PostDate').datepicker({
	format: 'dd-mm-yyyy',
      autoclose: true
    });

 });
 
</script>

</body>
</html>