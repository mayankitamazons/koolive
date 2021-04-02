<?php
include("config.php");
include("IPay88.class.php");
$ipay88 = new IPay88('M31571'); // MerchantCode
$ipay88->setMerchantKey('IzXIhfJHUJ'); /*YOUR_MERCHANT_KEY*/
function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg){           
    $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
    $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
    $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
     $url = "http://gateway.onewaysms.com.au:10001/".$query_string;  
    
	// Initialize a CURL session. 
	$ch = curl_init();  
	  
	// Return Page contents. 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	  
	//grab URL and pass it to the variable. 
	curl_setopt($ch, CURLOPT_URL, $url); 
	  
	$result = curl_exec($ch); 
	 $ok = "success"; 
	      
    return $ok;  
} 

if($_POST){
	if($_POST['login_user_id'] != ''){
	$logged_user_id = $_POST['login_user_id'];
	}else{
		$logged_user_id = 0;
	}
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
	$amount = $_POST['hidden_final_cart_price'];
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
	
	$sms_to = "60123115670";
	$sms_msg = $mobile_number." tried to do payment(RM ".$amount.") via FPX";
	$smsend = gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to, $sms_msg); 
	echo json_encode($res_array);
}exit;
?>