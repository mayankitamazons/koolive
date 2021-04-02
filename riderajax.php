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
		$on_query = "INSERT INTO `rider_onoff` (`rd_id`, `rd_r_id`, `rd_online`, `rd_online_time`, `rd_offline_time`, `rd_createddate`, `rd_updateddate`) VALUES (NULL, '".$riderid."', '".$r_online."', '".$r_updateddate."', '', '".$r_updateddate."', '".$r_updateddate."')";
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
		
		$query_2 = "UPDATE `rider_onoff` SET `rd_online` = $r_online, `rd_offline_time` = '".$r_offline_time."' , `rd_updateddate` = '".$r_updateddate."' WHERE `rd_id` = ".$on_rider_id;
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

	$receipt_photo = $_FILES['receipt_photo']['name'];
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

	if($query_up){echo true;}else{die();}
}


?>