<?php 
include("config.php");
$profile_tab="y";
$me="profile_merchant";
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$site_url="https://www.koofamilies.com/";
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($bank_data);
// die;   
$k_history = mysqli_query($conn, "SELECT * FROM k_type WHERE user_id='".$_SESSION['login']."' ORDER BY date");
$referred_by = $bank_data['referred_by'];
$printer_style = $bank_data['printer_style']; 
// $isactive_custom_message = (isset(json_decode($bank_data['custom_message'])->message)) ? true : false;
$custom_things = json_decode($bank_data['custom_message']);
if($bank_data['referred_by'] == "")
    $referred_by = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_roles = '1' and  referral_id='".$_SESSION['referral_id']."'"))['referred_by'];
   
$user_mobile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'];
if(isset($_POST['submit']))
{
	extract($_POST);
	$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, created_at, referred_by, k_date, account_type FROM users WHERE id='".$_SESSION['login']."'"));
	$user_id = $ref_result['id'];
	if(($ref_result['created_at'] != null) || ($ref_result['created_at'] != "")){
		$created_date = strtotime($ref_result['created_at']);
		$num_date = ceil((time() - $created_date)/60/60/24);
	} else {
		$date = date('y-m-d');
		$num_date = 183;
	}
	if($ref_result['referred_by'] == ""){
	    $num_date = 183;
		$date = "";
	}
    
    
    $type_expire = ceil((time() - strtotime($ref_result['k_date']))/60/60/24);
    
	$error = "";
	$expired_flag = false;
    $expired_type_flag = false;
    $printer_style=$_POST['printer_style'];
    $printer_profile=$_POST['printer_profile'];
	$usb_name=$_POST['usb_name'];
    $shop_open=$_POST['shop_open'];
	$membership_plan =$_POST['membership_plan'];
	$coupon_offer =$_POST['coupon_offer'];
	$discounted_product=$_POST['discounted_product'];
	$spassword_need=$_POST['spassword_need'];
    $location_order=$_POST['location_order'];
    $delivery_plan=$_POST['delivery_plan'];
    $location_range=$_POST['location_range'];
	
    $free_delivery=$_POST['free_delivery'];
	$realname = addslashes($_POST['realname']);
	$company = addslashes($_POST['company']);
	$register = addslashes($_POST['register']);
	$address = addslashes($_POST['address']);
	$gst = addslashes($_POST['gst']);
	$sst = addslashes($_POST['sst']);
	$sst_rate = addslashes($_POST['sst_rate']);
    $discounts = addslashes($_POST['discounts']);
    
	$main_merchant_id = $_POST['merchant_id'];
	
	$order_print_setting = addslashes(isset($_POST['order_print_setting']) ? $_POST['order_print_setting'] : '');
	//$order_print_live_setting = addslashes(isset($_POST['order_print_live_setting']) ? $_POST['order_print_live_setting'] : '');
	$facsimile = addslashes($_POST['facsimile']);
	$business1 = addslashes($_POST['business1']);
	$business2 = addslashes($_POST['business2']);
	$name_card = addslashes($_POST['name_card']);
	$card_number = addslashes($_POST['card_number']);
	$expiry_date = addslashes($_POST['expiry_date']);
	$cvv = addslashes($_POST['cvv']);
	$handphone_number = addslashes($_POST['handphone_number']);
	$bankname = addslashes($_POST['bankName']);
	$name_accoundholder = addslashes($_POST['name_accoundholder']);
	$ac_num = addslashes($_POST['ac_num']);
	$charge = addslashes($_POST['charge']);
	$nric_number = addslashes($_POST['nric_number']);
	$address_person = addslashes($_POST['address_person']);
	$hand_phone = addslashes($_POST['hand_phone']);
	$google_map = addslashes($_POST['google_map']);
	$latitude =  addslashes($_POST['latitude']);
	$longitude =  addslashes($_POST['longitude']);
	$doc_copy = addslashes(isset($_POST['doc_copy']) ? $_POST['doc_copy'] : '');
	$company_doc = addslashes(isset($_POST['company_doc']) ? $_POST['company_doc'] : '');
	$number_lock = addslashes(isset($_POST['number_lock']) ? $_POST['number_lock'] : '');
	$k_lock = addslashes(isset($_POST['k_lock']) ? $_POST['k_lock'] : '');
	$account_type = addslashes($_POST['account_type']);
	$merchant_address = addslashes($_POST['merchant_address']);
	$print_ip_address = addslashes(isset($_POST['print_ip_address']) ? $_POST['print_ip_address'] : '');
	$merchant_code = $bank_data['merchant_code'];
	$guest_permission = addslashes(isset($_POST['guest_permission']) ? $_POST['guest_permission'] : '');
	$voice_recognition = addslashes(isset($_POST['voice_recognition']) ? $_POST['voice_recognition'] : '');
	$unrecognize_coin = addslashes(isset($_POST['unrecognize_coin']) ? $_POST['unrecognize_coin'] : '');
	$pending_time = addslashes(isset($_POST['pending_time']) ? $_POST['pending_time'] : '');
	$next_pending_time = addslashes(isset($_POST['next_pending_time']) ? $_POST['next_pending_time'] : '');
	$menu_type = addslashes(isset($_POST['menu_type']) ? $_POST['menu_type'] : '');
	if($merchant_code == ""){
	    $code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(SUBSTR(merchant_code,5)) + 1 code FROM users "))['code'];
	    $merchant_code = "KOO_".str_pad($code,5,'0',STR_PAD_LEFT);
	}
	// start of order related field 
	
	$pre_fill_delivery_address=$_POST['pre_fill_delivery_address'];
	$section_required=$_POST['section_required'];
	$table_on_orderlist=$_POST['table_on_orderlist'];
	$paypal_enable=$_POST['paypal_enable'];
	$section_on_orderlist=$_POST['section_on_orderlist'];
	$table_required=$_POST['table_required'];
	$delivery_address_exit=$_POST['delivery_address_exit'];
	$section_exit=$_POST['section_exit'];
	$table_exit=$_POST['table_exit'];
	if($delivery_address_exit =="on") $delivery_address_exit = '1';
	else $delivery_address_exit = "0";
	if($section_exit =="on") $section_exit = '1';
	else $section_exit = "0";
	if($table_exit =="on") $table_exit = '1';
	else $table_exit = "0";
	if($section_required =="on") $section_required = '1';
	else $section_required = "0";
	if($table_required =="on") $table_required = '1';
	else $table_required = "0";
	if($table_on_orderlist =="on") $table_on_orderlist = 'y';
	else $table_on_orderlist = "n";
	if($paypal_enable =="on") $paypal_enable = 'y';
	else $paypal_enable = "n";
	if($section_on_orderlist =="on") $section_on_orderlist = 'y';
	else $section_on_orderlist = "n";
	if($shop_open =="on") $shop_open = '1';
	else $shop_open = "0";
	if($membership_plan =="on") $membership_plan = '1';
	else $membership_plan = "0";
	if($coupon_offer =="on") $coupon_offer = '1';
	else $coupon_offer = "0";
	if($discounted_product =="on") $discounted_product = '1';
	else $discounted_product = "0";
	if($spassword_need =="on") $spassword_need = '1';
	else $spassword_need = "0";
	if($location_order =="on") $location_order = '1';
	else $location_order = "0";
	if($delivery_plan =="on") $delivery_plan = '1';
	else $delivery_plan = "0";
	/* jupiter 24.02.19*/
	$cash_check = addslashes(isset($_POST['cash_check']) ? $_POST['cash_check'] : '');
	$credit_check = addslashes(isset($_POST['credit_check']) ? $_POST['credit_check'] : '');
	$wallet_check = addslashes(isset($_POST['wallet_check']) ? $_POST['wallet_check'] : '');
	$boost_check = addslashes(isset($_POST['boost_check']) ? $_POST['boost_check'] : '');
	$grab_check = addslashes(isset($_POST['grab_check']) ? $_POST['grab_check'] : '');
	$wechat_check = addslashes(isset($_POST['wechat_check']) ? $_POST['wechat_check'] : '');
	$touch_check = addslashes(isset($_POST['touch_check']) ? $_POST['touch_check'] : '');
	$fpx_check = addslashes(isset($_POST['fpx_check']) ? $_POST['fpx_check'] : '');
	$setup_shop=$_POST['setup_shop'];
	
	if($cash_check =="on") $cash_check = '1';
	else $cash_check = "0";
	if($credit_check =="on") $credit_check = '1';
	else $credit_check = "0";
	if($wallet_check =="on") $wallet_check = '1';
	else $wallet_check = "0";
	if($boost_check =="on") $boost_check = '1';
	else $boost_check = "0";
	if($grab_check =="on") $grab_check = '1';
	else $grab_check = "0";
	if($wechat_check =="on") $wechat_check = '1';
	else $wechat_check = "0";
	if($touch_check =="on") $touch_check = '1';
	else $touch_check = "0";
	if($fpx_check =="on") $fpx_check = '1';
	else $fpx_check = "0";
	if($number_lock =="on") $number_lock = '1';
	else $number_lock = "0";
	
	if($k_lock == "on") $k_lock = '1';
	else $k_lock = "0";
	
	if($guest_permission == "on") $guest_permission = '1';
	else $guest_permission = "0";
	if($voice_recognition == "on"){
		$voice_recognition = '1';
	}else{
		$voice_recognition = "0";
	} 
	if($unrecognize_coin == "on") $unrecognize_coin = '1';
	else $unrecognize_coin = "0";
	if($num_date < 183){
		$expired_flag = false;
		$referred_by = $ref_result['referred_by'];
		$date = $ref_result['created_at'];
	} else {
		$expired_flag = true;
		$referral_id = addslashes($_POST['referral_id']);
		$existing_referral = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE referral_id='".$referral_id."'"));
		if(count($existing_referral) > 0){
		    
		    $referred_by = $referral_id;
		    $date = date('y-m-d');
		} else {
		    $referred_by = $ref_result['referred_by'];
		    $date = $ref_result['created_at'];
		    
		}
	}
	
	if($type_expire < 2){
	    $expired_type_flag = false;
	    $account_type = $ref_result['account_type'];
	    $k_date = $ref_result['k_date'];
	} else {
	    $expired_type_flag = true;
	    $account_type = addslashes($_POST['account_type']);
	    $k_date = date('Y-m-d');
	    mysqli_query($conn, "INSERT INTO k_type SET user_id='$user_id', type='$account_type', date='$k_date'");
	}
	$flag = false;
	
	
	if($_FILES['doc_copy']['name'] != "")
	{
		$filename = $_FILES['doc_copy']['name'];
		move_uploaded_file($_FILES['doc_copy']['tmp_name'], "documents/".$filename);
	}
	else
	{
		$filename = $bank_data['doc_copy'];
	}
	if($_FILES['shortcut_icon']['name'] != "")
	{
		$shortcut_icon = $_FILES['shortcut_icon']['name'];
		move_uploaded_file($_FILES['shortcut_icon']['tmp_name'], "images/shortcut_icon/".$shortcut_icon);  
	}
	else
	{
		$shortcut_icon = $bank_data['shortcut_icon'];
	}
	if($_FILES['custom_msg_image']['name'] != "")
	{
		$custom_msg_image = $_FILES['custom_msg_image']['name'];
		move_uploaded_file($_FILES['custom_msg_image']['tmp_name'], "customimage/".$custom_msg_image);
	}
	else
	{
		$custom_msg_image = $bank_data['custom_msg_image'];
	}
	
	if($_FILES['company_doc']['name'] != "")
	{
		$filenamess = $_FILES['company_doc']['name'];
		move_uploaded_file($_FILES['company_doc']['tmp_name'], "documents/profile_images/".$filenamess);
	}
	else
	{
		$filenamess = $bank_data['company_doc'];
	}
	if($write_up_share =="on") $write_up_share = '1';
	else $write_up_share = "0";
	if($auto_accept_order =="on") $auto_accept_order = '1';
	else $auto_accept_order = "0";
	$old_image=$bank_data['old_image'];
	  if(isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["banner_image"]["name"];
        $filetype = $_FILES["banner_image"]["type"];
        $filesize = $_FILES["banner_image"]["size"];
 
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");    
        
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
			// Check whether file exists before uploading it
					$uniquesavename=time().uniqid(rand(1000,9999)).".png";
					
					$destFile = "banner_image/".$uniquesavename;
					require_once('sirv.api.class.php');
					$sirvClient = new SirvClient(
					  // S3 bucket
					  'koofamilies',
					  // S3 Access Key
					  'click4mayank@gmail.com',
					  // S3 Secret Key
					  'iFOyO1LVMp7EOYIW3IP9VOn76UBFFWdxGaDzuJGj2tHlHMP0'
					);
					if ($sirvClient->testConnection()) {  
					  // Connection SUCCEEDED
					  // echo "connected";
						$res = $sirvClient->uploadFile(
						  // File path on Sirv
						  $destFile,
						  // Local file name
						 $_FILES["banner_image"]["tmp_name"] 
						);
						if($old_image)
							{
								$old_image_path="banner_image/".$old_image;  
								$sirvClient->deleteFile($old_image_path);  
							}
						if($res['full_url']=='')
						{
							$destFile = "/home/koofamilies/public_html/images/banner_image/".$uniquesavename;
							move_uploaded_file($_FILES["banner_image"]["tmp_name"],$destFile); 
						}
						else
						{
							$banner_cdn_url=$res['full_url'];
						}

					} else {
					  // Connection FAILED
						$destFile = "/home/koofamilies/public_html/images/banner_image/".$uniquesavename;
						move_uploaded_file($_FILES["banner_image"]["tmp_name"],$destFile); 
					}
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } 
	else
	{
		if($about['image'])
		{
			$uniquesavename=$about['image'];
		}
	}
	$order_extra_charge=$_POST['order_extra_charge'];
	$delivery_rate=$_POST['delivery_rate'];
	$order_min_charge=$_POST['order_min_charge'];
	$pre_fill_delivery_address=$_POST['pre_fill_delivery_address'];
	$custom_msg_time=$_POST['custom_msg_time'];
	$custom_message=$_POST['custom_message'];
	$fund_password=$_POST['fund_password'];
	$special_coin_name=$_POST['special_coin_name'];
	$special_coin_max=$_POST['special_coin_max'];
	$special_coin_min=$_POST['special_coin_min'];
	$service=$_POST['service'];
	$business_nature=$_POST['business_nature'];
	$invoice_company=$_POST['invoice_company'];
	$merchant_remark=$_POST['merchant_remark'];
	
	   
	if($flag == false)
	{
		$qur="UPDATE users SET merchant_remark='$merchant_remark',invoice_company='$invoice_company',banner_image='$uniquesavename',shortcut_icon='$shortcut_icon',paypal_enable='$paypal_enable',
		 business_nature='$business_nature',special_coin_min='$special_coin_min',special_coin_max='$special_coin_max',unrecognize_coin='$unrecognize_coin',special_coin_name='$special_coin_name',
		 fund_password='$fund_password',delivery_plan='$delivery_plan',membership_plan='$membership_plan',coupon_offer='$coupon_offer',custom_msg_image='$custom_msg_image',section_on_orderlist='$section_on_orderlist',
		 table_on_orderlist='$table_on_orderlist',order_min_charge='$order_min_charge',order_extra_charge='$order_extra_charge',spassword_need='$spassword_need',discounted_product='$discounted_product',
		 setup_shop='$setup_shop',shop_open='$shop_open',printer_profile='$printer_profile',usb_name='$usb_name',printer_style='$printer_style',merchant_code='$merchant_code', merchant_url='$merchant_address', 
		 name='$realname',latitude='$latitude', longitude='$longitude', company='$company',register='$register',address='$address',gst='$gst', sst='$sst',print_ip_address='$print_ip_address',
		 order_print_setting='$order_print_setting', facsimile_number='$facsimile',referred_by='$referred_by', business1='$business1', business2='$business2', name_card='$name_card',card_number='$card_number',
		 expiry_date='$expiry_date',cvv='$cvv',bank_name='$bankname',name_accoundholder='$name_accoundholder',bank_ac_num='$ac_num',charge='$charge',nric_number='$nric_number',address_person='$address_person',
		 hand_phone='$hand_phone',google_map='$google_map',doc_copy='$filename',company_doc='$filenamess',number_lock='$number_lock', handphone_number='$handphone_number', created_at='$date', account_type='$account_type',
		 k_date='$k_date', k_lock='$k_lock', guest_permission='$guest_permission',voice_recognition='$voice_recognition', 
		next_pending_time='$next_pending_time',pending_time='$pending_time',menu_type='$menu_type', custom_message='$custom_message',custom_msg_time='$custom_msg_time',
		section_required='$section_required',table_required='$table_required',cash_check='$cash_check',credit_check='$credit_check',
		wallet_check='$wallet_check',boost_check='$boost_check',grab_check='$grab_check',wechat_check='$wechat_check',touch_check='$touch_check',service_id='$service',
		fpx_check='$fpx_check',discount='$discounts',mian_merchant='$main_merchant_id',location_order='$location_order',location_range='$location_range',free_delivery='$free_delivery'
		,delivery_address_exit='$delivery_address_exit',section_exit='$section_exit',table_exit='$table_exit',pre_fill_delivery_address='$pre_fill_delivery_address',write_up_share='$write_up_share',auto_accept_order='$auto_accept_order' WHERE id='".$_SESSION['login']."'";
		// die;                                     
		$test_test = mysqli_query($conn,$qur);
		$error .= "Successfully Updated profile Details.<br>";
		if($expired_flag == false){
			$error .= "You can change the referral id after ".(183 - $num_date)." days. <br />"; 
		}
		if($expired_type_flag == false){
		    $error .= "You can change the K1/K2 tomorrow.";
		}
	}
} 
else
{
	
	$realname = $bank_data['name'];
	$company = $bank_data['company'];
	$invoice_company = $bank_data['invoice_company'];
	$merchant_remark = $bank_data['merchant_remark'];
	$register = $bank_data['register'];
	$address = $bank_data['address'];
	$gst = $bank_data['gst'];
	$sst = $bank_data['sst'];
	$sst_rate = $bank_data['sst_rate'];
	$discounts = $bank_data['discount'];
	$mian_merchant = $bank_data['mian_merchant'];
	$facsimile = $bank_data['facsimile_number'];
	$business1 = $bank_data['business1'];
	$business2 = $bank_data['business2'];
	$bank_name = $bank_data['bank_name'];
	$charge = $bank_data['charge'];
	$nric_number = $bank_data['nric_number'];
	$address_person = $bank_data['address_person'];
	$hand_phone = $bank_data['hand_phone'];
	$google_map = $bank_data['google_map'];
	$name_card = $bank_data['name_card'];
	$card_number = $bank_data['card_number'];
	$expiry_date = $bank_data['expiry_date'];
	$cvv = $bank_data['cvv'];
	$bank_ac_num = $bank_data['bank_ac_num'];
	$name_accoundholder = $bank_data['name_accoundholder'];
	$profile_pic = $bank_data['doc_copy'];
	$custom_msg_image = $bank_data['custom_msg_image'];
	$company_doc = $bank_data['company_doc'];
	$handphone_number = $bank_data['handphone_number'];
	//$referral_id = $bank_data['referred_by'];
	$number_lock = $bank_data['number_lock'];
	$google_map = $bank_data['google_map'];
	$latitude= $bank_data['latitude'];
	$longitude = $bank_data['longitude'];
    $account_type = $bank_data['account_type'];
    $k_lock = $bank_data['k_lock'];
	$shop_open = $bank_data['shop_open'];
	$membership_plan = $bank_data['membership_plan'];
	$coupon_offer = $bank_data['coupon_offer'];
	$discounted_product = $bank_data['discounted_product'];
	$spassword_need = $bank_data['spassword_need'];
	$location_order = $bank_data['location_order'];
	$delivery_plan = $bank_data['delivery_plan'];
	$location_range = $bank_data['location_range'];
	$free_delivery = $bank_data['free_delivery'];
    $merchant_code = $bank_data['merchant_code'];
	$print_ip_address = $bank_data['print_ip_address'];
    $merchant_address = $bank_data['merchant_url'];
	$order_print_setting = $bank_data['order_print_setting'];
	// $order_print_live_setting = $bank_data['order_print_live_setting'];
	$guest_permission = $bank_data['guest_permission'];
	$voice_recognition = $bank_data['voice_recognition'];
	$pending_time = $bank_data['pending_time'];
	$next_pending_time = $bank_data['next_pending_time'];
	$menu_type = $bank_data['menu_type'];
	$setup_shop = $bank_data['setup_shop']; 
	
	/*jupiter 24.02.19*/
	$cash_check = $bank_data['cash_check'];
	$credit_check = $bank_data['credit_check'];
	$wallet_check = $bank_data['wallet_check'];
	$boost_check = $bank_data['boost_check'];
	$grab_check = $bank_data['grab_check'];
	$wechat_check = $bank_data['wechat_check'];
	$touch_check = $bank_data['touch_check'];
	$fpx_check = $bank_data['fpx_check'];
	$order_extra_charge = $bank_data['order_extra_charge'];
	$delivery_rate = $bank_data['delivery_rate'];
	$order_min_charge = $bank_data['order_min_charge'];  
	$pre_fill_delivery_address = $bank_data['pre_fill_delivery_address']; 
}
/*jupiter 24.02.19*/
if(isset($_POST['payment_submit'])){
	$payment_type = $_POST['payment_type'];
	$user = $_POST['user_id'];
	$name = $_POST['merchat_name'];
	$mobile = $_POST['mobile_number'];
	$reference = $_POST['reference'];
	$prev_qr = $_POST['prev_qr'];
	$filename = $_FILES['qr_code']['name'];
	header('Content-type: application/json');
    $ds          = DIRECTORY_SEPARATOR;
    $storeFolder = 'uploads/';
    if ($filename != "") {
        $tempFile       = $_FILES['qr_code']['tmp_name'];  
        $filename       = $_FILES['qr_code']['name'];     
        $targetPath     = $storeFolder;
        $path_parts     = pathinfo($_FILES["qr_code"]["name"]);
        $extension      = $path_parts["extension"];
        $targetFile     = $targetPath . $filename;
        move_uploaded_file($tempFile, $targetFile); //6
    } else {
    	$filename = $prev_qr;
    }
    $sql = "SELECT * FROM payments WHERE type='$payment_type' and user = '$user'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if($rowCount > 0){
    	$sql = "DELETE FROM payments WHERE type='$payment_type' and user = '$user'";
    	mysqli_query($conn, $sql);
    }
    $sql = "INSERT INTO payments VALUES('', '$payment_type', '$user', '$name', '$mobile', '$reference', '$filename')";
    mysqli_query($conn, $sql);
    header('location: profile_merchant.php');
}
if(isset($_POST['method'])){
	$user = $_POST['user'];
	$payment = $_POST['payment'];
	$sql = "SELECT * FROM payments WHERE type='$payment' and user = '$user'";
	$result = mysqli_query($conn, $sql);
	$payment = mysqli_fetch_assoc($result);
	$res = array(
		"name" => $payment['name'],
		"mobile" => $payment['mobile'],
		"remark" => $payment['remark'],
		"qr_code" => $payment['qr_code']
	);
	echo json_encode($res);
	exit();
}
/*user password*/
if(isset($_POST['submit_pass']))
{
	$old_password = addslashes($_POST['old_password']);
	$new_password = addslashes($_POST['new_password']);
	$confirm_new_password = addslashes($_POST['confirm_new_password']);
	// $questions = addslashes($_POST['questions']);
	// $answers = addslashes($_POST['answer']);
	$flag = false;
	$error = "";    
	
	if($old_password == "" || $new_password == "" || $confirm_new_password == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	}
	$user_old_password = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM users WHERE id='".$_SESSION['login']."'"))['password'] ;
	// $security_questions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions FROM users WHERE id='".$_SESSION['login']."'"))['security_questions'];
	// $security_answer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_answer FROM users WHERE id='".$_SESSION['login']."'"))['security_answer'];
	if($user_old_password != $old_password)
	{
		$flag = true;
		$error .= "Old Password is incorrect.<br>";
	}
	
	if(strlen($new_password) < 6 || strlen($new_password) > 15)
	{
		$flag = true;
		$error .= "New Password must between 6 to 15 characters.<br>";
	}
	
	if($new_password != $confirm_new_password)
	{
		$flag = true;
		$error .= "New Password does not match.<br>";
	}   
	
		
	
	if($flag == false)
	{
		
		mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE id='".$_SESSION['login']."'");
		$error = "Password Successfully Changed.";
	}
}   
/*user password*/
/*fund password*/

