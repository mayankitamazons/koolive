<?php
include("config.php");
// error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfiles\SimpleCapabilityProfile;


if(isset($_POST['update_ingredients'])){
    $words = $_POST['update_ingredients'];
    $id = $_SESSION['login'];
    if(!mysqli_query($conn,"UPDATE users SET preset_words='$words' WHERE id='$id'")){
        die(false);
    }
    die(true);
}  
if(isset($_POST['method']) && ($_POST['method'] == "directlogin")){
   extract($_POST);
  $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_roles='$user_roles' and id='".$login_cache_id."'"));
	if($user_data)
	{
      $user_id=$user_data['id'];
	  $_SESSION['login'] = $user_id;
   				$_SESSION['user_id'] = $user_id;
   				$_SESSION['setup_shop'] = $user_data['setup_shop'];
   				$_SESSION['referral_id'] = $user_data['referral_id'];
   				$_SESSION['name'] = $user_data['name'];
				$_SESSION['login_user_role'] = $user_data['user_role'];
   				$_SESSION['mobile'] = $user_data['mobile_number'];
       $res = array('msg'=>"User exit",'status'=>true,'user_id'=>$user_id,'user_roles'=>$user_roles);
	}
    else
    {
		$res = array('msg'=>"Fresh Entry",'status'=>false);
	}
	
    echo json_encode($res);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "productcartupdate")){ 
	 extract($_POST);
	 $up=mysqli_query($conn,"update products set add_to_cart_button='$cart_status' where id='$product_id'");
	 if($up)
		$res = array('msg'=>"Prodcut Cart Updated",'status'=>true);	
	else
	$res = array('msg'=>"Failed to update",'status'=>false);
	echo json_encode($res);
	die;
}   
if(isset($_POST['method']) && ($_POST['method'] == "onesingalidsave")){ 
	 extract($_POST);
	 $up=mysqli_query($conn,"update users set onesignal_player_id='$onesignal_player_id' where id='$user_id'");
	 if($up)
		$res = array('msg'=>"Player id Updated",'status'=>true);	
	else
	$res = array('msg'=>"Failed to update",'status'=>false);
	echo json_encode($res);
	die;
}  
if(isset($_POST['method']) && ($_POST['method'] == "riderdetailsave")){ 
	 extract($_POST);
	 $up=mysqli_query($conn,"update order_list set rider_info='$rider_info' where id='$order_id'");
	 if($up)
		$res = array('msg'=>"Rider detail updated",'status'=>true);	
	else
	$res = array('msg'=>"Failed to update",'status'=>false);
	echo json_encode($res);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "adminfeedbacksave")){
	 extract($_POST);
	 $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM feedback WHERE  feedback_id='".$selected_id."'"));
	if($user_data)
	{
		$up=mysqli_query($conn,"update feedback set remark='$remark' where feedback_id='$selected_id'");
		$res = array('msg'=>"Feedback found",'status'=>true);	
	}
	else
	{
		$res = array('msg'=>"Feedback id not found",'status'=>false);	
	}
	echo json_encode($res);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "adminprofilesave")){
   extract($_POST);
   // print_r($_POST);
   // die;
	$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  id='".$selected_user_id."'"));
	if($user_data)
	{
		if($delivery_rate!='')
		{
		
		}
		else
		{
			$delivery_rate=$user_data['delivery_rate'];
		}
		if($delivery_take_up)
		{
			if($delivery_take_up =="on") $delivery_take_up = '1';
			else $delivery_take_up = "0";
		}
		else
		{
			$delivery_take_up=$user_data['delivery_take_up'];
			
		}
		if($delivery_dive_in)
		{
			if($delivery_dive_in =="on") $delivery_dive_in = '1';
			else $delivery_dive_in = "0";
		}
		else
		{
			$delivery_dive_in=$user_data['delivery_dive_in'];
		} 
		if($vendor_comission=='')
			$vendor_comission=$user_data['vendor_comission'];  
		if($special_price_value=='')
			$special_price_value=$user_data['special_price_value'];
		if($price_hike=='')
		$price_hike=$user_data['price_hike']; 
		if($custom_msg_time=='')
		$custom_msg_time=$user_data['custom_msg_time'];  
		if($popular_restro=='')
		$popular_restro=$user_data['popular_restro'];
		if($show_merchant=='')
		$show_merchant=$user_data['show_merchant'];
        $s=mysqli_query($conn,"update users set custom_msg_time='$custom_msg_time',price_hike='$price_hike',popular_restro='$popular_restro',show_merchant='$show_merchant',special_price_value='$special_price_value',vendor_comission='$vendor_comission',delivery_rate='$delivery_rate',delivery_take_up='$delivery_take_up',delivery_dive_in='$delivery_dive_in' where id='$selected_user_id'");
		if($s)
		$res = array('msg'=>"Updated",'status'=>true);  
		else
		$res = array('msg'=>"Failed to update",'status'=>false);	
	}
    else
    {
		$res = array('msg'=>"User Not Found",'status'=>false);	
	}
    
    echo json_encode($res);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "marketbal")){
	extract($_POST);
	$m_detail= mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM users WHERE name='$tags_merchant' and user_roles='2' LIMIT 1")); 
	$coin_merchant_id=$m_detail['id'];  
	if($coin_merchant_id) 
	{
	 $q="select sum(amount) as total_amount from tranfer as t where receiver_id in(select user_id from unrecoginize_coin where merchant_id='$coin_merchant_id') and coin_merchant_id='$coin_merchant_id'";
		$parq=mysqli_query($conn,$q);
		$p_total=mysqli_fetch_assoc($parq)['total_amount'];
		if($p_total==null)
			$p_total=0;
	}
	else
	{
		$p_total=0;
	}
	echo $p_total;
}
if(isset($_POST['method']) && ($_POST['method'] == "savelimit")){
	extract($_POST);
	if($user_id && $merchant_id && $coin_max_limit && $limit_class)
	{
		$checkmerchant=mysqli_query($conn,"select * from   unrecoginize_coin WHERE user_id='$user_id' and merchant_id='$merchant_id' and status=1");
		$totalrecord=mysqli_num_rows($checkmerchant);
		if($totalrecord)
		{
			$res=array('status'=>false,'msg'=>'Already Exit');	
		}
		else
		{
			if($limit_class=="A")
				$coin_limit=200;
			 else if($limit_class=="B")
				$coin_limit=500;
			else if($limit_class=="C")
				$coin_limit=5000;
			else if($limit_class=="D")
				$coin_limit=10000;
			$q = mysqli_query($conn,"INSERT INTO unrecoginize_coin(user_id,merchant_id,coin_max_limit,status,coin_class,coin_limit) VALUES ('$user_id', '$merchant_id', '$coin_max_limit', '1','$limit_class','$coin_limit')");
			if($q)
				$res=array('status'=>true,'msg'=>'Updated');  
		}
	}     
	else{
		$res=array('status'=>false,'msg'=>'Required Parameter missing');	
	}
	echo json_encode($res);
	die;    
}
if(isset($_POST['method']) && ($_POST['method'] == "userdetailbyname")){
	extract($_POST);
	 $q="SELECT * FROM users WHERE name='".$transfer_name."'";
	// die;
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn,$q));
	if($p_detail)
	{
		$p_detail=array('status'=>true,'msg'=>'User Detail','data'=>$p_detail);    
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Mobile No is Not Register');
	}  
	echo json_encode($p_detail);
	die;
}

if(isset($_POST['method']) && ($_POST['method'] == "userdetail")){
	extract($_POST);
	if (substr($mobile_number, 0,2) === "60") {
			
	}
	else
	{
		$mobile_number="60".$mobile_number;
	}
	
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='".$mobile_number."'"));
	
	if($p_detail)
	{
		$both_user='n';  
		$user_check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_roles='1' and mobile_number='".$mobile_number."'"));
	    $mer_check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' and mobile_number='".$mobile_number."'"));
		if($user_check && $mer_check)
		$both_user='y';
		$p_detail=array('status'=>true,'msg'=>'User Detail','data'=>$p_detail,'both_user'=>$both_user);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Mobile No is Not Register');
	}  
	echo json_encode($p_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "userselectedinfo")){
	extract($_POST);
	if (substr($mobile_number, 0,2) === "60") {
			
	}
	else
	{
		$mobile_number="60".$mobile_number;
	}
	
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_roles='$user_selected_role' and  mobile_number='".$mobile_number."'"));
	
	if($p_detail)
	{
		
		$p_detail=array('status'=>true,'msg'=>'User Detail','data'=>$p_detail);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Mobile No is Not Register');
	}  
	echo json_encode($p_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "editpartner")){
	extract($_POST);
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM unrecoginize_coin WHERE id='".$plan_id."'"));
	// print_R($p_detail);
	// die;
	if($p_detail)
	{

		if($coin_class=="A")
			$coin_limit=100;
		if($coin_class=="B")
			$coin_limit=500;
		if($coin_class=="C")
			$coin_limit=5000;
		if($coin_class=="D")
			$coin_limit=10000;
		mysqli_query($conn, "UPDATE unrecoginize_coin SET  coin_max_limit ='$coin_max_limit',coin_class='$coin_class',coin_limit='$coin_limit' WHERE id='$plan_id'");   

		mysqli_query($conn, "UPDATE unrecoginize_coin SET  coin_max_limit ='$coin_max_limit' WHERE id='$plan_id'");

		$p_detail=array('status'=>true,'msg'=>'status updated','data'=>$p_detail);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Fail to update status');
	}  
	echo json_encode($p_detail);
	die;  
}

