<?php
include("config.php");
?>
<?php 
if(isset($_POST['type']) && $_POST['type'] == 'onlinefunction'){
	//update rider online status and time
	$btn_status = $_POST['btn_status'];
	$riderid = $_POST['riderid'];
	if($btn_status == 'online'){
		$online_time = date('Y-m-d H:i:s');
		$r_updateddate = date('Y-m-d H:i:s');
		$r_online	= 1;
		
		$query_up = "UPDATE `tbl_riders` SET `r_online` = $r_online, r_online_datetime = '".$online_time."', r_updateddate = '".$r_updateddate."' WHERE `tbl_riders`.`r_id` = ".$riderid;
		mysqli_query($conn, $query_up);
		$insert_id = mysqli_insert_id($conn);
		
		
		//insert online entry in rider_onoff
		$on_query = "INSERT INTO `rider_onoff` (`rd_id`, `rd_r_id`, `rd_online`, `rd_online_date`, `rd_online_time`, `rd_offline_time`, `rd_createddate`, `rd_updateddate`) VALUES (NULL, '".$riderid."', '".$r_online."','".date('Y-m-d')."', '".$r_updateddate."', '', '".$r_updateddate."', '".$r_updateddate."')";
		//echo $on_query;
		mysqli_query($conn, $on_query);
		
		
	}else{
		$r_online	= 0; //offline
		$r_offline_time = date('Y-m-d H:i:s');
		$r_updateddate = date('Y-m-d H:i:s');
		$query_up = "UPDATE `tbl_riders` SET `r_online` = $r_online, r_offline_time = '".$r_offline_time."' , r_updateddate = '".$r_updateddate."' WHERE `tbl_riders`.`r_id` = ".$riderid;
		mysqli_query($conn, $query_up);
		
		//update offline last entry in rider_onoff
		$on_select = "select * from rider_onoff where `rd_r_id` = ".$riderid."  and rd_online = 1 ORDER BY `rider_onoff`.`rd_id` ASC";
		//echo $on_select;
		//echo '<br/>';
		$on_rows = mysqli_fetch_assoc(mysqli_query($conn, $on_select));
		$on_rider_id =  $on_rows['rd_id'];
		
		$query_2 = "UPDATE `rider_onoff` SET `rd_online` = $r_online,`rd_online_date` = '".date('Y-m-d')."', `rd_offline_time` = '".$r_offline_time."' , `rd_updateddate` = '".$r_updateddate."' WHERE `rd_id` = ".$on_rider_id;
		//echo $query_2;
		mysqli_query($conn, $query_2);
	}
	exit;
}


if(isset($_POST['data']) && $_POST['data']=='accept_order'){
	$order_id = $_POST['order_id'];
	$riderid = $_POST['riderid'];
	$rider_od_accept_time = date('Y-m-d H:i:s');
	
	$od_query = mysqli_query($conn, "select * from order_list where id =".$order_id);
	$od_result = mysqli_fetch_assoc($od_query);
	$rider_od_assign_time = $od_result['rider_od_assign_time'];
	$currentDate = date('Y-m-d H:i:s');
	// Declare and define two dates 
	$date1 = strtotime($rider_od_assign_time); 
	$date2 = strtotime($currentDate); 

	/*$diff = abs($date2 - $date1); 
	//$years = floor($diff / (365*60*60*24)); 
	//$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
	//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 
	$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
	$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
	$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
	//printf("%d years, %d months, %d days, %d hours, " . "%d minutes, %d seconds", $years, $months, $days, $hours, $minutes, $seconds); 
	$minute_countdown = $hours.":".$minutes.":".$seconds;*/
	$minute_countdown = $_POST['minute_countdown'];

	$query_up = "UPDATE `order_list` SET `rider_accept_id` = $riderid, rider_od_accept_time = '".$rider_od_accept_time."', rider_od_time_count = '".$minute_countdown."'  WHERE `id` = ".$order_id;
	//echo $query_up;
	mysqli_query($conn, $query_up);
	if($query_up){echo true;}else{die();}
}


if(isset($_POST['data']) && $_POST['data']=='reach_shop'){
	$order_id = $_POST['order_id'];
	$riderid = $_POST['riderid'];
	$rider_arrive_shop = date('Y-m-d H:i:s');

	$query_up = "UPDATE `order_list` SET rider_arrive_shop = '".$rider_arrive_shop."'  WHERE `id` = ".$order_id;
	mysqli_query($conn, $query_up);
	
	
	$query_up2 = "UPDATE `order_list` SET  rider_m_price_diff = '".$_POST['final_order_amount']."' WHERE `id` = ".$order_id;
	mysqli_query($conn, $query_up2);
	
	
	

	if($query_up){echo true;}else{die();}
}


