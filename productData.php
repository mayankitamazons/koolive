<?php
include("config.php");
 $_SESSION['mm_id']= $_SESSION['login'];

//$pro_remark = $_POST['remark'];
 $pid = $_POST['p_id'];
 $sp_id = $_POST['sp_id'];
 $extravar = $_POST['extravar'];
 $remark = $_POST['remark'];

 $sql = "select * from products where id=".$pid;
 $rel = mysqli_query($conn, $sql);
 $sub_sql = "select * from sub_products where id='".$sp_id."' and product_id ='".$pid."'";
 $sub_rel = mysqli_query($conn, $sub_sql);

 if($row = mysqli_fetch_assoc($rel))
 {
 		if($sub_row = mysqli_fetch_assoc($sub_rel))
 		{	
 			if($sub_row['name']!=""){
  			$r ='<div>-'.$sub_row['name'].'</div>';

  			$sub_p = $sub_row['product_price'];

  		}else{
  			$r = '<div></div>';
  			//$sub_p ='0.00';
  		}

 		}		

 	echo'<tr id='.$row['id'].' class="rd"><td>'.$row['product_name'].''.$r.'<input type="hidden" id="name'.$row['id'].'" name="name[]" value="'.$row['product_name'].'"><input type="hidden" id="p_code'.$row['id'].'" name="p_code[]" value="'.$row['product_type'].'"><input type="hidden" id="subpro_id'.$sub_row['id'].'" name="subpro_id[]" value="'.$sub_row['id'].'"></td><td>'.$row['product_price'].'<input type="hidden" id="price'.$row['id'].'" value="'.$row['product_price'].'" name="price[]"><input type="hidden" name="pro_id[]" id="pro_id" value="'.$row['id'].'"></td><td>'.$sub_p.'<input type="hidden" id="subpro_price'.$row['id'].'" name="subpro_price[]" value="'.$sub_p.'"></td><td><input type="text"  class="form-control qd" style="width:65px" id="qty'.$row['id'].'" name="qty[]" onkeyup="changeqty(this)" value="1"></td><td class="text-right"><input type="text" class="form-control subt" style="width:90px" id="subtotal'.$row['id'].'" value="'.number_format($row['product_price']+$sub_row['product_price'],2).'" name="subtotal[]" readonly></td><td id="re'.$row['id'].'">'.$remark.' '.$extravar.'<input type="hidden" id="remark_val'.$row['id'].'" name="remark_val[]" value="'.$remark.' '.$extravar.'"></td>
 	<td  id="'.$row['id'].'" class="text-center" onclick="deleterow(this)" ><i class="fa fa-times tip pointer posdel"  title="Remove" style="cursor:pointer;"></i></td></tr>';

 }


?>