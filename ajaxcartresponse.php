<?php 

if($_POST['type'] == 'save_session'){
	session_start();
	//$_SESSION['AjaxCartResponse'][$_POST['id']] = $_POST;
	$merchant_id = $_SESSION['AjaxCartResponse']['merchant_id'];
	$_SESSION['AjaxCartResponse'][$merchant_id][$_POST['id']] = $_POST;
	
	echo '<pre>';
	print_R($_SESSION['AjaxCartResponse'][$merchant_id]);
	exit;
}



if($_POST['type'] == 'qty_update'){
	session_start();
	$id = $_POST['id'];
	$merchant_id = $_SESSION['AjaxCartResponse']['merchant_id'];
	if(array_key_exists($id,$_SESSION['AjaxCartResponse'][$merchant_id])){
		$_SESSION['AjaxCartResponse'][$merchant_id][$id]['p_total'] = $_POST['total'];
		$_SESSION['AjaxCartResponse'][$merchant_id][$id]['quantity'] = $_POST['qty'];
	}
	echo '<pre>';
	print_R($_SESSION['AjaxCartResponse'][$merchant_id]);
	exit;
}

if($_POST['type'] == 'remove_product'){
	session_start();
	$id = $_POST['id'];
	$merchant_id = $_SESSION['AjaxCartResponse']['merchant_id'];
	if(array_key_exists($id,$_SESSION['AjaxCartResponse'][$merchant_id])){
		unset($_SESSION['AjaxCartResponse'][$merchant_id][$id]);
	}
	echo '<pre>';
	print_R($_SESSION['AjaxCartResponse'][$merchant_id]);
	exit;
}

if($_POST['type'] == 'empty_cart'){
	session_start();
	
	$merchant_id = $_SESSION['AjaxCartResponse']['merchant_id'];
	// echo $merchant_id;
	// if (array_key_exists($merchant_id,$_SESSION['AjaxCartResponse'])) {
	if(isset($_SESSION['AjaxCartResponse'][$merchant_id])) { 
	unset($_SESSION['AjaxCartResponse'][$merchant_id]);
	
	// echo '<pre>';
	
	// print_R($_SESSION['AjaxCartResponse'][$merchant_id]);
	}
	exit;
	
}