if(isset($_POST['data']) && $_POST['data']=='merchnt_update'){
	$orderid = $_POST['orderid'];
	$paid_amount = $_POST['paid_amount'];
	$price_diff = $_POST['price_diff'];
	$reason_diff = $_POST['reason_diff'];
	$bank_amount = $_POST['bank_amount'];
	$cash_amount = $_POST['cash_amount'];
	$mode = $_POST['mode'];
	$submit_date = date('Y-m-d H:i:s');
	$receipt_photo = "";
	$food_photo = "";
	//$price_diff = "";
	$reason_diff = "";
	$riderid = $_POST['riderid'];
	
	/*$receipt_photo = $_FILES['receipt_photo']['name'];
	$food_photo = $_FILES['food_photo']['name'];

	// upload receipt image
	$infoExt = getimagesize($_FILES['receipt_photo']['tmp_name']);
	if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
		$file	=	preg_replace('/\\s+/', '-', time().$file);
		$path   =   $_SERVER['DOCUMENT_ROOT'].'/upload/order_receipt/';
		$receipt_name_file = $orderid."_".date('Ymdhis');
		$ext=end(explode(".", $_FILES["receipt_photo"]["name"]));//gets extension	
		$receipt_image_file = $receipt_name_file.".".$ext;
		move_uploaded_file($_FILES["receipt_photo"]["tmp_name"], $path.$receipt_image_file);
	}
	
	//upload Food image
	$infoExt_1 = getimagesize($_FILES['food_photo']['tmp_name']);
	if(strtolower($infoExt_1['mime']) == 'image/gif' || strtolower($infoExt_1['mime']) == 'image/jpeg' || strtolower($infoExt_1['mime']) == 'image/jpg' || strtolower($infoExt_1['mime']) == 'image/png'){
		$file1	=	preg_replace('/\\s+/', '-', time().$file1);
		$path1  =   $_SERVER['DOCUMENT_ROOT'].'/upload/order_food/';
		$food_name_file = $orderid."_".date('Ymdhis');
		$ext=end(explode(".", $_FILES["food_photo"]["name"]));
		$food_image_file = $food_name_file.".".$ext;
		move_uploaded_file($_FILES["food_photo"]["tmp_name"], $path1.$food_image_file);
	}
	*/
	
	$query_up = "UPDATE `order_list` SET rider_cash_amount = '".$cash_amount."' , rider_bank_amount = '".$bank_amount."', rider_total_amount = '".$paid_amount."', rider_m_receipt_img = '".$receipt_image_file."', rider_m_food_img = '".$food_image_file."', rider_m_price_diff = '".$price_diff."', rider_reason_dif = '".$reason_diff."', update_merchnt_details = '".$submit_date."'   WHERE `id` = ".$orderid;
	mysqli_query($conn, $query_up);
	
	if($query_up){echo true;}else{die();}
}