if(isset($_POST['submit_fundpass']))
{
	$old_fundpassword = addslashes($_POST['old_fundpassword']);
	$new_fundpassword = addslashes($_POST['new_fundpassword']);
	$confirm_new_fundpassword = addslashes($_POST['confirm_new_fundpassword']);
	$questions = addslashes($_POST['questions']);
	$answers = addslashes($_POST['answer']);
	$flag = false;
	$error = "";    
	
	if($old_fundpassword == "" || $new_fundpassword == "" || $confirm_new_fundpassword == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	}
	$user_old_fundpassword = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fund_password FROM users WHERE id='".$_SESSION['login']."'"))['fund_password'] ;
	$security_questions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions FROM users WHERE id='".$_SESSION['login']."'"))['security_questions'];
	$security_answer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_answer FROM users WHERE id='".$_SESSION['login']."'"))['security_answer'];
	if($user_old_fundpassword != $old_fundpassword)
	{
		$flag = true;
		$error .= "Old Password is incorrect.<br>";
	}
	
	if(strlen($new_fundpassword) < 6 || strlen($new_fundpassword) > 15)
	{
		$flag = true;
		$error .= "New Password must between 6 to 15 characters.<br>";
	}
	
	if($new_fundpassword != $confirm_new_fundpassword)
	{
		$flag = true;
		$error .= "New Password does not match.<br>";
	}
	
		
	
	if($flag == false)
	{
		mysqli_query($conn, "UPDATE users SET fund_password='$new_fundpassword' WHERE id='".$_SESSION['login']."'");
		$error = "Fund Password Successfully Changed.";
	}
}
if(empty($_GET['ms']))
{
	$ms=$_GET['ms'];
	$url="profile_merchant.php?ms=".md5(rand());

header("Location:$url");
exit();
}
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	.account_kType{
	    margin-bottom: 10px;
	}
	/* Jupiter 24.02.19*/
	.payment_tick{
		width: 20px;
		height: 20px;
		margin-right: 15px;
	}
	.payment_label{
		margin-top: -27px;
    	margin-left: 30px;
	}
	.payment_btn{
		margin-left: 125px;
	    display: block;
	    margin-bottom: 15px;
	    margin-top: -45px;
	    line-height: 0.57143;
	}
	.custom_message_val{
		width: 100%;
		height: 200px;
		padding: 5px;
		box-sizing: border-box;
		border-radius: 5px;
		border: 1px solid #e4e9f0;
		resize: none;
	}
	#controls {
  display: flex;
}
button.pbtn{
  flex-grow: 1;
  height: 2.5rem;
  min-width: 2rem;
  border: none;
  border-radius: 0.15rem;
  background: #fb9678;
  margin-left: 2px;
  box-shadow: inset 0 -0.15rem 0 rgba(0, 0, 0, 0.2);
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  color:#ffffff;
  font-weight: bold;
  font-size: 1rem;
}
button.pbtn:hover, button:focus {
  outline: none;
  background: #fa7953;
}
button.pbtn::-moz-focus-inner {
  border: 0;
}
button.pbtn:active {
  box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.2);
  line-height: 3rem;
}
button.pbtn:disabled {
  pointer-events: none;
  background: lightgray;
}

