<?php
include("config.php");
include("IPay88.class.php");
$ipay88 = new IPay88('M31571'); // MerchantCode
$ipay88->setMerchantKey('IzXIhfJHUJ'); /*YOUR_MERCHANT_KEY*/

if($_POST){
	
	$logged_user_id = $_POST['login_user_id'];
	$merchant_id = $_POST['m_id'];
	$mobile_number = "60".$_POST['mobile_number'];
	$prd_ids = implode(",",$_POST['p_id']);
	$reponse = serialize($_POST);
	$datetime = date('Y-m-d H:i:s');
	
	$fetch_user = "select * from users where mobile_number= ".$mobile_number;
	$user_query = mysqli_query($conn,$fetch_user);
	$srow = mysqli_fetch_assoc($user_query);
	$user_name = $srow['name'];
	$user_email = $srow['email'];
	
	$query="INSERT INTO `tbl_temp_saveorder` (`t_user_id`, `t_m_id`, `t_mobile_number`, `t_order_response`, `t_createddate`) VALUES ($logged_user_id, $merchant_id, $mobile_number, '".$reponse."', '".$datetime."')";
	#echo $query;
	mysqli_query($conn,$query);
	$last_id = mysqli_insert_id($conn);
	
	
	//Create dynamic signature
	$amount = '1.00';//$_POST['hidden_final_cart_price'];
	$sign_price = str_replace('.', '', str_replace(',', '', $amount));
	$signature = '';
	$signature .= 'IzXIhfJHUJ';
    $signature .= 'M31571';
    $signature .= 'ipay88000'.$last_id;
    $signature .= $sign_price;
    $signature .= 'MYR';
	
	$source = sha1($signature);
	$bin = '';
    for ($i = 0; $i < strlen($source); $i += 2) {
      $bin .= chr(hexdec(substr($source, $i, 2)));
    }
    $signature = base64_encode($bin);
	
	$res_array = array();
	$res_array['refNo'] = "ipay88000".$last_id ;
	//$res_array['amount'] = $last_id;
	$res_array['proddesc'] = "productids".$prd_ids;
	$res_array['username'] = $user_name;
	$res_array['useremail'] = $user_email;
	$res_array['usercontact'] = $mobile_number;
	$res_array['remark_ipay88'] = "temp_orderid".$last_id ;
	$res_array['signature'] = $signature;
	echo json_encode($res_array);
}exit;
?>