if(isset($_POST['data']) && $_POST['data']=='complete_order'){
	$order_id = $_POST['order_id'];
	$riderid = $_POST['riderid'];
	$rider_complete_time = date('Y-m-d H:i:s');

	$query_up = "UPDATE `order_list` SET rider_complete_order = 1, rider_complete_time = '".$rider_complete_time."'  WHERE `id` = ".$order_id;
	mysqli_query($conn, $query_up);
	
	//start save data in riders_cash_history
	$query_order = mysqli_query($conn, "select * from order_list where id = ".$order_id."");
	$od_q = mysqli_fetch_assoc($query_order);
	$admin_cash_price = $od_q['admin_cash_price'];
	$admin_commission_price = $od_q['admin_commission_price'];
	$rider_cash_amount = $od_q['rider_cash_amount'];
	$rc_cash_price = $admin_cash_price - $rider_cash_amount;
	$user_id = $od_q['user_id'];
	
	/* START: Get 2% rebate of every order*/
	//if($user_id == 6347){
		$territory_price_array = explode("|",$od_q['territory_price']);
		$terr_id = $territory_price_array[0];
		$territory_price = $territory_price_array[1];
		
		$total_cart_amount = $od_q['total_cart_amount'];
		$deliver_tax_amount = $od_q['deliver_tax_amount'];
		$special_delivery_amount = $od_q['special_delivery_amount'];
		$speed_delivery_amount = $od_q['speed_delivery_amount'];
		$donation_amount_value = $od_q['donation_amount']; 
		$discount = $od_q['membership_discount'];
		$coupon_discount = $od_q['coupon_discount'];
		$merchant_id  = '6741';//$od_q['merchant_id'];//'6741';//$od_q['merchant_id'];
		$invoice_no  = $od_q['invoice_no'];
		
		$orderFinalTOTAL = @number_format(($total_cart_amount + $territory_price + $deliver_tax_amount + $special_delivery_amount +$speed_delivery_amount + $donation_amount_value) - ($discount + $coupon_discount));

		$two_Perc_rebeat =   @number_format(($orderFinalTOTAL * (2 / 100)),2);
		$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$merchant_id."'"));
	
		//echo "select * from tranfer where sender_id='$merchant_id' and invoice_no='$invoice_no' and amount='$two_Perc_rebeat' and  receiver_id = '".$user_id."'";
		$chk_rec = mysqli_fetch_assoc(mysqli_connect($conn,"select * from tranfer where sender_id='$merchant_id' and invoice_no='$invoice_no' and amount='$two_Perc_rebeat' and  receiver_id = '".$user_id."'"));
		if($chk_rec){
		}else{
		if($merchant_data['special_coin_name'] != ""){
			$merchantCoinName = $merchant_data['special_coin_name']; //get the coinname
		}else{
			//if coin name not added, then take 1st 3 letter 
			$merchantCoinName = substr($merchant_data['name'],0,3)." - cashback";
			$coinname_query = mysqli_query($conn,"update users SET `special_coin_name` = '".$merchantCoinName."' where id = '".$merchant_data['id']."'  ");
		}
		
		$fetchWalletdetails = mysqli_fetch_assoc(mysqli_query($conn, "select * from special_coin_wallet where user_id = '$user_id' and merchant_id ='$merchant_id'")); //fetch data
		
		if($fetchWalletdetails['id']){
			//if data availble then update coin value
			$userCoinBal = @number_format(($fetchWalletdetails['coin_balance'] + $two_Perc_rebeat),2);
			$update_user_coin=  mysqli_query($conn,"update special_coin_wallet set coin_balance='$userCoinBal', coin_last_rebt_date = '".date('Y-m-d H:i:s')."' where user_id='$user_id' and merchant_id='$merchant_id'");
		}else{
			//if data not availble then insert coin value
			$userCoinBal = @number_format($two_Perc_rebeat,2);
			$insert_user_coin=  mysqli_query($conn,"INSERT INTO special_coin_wallet SET user_id='$user_id',merchant_id='$merchant_id',coin_balance='$userCoinBal', created = '".date('Y-m-d H:i:s')."',coin_last_rebt_date = '".date('Y-m-d H:i:s')."'");
		}
		$update_order_coin=  mysqli_query($conn,"update order_list set coin_rebate_value='$two_Perc_rebeat' where id='$order_id'");
		
		
		$insert_history=  mysqli_query($conn,"INSERT INTO tranfer SET sender_id='$merchant_id',invoice_no='$invoice_no',
		amount='$two_Perc_rebeat', receiver_id = '".$user_id."',
		coin_merchant_id = '".$merchant_id."',
		wallet='MYR',
		created_on='".strtotime(date('Y-m-d H:i:s'))."',
		status='0',
		details='2% rebate',type_method='2% rebate',remark='2% rebate',created_date='".date('Y-m-d H:i:s')."'");
		}
	//}
	 /*END: Get 2% rebate of every order*/
	 
	
	
	$select_q = mysqli_query($conn, "select * from riders_cash_history where rc_od_id = ".$order_id." and rc_r_id = ".$riderid."");
	$assoc_q = mysqli_fetch_assoc($select_q);
	$rc_id = $assoc_q['rc_id'];
	if($rc_id != ''){
		//update
		//echo '1';
		$query_rc = "UPDATE `riders_cash_history` SET rc_cash_price = '".$rc_cash_price."',rc_commission = '".$admin_commission_price."' , rc_updateddate = '".$rider_complete_time."'   WHERE `rc_id` = ".$rc_id;
		//echo $query_rc;
		mysqli_query($conn, $query_rc);
	}else{
		//insert
		$qu_in = "INSERT INTO `riders_cash_history` (`rc_r_id`, `rc_od_id`, `rc_cash_price`,`rc_commission`, `rc_handover_admin`, `rc_createddate`) VALUES ( '".$riderid."', '".$order_id."', '".$rc_cash_price."','".$admin_commission_price."', '0', '".$rider_complete_time."');";
		mysqli_query($conn, $qu_in);
	}
	
	//end
	if($query_up){echo true;}else{die();}
}


?>