if($_POST['type'] == 'repeat_last_order'){
	include("config.php");
	session_start();
	
	
	//echo $_SESSION['merchant_id'];exit;
	$user_id = $_POST['logged_user_id'];
	
	$merchant_id = $_POST['merchant_id'];
	$merchant_mbl_id = $_POST['merchant_mbl_id'];
	
	$query_new = "SELECT SQL_NO_CACHE order_list.*, sections.name as section_name FROM order_list left join sections on order_list.section_type = sections.id WHERE order_list.user_id ='".$_SESSION["user_id"]."' and merchant_id = '".$merchant_id."' ORDER BY `created_on` DESC ";
    $total_rows = mysqli_query($conn,$query_new);
	$orderArray = mysqli_fetch_assoc($total_rows);
	
	$response = '';
	$product_ids = array();
	if($orderArray['product_id']!= ''){
		$product_ids = explode(",",$orderArray['product_id']);
	}
	
	$quantity_ids = explode(",",$orderArray['quantity']);
	$amount_val = explode(",",$orderArray['amount']);
	$rebate_amount = explode(",",$orderArray['rebate_amount']);
	$remarks_product = explode("|",$orderArray['remark']);
	
	$cartData = array();
	$v_array = array();
	$varient_type=$orderArray['varient_type'];
	if($varient_type)
	{
		$v_str=$orderArray['varient_type'];
		$v_array=explode("|",$v_str);
	}
	$i=0;
	/*echo $orderArray['product_id'];
	
	echo count($product_ids);
	echo '<pre>';
	print_R($product_ids);*/
	
	if(count($product_ids)> 0){
		foreach ($product_ids as $key )
	{
		if(is_numeric($key))
		{
			$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
			$product_subitem = "";
			if($v_array[$i])
			{
				$v_match=$v_array[$i];
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
						
					$product_subitem .= "&nbsp;&nbsp;&nbsp;&nbsp;"."-".$srow['name']." (Rm ".number_format($srow['product_price'],2).")<br/>";
					
				}
			}
			
			if($product['code'] != ''){
				$prd_code = $product['code'];
			}else{
				$prd_code = "-";
			}
			
			$product_qty_price = $quantity_ids[$i] * $amount_val[$i];
			$cartData[$product['id']]['name'] = $product['product_name']."<br>".$product_subitem;
			$cartData[$product['id']]['rebate_amount'] = $orderArray['rebate_amount'];
			$cartData[$product['id']]['id'] = $product['id'];
			$cartData[$product['id']]['rebate_per'] = $rebate_amount[$i];
			$cartData[$product['id']]['product_price'] = $amount_val[$i];//$product['product_price'];
			$cartData[$product['id']]['quantity'] = $quantity_ids[$i];
			$cartData[$product['id']]['s_id'] = $product['id'];
			$cartData[$product['id']]['code'] = $prd_code;
			$cartData[$product['id']]['single_remarks'] = $remarks_product[$i];
			$cartData[$product['id']]['remark_lable'] = "Remarks";//$product['id'];
			$cartData[$product['id']]['extra_price'] = "0.00";//$product['id'];
			$cartData[$product['id']]['extra_child_id'] = "extra_child_".$product['id'];//$product['id'];
			$cartData[$product['id']]['p_total'] = number_format($product_qty_price,2);//$product['id'];
			$cartData[$product['id']]['varient_type'] = $product['varient_type'];
			
			
			
			
			
			$response .= "<tr class='producttr'>  <td><button remove_productid=".$product['id']." type='button' class='removebutton'>X</button> </td><td class='pro1_name'>".$cartData[$product['id']]['name']."</td><td><input type='hidden' name='rebate_amount[]' class='rebate_amount' value=".$orderArray['rebate_amount']." id='".$product['id']."rebate_amount'><input type='hidden' name='rebate_per[]' value=".$rebate_amount[$i]." id='".$product['id']."rebate_per'><input style='width:50px;'  onchange='UpdateTotal(".$product['id'].",".$amount_val[$i].")'  type=number name='qty[]' min='1' maxlength='3' class='product_qty quatity'  value=".$quantity_ids[$i]." id='".$product['id']."_test_athy'><input type= hidden name='p_id[]' value= ".$product['s_id']."><input type= hidden name='p_code[]' value= ".$prd_code."><input type='hidden' name='ingredients' value='".$product['remark']."'/></td><td>".$prd_code."</td><td><a href='#remarks_area' data-rid='".$product['id']."' role='button' class='introduce-remarks btn btn-large btn-primary hideLoader' data-toggle='modal'>" .(($remarks_product[$i] == '') ? $cartData[$product['id']]['remark_lable'] : $remarks_product[$i]). "</a><input type='hidden' id='extra_child_".$product['id']."' name='extra' value='0.00'></td><td><input style='width:70px;text-align:right;' type='text' name='p_extra' id=extra_child_".$product['id']."' value='0.00' readonly></td><td><input style='width:70px;' type='text' name='p_price[]' value='".$amount_val[$i]."' readonly></td><td><input type='text' style='width:70px;' class='p_total' name='p_total[]' value= ".number_format($product_qty_price,2)." readonly  id='".$product['id']."_cat_total'><input type='hidden' name='varient_type[]' value=".$product['varient_type']."></td></tr>";

			
		}
		$i++;
	}
	
	
	$_SESSION['AjaxCartResponse'][$merchant_mbl_id] = $cartData;
	
	}else{
		
		$response = '<tr><th colspan="8" style="text-align:center">'.$_POST['no_order_msg'].'</th></tr>';
		
	}
	echo $response;
	/*echo '<pre>';
	print_R($_SESSION['AjaxCartResponse'][$merchant_id]);*/
	exit;
	
 
}

?>