if(isset($_POST['method']) && ($_POST['method'] == "savename")){
	 $user_id=$_POST['user_id'];
	 $fund_username=$_POST['fund_username'];
	 $fund_password=$_POST['fund_password'];
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'"));
	
	if($p_detail)
	{

		$u_id=$p_detail['id'];
		// echo "UPDATE users SET name='$fund_username',fund_password='$fund_password' WHERE id='$u_id'";
		  // die;   
		mysqli_query($conn, "UPDATE users SET name='$fund_username',fund_password='$fund_password' WHERE id='$u_id'");   

		mysqli_query($conn, "UPDATE users SET name='$fund_username',fund_password='$fund_password' WHERE id='$user_id'");

		$p_detail=array('status'=>true,'msg'=>'status updated','data'=>$p_detail);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Fail to update status');
	}  
	echo json_encode($p_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "changemembership")){
	 $user_id=$_POST['user_id'];
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'"));
	
	if($p_detail)
	{
		$membership=$p_detail['membership'];
		if($membership=="trial")
			$new_m="full";
		else
			$new_m="trial";
		
		mysqli_query($conn, "UPDATE users SET membership='$new_m' WHERE id='$user_id'");
		$p_detail=array('status'=>true,'msg'=>'Membership status updated','data'=>$p_detail);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Fail to update Membership status');
	}  
	echo json_encode($p_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "resendlink")){
	$cm=$user_mobile=$_POST['user_mobile'];
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='".$user_mobile."'"));
	if($p_detail)
	{
		$user_role=$p_detail['user_roles'];
		$user_id=$p_detail['id'];
		
		$code = uniqid();
		$current_url = "https://".$_SERVER['HTTP_HOST'].""."/login.php";   
		// gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id");
	    
		 mysqli_query($conn, "UPDATE users SET verification_code='$code' WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
		  // gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id");
		Print("Sending to one way sms .This link is only valid for 10 minutes" . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id"));
		// gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id");
		// Print("Sending to one way sms " . );
		

		$p_detail=array('status'=>true,'msg'=>'Resnd Link send to mobile Number','data'=>$p_detail);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Mobile No is Not Registered');
	}  
	echo json_encode($p_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "resendlinkonmerchant")){
	$cm=$user_mobile=$_POST['user_mobile'];
	
	$merchant_mobile=$_POST['merchant_mobile'];
	$p_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='".$user_mobile."'"));
	if($p_detail)
	{
		$user_role=$p_detail['user_roles'];
		$user_id=$p_detail['id'];
		// $cm=$user_mobile="919001025477";
		$code = uniqid();
		$current_url = "https://www.koofamilies.com/view_merchant.php?sid=".$merchant_mobile;
		mysqli_query($conn, "UPDATE users SET verification_code='$code' WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
		gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url&code=$code&id=$user_id");
		// Print("Sending to one way sms " . );
		$p_detail=array('status'=>true,'msg'=>'Resnd Link send to mobile Number','data'=>$p_detail);
	}
	else
	{
		$p_detail=array('status'=>false,'msg'=>'Mobile No is Not Registered');
	}  
	echo json_encode($p_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "topupmerchant")){
	$topup_amount=$_POST['topup_amount'];
	$user_id=$_POST['user_id'];
	if($topup_amount && $user_id)
	{
		$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$user_id."'"));
		
		if($merchant_detail)
		{
			$s_coin_name=$merchant_detail['special_coin_name'];
			$old_bal=$merchant_detail['balance_usd'];
			if($old_bal=='')
				$old_bal=0;
			$new_bal=$old_bal+$topup_amount;
			
			$datetime = date('Y-m-d H:i:s');
			$creaed_on = strtotime($datetime);
			// $creaed_on=strtotime(date('d-m-y h:i:s'));
			$update=mysqli_query($conn, "UPDATE users SET balance_usd='$new_bal' WHERE id='$user_id'");
			if($update)
			{
				$array_detail=array('status'=>true,'msg'=>'Topup Done','amount'=>$new_bal);
				mysqli_query($conn,"INSERT INTO tranfer (`sender_id`, `amount`, `receiver_id`, `wallet`, `created_on`, `status`, `details`,`invoice_no`,`type_method`,`coin_merchant_id`) VALUES ('6419', '$topup_amount', '$user_id', '$s_coin_name', '$creaed_on', '1', 'self topup','','topup','$user_id')");
				mysqli_query($conn,"INSERT INTO `wallet_history` (`sender_id`, `total_amount`,`order_ids`) VALUES ('6419','$topup_amount','')");
				$noti_string = 'Self  Topup '.$topup_amount." ".$s_coin_name;   
				$datetime = date('Y-m-d H:i:s');
				$created_on = strtotime($datetime);   
				$noti = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$user_id.'", "'.$noti_string.'", "receive", "'.$created_on.'", "0")';
				$notification = mysqli_query($conn, $noti);   
			}   
			else
			{
				$array_detail=array('status'=>false,'msg'=>'Topup Failed');	
			}
		}
		else
		{
			$array_detail=array('status'=>false,'msg'=>'Merchant id not found');
		}
		
	}
	else
	{
		$array_detail=array('status'=>false,'msg'=>'Required Parameter missing');
	}
	echo json_encode($array_detail);
	die;
}
if(isset($_POST['method']) && ($_POST['method'] == "paypalcash")){
	$order_id=$_SESSION['order_id'];
	if($_SESSION['login'])
		$user_id=$_SESSION['login'];
	else
		$user_id=$_SESSION['tmp_login'];
	// echo "SELECT * FROM order_list WHERE user_id='".$user_id."' and id='$order_id'";
	// die;
	if($order_id)
	{
		$order_q = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id='".$user_id."' and id='$order_id'");
		$total_rows=mysqli_num_rows($order_q);
		if($total_rows>0)
		{
		  $odata=mysqli_fetch_assoc($order_q);
		  $o_status=$odata['status'];
		  if($o_status==3)
		  {
			mysqli_query($conn, "update order_list set wallet='cash',status='2' where id='$order_id'");  
				$array_detail=array('status'=>true,'msg'=>"Order Completed");
		  }
		  else
		  {
			$o_status=false;
			$msg="Something Went Wrong ,Contact to support with Tras id:".$odata['id'].$odata['invoice_no']; 
			$array_detail=array('status'=>false,'msg'=>$msg,'type'=>'wrong');
		  }  
		}
		else
		{
			$o_status=false;
			// $msg="Order Not Completed, Try Again or try different method";
			$array_detail=array('status'=>false,'msg'=>'Order Not Completed, Try Again or try different method','type'=>'wrong');
		}
	}
	else
	{
		
		$array_detail=array('status'=>false,'msg'=>'Failed Try again','type'=>'wrong');
	}
	echo json_encode($array_detail);
	 die;
}
if(isset($_POST['method']) && ($_POST['method'] == "deductfund")){
	$mobile_num=$_POST['mobile_num'];
	if(strpos($mobile_num, "60") === 0) 
	{
		$sender_mobile=$_POST['mobile_num'];
	}
	else
	{
	   $sender_mobile="60".$_POST['mobile_num'];	
	}
	// echo $sender_mobile;
	// die;
	$merchant_id=$_POST['merchant_id'];
	$session_user_id=$_SESSION['login'];
	$paid_amount=$_POST['paid_wallet_amount'];
	$selected_invoice_id=$_POST['selected_invoice_id'];
	$selected_invoice_id=ltrim($selected_invoice_id,",");
	$selected_invoice_id=rtrim($selected_invoice_id,",");
	$sender_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='".$sender_mobile."'"));
	$reciver_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
	if($sender_detail)
	{
		 $user_balance=$sender_detail['balance_myr'];
		 $rec_bal=$reciver_detail['balance_myr'];
		 $sender_id=$sender_detail['id'];
		 $creaed_on=strtotime(date('d-m-y h:i'));
		// $merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
		// $merchant_balance=$sender_detail['balance_myr'];
		if($user_balance>=$paid_amount)
		{
			 $new_bal=$user_balance-$paid_amount;
			 $rec_new_bal=$rec_bal+$paid_amount;
			
			$update=mysqli_query($conn, "UPDATE users SET balance_myr='$new_bal' WHERE mobile_number='$sender_mobile'");
			$update2=mysqli_query($conn, "UPDATE users SET balance_myr='$rec_new_bal' WHERE id='$merchant_id'");
			mysqli_query($conn, "UPDATE order_list SET status='1',wallet='ewallet',order_process_by='$session_user_id' WHERE id in($selected_invoice_id)");
			mysqli_query($conn,"INSERT INTO tranfer (`sender_id`, `amount`, `receiver_id`, `wallet`, `created_on`, `status`, `details`,`invoice_no`,`type_method`) VALUES ('$sender_id', '$paid_amount', '$merchant_id', 'MYR', '$creaed_on', '1', 'ewallet used','$selected_invoice_id','ewallet')");
			mysqli_query($conn,"INSERT INTO `wallet_history` (`sender_id`, `total_amount`,`order_ids`) VALUES ('$sender_id','$new_bal','$selected_invoice_id')");
			$new_bal=number_format($new_bal,2);
			if($update)
				 $array_detail=array('status'=>true,'msg'=>'Order Completed','pending_bal'=>$new_bal);   
			else
			 $array_detail=array('status'=>false,'msg'=>'Failed to Deduct Balance');	
		}
		else
		{
			 $array_detail=array('status'=>false,'msg'=>'Not have Sufficient Balance in Wallet','type'=>'lessbal');	
		}
	}
	else
	{
	  $array_detail=array('status'=>false,'msg'=>'Invalid Mobile No of Sender','type'=>'wrong');
	}
	 echo json_encode($array_detail);
	 die;
}

if(isset($_POST['method']) && ($_POST['method'] == "favorite")){
    if($_POST['favorite'] == 1){
        mysqli_query($conn, "INSERT INTO favorities VALUES('', ".$_POST['user_id'].", ".$_POST['merchant_id'].")");
    } else {
        mysqli_query($conn, "DELETE FROM favorities WHERE user_id = ".$_POST['user_id']." AND favorite_id = ".$_POST['merchant_id']);
    }
}
if(isset($_POST['method']) && ($_POST['method'] == "shiftupload")){
   $user_id=$_POST['user_id'];
   // print_r($_FILES);
   // die;
    if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $extensions= array("jpeg","jpg","pdf");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 20971520){
         $errors[]='File size must be excately 20 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"shiftreport/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
   }
}
    if( isset( $_POST['method']) && ( $_POST['method'] == "usercheckold" )  ) {
	  $mobile_number=$_POST['mobile_number'];
	  $merchant_id=$_POST['merchant_id'];
	  $special_coin_name=$_POST['special_coin_name'];
	  $mobile_check="60".$mobile_number;
	   // echo "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'";
	   // die;
	   $loginmatch = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'"));
	  
	   if($loginmatch)
	   {
		    $user_id=$loginmatch['id'];
		    if($special_coin_name)
		   {
			  
				$walletcheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  coin_balance>0 and coin_active='y' and user_id='$user_id' and merchant_id ='".$merchant_id."'"));
				if($walletcheck)
				{
					$balance_special=$walletcheck['coin_balance'];
				}
				else
				{
					$balance_special=0;
				}
		   
		   }
		   else
		   {
			   $balance_special=0;
		   }
		   
		   extract($loginmatch);
		   // $loginmatch['balance_usd']=number_format($balance_usd,2);
		   // $loginmatch['balance_inr']=number_format($balance_inr,2);
		   // $loginmatch['balance_myr']=number_format($balance_myr,2);
		   $balance_inr=$loginmatch['balance_inr'];
		   $loginmatch['balance_special']=$balance_special;  
		   if($balance_inr=='')
			   $loginmatch['balance_inr']="0.00";
		   //  user has to be added in defalut or trial plan first 
		     // $entry_plan=mysqli_fetch_assoc(mysqli_query($conn,"select membership_plan.user_id from membership_plan as plan where plan.user_id=''");
		    // $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$merchant_id' ";
			$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$merchant_id'"));
			if($total_shop['total_order_amount'])
			{
				$total_shop_amount=number_format($total_shop['total_order_amount'],2);
				$total_shop=$total_shop['total_order_amount'];
			}
			else
			{
				$total_shop_amount=0;
				$total_shop=0;
			}
			$local_coin = mysqli_fetch_assoc(mysqli_query($conn,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$merchant_id' and user_mobile='$mobile_check'"));
			if($local_coin['total_coin']>0)
			{
				$total_shop=$local_coin['total_coin']+$total_shop;
			}
			 // $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$merchant_id' and membership_plan.total_max_order_amount>='$total_shop' and  membership_plan.total_min_order_amount<='$total_shop'";
			// echo "SELECT * FROM membership_plan WHERE user_id='$merchant_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount";
			// die;
			// echo "SELECT * FROM membership_plan WHERE user_id='$merchant_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount";
			// die;
			//get default plan for merchant if you exit on that or not 
			// echo $defalut_plan="select plan.id from membership_plan as plan   inner join user_membership_plan.plan_id=plan.id where plan.user_id='$merchant_id' and default_plan='y' and user_membership_plan.user_id='$user_id'";
			$defalut_plan="select count(plan.id) as total_count from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
			and u.user_id='$user_id'";
			$defalutplan = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan))['total_count'];
			if($defalutplan==0)
			{
				$item = array('msg'=>"No Defalut plan set",'status'=>true,'plan_label'=>'','plan_benefit'=>'','data'=>$loginmatch);
			} 
			else
			{
				$user_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$merchant_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount"));
				// $user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));
				if($user_plan)
				{ 
					$plan_type=$user_plan['plan_type']; 
					if($plan_type=="fix")
					$plan_la="Rm ".$user_plan['plan_benefit']." off";
					else 
					$plan_la=$user_plan['plan_benefit']." %";
				   $plan_label="You will recieve MemberShip Discount of ".$plan_la." on Total Order Amount";
				   $plan_benefit=$plan_la;
				}      
				else
				{
					$plan_label='';
					$plan_benefit='';
				}
				$item = array('msg'=>"User Already Exit",'status'=>true,'data'=>$loginmatch,'plan_label'=>$plan_label,'plan_benefit'=>$plan_benefit);
			}
			
			
	   }
	   else
	   {
		   $item = array('msg'=>"new user",'status'=>false,'plan_label'=>'','plan_benefit'=>'');
		   
	   }  
	   echo json_encode($item);
		die;       
  }
    if( isset( $_POST['method']) && ( $_POST['method'] == "usercheck" )  ) {
	  $mobile_number=$_POST['mobile_number'];
	  $merchant_id=$_POST['merchant_id'];
	  $special_coin_name=$_POST['special_coin_name'];
	  $mobile_check="60".$mobile_number;
	   // echo "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'";
	   // die;
	   $loginmatch = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'"));
	  
	   if($loginmatch)
	   {
		    $user_id=$loginmatch['id'];
		    if($special_coin_name)
		   {
			  
				$walletcheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  coin_balance>0 and coin_active='y' and user_id='$user_id' and merchant_id ='".$merchant_id."'"));
				if($walletcheck)
				{
					$balance_special=$walletcheck['coin_balance'];
				}
				else
				{
					$balance_special=0;
				}
		   
		   }
		   else
		   {
			   $balance_special=0;
		   }
		   
		   extract($loginmatch);
		   // $loginmatch['balance_usd']=number_format($balance_usd,2);
		   // $loginmatch['balance_inr']=number_format($balance_inr,2);
		   // $loginmatch['balance_myr']=number_format($balance_myr,2);
		   $balance_inr=$loginmatch['balance_inr'];
		   $loginmatch['balance_special']=$balance_special;  
		   if($balance_inr=='')
			   $loginmatch['balance_inr']="0.00";
		   //  user has to be added in defalut or trial plan first 
		     // $entry_plan=mysqli_fetch_assoc(mysqli_query($conn,"select membership_plan.user_id from membership_plan as plan where plan.user_id=''");
		    // $query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$user_id' and user_membership_plan.merchant_id = '$merchant_id' ";
			
			
			 
			// $defalutplan = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan))['total_count'];
			$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
				and u.user_id='$user_id'";
			$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));
			$defalutplan=$defalutarray['total_count'];
			$created_date=$defalutarray['created'];
			if($defalutplan==0)
			{
				if($loginmatch)
				$item = array('userstatus'=>'old','msg'=>"No Defalut plan set",'status'=>true,'plan_label'=>'','plan_benefit'=>'','data'=>$loginmatch);
				else
				$item = array('userstatus'=>'new','msg'=>"No Defalut plan set",'status'=>false,'plan_label'=>'','plan_benefit'=>'');	
			} 
			else
			{
				$total_shop=mysqli_fetch_assoc(mysqli_query($conn,"select sum(total_cart_amount) as total_order_amount from order_list where user_id='$user_id' and merchant_id='$merchant_id' and created_on>='$created_date'"));
				if($total_shop['total_order_amount'])
				{
					$total_shop_amount=number_format($total_shop['total_order_amount'],2);
					$total_shop=$total_shop['total_order_amount'];
				}
				else
				{
					$total_shop_amount=0;
					$total_shop=0;
				}
				$local_coin = mysqli_fetch_assoc(mysqli_query($conn,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$merchant_id' and user_mobile='$mobile_check' and order_date>='$created_date'"));
				if($local_coin['total_coin']>0)
				{
					$total_shop=$local_coin['total_coin']+$total_shop;
				}
				$user_plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE user_id='$merchant_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount"));
				// $user_plan = mysqli_fetch_assoc(mysqli_query($conn,$query));
				if($user_plan)
				{ 
					$plan_type=$user_plan['plan_type']; 
					if($plan_type=="fix")
					$plan_la="Rm ".$user_plan['plan_benefit']." off";
					else 
					$plan_la=$user_plan['plan_benefit']." %";
				   $plan_label="You will recieve MemberShip Discount of ".$plan_la." on Total Order Amount";
				   $plan_benefit=$plan_la;
				}      
				else
				{
					$plan_label='';
					$plan_benefit='';
				}
				
				$item = array('userstatus'=>'old','msg'=>"User Already Exit",'status'=>true,'data'=>$loginmatch,'plan_label'=>$plan_label,'plan_benefit'=>$plan_benefit);
			}
			
			
	   }
	   else
	   {
		   $item = array('userstatus'=>'new','msg'=>"new user",'status'=>false,'plan_label'=>'','plan_benefit'=>'');
		   
	   }     
	   echo json_encode($item);
		die;       
  }
