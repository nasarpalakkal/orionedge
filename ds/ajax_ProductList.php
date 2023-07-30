<?php
session_start();
$admid=$_SESSION['ADUSER'];
$RoleID=$_SESSION['RoleID'];
include("../conn.php");
if(!empty($_POST["Category"])) {
$a=$_POST["Category"];
				
		if($a=="DefualtButton")
		{
		$item = mysqli_query($link,"SELECT A.item_no,A.UploadImage,A.item_descr,B.retail_price as sprice FROM inventory as A LEFT JOIN inventory_uom as B on A.item_no=B.item_no and B.sno=0 where A.ShowInWarehouse=1");	
		while($obj_product=mysqli_fetch_array($item))
		{
		if($obj_product["category"]=="")
		{
						$file = 'item_image/'.$obj_product["UploadImage"];
						if($obj_product["UploadImage"])
						{
							if (file_exists($file)) {
								$imagedisplay=$file;
							} else {
								$imagedisplay='item_image/noimage.png';
							}
						}
						else
						{
							$imagedisplay='item_image/noimage.png';
						}
		?>
		<button type="button" data-name="Minion Hi" id="product-<?php echo $i; ?>" value='TOY01' class="btn btn-both btn-flat product responsive" onClick = "cartAction('add','<?php echo $obj_product["item_no"]; ?>')"><span class="bg-img"><img src="<?php echo $imagedisplay; ?>" style="width: 100%; height: 100%;"></span><span><?php echo $obj_product["item_descr"]; ?> <br> <?php echo "SR-".$obj_product["sprice"]; ?> </span><?php if($QtyDisplay==1){ ?> <span id="Updated_Qty<?php echo $obj_product["item_no"]; ?>"> <?php echo "(العدد (". $obj_product["qty"]; ?></span> <?php } ?> </span></button>
		<?php
		}
		}
		}
		else
		{
			$item = mysqli_query($link,"SELECT A.item_no,A.UploadImage,A.item_descr,B.retail_price as sprice FROM inventory as A LEFT JOIN inventory_uom as B on A.item_no=B.item_no and B.sno=0 where category='$a' and A.ShowInWarehouse=1");	
		while($obj_product=mysqli_fetch_array($item))
		{
		if($obj_product["category"]=="")
		{
						$file = 'item_image/'.$obj_product["UploadImage"];
						if($obj_product["UploadImage"])
						{
							if (file_exists($file)) {
								$imagedisplay=$file;
							} else {
								$imagedisplay='item_image/noimage.png';
							}
						}
						else
						{
							$imagedisplay='item_image/noimage.png';
						}
		?>
		<button type="button" data-name="Minion Hi" id="product-<?php echo $i; ?>" value='TOY01' class="btn btn-both btn-flat product responsive" onClick = "cartAction('add','<?php echo $obj_product["item_no"]; ?>')"><span class="bg-img"><img src="<?php echo $imagedisplay; ?>" style="width: 100%; height: 100%;"></span><span><?php echo $obj_product["item_descr"]; ?> <br> <?php echo "SR-".$obj_product["sprice"]; ?> </span><?php if($QtyDisplay==1){ ?> <span id="Updated_Qty<?php echo $obj_product["item_no"]; ?>"> <?php echo "(العدد (".$obj_product["qty"]; ?></span> <?php } ?> </span></button>
		<?php
		}	
		}
		}	
			
}
?>