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
	$logincount = 0;
	$merchant_id = $_POST['m_id'];
	$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SQL_NO_CACHE * FROM users WHERE id='".$merchant_id."'"));
	$guest_permission = $merchant_data['guest_permission'];
	$referral_by = $merchant_data['referral_id'];
	
	$mobile_number = "60".$_POST['mobile_number'];
	
	if($mobile_number)
	{
		$f_letter=$mobile_number[0];
		if($f_letter==0)
		{
			$mobile_number = substr($mobile_number, 1);
		}
		if($guest_permission==1)
		{
			// check mobile start with 1 and  greater than 9 
			$f_letter=$mobile_number[0];
			if (($f_letter=="1") ) {
				 // Yes
				 $show_alert="y";
				 $password_created="n";
				 $otp_verified="n";
			}
			else
			{
				$show_alert="n";
				$password_created="y";
				 $otp_verified="y";
			}
		}
		else
		{
			$show_alert="y";
			$otp_verified="n";
		}
	}
		
	 
	$mobile_number = "60".$_POST['mobile_number'];
	$prd_ids = implode(",",$_POST['p_id']);
	$reponse = serialize($_POST);
	$datetime = date('Y-m-d H:i:s');
	
	$fetch_user = "select * from users where mobile_number= ".$mobile_number;
	$user_query = mysqli_query($conn,$fetch_user);
	$srow = mysqli_fetch_assoc($user_query);
	$user_name = $srow['name'];
	$user_email = $srow['email'];
	$logincount=mysqli_num_rows($user_query); //new
	
	
	//add user if not added -- 22/04/2021
	$m_id = $merchant_id;
	if($logincount>0)
	{
		$userdata=mysqli_fetch_assoc($user_query);
		$user_id=$userdata['id'];
		$user_mobile=$userdata['mobile_number'];
		$user_name = $userdata['name'];
		$user_email = $userdata['email'];
	
		if($mobile_number==$user_mobile){}else{$user_mobile=$mobile_number;}
		
		$myr_bal=$userdata['balance_myr'];
	    $usb_bal=$userdata['balance_usd'];
	    $inr_bal=$userdata['balance_inr'];
		$user_name=$userdata['name'];
		$otp_verified=$userdata['otp_verified'];
		if($otp_verified=="y")
		$newuser="n";
		else
		$newuser="y";
	
		if($newuser=="y")
		{
		  // get all the past order made by that user id
		   $q=mysqli_query($conn,"select count(id) as total_order from order_list where user_id='$user_id'");
		   $totalcount=mysqli_fetch_assoc($q);
		   $totalcount=$totalcount['total_order'];
		}
			// get all order place on today 
			$todaydate=date('Y-m-d');   
			
			if($m_id=='1567')   
			{
				$q=mysqli_query($conn,"select count(id) as total_order from order_list where date(created_on)='$todaydate' and user_id='$user_id' and merchant_id='$m_id'");
				$totalcount=mysqli_fetch_assoc($q);
				$totalcount=$totalcount['total_order'];
				if($totalcount>2)
				{   
					$_SESSION['today_limit']='expire';
					header("Location: ".$site_url."/view_merchant.php"); 
					die;
				}  
			}
		$_SESSION['user_id']=$user_id;
	}
	else
	{
		if($mobile_number)
		{
			// create new user account with respect to merchant 
			$code = uniqid();
			$ref = $mobile_number." ".$code;
			$user_role=1;
			$name = '';
			$reocrd=mysqli_query($conn, "INSERT INTO users SET isLocked='0',referral_id='$ref',referred_by='$referral_by',name='$name',user_roles='$user_role',mobile_number='$mobile_number',guest_user='y',login_status='1',password_created='$password_created',otp_verified='$otp_verified'");
            $user_id=mysqli_insert_id($conn);
			$logged_user_id = $user_id;
			$user_mobile="60".$mobile_number;   
			$newuser="y";
			$date = date('Y-m-d H:i:s');
			 $loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_number."'");	
			$userdata=mysqli_fetch_assoc($loginmatch);
		}
	}
	//ENd - 22/04/2021
	
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
	
	/*$sms_to = "60123115670";
	$sms_msg = $mobile_number." tried to do payment(RM ".$amount.") via FPX";
	$smsend = gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", $sms_to, $sms_msg); 
	*/echo json_encode($res_array);
}exit;
?>