if(isset($_POST['method']) && ($_POST['method'] == "getNoneImageProduct")){
    if($_POST['type'])
	{
		// $catparent=$_POST['mainclick'];
		
		$resultcat = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$_POST['id']."' and catparent = '".$_POST['category']."' order by id limit 0,1");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		$category_name=$resultdata['category_name'];
		$category_name = mysqli_real_escape_string($conn, $category_name);
		 $category_name = str_replace(' ', '-', $category_name);
		$result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$category_name."' and image='' and status=0");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		
	}
	else
	{
		// echo "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image!='' and status=0";
		// die;
	   $result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image='' and status=0");
	
	}
	$array_product = array();
    while ($row=mysqli_fetch_row($result)){
		
        $item = array("image"=>$row[6],"product_name"=> $row[2], "category"=> $row[3], 'type'=> $row[4], 'price'=> $row[5], 'remark'=>$row[8], 'id' => $row[0]);
        array_push($array_product, $item);
    }

    echo json_encode($array_product);
}
if(isset($_POST['method']) && ($_POST['method'] == "getImageProduct")){
	
	if($_POST['type'])
	{
		// $catparent=$_POST['mainclick'];
		
		$resultcat = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$_POST['id']."' and catparent = '".$_POST['category']."' and status='0' order by id limit 0,1");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		   $category_name=$resultdata['category_name'];
		// die;
		// $category_name="Diam-Sum-点心";
		 $category_name = mysqli_real_escape_string($conn, $category_name);
		 $category_name = str_replace(' ', '-', $category_name);
	
		$result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$category_name."' and image!='' and status=0");
		$resultdata=mysqli_fetch_array($resultcat,MYSQLI_ASSOC);
		
	}
	else
	{
		// echo "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image!='' and status=0";
		// die;
	   $result = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$_POST['id']."' and category = '".$_POST['category']."' and image!='' and status=0");
	
	}
    
    $array_product = array();
    while ($row=mysqli_fetch_row($result)){
		
        $item = array("image"=>$row[6],"product_name"=> $row[2], "category"=> $row[3], 'type'=> $row[4], 'price'=> $row[5], 'remark'=>$row[8], 'id' => $row[0]);
        array_push($array_product, $item);
    }

    echo json_encode($array_product);
}
if(isset($_POST['method']) && ($_POST['method'] == "getFavoriteByBusiness")){
    $id = $_POST['user_id'];
    $type = $_POST['type'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
	if($latitude && $longitude)
	{
		$q="SELECT users.name, user_id, favorite_id, users.latitude, users.longitude, users.account_type,
      (6371 * ACOS ( COS ( RADIANS (".$_POST['latitude'].")) * COS ( RADIANS(users.latitude)) * COS(RADIANS(users.longitude) - RADIANS(".$_POST['longitude']."))
       + SIN(RADIANS(".$_POST['latitude'].")) * SIN(RADIANS(users.latitude)))) AS distance
    FROM favorities
    inner  join users on favorities.favorite_id=users.id
    where  user_id='$id' AND (users.service_id = '$type')";
	}
	else
	{
		$q="SELECT users.name, user_id, favorite_id, users.latitude, users.longitude, users.account_type
                             FROM favorities INNER JOIN users ON favorities.favorite_id = users.id
                             WHERE user_id='$id' AND (users.service_id = '$type')";
	}
	// echo $q;
	// die;
    $result = mysqli_query($conn,$q);

    $array_favorite = array();
    while ($row=mysqli_fetch_assoc($result)){

        // $sql_transaction = "SELECT COUNT(id) ordered_num
							// FROM order_list
							// WHERE user_id = '".$id."' AND merchant_id = '". $row[2]."' AND STATUS='1'";
        // $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
        // $sql_favorite = "SELECT COUNT(id) favorite_num
    						// FROM favorities
    						// WHERE favorite_id = '".$row[2]."'";
        // $result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));
       // print_R($row);
	   if($row['distance'])
		   $distance=number_format($row['distance'],2);
	   else
		   $distance='';
	   
        $item = array("distance"=>$distance,"name"=> $row['name'], "id"=> $row['user_id'], 'favorite_id'=> $row['favorite_id']);
        array_push($array_favorite, $item);
    }

    echo json_encode($array_favorite);
}

if(isset($_POST['method']) && ($_POST['method'] == "getNearbyRestaurants")){
    $id = $_POST['user_id'];
    $type = $_POST['type'];
    $result = mysqli_query($conn, "SELECT users.name, users.id user_id, users.latitude, users.longitude, users.account_type
                             FROM users 
                             WHERE user_roles='2' AND (users.business1 = '$type' OR users.business2 = '$type') limit 0,5");

    $array_nearby = array();
    while ($row=mysqli_fetch_assoc($result)){
        $sql_transaction = "SELECT COUNT(id) ordered_num
							FROM order_list
							WHERE user_id = '".$id."' AND merchant_id = '". $row['user_id']."' AND STATUS='1'";
        $result_transaction = mysqli_fetch_assoc(mysqli_query($conn,$sql_transaction));
        $sql_favorite = "SELECT COUNT(id) favorite_num
    						FROM favorities
    						WHERE favorite_id = '".$row['user_id']."'";
        $result_favorite = mysqli_fetch_assoc(mysqli_query($conn,$sql_favorite));

        if($id == "") $transaction_num = 0;
        else $transaction_num = $result_transaction['ordered_num'];

        $account_type = $row['account_type'];
        if($row['account_type'] != "") $account_type = $row['account_type'].", ";

        $item = array("name"=> $row['name'], "id"=> $row['user_id'], 'account_type'=>$account_type, 'latitude'=>$row['latitude'], 'longitude'=>$row['longitude'], 'order_num'=>$transaction_num, 'favorite_num'=>$result_favorite['favorite_num']);
        array_push($array_nearby, $item);
    }

    echo json_encode($array_nearby);
}

if(isset($_POST['method']) && ($_POST['method'] == "k_type")){
    $id = $_POST['id'];
    $comment = $_POST['complain'];
    $image = $_POST['image'];
    $role = $_POST['role'];
    if($role == '1'){
        mysqli_query($conn, "UPDATE k1k2_history SET user_comment='$comment', user_complain='$image' where id='$id'");
    } else if($role == '2'){
        mysqli_query($conn, "UPDATE k1k2_history SET merchant_comment='$comment', merchant_complain='$image' where id='$id'");
    }
}

if(isset($_POST['method']) && ($_POST['method'] == "getUnreadMsg")){
    $id = $_POST['id'];

    $data = mysqli_query($conn, "SELECT sender, COUNT(id) num FROM chat_history WHERE STATUS = 'unread' and receiver='$id' GROUP BY sender");
    $array_unread = array();
    while ($row=mysqli_fetch_assoc($data)){
        $item = array("num"=> $row['num'], "sender"=>$row['sender']);
        array_push($array_unread, $item);
    }
    echo json_encode($array_unread);
}


if(isset($_POST['method']) && ($_POST['method'] == "getOrderDetail")){
    $id = $_POST['id'];

    $data = mysqli_query($conn, "SELECT * FROM order_list WHERE id='$id'");
    $array_detail = array();
    while ($row=mysqli_fetch_assoc($data)){
        $user_id = $row['user_id'];
        $merchant_id = $row['merchant_id'];
        $varient_type = $row['varient_type'];
        $product_ids = explode(",",$row['product_id']);
        $product_qty = explode(",", $row['quantity']);
        $product_amt = explode(",", $row['amount']);
        $product_code = explode(",", $row['product_code']);
        $remark_ids = explode("|",$row['remark']);
        $location = isset($row['location']) ? $row['location'] : '';
        $section_type = isset($row['section_type']) ? $row['section_type'] : '';
        $table_type = isset($row['table_type']) ? $row['table_type'] : '';
        $user_id = isset($row['user_id']) ? $row['user_id'] : '';
        $array_product_names;
        for($i = 0;  $i < count($product_ids); $i++){
            if(is_numeric($product_ids[$i])){
                $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
            } else {
                $product_name = $product_ids[$i];
            }
            $array_product_names[$i] = $product_name;
        }
        // $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
		$order_name=$row['user_name'];
		$user_mobile=$row['user_mobile'];
        $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register,address FROM users where id='$merchant_id'"));
        $register = $ref_result['register'];
        $merchant_name = $ref_result['name'];
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];  
		if($ref_result['address'])
		{
			$register=$ref_result['address'];
		}
		else
		{
			$register=$ref_result['register'];
		}
        $item = array('user_mobile'=>$user_mobile,'varient_type'=>$varient_type,'product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type,'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
        array_push($array_detail, $item);     
    }  
    echo json_encode($array_detail);
}

if(isset($_POST['method']) && ($_POST['method'] == "getOrderDetailByIdAndInvoice")){
    $invoice_id = $_POST['invoice_no'];
    $id = $_POST['id'];
    $data = mysqli_query($conn, "SELECT * FROM order_list WHERE invoice_no='$invoice_id' AND id='$id'");
    $array_detail = array();
    while ($row=mysqli_fetch_assoc($data)){
        $user_id = $row['user_id'];
        $merchant_id = $row['merchant_id'];
		   $varient_type = $row['varient_type'];
        $product_ids = explode(",",$row['product_id']);
        $product_qty = explode(",", $row['quantity']);
        $product_amt = explode(",", $row['amount']);
        $remark_ids = explode("|",$row['remark']);
        $product_code = explode(",", $row['product_code']);
        $location = isset($row['location']) ? $row['location'] : '';
        $section_type = isset($row['section_type']) ? $row['section_type'] : '';
        $table_type = isset($row['table_type']) ? $row['table_type'] : '';
        $user_id = isset($row['user_id']) ? $row['user_id'] : '';
        for($i = 0;  $i < count($product_ids); $i++){
            if(is_numeric($product_ids[$i])){
                $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
            } else {
                $product_name = $product_ids[$i];
            } 
            $array_product_names[$i] = $product_name;
        }
        $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
        $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, gst, sst, register FROM users where id='$merchant_id'"));
        $merchant_name = $ref_result['name'];
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];
        $register = $ref_result['register'];
        $item = array('varient_type'=>$varient_type,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type, 'location' => $location, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
        array_push($array_detail, $item);
    }
    echo json_encode($array_detail);
}
function generatePIN($digits = 4){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}
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
if( isset( $_POST['method']) && ( $_POST['method'] == "sendotp" )  ) {
	$otp = generatePIN();
	$login_token = generatePIN();
    // $_SESSION['is_new_user'] = true;
    $_SESSION['otp_code'] = $otp;
    // $_SESSION['guest_mobile_number'] = $cm;
    $cm=$_POST['usermobile'];
    $merchant_id=$_POST['merchant_id'];
    $_SESSION['step'] = 'otp';
	if($merchant_id)
	{
		$merchant_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='".$merchant_id."'"));
		$merchant_name=$merchant_detail['name'];
		$sms_msg="Link to create shortcut "."https://www.koofamilies.com/structure_merchant.php?merchant_id=".$merchant_id."&l=".$login_token;
		gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$otp is your otp code for Koo Families $sms_msg");
	}
    // gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "One time password for Koo Families is $otp");
	else
	{
    gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$otp is your otp code for Koo Families ");  
	}
	 $item = array('otp'=>$otp,'status'=>true);
      	 mysqli_query($conn,"UPDATE `users` SET `user_otp` = '$otp',`login_token`='$login_token' WHERE `users`.`mobile_number` ='$cm'");
		echo json_encode($item);
		die;
}
if( isset( $_POST['method']) && ( $_POST['method'] == "sendotpwithlink" )  ) {
	$otp = generatePIN();
	$login_token = generatePIN();
    // $_SESSION['is_new_user'] = true;
    $_SESSION['otp_code'] = $otp;
    // $_SESSION['guest_mobile_number'] = $cm;
    $cm=$_POST['usermobile'];
	$u_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE mobile_number='".$cm."'"));
    $user_id=$u_detail['id'];
    // $merchant_id=$_POST['merchant_id'];
    $_SESSION['step'] = 'otp';
	$rand= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,4);
	$url="https://www.koofamilies.com/orderlist.php?did=".$user_id."&vs=".$rand."&ot=y";    
	$send_sms="OTP is ".$otp.", or clicking on ".$url." to view your past order";
    // gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$otp is your otp code for Koo Families ");  
    gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm",$send_sms);  
	
	 $item = array('otp'=>$otp,'status'=>true);
      	 mysqli_query($conn,"UPDATE `users` SET `user_otp` = '$otp',`login_token`='$login_token' WHERE `users`.`mobile_number` ='$cm'");
		echo json_encode($item);
		die;
}
if( isset( $_POST['method']) && ( $_POST['method'] == "forgotpass2" )  ) {
	$cm=$_POST['usermobile'];
	if(substr( $cm, 0,2) === "60")
	{
	}
	else
	{
		$cm="60".$cm;
	}
	$otp = generatePIN();
	// $cm="+919001025477";
	$user_role=1;
	$data = mysqli_query($conn, "SELECT  password,isLocked FROM users WHERE mobile_number='$cm' ");
	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);  
	// print_R($row);
	// die;
	$lock_status = $row['isLocked'];
	$password = $row['password'];
	
	if($lock_status == 1)
	{
		$error .= "Sorry, the user account blocked, please contact support.<br>";
	}
	if($error)
	{
	   $item = array('msg'=>$error,'status'=>false);	
	}
	else
	{  
		$rand =mt_rand();
		// $forgot_url = "https://".$_SERVER['HTTP_HOST']."/forgot_password.php?rand=".$rand."&mn=".$cm;
		 mysqli_query($conn, "UPDATE users SET user_otp='$otp',rand_num='$rand',resetdate='".time()."' WHERE mobile_number='$cm'");
		 // $cm="+919001025477";
		  gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "$otp is your otp code for Koo Families Account Reset");  
		   // gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url");
		 // gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url");
		  
		 $item = array('otp'=>$otp,'msg'=>"Sms for resetting password has been sent to this mobile number",'status'=>true);
	}
	echo json_encode($item);
	die;
	
}
if( isset( $_POST['method']) && ( $_POST['method'] == "forgotresetpassword" )  ) {      
   $cm=$_POST['usermobile'];
   $password=$_POST['password'];
	// $cm="+919001025477";
	$user_role=1;
	// echo "SELECT  password,isLocked FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role' ";
	// die;
	$data = mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm'");

	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	 $count = mysqli_num_rows($data);
	
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
		 $item = array('msg'=>$error,'status'=>false);
	}
	else
	{
		$data=mysqli_fetch_assoc($data);
		 $user_id=$data['id'];
		// die;
		   unset($_SESSION['tmp_login']);   
		
		$_SESSION['login'] = $user_id;
						$_SESSION['user_id'] = $user_id;
						$_SESSION['setup_shop'] = $data['setup_shop'];
						$_SESSION['referral_id'] = $data['referral_id'];
						$_SESSION['name'] = $data['name'];
						$_SESSION['login_user_role'] = $data['user_roles'];
						$_SESSION['mobile'] = $data['mobile_number'];
		mysqli_query($conn, "UPDATE users SET password='$password',user_otp='' WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
		
		 $item = array('msg'=>"Password Change Successfully",'status'=>true,'data'=>$data);
	}
	echo json_encode($item);
	die;
}
if( isset( $_POST['method']) && ( $_POST['method'] == "forgotpass" )  ) {
	$cm=$_POST['usermobile'];
	// $cm="+919001025477";
	$user_role=1;
	$data = mysqli_query($conn, "SELECT  password,isLocked FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);      
	$lock_status = $row['isLocked'];
	$password = $row['password'];
	
	if($lock_status == 1)
	{
		$error .= "Sorry, the user account blocked, please contact support.<br>";
	}
	if($error)
	{
	   $item = array('msg'=>$error,'status'=>false);	
	}
	else
	{
		$rand =mt_rand();
		$forgot_url = "https://".$_SERVER['HTTP_HOST']."/forgot_password.php?rand=".$rand."&mn=".$cm;
		 mysqli_query($conn, "UPDATE users SET rand_num='$rand',resetdate='".time()."' WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
		 // $cm="+919001025477";
		   gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url");
		 // gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url");
		  
		 $item = array('msg'=>"Sms for resetting password has been sent to this mobile number",'status'=>true);
	}
	echo json_encode($item);
	die;
	
}
if( isset( $_POST['method']) && ( $_POST['method'] == "userjoin" )  ) {
	$user_id=$_POST['user_id'];
	$order_id=$_POST['order_id'];
	$usermobile=$_POST['usermobile'];
	$login_password=$_POST['login_password'];
	if($user_id)
	{
		  mysqli_query($conn, "UPDATE users SET password='$login_password', guest_user='active' where id='$user_id'");
		  mysqli_query($conn, "UPDATE order_list SET newuser='join' where id='$order_id'");
		  $item = array('status'=>true);
      
		echo json_encode($item);
		die;
	}
}
if( isset( $_POST['method']) && ( $_POST['method'] == "neworder" )  ) {
	$last_id=$_POST['last_id'];
	
	$merchant_id=$_POST['merchant_id'];

	$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,order_place FROM order_list WHERE id>$last_id AND merchant_id='$merchant_id' ORDER BY ID DESC LIMIT 0,1"));
	
	if($data['id'])
	{
		$array_detail = array();
		 $item = array('id'=>$data['id'],'order_place'=>$data['order_place'],'status'=>true);
           // array_push($array_detail, $item);
		   // print_R($item);
		echo json_encode($item);
	}
	else
	{
		 // $array_detail ='';
        echo '';
	}
	
		
}
if( isset( $_POST['method']) && ( $_POST['method'] == "getUnPrintedOrder2" )  ) {
    $id = $_POST['id'];

    $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, id, order_print_setting,order_print_live_setting, sst, gst, register FROM users WHERE id='".$id."'"));
    if( $ref_result['order_print_setting'] === 'on' ) {
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];  
        $register = $ref_result['register'];
        $merchant_name = $ref_result['name'];
		  $varient_type = $row['varient_type'];
		  if($ref_result['order_print_live_setting']=='on')
		  {
			$data = mysqli_query($conn, "SELECT * FROM order_list WHERE status=0 AND merchant_id='$id' AND auto_print!='1' and  created_timestamp >= NOW() - INTERVAL 10 MINUTE ORDER BY ID DESC LIMIT 10");
        
		  }
		  else
		  {
			$data = mysqli_query($conn, "SELECT * FROM order_list WHERE status=0 AND merchant_id='$id' and  order_place='local' and auto_print!='1' and  created_timestamp >= NOW() - INTERVAL 10 MINUTE ORDER BY ID DESC LIMIT 10");
          
		  }
		  
		  $array_detail = array();
        while ( $row=mysqli_fetch_assoc($data) ) {
            $user_id = $row['user_id'];
            $order_id = $row['id'];
            $merchant_id = $row['merchant_id'];
			
            $wallet = isset($row['wallet']) ? $row['wallet'] : '';
            $product_ids = explode(",",$row['product_id']);
            $product_qty = explode(",", $row['quantity']);
            $product_amt = explode(",", $row['amount']);
            $product_code = explode(",", $row['product_code']);
            $remark_ids = explode("|",$row['remark']);
            $location = isset($row['location']) ? $row['location'] : '';
            $section_type = isset($row['section_type']) ? $row['section_type'] : '';
            $table_type = isset($row['table_type']) ? $row['table_type'] : '';
            $user_id = isset($row['user_id']) ? $row['user_id'] : '';
            $array_product_names = [];
            for($i = 0;  $i < count($product_ids); $i++){
                if(is_numeric($product_ids[$i])){
                    $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
                } else {
                    $product_name = $product_ids[$i];
                }
                $array_product_names[$i] = $product_name;
            }
            // $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
			$order_name=$row['user_name'];
			$user_mobile=$row['user_mobile'];
            $item = array('user_mobile'=>$user_mobile,'varient_type'=>$varient_type,'product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type,'location' => $location, "wallet" => $wallet, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
            array_push($array_detail, $item);
			
        }
        echo json_encode($array_detail);
    } else {
        $array_detail = array();
        echo json_encode($array_detail);
    }
}
if( isset( $_POST['method']) && ( $_POST['method'] == "getUnPrintedOrder" )  ) {
    $id = $_POST['id'];

    $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, id, order_print_setting, sst, gst, register FROM users WHERE id='".$id."'"));
    if( $ref_result['order_print_setting'] === 'on' ) {
        $sst = $ref_result['sst'];
        $gst = $ref_result['gst'];  
        $register = $ref_result['register'];
        $merchant_name = $ref_result['name'];
		  $varient_type = $row['varient_type'];
        $data = mysqli_query($conn, "SELECT * FROM order_list WHERE status=0 AND merchant_id='$id' AND auto_print!='1' ORDER BY ID DESC LIMIT 10");
        $array_detail = array();
        while ( $row=mysqli_fetch_assoc($data) ) {
            $user_id = $row['user_id'];
            $order_id = $row['id'];
            $merchant_id = $row['merchant_id'];
			
            $wallet = isset($row['wallet']) ? $row['wallet'] : '';
            $product_ids = explode(",",$row['product_id']);
            $product_qty = explode(",", $row['quantity']);
            $product_amt = explode(",", $row['amount']);
            $product_code = explode(",", $row['product_code']);
            $remark_ids = explode("|",$row['remark']);
            $location = isset($row['location']) ? $row['location'] : '';
            $section_type = isset($row['section_type']) ? $row['section_type'] : '';
            $table_type = isset($row['table_type']) ? $row['table_type'] : '';
            $user_id = isset($row['user_id']) ? $row['user_id'] : '';
            $array_product_names = [];
            for($i = 0;  $i < count($product_ids); $i++){
                if(is_numeric($product_ids[$i])){
                    $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_name FROM products WHERE id ='".$product_ids[$i]."'"))['product_name'];
                } else {
                    $product_name = $product_ids[$i];
                }
                $array_product_names[$i] = $product_name;
            }
            // $order_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users where id='$user_id'"))['name'];
			$order_name=$row['user_name'];
			$user_mobile=$row['user_mobile'];
            $item = array('user_mobile'=>$user_mobile,'varient_type'=>$varient_type,'product_ids'=>$product_ids,'register' => $register, 'sst' => $sst, 'gst' => $gst, 'user_id' => $user_id, 'product_code' => $product_code, 'table_type' => $table_type,'section_type'=>$section_type,'location' => $location, "wallet" => $wallet, 'remark' => $remark_ids, 'invoice_no' => $row['invoice_no'] , 'status' => $row['status'] , 'id' => $row['id'] , 'username' =>$order_name, 'merchantname' => $merchant_name, 'product_name' => $array_product_names, 'product_qty' => $product_qty, 'product_amt' => $product_amt);
            array_push($array_detail, $item);
			
        }
        echo json_encode($array_detail);
    } else {
        $array_detail = array();
        echo json_encode($array_detail);
    }
}
if( isset( $_POST['method']) && ( $_POST['method'] == "normalorder" ) ) {
	$print='n';
	
	$order = $_POST['order'];
	// print_R($order);
	// die;
	$product_ids=$order['product_ids'];
	$mer_id=670;
    $date = $_POST['date'];
    $time = $_POST['time'];
	
    $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, print_ip_address,printer_style,printer_profile,usb_name FROM users WHERE id='".$_SESSION['login']."'"));
	// print_R($ref_result);
	// die;
	
	$ip_address = $ref_result['print_ip_address'];
	$print_report=OrderCustomprint($ip_address,$order,$date,$time,$conn,$ref_result);
	echo json_encode($print_report);
	die;
}
if( isset( $_POST['method']) && ( $_POST['method'] == "pintOrder" ) ) {
	$print='n';
	
	$order = $_POST['order'];
	// print_R($order);
	// die;
	$product_ids=$order['product_ids'];
	$mer_id=670;
    $date = $_POST['date'];
    $time = $_POST['time'];
	
    $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, print_ip_address,printer_style,printer_profile,usb_name FROM users WHERE id='".$_SESSION['login']."'"));
    // $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, print_ip_address,printer_style FROM users WHERE id='".$mer_id."'"));
    // print_R($ref_result);
	// die;
	 $printer_style=$ref_result['printer_style'];
	
     if(($printer_style=="product") || ($printer_style=="combo"))
	{
		if($printer_style=="combo")
		{
			$ip_address = $ref_result['print_ip_address'];
			$print_report=OrderCustomprint($ip_address,$order,$date,$time,$conn,$ref_result);
		}
		// print_R($product_ids);
		// die;
		$prdouct_str=implode(",",$product_ids);
		  $prdouct_str=$order['product_id'];
		$p_array=explode(',',$prdouct_str);
	$product_result=[];
	foreach($product_ids as $p)
	{
		
		$product_result[] = mysqli_fetch_array(mysqli_query($conn, "SELECT products.id,products.print_ip_address,products.product_name,products.product_type,products.remark,products.printer_ip_2,products.printer_profile,products.usb_name FROM products WHERE products.id in($p)"));
		
	}
		//$product_result = mysqli_fetch_all(mysqli_query($conn, "SELECT products.id,products.print_ip_address,products.product_name,products.product_type,products.remark,products.printer_ip_2 FROM products WHERE products.id in($prdouct_str)"));
	 
	 // $prow=mysqli_fetch_array($product_result);
	  // print_R($order);
	  // die;
		$i=0;
		
		
		$remarkarray=$order['remark'];
		
		
		$product_qty=$order['product_qty'];
		foreach ($product_result as $key => $item) {
			$s_t=$item['printer_profile'];
			$s_name=$item['usb_name'];
			// print_R($item);
			// die;
			$printer_pro=$item['printer_profile'];
			if($printer_pro=="usb")
			{
				if($item['usb_name'])
				{
					$usb_name=$item['usb_name'];
					$d['product_id']=$item[0];
					$d['product_qty']=$product_qty[$i];
					$d['product_name']=$item[2];
					$d['remark']=$remarkarray[$i];
					$d['product_type']=$item[3];
					$d['p_remark']=$item[4];
					$d['printer_profile']=$item['printer_profile'];
					$d['usb_name']=$item['usb_name'];
					$arr[$usb_name][$key]=$d;
				}
			}  
			else
			{
				$usb_name='';
				if($item[1])
				{
					$d['product_id']=$item[0];
					$d['product_qty']=$product_qty[$i];
					$d['product_name']=$item[2];
					$d['remark']=$remarkarray[$i];
					$d['product_type']=$item[3];
					$d['p_remark']=$item[4];
					$d['printer_profile']=$item['printer_profile'];
					$d['usb_name']=$item['usb_name'];
					$arr[$item[1]][$key]=$d;
				}
				if($item[5])
				{
					
					$d['product_id']=$item[0];
					$d['product_qty']=$product_qty[$i];
					$d['product_name']=$item[2];
					$d['remark']=$remarkarray[$i];
					$d['product_type']=$item[3];
					$d['p_remark']=$item[4];
					$d['printer_profile']=$item['printer_profile'];
					$d['usb_name']=$item['usb_name'];
					$arr2[$item[5]][$key]=$d;
				}
			}
			$i++;
		}
		$section_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id='".$order['section_type']."'"));
			$order['section_name']=$section_name=$section_data['name'];
		// print_R($arr);
		// die;
		foreach($arr as $kp=>$p)
		{
			if($kp)
			{
				$kp=$kp;
				
				$print_report=singleorderprint($kp,$p,$date,$time,$order,$conn,$s_t,$s_name);
			}
			// else
			// {
				// $kp = $ref_result['print_ip_address'];
			// }
			// $print_report=singleorderprint($kp,$p,$date,$time,$order,$conn,$printer_pro,$usb_name);
			// break;
		}
		// print_R($arr2);
		// die;   
		foreach($arr2 as $kp=>$p)
		{
			if($kp)
			{
				$kp=$kp;
			
				$print_report=singleorderprint($kp,$p,$date,$time,$order,$conn,$s_t,$s_name);
				//$print_report=singleorderprint($kp,$p,$date,$time,$order,$conn,$printer_pro,$usb_name);
			}
			// else
			// {
				// $kp = $ref_result['print_ip_address'];
			// }
			
			// break;
		}
		$order_id=$order['id'];
		mysqli_query($conn,"UPDATE `order_list` SET `auto_print` = '1' WHERE `order_list`.`id` ='$order_id'");
        
	}
	else if($printer_style=="section")
	{
		// section based printing 
		$section_type=$order['section_type'];
		$section_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, printer_ip FROM sections WHERE id='$section_type'"));
		 $ip_address = $section_result['printer_ip'];
		$ref_result=[];
		$print_report=OrderCustomprint($ip_address,$order,$date,$time,$conn,$ref_result);
	}
	else
	{
		// if( ! isset( $ref_result['print_ip_address'] ) ) {
			// echo('print_setting_error');
			// die();
		// }
		$ip_address = $ref_result['print_ip_address'];
		$print_report=OrderCustomprint($ip_address,$order,$date,$time,$conn,$ref_result);
	}
    // echo $ip_address;
	// die;
	// die;
    //OrderCustomprint($ip_address,$order,$date,$time,$conn);
	echo json_encode($print_report);
	die;
	
}
if(isset($_POST['method']) && ($_POST['method'] == "subproduct")){
	$product_id = $_POST['product_id'];
	// $product_id =433;
	$result = array();
	$total_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE product_id='".$product_id."'");
	 $ref_result=mysqli_affected_rows($conn);

	if($ref_result)
	{
		$res['status']=200;
		while ($row=mysqli_fetch_assoc($total_rows)){
			$item = array("id" => $row['id'], "name" => $row['name'], 
        				'product_price' => $row['product_price']);
        
			array_push($result, $item);
		}
		 
		$res['data']=$result;
	}
	else
	{
		$res['status']=404;
		// $res['data']=ref_result;
	}
	echo json_encode($res);
	die;
}
function singleorderprint($ip_address,$data,$date,$time,$order,$conn,$s_t,$s_name)
{
	// echo $ip_address;
	// $ip_address=192.168.2.250;
	  
	   // die;  
		if( $data == null ) {
			$res['status']=false;
			$res['message']="Failed to make print Due to Order Blank";
			
			//echo( "empty" );
			}
		else
		{
			
			try {
				  if($s_t=="usb")
					{
						// echo "usb side";
						// $usb_name=$merchat_detail['usb_name'];
						$connector = new WindowsPrintConnector($s_name);
					}
					else
					{
						// echo "ip side";
						$connector = new NetworkPrintConnector($ip_address, 9100,5);
					}
				

			} catch( Exception $e ) {
				echo('print_setting_error');
				echo(print_r($e, true));
				// die();
				$res['status']=false;
				$res['message']="Printer is not connected";
			}
			// $profile = CapabilityProfile::load("default");
			$printer = new Printer($connector);
			$printer -> getPrintConnector() -> write(PRINTER::ESC . "B" . chr(4) . chr(1));
			$i=0;
			$size_number = 3; 
			$size_name = 15;
		    $size_qty = 3;
			$p_number=5;
			$size_remark=12;
			
			try {
				// $printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				// $printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text(  $order['id'] . '-' . $order['invoice_no'] . "\n" );
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				// $printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text("  Section : " . $order['section_name'] . "       "."Table : " .$order['table_type']. "\n");
				// $printer -> textChinese("示例文本打印机!\n\n");
				 // $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				// $printer -> text("  Section : " . $order['section_type'] . "\n");   
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				// $printer -> selectPrintMode(Printer::MODE_FONT_A);
				// $printer -> text("\n"); 
				$printer -> setEmphasis(false);
				$printer -> text( '  ' . $date . ' ' . $time . "\n");
				$printer -> text("\n");
				
				// $printer -> text( "  No  Name                                   Qty" . "\n");   
				// $printer -> text("\n"); 
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> setEmphasis(true);
					$size_name = 11;
					$dcount=0;
				$v_str=$order['varient_type'];
				$v_array=explode("|",$v_str);
				// print_R($v_array);
				// die;
				$dcount=0;
				$invoce_id=$order['invoice_no'];
				// $merchant_id=$order['merchant_id'];
				$v_order=1;
				foreach($data as $d)
				{
						// $pr_str=$p[0];
					// print_R($pr_str);
					// die;
					$product_id=$d['product_id'];
								$name=$d['product_name'];
								$qty=$d['product_qty'];
								$remark=$d['remark'];
								$p_code=$d['product_type']; 
								$product_remark=$d['p_remark'];
					$number = $i + 1;
					$j=0;
					
					$printer -> textChinese( ' '.$name."\n");  
					$printer -> textChinese( ' ( '.$p_code.' )          '. $qty ."\n");
					 // $printer -> text( '   '.  $p_code."\n");
					if(strlen($remark)>0)
						 {
						 $printer -> textChinese( '   '.$remark."\n"); 
						 }
						if(strlen($product_remark)>0)
						 {
						 $printer -> textChinese( '   '.$product_remark."\n"); 
						 }
					
					$v_data =mysqli_query($conn, "SELECT varient_id FROM order_varient WHERE merchant_id='".$_SESSION['login']."' and product_id='$product_id' and invoice_no='$invoce_id' and v_order='$v_order'");
					while ($srow=mysqli_fetch_assoc($v_data)){
						$v_id=$srow['varient_id'];
						$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id='$v_id'");
						while ($srow1=mysqli_fetch_assoc($sub_rows)){  
							// print_R($srow1); 
							// die;
							  $v_name=$srow1['name'];
							// die;
							 // die;
							if($v_name)
							{
								$printer -> textChinese( '   '.$v_name."\n"); 
							}
						}
						$v_name='';
					}
					// print_R($varray);
					$dcount++;
					$v_order++;
						$printer -> text("\n");
					
				}
				// $printer -> text(  $order['id'] . '-' . $order['invoice_no'] . "\n" );
				
					$i++;
				
				$printer -> text("\n");
				// $printer -> text("\n");   
				$printer -> cut( Printer::CUT_FULL, 3 ); 

				 $res['status']=true;
				$res['message']="Print Successfully";
				// print_R($res);
				// die;
			}
			finally {
				$printer -> close();
			}
		}
		return  $res;
}
function OrderCustomprint($ip_address,$order,$date,$time,$conn,$merchat_detail)
	{     
	     // print_R($order);
		 // die;
	   	if( $order == null ) {
			$res['status']=false;
			$res['message']="Failed to make print Due to Order Blank";
			
			//echo( "empty" );
			} else {
				
				// $ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$order['user_id']."'"));
			// if( ! isset( $ref_result['mobile_number'] ) ) {
				// $res['status']=false;
			// $res['message']="User id Not found";
				
			// }
			// print_R($merchat_detail);
			 $printer_t=$merchat_detail['printer_profile'];
		      
			try {
					if($printer_t=="usb")
					{
						$usb_name=$merchat_detail['usb_name'];
						$connector = new WindowsPrintConnector($usb_name);
					}
					else
					{
						$connector = new NetworkPrintConnector($ip_address, 9100,5);
					}
				
				
			} catch( Exception $e ) {
				//echo('print_setting_error');
				//echo(print_r($e, true));
				// die();
				$res['status']=false;
				$res['message']="Printer is not connected";
			}
			// $res['status']=false;
			// $res['message']="Printer is not connected";
			//echo(print_r($connector, true));
			
			
			// 3 + 16 + 8 + 5 + 7 + 7
			$mobile = $order['user_mobile'];
			$section_id=$order['section_type'];
			$section_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM sections WHERE id='".$order['section_type']."'"));
			// print_R($section_data);
			// die;
			$section_name=$section_data['name'];
			$invoce_id=$order['invoice_no'];
			$order_id=$order['id'];
			$printer = new Printer($connector);
			$printer -> getPrintConnector() -> write(PRINTER::ESC . "B" . chr(4) . chr(1));
			try {
				
				$printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( $order['merchantname'] . "\n" );
				// $printer -> text("\n");
				if($order['register'])
				{
					$printer -> selectPrintMode(Printer::MODE_FONT_A);
					$printer -> setEmphasis(false);
					$printer -> text( '( ' . $order['register'] . ' )' . "\n" );
					// $printer -> text("\n");
				}
				if($order['username'])
				{
					$printer -> setEmphasis(true);
					$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
					$printer -> textChinese( "Customer : " . $order['username'] . "\n" );
					// $printer -> text("\n");
					// $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
					 // $printer -> selectPrintMode(Printer::MODE_FONT_A);
				}
				if($mobile)
				{
					$printer -> setEmphasis(false);
					$printer -> text( 'Phone : ' . $mobile . "\n" );
					// $printer -> text("\n");
				}
				
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> setEmphasis(false);
				// $printer -> text("\n");
				$location = $order['location'];
				$words = explode(" ", $location);
				$rows_locations = [];
				$rows_location = '';
				for( $i = 0 ; $i < sizeof( $words ) ; $i ++ ) {
					
					$word = $words[$i];
					$word .= ' ';
					if( strlen( $rows_location ) + strlen( $word ) < 30 ) {
						$rows_location .= $word;
						if( $i == sizeof( $words ) - 1 ) {
							array_push( $rows_locations, $rows_location);
						}
					} else {
						array_push( $rows_locations, $rows_location);
						$rows_location = $word;
						if( $i == sizeof( $words ) - 1 ) {
							array_push( $rows_locations, $rows_location);
						}
					}
				}
				foreach( $rows_locations as $item ) {
					$printer -> text( ' ' . $item . "\n" );
					// $printer -> text("\n");
				}
				if($order['gst'])
				{
				 $printer -> text( 'GST ID : ' . $order['gst'] . "\n" );
				}
				// $printer -> text("\n");
				if($order['sst'])
				{
				 $printer -> text( 'SST NO : ' . $order['sst'] . "\n" );
				}
				// $printer -> text("\n");
				// $printer -> text("\n");
				
				if($order['invoice_no'])
				{
					$printer -> barcode( $order['id'] . '-' . $invoce_id, Printer::BARCODE_CODE39 );  
				}     
				// $printer -> text("\n");   
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text(  $order['id'] . '-' . $invoce_id . "\n" );
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				// $printer -> text("\n");
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				$printer -> text("\n");
				$printer -> setEmphasis(true);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text("  Table : " . $order['table_type'] . "  "."Section : " .$section_name. "\n");
				 // $printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				// $printer -> text("  Section : " . $order['section_type'] . "\n");   
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> text("\n"); 
				$printer -> setEmphasis(false);
				$printer -> text( '  ' . $date . ' ' . $time . "\n");
				$printer -> text("\n");
				$printer -> selectPrintMode(Printer::MODE_FONT_A);
				$printer -> text( "  No  Name( Code )    Qty  Remark  Price  Amount " . "\n");
				$printer -> text("\n");
				$total = 0;
				$qty_total = 0;
				$v_order=1;
				
				for( $i = 0 ; $i < sizeof( $order['product_name'] ) ; $i ++ ) {
					
					if( $order['product_qty'][$i] && $order['product_amt'][$i] ) {
						$amount = $order['product_qty'][$i] * $order['product_amt'][$i];
					} else {
						$amount = 0;
					}
					$qty_total += $order['product_qty'][$i];
					$total += $amount;
					$amount=number_format($amount,2);
					$total=number_format($total,2);
					$remark = isset($order['remark'][$i]) ? $order['remark'][$i] : '';
					$product_code = isset($order['product_code'][$i]) ? $order['product_code'][$i] : '';
					$product_code  = '(' . $product_code . ')';
					$name = $order['product_name'][$i];
					$product_id = $order['product_ids'][$i];
					$qty = $order['product_qty'][$i];
					$price = $order['product_amt'][$i];
					$name .= $product_code;
					$number = $i + 1;
					$size_number = 3;
					$size_name = 12;
					$size_remark = 7;
					$size_qty = 4;
					$size_price = 6;
					$size_amount = 6;
					$lines = max(intval( strlen($number) / $size_number) , intval( strlen($name) / $size_name) , intval( strlen($remark) / $size_remark) , intval( strlen($qty) / $size_qty) , intval( strlen($price) / $size_price) , intval( strlen($amount) / $size_amount) );
					$lines ++;
					$v_data =mysqli_query($conn, "SELECT varient_id FROM order_varient WHERE product_id='$product_id' and invoice_no='$invoce_id' and v_order='$v_order'");
					for( $j = 0 ; $j < $lines ; $j++) {  
						$number_print = '';
						if( strlen($number) > ($j) * $size_number ) {
							$number_print = substr($number, $j * $size_number, min($size_number, strlen($number) - ($j) * $size_number));
						}
						$number_print = str_pad($number_print,  $size_number, "   ");
						$name_print = '';
						if( strlen($name) > ($j) * $size_name ) {
							$name_print = substr($name, $j * $size_name, min($size_name, strlen($name) - ($j) * $size_name));
						}
						$name_print = str_pad($name_print,  $size_name, "   ");
						$remark_print = '';
						if( strlen($remark) > ($j) * $size_remark ) {
							$remark_print = substr($remark, $j * $size_remark, min($size_remark, strlen($remark) - ($j) * $size_remark));
						}
						$remark_print = str_pad($remark_print,  $size_remark, "   ");
						$qty_print = '';
						if( strlen($qty) > ($j) * $size_qty ) {
							$qty_print = substr($qty, $j * $size_qty, min($size_qty, strlen($qty) - ($j) * $size_qty));
						}
						$qty_print = str_pad($qty_print,  $size_qty, "   ");
						$price_print = '';
						if( strlen($price) > ($j) * $size_price ) {
							$price_print = substr($price, $j * $size_price, min($size_price, strlen($price) - ($j) * $size_price));
						}
						$price_print = str_pad($price_print,  $size_price, "   ");
						$amount_print = '';
						if( strlen($amount) > ($j) * $size_amount ) {
							$amount_print = substr($amount, $j * $size_amount, min($size_amount, strlen($amount) - ($j) * $size_amount));
						}
						$amount_print = str_pad($amount_print,  $size_amount, "   ");
						$printer -> textChinese( '  ' . $number_print . ' ' .  $name_print . '    ' . $qty_print. ' ' . $remark_print . ' ' . $price_print . ' ' . $amount_print . "\n");
					    
					
					}
						while ($srow=mysqli_fetch_assoc($v_data)){
							
						$v_id=$srow['varient_id'];   
						$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id='$v_id'");
						while ($srow1=mysqli_fetch_assoc($sub_rows)){  
							// print_R($srow1); 
							// die;
							  $v_name=$srow1['name'];
							// die;
							 // die;
							if($v_name)
							{
								$printer -> textChinese( '   '.$v_name."\n"); 
							}
							$v_name='';
						}
						$v_name='';
					}
					// $varray=$order['varient'];
					
					// foreach($varray  as $vi)
					// {
						
						// $v_text=$vi['name']."( ".$vi['product_price'].")";
					    // $printer -> textChinese( '   '.$v_text."\n"); 
					// }
					$printer -> text("\n");
					$v_order++;
				}
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				$printer -> selectPrintMode(Printer::MODE_FONT_B | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
				$printer -> text( "------------ -------------" . "\n");
				$printer -> text( "Qty: " . $qty_total . "      " . "Total: RM $total" . "   " . "\n");
				$printer -> text( "============ =============" . "\n");
				$printer -> text("\n");
				$printer -> text("\n");
				$printer -> cut( Printer::CUT_FULL, 3 );  
				$printer -> close();
				//echo('success');   
				$order_id=$order['id'];
				 mysqli_query($conn,"UPDATE `order_list` SET `auto_print` = '1' WHERE `order_list`.`id` ='$order_id'");
				$res['status']=true;
				$res['message']="Print Successfully";
			} finally {
				$printer -> close();
			}
		}
			return $res;
	}  