button.pbtn:first-child {
  margin-left: 0;
}

audio {
  display: block;
  width: 100%;
  margin-top: 0.2rem;
}

#recordingsList{
	padding: 0px;
	margin: 20px 0px;
}


	</style>
	<script src="https://scripts.sirv.com/sirv.js" defer></script>  
</head>
<body class="header-light sidebar-dark sidebar-expand pace-done">
    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="container">
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
					<div class="container" > 
					    <div class="row">
					        <div class="well col-md-6">
							<form method="post" enctype="multipart/form-data" id="profile_account">
								<div class="panel price panel-red">
									<h2>Update Profile Details</h2>
									<br><br>
									<div class="form-group">
										<label>User Name</label>
										<input type="text" name="realname" class="form-control" value="<?php if(isset($realname)){ echo $realname; }?>">
									</div>
									<div class="form-group">
										<label>Name Of Company</label>
										<input type="text" name="company" class="form-control" value="<?php if(isset($company)){ echo $company; }?>">
									</div>
									<div class="form-group">
										<label>Merchant Remark</label>
										<input type="text" name="merchant_remark" class="form-control" value="<?php if(isset($merchant_remark)){ echo $merchant_remark; }?>">
									</div>
									<div class="form-group">
										<label>Name Of Company For invoice</label>
										<input type="text" name="invoice_company" class="form-control" value="<?php if(isset($invoice_company)){ echo $invoice_company; }?>">
										<small>Suggest you to write English name of company</small>
									</div>
									<div class="form-group">
										<label>Merchant Code<br></label>
										<input type="text" name="merchant_code" disabled class="form-control" value="<?php if(isset($merchant_code)){ echo $merchant_code; }?>">
									</div>
									<div class="form-group">
										<label>Subscription Plan  </label><br>
										<input class="membership_plan" type="checkbox" name="membership_plan" <?php if($membership_plan == '1') echo "checked='checked'";?>> Subscription Plan<br>
									</div>
									<div class="form-group">
										<label>Coupon Offer  </label><br>
										<input class="coupon_offer" type="checkbox" name="coupon_offer" <?php if($coupon_offer == '1') echo "checked='checked'";?>> Coupon Offer<br>
									</div>
									<div class="form-group">
										<label>Shop Opening </label><br>
										<input class="shop_open" type="checkbox" name="shop_open" <?php if($shop_open == '1') echo "checked='checked'";?>>Shop Open<br>
									</div> 
									<div class="form-group">
										<label>Discount on Product </label><br>
										<input class="discounted_product" type="checkbox" name="discounted_product" <?php if($discounted_product == '1') echo "checked='checked'";?>> Discount on product<br>
									</div> 
									<div class="form-group">
										<label>Delete Order </label><br>
										<input class="spassword_need" type="checkbox" name="spassword_need" <?php if($spassword_need == '1') echo "checked='checked'";?>> Key In Special Password<br>
									</div> 
									
									<div class="form-group">
										<label>Order Print Setting</label><br>
										<input class="order_print_setting" type="checkbox" name="order_print_setting" <?php if($order_print_setting) echo "checked='checked'";?> >Auto Print Invoice<br>
										
									</div> 
									<?php if($bank_data['fund_password']==''){ ?>		
									<div class="form-group">
										<label>Fund Password</label><br>
										<input class="form-control" type="password"  id="fund_password" name="fund_password" value="<?php echo $bank_data['fund_password'];?>">
										 <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
										<span onclick="myFunction()" id="eye_pass"> <?php echo $language['show_password']; ?> </span>   
									</div>   <?php }  else {?>
									<input class="form-control" type="hidden" name="fund_password" value="<?php echo $bank_data['fund_password'];?>">
										
									<?php } ?>
									<div class="form-group">
										<label>Special Coin Name</label><br>
										<input class="form-control" type="text" name="special_coin_name" value="<?php echo $bank_data['special_coin_name'];?>">
										
									</div>
									<div class="form-group">
										<label>Special Coin Min</label><br>
										<input class="form-control" type="text" name="special_coin_min" value="<?php echo $bank_data['special_coin_min'];?>">
										
									</div>
									<div class="form-group">
										<label>Special Coin Max</label><br>
										<input class="form-control" type="text" name="special_coin_max" value="<?php echo $bank_data['special_coin_max'];?>">
										
									</div>
									<div class="form-group">
									  <label>Paypal Payment</label>
											<input class="new_creditcard" type="checkbox" name="paypal_enable" <?php if($bank_data['paypal_enable'] == 'y') echo "checked='checked'"; ?>  > <br>
											
									</div>
									<div class="form-group">
                                        <label>Nature of Business</label>
										
										<select class='' name="service" style="">
											<option>Select Nature of Business</option>
											<?php
												$sql = mysqli_query($conn, "SELECT * FROM service WHERE status=1");
	                                           	$selected = '';
	                                           	while($data = mysqli_fetch_array($sql))
	                                           	{
	                                           		if($data['id'] == $bank_data['service_id']){
	                                           			$selected= 'selected';
	                                           		}else{
	                                           			$selected = '';
	                                           		}
	                                           	 	echo'<option value="'.$data['id'].'" '.$selected.'>'.$data['short_name'].'</option>';
	                                           	}

											?>
                                        </select>
                                    </div>
									<div class="form-group">
                                        <label>About Business</label>
										<input class="form-control" type="text" name="business_nature" maxlength="30" value="<?php echo $bank_data['business_nature'];?>">
										<small style="color:red;">Note: About Business up to 30 characters</small>
										
                                    </div>
									   <div class="form-group">
                                        <label>Printer Type <?php  $printer_profile=$bank_data['printer_profile']; ?></label>
										
                                        <select class='' name="printer_profile" style="">
										  <option <?php if($printer_profile == 'ip') echo 'selected'; ?> value="ip">IP PRINTER</option>
                                            <option <?php if($printer_profile == 'usb') echo 'selected'; ?> value="usb">USB</option>
                                          
                                        </select>
                                    </div>
									<div class="form-group">
										<label>USB Sharing Name </label>
										<input type="text" name="usb_name" class="form-control" value="<?php if(isset($bank_data['usb_name'])){ echo $bank_data['usb_name']; }?>">
									   <p>Hint :From the Control Panel, open Devices and Printers.
										Right-click the printer you want to share. Click Printer Properties, and then select the Sharing tab.
										Check Share this Printer. Under Share name, select a shared name to identify the printer. Click OK. </p>
									</div>  
									<div class="form-group">
										<label>Printer IP Address</label>
										<input type="text" name="print_ip_address" class="form-control" value="<?php if(isset($print_ip_address)){ echo $print_ip_address; }?>">
									</div>
										<div class="form-group">
										<label>Choose business partner/ main merchant<br></label>
										<!--<input type="text" name="main_merchant" class="form-control" value=""> -->
									
				                           <input type="text"  id="txtname"  name="merchant_id" class="form-control" placeholder=" Search By Merchant name" > 
				                           <br> 
				                           <ul class="dropdown-menu txtname" role="menu" aria-labelledby="dropdownMenu"  id="Dropdown_name">
				                           </ul>
				                       
									</div> 
									<div class="form-group">
										<label>Your main merchant <br></label>
										<input type="text" name="" disabled class="form-control" value="<?php if(isset($bank_data['mian_merchant'])){ echo $bank_data['mian_merchant']; } 	?>">
									</div>

									
									<div class="form-group">

									    <label>Your List of sub merchant</label><br>
									    <table class="table table-striped">
									        <thead>
                                                <tr>
                                                    <th>Name</th>           
                                                    <th>Type</th>                    
                                                    <th>Action</th>    
                                              </tr>
                                           </thead>
                                           <tbody >
                                           	<?php 
                                           	$sql = mysqli_query($conn, "SELECT * FROM users WHERE mian_merchant='".$realname."'");
                                           	
                                           	while($data = mysqli_fetch_array($sql))
                                           	 {
                                           	 	echo'<tr><td>'.$data['name'].'</td>
                                           		<td>'.$data['business1'].'</td>
                                           		<td><a href="#" style="color:red;" id="'.$data['id'].'" class="trash">Delete</a></td></tr>';
                                           	
                                              }
                                           	?>
                                          
                                         	</tbody>
                                         	
									    </table>
									</div>
									
									<div class="form-group">
										<label>Custom Message</label><br>    
										
										<div class="custom_message_container">
											<textarea class="custom_message form-control" name="custom_message" placeholder="This message pops up when your clients scan your QR codes"><?php echo $bank_data['custom_message']; ?></textarea>
											<br>
											<label>Time of the pop-up</label> <small>(Note: The time will start counting once the page is loaded.)</small>
											<input type="number" class="form-control" name="custom_msg_time" placeholder="Time in seconds. Default 5, Max. 10" max="10" min="0" value='<?php echo $bank_data['custom_msg_time']; ?>'/>
										</div>
											<div class="form-group">
										<label>Image for Custom Message</label><br>
										<input type="file" name="custom_msg_image" <?php if(isset($custom_msg_image) && $custom_msg_image == ""){ 
											//echo "required"; 
											} ?>>
										<?php
										if(isset($custom_msg_image) && $custom_msg_image != "")
										{
										?>
										<div class="container">
											 <img class="img-responsive" style="margin:0 auto;" src="customimage/<?php echo $custom_msg_image;?>" alt="">
														 
											  <button class="btn">Delete</button>
										</div>
									
										<?php
										}
										?>
									</div>
									</div>
									
									<div class="form-group">
										<label>Registration Number of the Company</label>
										<input type="text" name="register" class="form-control" value="<?php if(isset($register)){ echo $register; }?>">
									</div>
									<div class="form-group">
										<label>Address</label>
										<input type="text" name="address" class="form-control" value="<?php if(isset($address)){ echo $address; }?>">
									</div>
									<div class="form-group">
										<label>GST Register Number</label>
										<input type="text" name="gst" class="form-control" value="<?php if(isset($gst)){ echo $gst; }?>" >
									</div>
									<div class="form-group">
										<label>SST Number</label><br>
										<input type="text" name="sst" class="form-control" value="<?php if(isset($sst)){ echo $sst; }?>" >
										
									</div>

									<div class="form-group">
										<label>Set Discount</label><br>
										<input type="text" name="discounts" placeholder="%" class="form-control" value="<?php if(isset($discounts)){ echo $discounts; }?>" > 
										
									</div>
									<!--div class="form-group">
										<label>Set SST Rate (%) </label><br>
										<input type="text" name="sst_rate" placeholder="%" class="form-control" value="<?php if(isset($sst_rate)){ echo $sst_rate; }?>" > 
										
									</div!-->

									<div class="form-group">
										<label>Telephone Number<br>
										<span class="tele_num"><?php echo $bank_data['mobile_number']; ?></span> 
										</label>
									</div>  
									<div class="form-group">
										<label>Order notification number<br>
										<input type="text" name="handphone_number" class="form-control" value="<?php if(isset($handphone_number)){ echo $handphone_number; }?>" >
									</div>
									<div class="form-group input-has-value">
										<label>Referral ID<br>
										<input type="text" name="referral_id" class="form-control" value="<?php if(isset($referred_by)){ echo $referred_by; }?>" >
									</div>
									<div class="form-group">
										<label>Facsimile Number</label>
										<input type="text" name="facsimile" class="form-control" value="<?php if(isset($facsimile)){ echo $facsimile; }?>" >
									</div>
									<div class="form-group"> 
										<label>Nature Of Business 1</label><br>
										<select name= "business1" class="form-control" >
											<option value="">Select a desired option</option>
											<option <?=$business1 === "Foods and Beverage, such as restaurants, healthy foods, franchise, etc"? 'selected="selected"' : ''; ?>>Foods and Beverage, such as restaurants, healthy foods, franchise, etc</option>
											<option <?=$business1 === "Motor Vehicle, such as car wash, repair, towing, etc"? 'selected="selected"' : ''; ?>>Motor Vehicle, such as car wash, repair, towing, etc</option>
											<option <?=$business1 === "Hardware, such as household, building, renovation to end users"? 'selected="selected"' : ''; ?>>Hardware, such as household, building, renovation to end users</option>
											<option <?=$business1 === "Grocery Shop such as bread, fish, etc retails shops"? 'selected="selected"' : ''; ?>> Grocery Shop such as bread, fish, etc retails shops</option>
											<option <?=$business1 === "Clothes such as T-shirt, Pants, Bra, socks,etc"? 'selected="selected"' : ''; ?>> Clothes such as T-shirt, Pants, Bra, socks,etc</option>
											<option <?=$business1 === "Business to Business (B2B) including all kinds of businesses"? 'selected="selected"' : ''; ?>> Business to Business (B2B) including all kinds of businesses</option>
										</select>
										
									</div>
									<div class="form-group"> 
										<label>Nature Of Business 2</label><br>
										<select name= "business2" class="form-control" >
											<option value="">Select a desired option</option>
											<option <?=$business2 === "Foods and Beverage, such as restaurants, healthy foods, franchise, etc"? 'selected="selected"' : ''; ?>>Foods and Beverage, such as restaurants, healthy foods, franchise, etc</option>
											<option <?=$business2 === "Motor Vehicle, such as car wash, repair, towing, etc"? 'selected="selected"' : ''; ?>>Motor Vehicle, such as car wash, repair, towing, etc</option>
											<option <?=$business2 === "Hardware, such as household, building, renovation to end users"? 'selected="selected"' : ''; ?>>Hardware, such as household, building, renovation to end users</option>
											<option <?=$business2 === "Grocery Shop such as bread, fish, etc retails shops"? 'selected="selected"' : ''; ?>> Grocery Shop such as bread, fish, etc retails shops</option>
											<option <?=$business2 === "Clothes such as T-shirt, Pants, Bra, socks,etc"? 'selected="selected"' : ''; ?>> Clothes such as T-shirt, Pants, Bra, socks,etc</option>
											<option <?=$business2 === "Business to Business (B2B) including all kinds of businesses"? 'selected="selected"' : ''; ?>> Business to Business (B2B) including all kinds of businesses</option>
										</select>
									</div>
									<!-- second part--->
									<div class="form-group">
										<label>Bank Details</label><br>
										<input class="new_creditcard" type="checkbox" name="credit_card" value="<?php if(isset($sst)){ echo $sst; }?>" >Add a New Credit Card<br>
										<input type="checkbox" class="bank_account" name="bank_account" value="<?php if(isset($sst)){ echo $sst; }?>" >Add a New Bank Account<br>
									</div>
									<div class="form-group">
										<label>Order Required Field </label><br>
										<input class="new_creditcard" type="checkbox" name="section_required" <?php if($bank_data['section_required'] == '1') echo "checked='checked'"; ?>  > Section <br>
										<input type="checkbox" class="bank_account" name="table_required" <?php if($bank_data['table_required'] == '1') echo "checked='checked'"; ?> > Table <br>  
									</div>
									<!-- Jupiter 24.02.19 -->
									<div class="form-group">
										<label >Payment Settings</label><br>
										<input type="checkbox" class="payment_tick" name="cash_check" <?php if($cash_check == '1') echo "checked='checked'"; ?>"><p class="payment_label">Cash</p>
										<input type="checkbox" class="payment_tick" name="credit_check" <?php if($credit_check == '1') echo "checked='checked'"; ?>"><p class="payment_label">Credit Card</p>
										<input type="checkbox" class="payment_tick" name="wallet_check" <?php if($wallet_check == '1') echo "checked='checked'"; ?>"><p class="payment_label">Wallet</p>
										<input type="checkbox" class="payment_tick" name="boost_check" <?php if($boost_check == '1') echo "checked='checked'"; ?>"><p class="payment_label" >Boost Pay</p><button class="btn btn-primary payment_btn" type="button" data-toggle="modal" data-target="#settingModal" title="Boost Pay" payment-id="1">Add</button>
										<input type="checkbox" class="payment_tick" name="grab_check" <?php if($grab_check == '1') echo "checked='checked'"; ?>"><p class="payment_label" >Grab Pay</p><button class="btn btn-primary payment_btn" type="button" data-toggle="modal" data-target="#settingModal" title="Grab Pay" payment-id="2">Add</button>
										<input type="checkbox" class="payment_tick" name="wechat_check" <?php if($wechat_check == '1') echo "checked='checked'"; ?>"><p class="payment_label" >WeChat</p><button class="btn btn-primary payment_btn" type="button" data-toggle="modal" data-target="#settingModal" title="WeChat" payment-id="3">Add</button>
										<input type="checkbox" class="payment_tick" name="touch_check" <?php if($touch_check == '1') echo "checked='checked'"; ?>"><p class="payment_label" >Touch & Go</p><button class="btn btn-primary payment_btn" type="button" data-toggle="modal" data-target="#settingModal" title="Touch & Go" payment-id="4">Add</button>
										<input type="checkbox" class="payment_tick" name="fpx_check" <?php if($fpx_check == '1') echo "checked='checked'"; ?>"><p class="payment_label" >FPX</p><button class="btn btn-primary payment_btn" type="button"data-toggle="modal" data-target="#settingModal" title="FPX" payment-id="5">Add</button>
									</div>
									<!--  -->
									<div class="form-group">
										<label>Merchant Address</label><br>
										<input type="text" name="merchant_address" class="form-control" value="<?php if(isset($merchant_address)){ echo $merchant_address; }?>" >
									</div>
									<div class="form-group">
										<label>Allow Visitor Order</label><br>
										<input type="checkbox" name="guest_permission"  <?php if($guest_permission == '1') echo "checked='checked'"; ?>/>
									</div>
								
									<div class="form-group">
										<label>Hide Number</label><br>
										<input class="hide_number" type="checkbox" name="number_lock" <?php if($number_lock == '1') echo "checked='checked'";?>" ><br>
									</div>
									<div class="form-group">
										<label>Pending time(minute)</label><br>
										<input type="text" name="pending_time" class="form-control" value="<?php if(isset($pending_time)){ echo $pending_time; }?>" >
									</div>
									<div class="form-group">
										<label>Next Pending time(minute)</label><br>
										<input type="text" name="next_pending_time" class="form-control" value="<?php if(isset($next_pending_time )){ echo $next_pending_time; }?>" >
									</div>  
										<div class="form-group">
										<label>Printer Style</label><br>
										      <input type="radio" name="printer_style" value="normal" <?php if($printer_style=="normal"){echo "checked";} ?>> Normal<br>
											  <input type="radio" name="printer_style" value="section" <?php if($printer_style=="section"){echo "checked";} ?>> Section<br>
											  <input type="radio" name="printer_style" value="product" <?php if($printer_style=="product"){echo "checked";} ?>> Product  <br/>
											  <input type="radio" name="printer_style" value="combo" <?php if($printer_style=="combo"){echo "checked";} ?>> Combo( Normal + Product)  
									</div> 
									<div class="form-group">
										<label>K1/K2 Type</label><br>
										<select class='account_kType' name="account_type" style="">
										    <option <?php if($account_type == '') echo 'selected'; ?> value="">Non K1/K2</option>
										    <option <?php if($account_type == 'K1') echo 'selected'; ?> value="K1">K1</option>
										    <option <?php if($account_type == 'K2') echo 'selected'; ?> value="K2">K2</option>
										    <option <?php if($account_type == 'K1 & K2') echo 'selected'; ?> value="K1 & K2">K1 & K2</option>
										</select>
										<br />
										<input type="checkbox" class="k_lock" name="k_lock" <?php if($k_lock == '1') echo "checked='checked'";?>" >
										I only allow above members to place orders. 
										<br>
									</div> 
                                    <div class="form-group">
                                        <label>Menu Type</label>
                                        <select class='menu_type' name="menu_type" style="">
                                            <!--option <?php if($menu_type == '1') echo 'selected'; ?> value="1">Layout 1</option!-->
                                            <option <?php if($menu_type == '2') echo 'selected'; ?> value="2">Layout 2</option>
                                        </select>
                                    </div>   
									
									<!-- credit card details--->
									<div class="credit_card">
    									<div class="form-group">
    										<label>NAME ON CARD</label>
    										<input type="text" name="name_card" class="form-control" value="<?php if(isset($name_card)){ echo $name_card; }?>" >
    									</div>
    									<div class="form-group">
    										<label>CARD NUMBER</label>
    										<input type="text" name="card_number" class="form-control" value="<?php if(isset($card_number)){ echo $card_number; }?>" >
    									</div>
    									
    									<div class="form-group">
    										<label>EXPIRATION DATE</label>
    										<input type="date" name="expiry_date" class="form-control"  value="<?php if(isset($expiry_date)){ echo $expiry_date; }?>" >
    									</div>
    									<div class="form-group">
    										<label>CVV</label>
    										<input type="text" name="cvv" class="form-control" value="<?php if(isset($cvv)){ echo $cvv; }?>" >
    									</div>
									
									</div>
									<!-- end credit card detils--->
									
									<!-- Bank branch details -->
									
									<div class="branch_details">
									<div class="form-group">
										<label>Bank Name</label>
										<input type="text" name="bankName" class="form-control" value="<?php if(isset($bank_name)){ echo $bank_name; }?>">
									</div>
									<div class="form-group">
										<label>Name of Account Holder</label>
										<input type="text" name="name_accoundholder" class="form-control" value="<?php if(isset($name_accoundholder)){ echo $name_accoundholder; }?>">
									</div>
