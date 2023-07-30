<?php
//--------------------------------------------------------------------------------------
function GetProfitandLoss($fromdate,$todate)
{
	global $link;

	$Tincome=0;
		$qry=mysqli_query($link,"select * from ac_chart_class where ctype=4");
					while($obj=mysqli_fetch_array($qry))
					{
					$id=$obj['cid'];
					$class_name=$obj['class_name'];
          $class_name_ar=$obj['class_name_ar'];
					$ctype=ClassType($obj->ctype);

				$query_account = mysqli_query($link,"select sum(dr+cr) from ac_gl_trans where ac_chart_class_cid='$id' and tran_date between '$fromdate' and '$todate'");
      	$obj_account=mysqli_fetch_array($query_account);
				if($obj_account[0]>0){$Rdr=$obj_account[0]; }else{$Rdr=0;}		
        $Tincome=$Tincome+$Rdr;
        			}

        			 $Texpense=0;
		$qry=mysqli_query($link,"select * from ac_chart_class where ctype=6");
					while($obj=mysqli_fetch_array($qry))
					{
					$id=$obj['cid'];
					$class_name=$obj['class_name'];
          $class_name_ar=$obj['class_name_ar'];
					$ctype=ClassType($obj->ctype);

					$query_account1 = mysqli_query($link,"select sum(dr+cr) from ac_gl_trans where ac_chart_class_cid='$id' and tran_date between '$fromdate' and '$todate'");
				$obj_account1=mysqli_fetch_array($query_account1);
				if($obj_account1[0]>0){$Edr=$obj_account1[0];}else{$Edr=0;}
         $Texpense= $Texpense+$Edr;
     				}

	return $Tincome-$Texpense;
}
?>