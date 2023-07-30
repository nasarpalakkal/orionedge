<?php
//--------------------------------------------------------------------------------------
function ClassType($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT descr,descr_ar FROM ac_class_type WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function ClassName($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT class_name,class_name_ar FROM ac_chart_class WHERE  cid='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function ChartTypeName($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT name,name_ar FROM ac_chart_types WHERE  id='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function GetAccountName($stock_id)
{
	global $link;
	$query = mysqli_query($link,"SELECT * FROM ac_chart_master WHERE  account_code='$stock_id'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function OpenPeriod()
{
	global $link;
	$query = mysqli_query($link,"select begin,end from fiscal_year where closed='0'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function CheckOpenPeriod($stock_id)
{
	global $link;
	$query = mysqli_query($link,"select closed from ac_period where '$stock_id' BETWEEN begin and end ");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function CurrencyRate($code)
{
	global $link;
	$query = mysqli_query($link,"select exchange_rate from currency where code='$code'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function DefaultCurrency()
{
	global $link;
	$query = mysqli_query($link,"select code from currency where def_status='1'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function FindCurrentPeriod($code)
{
	global $link;
	$query = mysqli_query($link,"select * from ac_period where begin  between '$code' and '$code'");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function FindPeriodBegingDate($code)
{
	global $link;
	$query = mysqli_query($link,"SELECT * FROM ac_period where fisical_year='$code' order by begin asc LIMIT 1");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
function GetCurrentFiscalYear()
{
	global $link;
	$query = mysqli_query($link,"SELECT financial_year FROM company ");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function GetFiscalYear($code)
{
	global $link;
	$query = mysqli_query($link,"SELECT id FROM fiscal_year where '$code' BETWEEN begin and end ");
	$myrow = mysqli_fetch_array($query);	
	return $myrow[0];
}
function GetJournalPost($code)
{
	global $link;
	$query = mysqli_query($link,"SELECT posting FROM ac_journal where trans_no='$code' ");
	$myrow = mysqli_fetch_array($query);	
	return $myrow;
}
?>