<!--
									<div class="form-group">
										<label>Branch Name</label>
										<input type="text" name="branchName" class="form-control" value="<?php if(isset($branchName)){ echo $branchName; }?>" required>
									</div>
-->
<!--
									<div class="form-group">
										<label>IFSC Code</label>
										<input type="text" name="ifsc_code" class="form-control" value="<?php if(isset($ifsc_code)){ echo $ifsc_code; }?>" required>
									</div>
-->
									<div class="form-group">
										<label>Account Number</label>
										<input type="text" name="ac_num" class="form-control" value="<?php if(isset($bank_ac_num)){ echo $bank_ac_num; }?>">
									</div>
									
									</div>
									
									<!-- end bank branch details -->
									
									<!-- end second part--->
									
									<!-- third part--->
									
									
									<div class="form-group"> 
        							<label>Person in charge of the company</label>
        							<input type="text" class="form-control" name="charge" value="<?php if(isset($charge)){ echo $charge; }?>">
        						</div>  
        						<div class="form-group">
        							<label>NRIC number of the person in charge</label>
        							<input type="text" class="form-control" name="nric_number" value="<?php if(isset($nric_number)){ echo $nric_number; }?>">
        						</div>  
        						<div class="form-group">
        							<label>Address of the person in charge</label>
        							<input type="text" class="form-control" name="address_person"  value="<?php if(isset($address_person)){ echo $address_person; }?>">
        						</div>
        						<div class="form-group">								
        							<label>Handphone number of the person in charge</label>
									<input type="text" class="form-control" name="hand_phone" value="<?php if(isset($hand_phone)){ echo $hand_phone; }?>">									
								</div>
        						
									
									<!-- end third part--->
									
									<!--fourth part--->
									
										<div class="form-group">
											<label>Add to Home Screen Logo (512*512)</label><br>
											<input type="file" name="shortcut_icon">
											<?php
											if(isset($bank_data['shortcut_icon']) && $bank_data['shortcut_icon'] != "")
											{
											?>
											
											<img src="<?php echo $site_url."/images/shortcut_icon/".$bank_data['shortcut_icon']; ?>"/>
											<?php
											}
											?>
										</div>
										<div class="form-group">
											<label> Home Screen Banner (800*400)</label><br>
											<input type="file" name="banner_image">
											<?php
											if(isset($bank_data['banner_image']) && $bank_data['banner_image'] != "")
											{
											?>
											
											<img  class="img-responsive Sirv"  src="<?php echo $image_cdn."banner_image/".$bank_data['banner_image']; ?>"/>   
											<?php
											}
											?>
										</div>   
									<div class="form-group">
										<label>Upload of the picture of NRIC of the person</label><br>
										<input type="file" name="doc_copy" <?php if(isset($profile_pic) && $profile_pic == ""){ 
											//echo "required"; 
											} ?>>
										<?php
										if(isset($profile_pic) && $profile_pic != "")
										{
										?>
										<a href="documents/<?php echo $profile_pic; ?>" target="_blank"><?php echo $profile_pic; ?></a>
										<?php
										}
										?>
									</div>
										<div class="form-group">
										<label>Upload of the SSM/Form 24/49 of the Company</label><br>
										<input type="file" name="company_doc" <?php if(isset($company_doc) && $company_doc == ""){
											 //echo "required"; 
											 } 
											 ?>>
										<?php
										if(isset($company_doc) && $company_doc != "")
										{
										?>
										<a href="documents/profile_images/<?php echo $company_doc; ?>" target="_blank"><?php echo $company_doc; ?></a>
										<?php
										}
										?>
									</div>
									<div class="form-group">							
            							<label>Address of the company as per Google Map API</label>
            							<input type="hidden" name="latitude" class="latitude" value="<?php if(isset($latitude)){ echo $latitude;}?>">
            							<input type="hidden" name="longitude" class="longitude" value="<?php if(isset($longitude)){ echo $longitude;}?>">
    									
										<input type="text" class="form-control" name="google_map" id="mapSearch" value="<?php if(isset($google_map)){ echo $google_map; }?>">										
								        <div id="map" style="height: 400px;"></div>
								    </div>
									<div class="card" style="margin: 2%;padding: 2%;">
									    <h3>Order Page </h3>
										<div class="form-group">  
											<label>Login/Logout Shop open,close  </label><br>
											   <input type="radio" name="setup_shop" value="y"  <?php if($setup_shop == "y") echo "checked='checked'"; ?>> Yes<br>
												  <input type="radio" name="setup_shop" value="n"  <?php if($setup_shop == "n") echo "checked='checked'"; ?>> No<br>
												  
										</div>
										<div class="form-group">
											<label> Delivery Plan </label><br>
											<input class="location_order" type="checkbox" name="delivery_plan" <?php if($delivery_plan == '1') echo "checked='checked'";?>>  Delivery Plan<br>
   
										</div> 
										<!--div class="form-group">
											<label> Delivery Tax</label><br>
											<input type="text" name="delivery_rate" class="form-control" value="<?php if(isset($delivery_rate)){ echo $delivery_rate; }?>" >
										 
										</div!-->
										<div cl
										<div class="form-group">
											<label>Fix Delivery Charges</label><br>
											<input type="text" name="order_extra_charge" class="form-control" value="<?php if(isset($order_extra_charge)){ echo $order_extra_charge; }?>" >
										   <small>Note: Work Only if not delivery plan is selected</small>
										</div>
										<div class="form-group">
											<label>Location Based Order </label><br>
											<input class="location_order" type="checkbox" name="location_order" <?php if($location_order == '1') echo "checked='checked'";?>> Location Order<br>
											<label>Location Range (KM)<br></label>
											<input type="text" name="location_range" maxlength='6'  class="form-control" value="<?php if(isset($location_range)){ echo $location_range; }?>">
										</div> 
										<div class="form-group">
											
											<label>KM for Free Delivery Charges & min Order Amount (KM)<br></label>
											<input type="text" name="free_delivery" maxlength='6'  class="form-control" value="<?php if(isset($free_delivery)){ echo $free_delivery; }?>">
										</div> 
										
										<div class="form-group">
											<label>Min order Amount</label><br>
											<input type="text" name="order_min_charge" class="form-control" value="<?php if(isset($order_min_charge)){ echo $order_min_charge; }?>" >
										</div>
										
										<div class="form-group">
											<label><h6>Order Required Field</h6></label><br>   
											<input class="new_creditcard" type="checkbox" name="delivery_address_exit" <?php if($bank_data['delivery_address_exit'] == '1') echo "checked='checked'"; ?>> Delivery Location<br>
											<label>Pre Fill address For Delivery Place</label> 
											<input type="text" name="pre_fill_delivery_address" id="pre_fill_delivery_address" class="form-control" value="<?php if(isset($pre_fill_delivery_address)){ echo $pre_fill_delivery_address; }?>" >
											<input class="new_creditcard" type="checkbox" name="section_exit" <?php if($bank_data['section_exit'] == '1') echo "checked='checked'"; ?>> Section<br>
											<label>Section Mandatory</label>
											<input class="new_creditcard" type="checkbox" name="section_required" <?php if($bank_data['section_required'] == '1') echo "checked='checked'"; ?>  > <br>
											<label>Section On Orderlist</label>
											<input class="new_creditcard" type="checkbox" name="section_on_orderlist" <?php if($bank_data['section_on_orderlist'] == 'y') echo "checked='checked'"; ?>  > <br>
											
											<input type="checkbox" class="bank_account" name="table_exit" <?php if($bank_data['table_exit'] == '1') echo "checked='checked'"; ?>> Table<br>
											<label>Table Mandatory</label>
											<input type="checkbox" class="bank_account" name="table_required" <?php if($bank_data['table_required'] == '1') echo "checked='checked'"; ?> > <br> 
											<label>Table On Orderlist</label>
											<input class="new_creditcard" type="checkbox" name="table_on_orderlist" <?php if($bank_data['table_on_orderlist'] == 'y') echo "checked='checked'"; ?>  > <br>
											
										</div>
										<div class="form-group">
											<label>Allow Visitor Order</label><br>
											<input type="checkbox" name="guest_permission" <?php if($guest_permission=='1') echo "checked";?>/> 
										</div>
										<div class="form-group">
											<input type="checkbox" name="voice_recognition" id="voice_recognition" <?php if($voice_recognition == 1) echo "checked"; ?> > 
											<label>Voice Recognition Order</label>
										</div>      
										<div class="form-group" id="controls-group" style="display: none;">
											<div class="recordTime">
												<span id="minutes">00</span>:<span id="seconds">00</span>
											</div>
											<input type="hidden" id="UserID" value="<?php echo $_SESSION['login']; ?>">
											<div id="controls">
												<button type="button" id="recordButton" class="pbtn">Record</button>
												<button type="button" id="pauseButton" class="pbtn" disabled>Pause</button>
												<button type="button" id="stopButton" class="pbtn" disabled>Stop</button>
											</div>
											<ol id="recordingsList"></ol>
											<div id="audioError"></div>
										</div>  
									
										<div class="form-group">
											<label>Partner Coin list</label><br>
											<input type="checkbox" name="unrecognize_coin" <?php if($profile_data['unrecognize_coin']=='1') echo "checked";?>/> 
										</div>  
										<div class="form-group">
											<label>Write up share</label><br>
											<input type="checkbox" name="write_up_share" <?php if($profile_data['write_up_share']=='1') echo "checked";?>/> 
										</div> 
										<div class="form-group">
											<label>Order Auto Accept</label><br>
											<input type="checkbox" name="auto_accept_order" <?php if($profile_data['auto_accept_order']=='1') echo "checked";?>/> 
										</div>  										
									</div>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Details">
								</div>
							</form>
						</div>
						<div class="col-md-6 well">
						    <form method="post">
        						<h3>Change Login Password</h3>
        						<div class="form-group">
        							<label>Old Password</label>
        							<input type="password" class="form-control" name="old_password">
        						</div>
        						<div class="form-group">
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_password">
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_password">
        						</div>
        						
        						<input type="submit" value="Change Password" name="submit_pass" class="btn btn-block btn-primary">
        					</form>
        					<!--fund password-->
							<?php if($bank_data['fund_password']) {?>
        				    <form method="post">
        						<h3>Change Fund Password</h3>
        						<div class="form-group">
        							<label>Old Password</label>
        							<input type="password" class="form-control" name="old_fundpassword" required>
        						</div>
        						<div class="form-group"> 
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_fundpassword" required>
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_fundpassword" required>
        						</div>
        						
        						
        						<input type="submit" value="Change Fund Password" name="submit_fundpass" class="btn btn-block btn-primary">
        					</form>	 <?php } ?>	
							<br/>
							<div class="form-group">
									    <label>Date of changing - Discount to K1/K2</label><br>
									    <table class="table table-striped" id="kType_table">
									        <thead>
                                                <tr>
                                                    <th>Date</th>           
                                                    <th>Type</th>                    
                                                    <th>Discount</th>    
                                              </tr>
                                           </thead>
                                           <tbody>
                                               <?php while ($row=mysqli_fetch_assoc($k_history)){ ?>
                                                <tr>
                                                    <td><?php echo substr($row['date'], 0, 10); ?></td>
                                                    <td><?php echo $row['type']?></td>
                                                    <?php if(strlen($row['type']) > 2){?>
                                                        <td>4%</td>
                                                    <?php } else if(strlen($row['type']) == 2){?>
                                                        <td>2%</td>
                                                    <?php } else if(strlen($row['type']) < 2){ ?>
                                                        <td>0%</td>
                                                    <?php }?>
                                                </tr>
                                               <?php }?>
                                           </tbody>
									    </table>
									</div>
					    </div>
					</div>
				</div>
                <h3 class="text_qrcode">QR Code</h3>
        		<br>
        		<?php
        		
        		$PPU = urlencode("$site_url/structure_merchant.php?sid=$user_mobile") ;
        		?>
        		<div class="col-md-3"></div>
        		<div class="well col-md-6">
					<div style="margin:10px">
						<img src="qrcode/qrcode.php?text=<?php echo $user_mobile; ?>" style="width:100%" class="text_qrcode">
					</div>
				</div>
				
                <div class="col-md-3"></div>
                 <div class="col-md-3"></div>
                <div class="well col-md-6">
                    <h3> This is sub domain website address</h3>
					<p style="font-size:18px;">Link :<a target="_blank" href="<?php  echo $site_url."/structure_merchant.php?sid=".$user_mobile; ?>"><?php echo $site_url."/structure_merchant.php?sid=".$user_mobile;; ?></a></p>
                <div style="margin:10px">
                <?php
                
                $txt = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$PPU&choe=UTF-8\" title=\"Link to KooFamilies.com\" />";
                echo $txt ;
                ?>
                </div>
                </div>
				 <div class="well col-md-6" style="text-align:center;">
                    <h3> View Merchant Qr Code</h3>
					<?php  $PPU2 = urlencode("$site_url/view_merchant.php?sid=$user_mobile") ; ?>
					<p style="font-size:18px;">Link :<a target="_blank" href="<?php  echo $site_url."/view_merchant.php?sid=".$user_mobile; ?>"><?php echo $site_url."/view_merchant.php?sid=".$user_mobile;; ?></a></p>
					<div style="margin:10px">
					<?php
					
					$txt = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$PPU2&choe=UTF-8\" title=\"Link to KooFamilies.com\" />";
					echo $txt ;
					?>
					</div>
                </div>
				 <div class="col-md-3"></div>
				  <div class="col-md-3"></div>
				  
				  <div class="well col-md-6" id="qrcode">
                    <h3> QR code for Table</h3>
					
					
                <div style="margin:10px">
                 
                    <form method="post" action="<?php echo $site_url.'/profile_merchant.php#qrcode'; ?>" >
                    <div class="form-group input-has-value">
										<label>Section</label>
										<input type="text" name="section" class="form-control" value="" required>
									</div>
									
									
									<div class="form-group input-has-value">
										<label>Table</label>
										<input type="text" name="tablehwe" class="form-control" value="" required>
									</div>
									
					<input type="submit" class="btn btn-block btn-primary" name="submithwe" value="Create QR Code">				
                    </form>
                    
                <?php
                if(isset($_POST['submithwe']))
                {
					$site_url="https://www.koofamilies.com/";    
                    $section=$_POST['section']; 
                     $tablehwe=$_POST['tablehwe'];
                     
                     $getdetail=$section."hweset".$tablehwe;
                     $basecode=base64_encode($getdetail) ;
                     
                    // echo "$site_url/view_merchant.php?sid=$user_mobile&data=$basecode";
                     
                     	$PPU = urlencode("$site_url/view_merchant.php?sid=$user_mobile&data=$basecode") ;
                     	
                     	 $txt = "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$PPU&choe=UTF-8\" title=\"Link to Google.com\" />";
                             echo $txt ;
            
                     	
                     	
                    
                }
                
                
                	
                	
             
                ?>
                </div>
                </div>
				
			</main>
        </div>
        <!-- /.widget-body badge -->
		 <div id="settingModal" class="modal fade" role="dialog">
  			<div class="modal-dialog">
			    <!-- Modal content-->
			    <div class="modal-content">
	      			<div class="modal-header">
	        			<button type="button" class="close" data-dismiss="modal">&times;</button>
	        			<h4 class="modal-title payment_title">Modal Header</h4>
	      			</div>
	      			<form class="" method="post" enctype="multipart/form-data">
	      				<input type="hidden" class="payment_type" name="payment_type" value="">
	      				<input type="hidden" class="user_id" name="user_id" value="">
	      				<div class="modal-body">
		        			<label class="">Merchant Name:</label>
		        			<input type="text" class="form-control" name="merchat_name">
		        			<label>Registered Mobile Number:</label>
		        			<input type="text" class="form-control" name="mobile_number">
		        			<label>QR Code:</label>
		        			<span class="qr_path"></span>
		        			<input type="hidden" name="prev_qr" >
		        			<input type="file" class="form-control" name="qr_code">
		        			<label>Remark/Reference:</label>
		        			<input type="text" class="form-control" name="reference">
		      			</div>
		      			<div class="modal-footer">
		      				<button type="submit" name="payment_submit" class="btn btn-primary">Submit</button>
		        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      			</div>
	      			</form>
	      			
	    		</div>
  			</div>
		</div>
    </div>
    <!-- /.widget-bg -->
    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4BfDrt-mCQCC1pzrGUAjW_2PRrGNKh_U&libraries=places" async defer></script> 
	<script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
  	<script src="js/sketch.js"></script>
