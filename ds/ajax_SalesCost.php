<?php
session_start();
include("../conn.php");
$id = $_POST['id'];
$qry3=mysqli_query($link,"select height,Weight,BMI,Pressure,Pulse,Temp,Resp,Waist,Hip,Complaints,Investigation,Findings,Remarks,patientID from patient_recored where id='$id'");
$obj3=mysqli_fetch_array($qry3);
				if($obj3['height']==""){$height="";} else{ $height=$obj3['height']; }
				if($obj3['Weight']==""){$Weight="";} else{ $Weight=$obj3['Weight']; }
				if($obj3['BMI']==""){$BMI="";} else{ $BMI=$obj3['BMI']; }
				if($obj3['Pressure']==""){$Pressure="";} else{ $Pressure=$obj3['Pressure']; }
				if($obj3['Pulse']==""){$Pulse="";} else{ $Pulse=$obj3['Pulse']; }
				if($obj3['Temp']==""){$Temp="";} else{ $Temp=$obj3['Temp']; }
				if($obj3['Resp']==""){$Resp="";} else{ $Resp=$obj3['Resp']; }
				if($obj3['Waist']==""){$Waist="";} else{ $Waist=$obj3['Waist']; }
				if($obj3['Hip']==""){$Hip="";} else{ $Hip=$obj3['Hip']; }
				if($obj3['Complaints']==""){$Complaints="";} else{ $Complaints=$obj3['Complaints']; }
				if($obj3['Investigation']==""){$Investigation="";} else{ $Investigation=$obj3['Investigation']; }
				if($obj3['Findings']==""){$Findings="";} else{ $Findings=$obj3['Findings']; }
				if($obj3['Remarks']==""){$Remarks="";} else{ $Remarks=$obj3['Remarks']; }
				
				$patientID=$obj3['patientID'];
				
	$s="";
	$qry4=mysqli_query($link,"select * from patient_prescription where patient_recored_id='$id'");
	while($obj4=mysqli_fetch_array($qry4))
	{	
	$s=$s.$obj4['prescription']."<br>";			
	}
	
	$dis="";
	$qry5=mysqli_query($link,"select B.descr from patient_diseases as A left join diseases_list as B on B.id=A.diseases_id left join patient_recored as C on C.id=A.patient_recored_id where C.patientID='$patientID'");
	while($obj5=mysqli_fetch_array($qry5))
	{	
	$dis=$dis.$obj5[0]."<br>";		
	}
	
	$med="";
	$a_medication=mysqli_query($link,"select B.descr from patient_medication as A left join medication_list as B on A.medication_id=B.id left join patient_recored as C on C.id=A.patient_recored_id where C.patientID='$patientID'");														
	while($a_row_medication=mysqli_fetch_array($a_medication))
	{
	$med=$med.$a_row_medication[0]."<br>";		
	}
	$Sur="";
	$a_surgery=mysqli_query($link,"select B.descr from patient_surgery as A left join patient_surgery_history as B on A.surgery_id=B.id left join patient_recored as C on C.id=A.patient_recored_id where C.patientID='$patientID'");																									
	while($a_row_surgery=mysqli_fetch_array($a_surgery))
	{
	$Sur=$Sur.$a_row_surgery[0]."<br>";		
	}
	
	$Oth="";
	$a_others=mysqli_query($link,"select others from patient_others as A left join patient_recored as C on C.id=A.patient_recored_id where C.patientID='$patientID'");																																				
	while($a_row_others=mysqli_fetch_array($a_others))
	{
	$Oth=$Oth.$a_row_others[0]."<br>";		
	}
	
								
$users_arr = array();
$users_arr[] = array("height" => $height,"Weight" => $Weight,"BMI" => $BMI,"Pressure" => $Pressure,"Pulse" => $Pulse,"Temp" => $Temp,"Resp" => $Resp,"Waist" => $Waist,"Hip" => $Hip,"Complaints" => $Complaints,"Investigation" => $Investigation,"Findings" => $Findings,"Remarks" => $Remarks ,"Tprescription" => $s,"diseases_ret" => $dis,"medication_ret" => $med,"surgery_ret" => $Sur,"others_ret" => $Oth);
echo json_encode($users_arr);
?>