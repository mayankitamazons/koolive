<?php
include("config.php");
include_once ('IPay88.class.php'); 
$ipay88 = new IPay88('M31571');

$response = $ipay88->getResponse();

//echo '<pre>';
//print_R($response);

$datetime = date('Y-m-d H:i:s');

$temp_order_id = str_replace("temp_orderid","",$response['data']['Remark']);

$fetch_data = "SELECT * FROM `tbl_temp_saveorder` where t_id = ".$temp_order_id;
$user_query = mysqli_query($conn,$fetch_data);
$srow = mysqli_fetch_assoc($user_query);	


//save response in tbl_ipay88_payment
$query_temp="INSERT INTO `tbl_ipay88_payment` (`p_order_id`, `p_user_id`, `p_trans_id`,`p_temp_order_id`, `p_status`, `p_amount`, `p_response`, `p_message`, `p_createddate`) VALUES ('', '".$srow['t_user_id']."', '".$response['data']['TransId']."', '".$temp_order_id."', '".$response['status']."', '".$response['data']['Amount']."', '".serialize($response['data'])."', '".$response['message']."', '".$datetime."')";
mysqli_query($conn,$query_temp);
$last_id = mysqli_insert_id($conn);
	
$update_query = "UPDATE `tbl_temp_saveorder` SET `t_transid` = '".$response['data']['TransId']."', `t_payment_status` = '".$response['status']."' WHERE `tbl_temp_saveorder`.`t_id` = ".$temp_order_id;	
mysqli_query($conn,$update_query);
	

$order_data = unserialize ($srow['t_order_response']);

$ipay_payment_status = $response['status'];
$ipay_message = $response['message'];
$ipay_p_id = $last_id;

echo '<pre>';
print_R($srow);
print_R($order_data);
exit;	
//end data

?>