</body>
</html>
<style>
select {
    height: 30px;
}
</style>
<script> 
	$(document).ready(function() {
		// $("#voice_recognition").click(function() {
			// if ($("#voice_recognition").is(":checked")) {
				// $("#controls-group").show();
			// } else {
				// $("#controls-group").hide();
			// }
		// });
	

	$('.delete_class').click(function(){
	    var tr = $(this).closest('tr'),
	        del_id = $(this).attr('id');

	    $.ajax({
	        url: "delete_page.php?delete_id="+ del_id,
	        cache: false,
	        success:function(result){
	            tr.fadeOut(1000, function(){
	                $(this).remove();
	            });
	        }
	    });
	});
	});
</script>
 <script type="text/javascript">
 	var $ = jQuery; 
   $(function() 
   {
    $( "#txtname" ).autocomplete({
    source: 'fetch_merchant.php'
    });
    
   });
   $(document).ready(function () {
       
       $("#txtname").keyup(function () {
           $.ajax({
               type: "POST",
               url: "fetch_merchant.php",
               data: {
                   keyword: $("#txtname").val()
               },
               dataType: "json",
               success: function (data) {
                   if (data.length > 0) {
                       $('#Dropdown_name').empty();
                       $('#txtname').attr("data-toggle", "dropdown");
                       $('#Dropdown_name').dropdown('toggle');
                   }
                   else if (data.length == 0) {
                       $('#txtname').attr("data-toggle", "");
                   }
                   $.each(data, function (key,value) {
   
                       if (data.length >= 0)
                           $('#Dropdown_name').append('<li role="presentation" ><a class="dropdownlivalue">' + value + '</a></li>');
                   });
               }
           });
       });
       
       $('ul.txtname').on('click', 'li a', function () {
           $('#txtname').val($(this).text());
           $("#Dropdown_name").css("display", "none");
   
       });
   });
   $(function(){
    $(document).on('click','.trash',function(){
        var del_id= $(this).attr('id');
        var ele = $(this).parent().parent();
			
	  $.ajax({
            type:'POST',
            url:'fetch_merchant.php',
            data:{'del_id':del_id},
            success: function(data){
            	if(data=="YES"){
                    ele.fadeOut().remove();
                 }else{
                        alert("can't delete the row")
                 }
             }
            });
        });
});
   /*
$(document).ready(function() {
    $("#txtname").change(function() {                
      $.ajax({    //create an ajax request to display.php
        type: "POST",
               url: "fetch_merchant.php",
               data: {
                   keyword: $("#txtname").val()
               },
               dataType: "json",   //expect html to be returned                
        success: function(response){ 
        		// alert(response);                   
            $("#append_data").append(response); 
           
        }
    });
});
}); */
    </script>   
<style>
  .tele_num{
	font-weight: 400;
    display: block;
    width: 345%;
    padding: 0.5625rem 1.2em;
    font-size: 0.875rem;
    line-height: 1.57143;
    color: #74708d;
    background-color: #fff;
    background-image: none;
    background-clip: padding-box;
    border: 1px solid #e4e9f0;
    border-radius: 0.25rem;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}
h3.text_qrcode {
    width: 100%;
}
.credit_card{
 display:none;
}
.branch_details{
display:none;
}
div#multiSelectCombo {
    width: 450px!important;
}
</style>
<script type="text/javascript">
        $(document).ready(function () {
         
			 $("input[name='exist_custom_message']").click(function(){
        		if(!$(this).prop("checked")){
		            $(".custom_message_container").hide();
		          }else{
		            $(".custom_message_container").show();
		          }
        	});
            $('.new_creditcard').click(function () {
            $('.credit_card').css('display', 'block');
            });
            
			$('.bank_account').click(function () {
             $('.branch_details').css('display', 'block');
             }); 
                
                
          });
		       function myFunction() {
  var x = document.getElementById("fund_password");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass").html('Hide Password');
			 $('#eye_slash').removeClass( "fa-eye-slash" );
            $('#eye_slash').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass").html('Show Password');
	  $('#eye_slash').addClass( "fa-eye-slash" );
            $('#eye_slash').removeClass( "fa-eye" );
  }
}
    </script>
    <script src="https://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
   
    <script>
        var colors = [
            { Name: "Foods and Beverage, such as restaurants, healthy foods,  franchise, etc" },
            { Name: "Motor Vehicle, such as car wash, repair, towing, etc" },
            { Name: "Hardware, such as  household, building, renovation to end users" },
            { Name: "Grocery Shop such as bread, fish, etc retails shops" },
            { Name: "Clothes such as T-shirt, Pants, Bra, socks,etc" },
            { Name: "Business to Business (B2B) including all kinds of businesses" },            
        ];
        
        	/* jupiter 24.02.19*/
        	$(".payment_btn").click(function(e){
        		$(".qr_path").html("");
        		var title = $(this).attr('title');
        		$(".payment_title").html(title);
        		var payment_id = $(this).attr('payment-id');
        		var user = '<?php echo $_SESSION['login'];?>';
        		$(".payment_type").val(payment_id);
        		$(".user_id").val(user);
        		$.ajax({
	                url : 'profile_merchant.php',
	                type : 'post',
	                dataType : 'json',
	                data: {payment: payment_id, user:user, method: "getPayment"},
	                success: function(data){
	                	if(data['name'] != null){
	                		$("input[name='merchat_name']").val(data['name']);
	                		$("input[name='mobile_number']").val(data['mobile']);
	                		$("input[name='prev_qr']").val(data['qr_code']);
	                		$(".qr_path").html(data['qr_code']);
	                		$("input[name='reference']").val(data['remark']);
	                	}
	                }
	            });
        	});
        	/**/
        $(function () {
            var latitude = 0;
            var longitude = 0;
             navigator.geolocation.getCurrentPosition(function(position) {
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;
                var myLatLng = {lat: latitude, lng: longitude};
                var map = new google.maps.Map(document.getElementById('map'), {
                      center: { lat: latitude, lng: longitude },
                      zoom: 20
                }); 
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    draggable: true
                });
                google.maps.event.addListener(marker, 'position_changed', function(){
                    var lat = marker.getPosition().lat();
                    var lng = marker.getPosition().lng();
                    
                    var latlng = new google.maps.LatLng(lat, lng);
                    var geocoder = geocoder = new google.maps.Geocoder();
                    
                    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            console.log(results[1].formatted_address);
                            $("#mapSearch").val(results[1].formatted_address);
                            /*if (results[1]) {
                                alert("Location: " + results[1].formatted_address);
                            }*/
                        }
                    });
                    
                    $(".latitude").val(lat);
                    $(".longitude").val(lng);
                    
                });
             });
             
             
            var searchBox = new google.maps.places.SearchBox(document.getElementById('mapSearch'));
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                places.forEach(function(place) {
                    var latitude = place.geometry.location.lat();
                    var longitude = place.geometry.location.lng();
                    $(".latitude").val(latitude);
                    $(".longitude").val(longitude);
                    var address = $("#mapSearch").val();
                });
            });
			
			
        });
    </script>  